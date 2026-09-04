<?php

namespace Tests\Feature;

use App\Gateways\RazorpayGateway;
use App\Exceptions\RefundNotAllowedException;
use App\Models\Campaign;
use App\Models\CampaignSettlement;
use App\Models\Donation;
use App\Models\FinancialLedger;
use App\Models\Organization;
use App\Models\PayoutAccount;
use App\Models\Refund;
use App\Models\SettlementItem;
use App\Models\User;
use App\Services\Financial\FinancialLedgerService;
use App\Services\Financial\FinancialReconciliationService;
use App\Services\Payment\DonationCompletionService;
use App\Services\Payment\RefundService;
use App\Services\SettlementService;
use App\Services\WalletService;
use App\Support\Money;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Razorpay\Api\Api;
use Tests\TestCase;

class FinancialAccountingTest extends TestCase
{
    use RefreshDatabase;

    private WalletService $walletService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->walletService = app(WalletService::class);

        Config::set('services.razorpay.key', 'rzp_test_key');
        Config::set('services.razorpay.secret', 'rzp_test_secret');
        Config::set('services.donation.gateway_fee_bearer', 'platform');
    }

    private function makeOwnerAndCampaign(): array
    {
        $owner = User::factory()->create(['role' => 'ngo']);
        $campaign = Campaign::create([
            'title' => 'Fee Test Campaign',
            'slug' => 'fee-test-'.uniqid(),
            'user_id' => $owner->id,
            'description' => 'Financial accounting test campaign.',
            'goal_amount' => 100000.00,
        ]);

        $org = Organization::create([
            'user_id' => $owner->id,
            'name' => 'Fee Org',
            'type' => 'trust',
        ]);

        return [$owner, $campaign, $org];
    }

    private function makeCompletedDonation($campaign, $owner, float $total = 1000.00, ?string $paymentId = null): Donation
    {
        $donor = User::factory()->create(['role' => 'donor']);

        $donation = Donation::create([
            'user_id' => $donor->id,
            'campaign_id' => $campaign->id,
            'donation_type' => 'money',
            'total_amount' => $total,
            'platform_fee' => round($total * 0.05, 2),
            'net_amount' => round($total - round($total * 0.05, 2), 2),
            'currency' => 'INR',
        ]);
        DB::table('donations')->where('id', $donation->id)->update([
            'payment_status' => 'completed',
            'is_refunded' => false,
            'paid_at' => now()->subDays(10),
            'payment_id' => $paymentId ?? ('pay_'.str_repeat('A', 20)),
        ]);

        return $donation->fresh();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Scenario 1 + 7: Donation capture writes a ledger entry (idempotent)
    // ─────────────────────────────────────────────────────────────────────────

    #[Test]
    public function donation_capture_writes_idempotent_ledger_entry(): void
    {
        [$owner, $campaign] = $this->makeOwnerAndCampaign();
        $donation = $this->makeCompletedDonation($campaign, $owner);

        $ledger = app(FinancialLedgerService::class);

        $ledger->recordDonationCaptured($donation);
        $ledger->recordDonationCaptured($donation);

        $this->assertDatabaseCount('financial_ledger', 1);
        $this->assertDatabaseHas('financial_ledger', [
            'event' => FinancialLedger::EVENT_DONATION_CAPTURED,
            'reference_type' => Donation::class,
            'reference_id' => $donation->id,
            'amount' => '1000.00',
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Scenario 2: gateway fee is captured from the payment provider
    // ─────────────────────────────────────────────────────────────────────────

    #[Test]
    public function gateway_fee_is_captured_from_payment_provider(): void
    {
        [$owner, $campaign] = $this->makeOwnerAndCampaign();

        $donation = Donation::create([
            'user_id' => $owner->id,
            'campaign_id' => $campaign->id,
            'donation_type' => 'money',
            'total_amount' => 1000.00,
            'platform_fee' => 50.00,
            'net_amount' => 950.00,
            'payment_id' => 'pay_fee_test_1234567890xyz',
            'currency' => 'INR',
        ]);

        // Mock Api whose payment entity exposes fee (₹18.00 = 1800 paise) + tax.
        $mockApi = new class extends Api {
            public function __construct() {}
            public $payment;
        };
        $mockApi->payment = new class($mockApi) extends \Razorpay\Api\Payment {
            private $api;
            public $id;
            public function __construct($api) { $this->api = $api; }
            public function fetch($id = null) {
                $this->id = $id;
                $this->attributes = ['id' => $id, 'fee' => 1800, 'tax' => 180];
                return $this;
            }
        };

        $gateway = new RazorpayGateway(
            keyId: 'test_key',
            keySecret: 'test_secret',
            webhookSecret: 'wh_secret',
            api: $mockApi
        );

        $fees = $gateway->fetchPaymentFees('pay_fee_test_1234567890xyz');
        $this->assertEquals(18.0, $fees['fee']);
        $this->assertEquals(1.8, $fees['tax']);

        $this->app->instance(RazorpayGateway::class, $gateway);

        app(DonationCompletionService::class)->complete($donation, 'pay_fee_test_1234567890xyz');

        $donation->refresh();
        $this->assertEquals('18.00', (string) $donation->gateway_fee);
        $this->assertEquals('1.80', (string) $donation->gateway_tax);
        $this->assertEquals('captured', $donation->fee_capture_status);
        $this->assertEquals('platform', $donation->gateway_fee_bearer);

        $this->assertDatabaseHas('financial_ledger', [
            'event' => FinancialLedger::EVENT_GATEWAY_FEE_CAPTURED,
            'reference_id' => $donation->id,
            'amount' => '19.80',
        ]);
    }

    #[Test]
    public function fee_capture_marks_unavailable_when_provider_lacks_fee(): void
    {
        [$owner, $campaign] = $this->makeOwnerAndCampaign();

        $donation = Donation::create([
            'user_id' => $owner->id,
            'campaign_id' => $campaign->id,
            'donation_type' => 'money',
            'total_amount' => 1000.00,
            'platform_fee' => 50.00,
            'net_amount' => 950.00,
            'payment_id' => 'pay_no_fee_1234567890xyz',
            'currency' => 'INR',
        ]);

        $mockApi = new class extends Api {
            public function __construct() {}
            public $payment;
        };
        $mockApi->payment = new class($mockApi) extends \Razorpay\Api\Payment {
            private $api;
            public $id;
            public function __construct($api) { $this->api = $api; }
            public function fetch($id = null) {
                $this->id = $id;
                $this->attributes = ['id' => $id];
                return $this;
            }
        };

        $gateway = new RazorpayGateway(
            keyId: 'test_key',
            keySecret: 'test_secret',
            webhookSecret: 'wh_secret',
            api: $mockApi
        );
        $this->app->instance(RazorpayGateway::class, $gateway);

        app(DonationCompletionService::class)->complete($donation, 'pay_no_fee_1234567890xyz');

        $donation->refresh();
        $this->assertNull($donation->gateway_fee);
        $this->assertEquals('unavailable', $donation->fee_capture_status);
        // No fee ledger entry written when unavailable.
        $this->assertDatabaseCount('financial_ledger', 1); // only donation_captured
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Scenario 3 + 4: refund reverses platform_earnings and writes ledger
    // ─────────────────────────────────────────────────────────────────────────

    #[Test]
    public function refund_reverses_platform_earnings_and_writes_ledger(): void
    {
        [$owner, $campaign, $org] = $this->makeOwnerAndCampaign();
        $donation = $this->makeCompletedDonation($campaign, $owner, 1000.00);

        // Book the platform fee like donation completion does.
        $campaign->increment('platform_earnings', (float) $donation->platform_fee);
        $campaign->refresh();
        $this->assertEquals('50.00', (string) $campaign->platform_earnings);

        // Fund the owner wallet so the refund reversal has funds to pull from.
        $wallet = $this->walletService->getOrCreateWallet($owner);
        $this->walletService->credit($wallet, (float) $donation->net_amount, 'donation', $donation->id, Donation::class);

        // Mock the gateway refund to succeed.
        $mock = $this->createMock(RazorpayGateway::class);
        $mock->method('initiateRefund')
            ->willReturn((object) ['id' => 'rfnd_test_abcdef']);
        $this->app->instance(RazorpayGateway::class, $mock);

        $admin = User::factory()->create(['role' => 'admin']);

        $refund = app(RefundService::class)->processAdminRefund($donation, $admin, 'test refund');

        $this->assertTrue($refund->isProcessed());

        $campaign->refresh();
        $this->assertEquals('0.00', (string) $campaign->platform_earnings);

        $this->assertDatabaseHas('financial_ledger', [
            'event' => FinancialLedger::EVENT_REFUND_PROCESSED,
            'reference_type' => Refund::class,
            'reference_id' => $refund->id,
            'amount' => '1000.00',
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Scenario 5: Reconciliation flags a missing ledger capture
    // ─────────────────────────────────────────────────────────────────────────

    #[Test]
    public function reconciliation_flags_donation_without_ledger_capture(): void
    {
        [$owner, $campaign] = $this->makeOwnerAndCampaign();
        $donation = $this->makeCompletedDonation($campaign, $owner, 1000.00);

        // No ledger entry written for this donation.
        $warnings = app(FinancialReconciliationService::class)->reconcileDonation($donation);

        $this->assertNotEmpty($warnings);
        $this->assertStringContainsString('no donation_captured ledger entry', implode(' ', $warnings));
    }

    #[Test]
    public function reconciliation_passes_for_consistent_donation(): void
    {
        [$owner, $campaign] = $this->makeOwnerAndCampaign();
        $donation = $this->makeCompletedDonation($campaign, $owner, 1000.00);
        $donation->gateway_fee = 18.00;
        $donation->fee_capture_status = 'captured';
        $donation->save();

        app(FinancialLedgerService::class)->recordDonationCaptured($donation);

        $warnings = app(FinancialReconciliationService::class)->reconcileDonation($donation);
        $this->assertEmpty($warnings);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Scenario 6 + 8: Payout safety + payout ledger + payout_amount
    // ─────────────────────────────────────────────────────────────────────────

    #[Test]
    public function payout_records_ledger_and_sets_payout_amount(): void
    {
        [$owner, $campaign, $org] = $this->makeOwnerAndCampaign();
        $donation = $this->makeCompletedDonation($campaign, $owner, 1000.00);

        PayoutAccount::create([
            'organization_id' => $org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0000',
            'fund_account_id' => 'fa_test_123',
            'is_verified' => true,
        ]);

        // Fund wallet and settle.
        $wallet = $this->walletService->getOrCreateWallet($owner);
        $this->walletService->credit($wallet, (float) $donation->net_amount, 'donation', $donation->id, Donation::class);
        $this->walletService->releaseReservesForDonations($wallet, [$donation]);

        $this->mockPayoutGateway();

        $settlement = app(SettlementService::class)->requestSettlement($org, [$donation->id]);
        $settlementService = app(SettlementService::class);

        // Approve and process.
        $this->approveSettlement($settlement);
        $settlement->refresh();
        $result = $settlementService->processSettlementPayout($settlement);

        $this->assertTrue($result['success']);

        $donation->refresh();
        $this->assertEquals('settled', $donation->settlement_status);
        $this->assertEquals((float) $donation->net_amount, (float) $donation->payout_amount);

        $this->assertDatabaseHas('financial_ledger', [
            'event' => FinancialLedger::EVENT_PAYOUT_COMPLETED,
            'reference_type' => CampaignSettlement::class,
            'reference_id' => $settlement->id,
        ]);
    }

    #[Test]
    public function payout_safety_blocks_settlement_with_refunded_donation(): void
    {
        [$owner, $campaign, $org] = $this->makeOwnerAndCampaign();
        $donation = $this->makeCompletedDonation($campaign, $owner, 1000.00);
        $donation->is_refunded = true;
        $donation->payment_status = 'refunded';
        $donation->save();

        PayoutAccount::create([
            'organization_id' => $org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0000',
            'fund_account_id' => 'fa_test_123',
            'is_verified' => true,
        ]);

        $wallet = $this->walletService->getOrCreateWallet($owner);
        $this->walletService->credit($wallet, 950.00, 'adjustment', 99, User::class);

        $this->expectException(\InvalidArgumentException::class);
        app(SettlementService::class)->requestSettlement($org, [$donation->id]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Fix F1: Refund ledger records the ACTUAL reversed platform earnings
    // ─────────────────────────────────────────────────────────────────────────

    #[Test]
    public function refund_ledger_records_actual_reversed_platform_earnings_when_clamped(): void
    {
        [$owner, $campaign, $org] = $this->makeOwnerAndCampaign();
        $donation = $this->makeCompletedDonation($campaign, $owner, 1000.00);

        // Book LESS than the donation's platform fee (20.00 instead of 50.00) to
        // simulate earnings already reduced (clamping case).
        $campaign->increment('platform_earnings', 20.00);
        $campaign->refresh();

        $wallet = $this->walletService->getOrCreateWallet($owner);
        $this->walletService->credit($wallet, (float) $donation->net_amount, 'donation', $donation->id, Donation::class);

        $mock = $this->createMock(RazorpayGateway::class);
        $mock->method('initiateRefund')->willReturn((object) ['id' => 'rfnd_clamp_123']);
        $this->app->instance(RazorpayGateway::class, $mock);

        $admin = User::factory()->create(['role' => 'admin']);
        $refund = app(RefundService::class)->processAdminRefund($donation, $admin, 'clamp test');

        // Only the booked 20.00 can be reversed (clamped to 0), not the full 50.00.
        $campaign->refresh();
        $this->assertEquals('0.00', (string) $campaign->platform_earnings);

        // Ledger metadata must reflect the actual 20.00 reversed, not the 50.00 fee.
        $ledger = FinancialLedger::where('event', FinancialLedger::EVENT_REFUND_PROCESSED)
            ->where('reference_id', $refund->id)
            ->first();
        $this->assertNotNull($ledger);
        $this->assertEquals('20.00', $ledger->metadata['platform_fee_reversed']);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Fix F2: Reconciliation subtracts gateway tax; includes refunded donations
    // ─────────────────────────────────────────────────────────────────────────

    #[Test]
    public function reconciliation_subtracts_gateway_tax_from_actual_revenue(): void
    {
        [$owner, $campaign] = $this->makeOwnerAndCampaign();
        $donation = $this->makeCompletedDonation($campaign, $owner, 1000.00);
        $donation->gateway_fee = 18.00;
        $donation->gateway_tax = 1.80;
        $donation->fee_capture_status = 'captured';
        $donation->save();

        $report = app(FinancialReconciliationService::class)->reconcile();

        // platform_fee 50.00 - gateway_fee 18.00 - gateway_tax 1.80 = 30.20
        $this->assertEquals('30.20', $report['summaries']['actual_platform_revenue']);
        $this->assertEquals('1.80', $report['summaries']['gateway_tax_total']);
        $this->assertEquals('18.00', $report['summaries']['gateway_fee_total']);
    }

    #[Test]
    public function reconciliation_includes_refunded_donations_explicitly(): void
    {
        [$owner, $campaign] = $this->makeOwnerAndCampaign();

        $refunded = $this->makeCompletedDonation($campaign, $owner, 1000.00);
        $refunded->is_refunded = true;
        $refunded->payment_status = 'refunded';
        $refunded->refunded_amount = 1000.00;
        $refunded->save();

        $captured = $this->makeCompletedDonation($campaign, $owner, 500.00, 'pay_'.str_repeat('B', 20));

        $report = app(FinancialReconciliationService::class)->reconcile();

        $this->assertEquals(1, $report['counts']['captured']);
        $this->assertEquals(1, $report['counts']['refunded']);
        $this->assertEquals('1000.00', $report['summaries']['refunded_total']);
        // Captured total includes ALL completed donations (kept + later refunded).
        $this->assertEquals('1500.00', $report['summaries']['captured_total']);
        $this->assertEquals('500.00', $report['summaries']['net_retained_amount']);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Fix F3: Refund blocked after payout / while processing
    // ─────────────────────────────────────────────────────────────────────────

    #[Test]
    public function refund_blocked_after_successful_payout(): void
    {
        [$owner, $campaign, $org] = $this->makeOwnerAndCampaign();
        $donation = $this->makeCompletedDonation($campaign, $owner, 1000.00);

        $settlement = CampaignSettlement::create([
            'campaign_id' => $campaign->id,
            'organization_id' => $org->id,
            'gross_amount' => 1000.00,
            'platform_fee' => 50.00,
            'net_amount' => 950.00,
            'status' => 'paid',
        ]);
        SettlementItem::create([
            'campaign_settlement_id' => $settlement->id,
            'donation_id' => $donation->id,
            'amount' => 950.00,
        ]);

        $admin = User::factory()->create(['role' => 'admin']);

        $this->expectException(RefundNotAllowedException::class);
        app(RefundService::class)->processAdminRefund($donation, $admin, 'should be blocked');
    }

    #[Test]
    public function refund_blocked_while_settlement_payout_is_processing(): void
    {
        [$owner, $campaign, $org] = $this->makeOwnerAndCampaign();
        $donation = $this->makeCompletedDonation($campaign, $owner, 1000.00);

        $settlement = CampaignSettlement::create([
            'campaign_id' => $campaign->id,
            'organization_id' => $org->id,
            'gross_amount' => 1000.00,
            'platform_fee' => 50.00,
            'net_amount' => 950.00,
            'status' => 'processing',
        ]);
        SettlementItem::create([
            'campaign_settlement_id' => $settlement->id,
            'donation_id' => $donation->id,
            'amount' => 950.00,
        ]);

        $admin = User::factory()->create(['role' => 'admin']);

        $this->expectException(RefundNotAllowedException::class);
        app(RefundService::class)->processAdminRefund($donation, $admin, 'should be blocked');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Partial refund policy + gateway fee bearer config safety
    // ─────────────────────────────────────────────────────────────────────────

    #[Test]
    public function partial_refund_webhook_is_rejected_without_mutation(): void
    {
        Config::set('services.razorpay.webhook_secret', 'wh_test_secret');

        [$owner, $campaign] = $this->makeOwnerAndCampaign();
        $donation = $this->makeCompletedDonation($campaign, $owner, 1000.00);

        // Full refund would be 1000.00; send a PARTIAL refund (500.00).
        $payload = [
            'event' => 'refund.processed',
            'payload' => [
                'refund' => [
                    'entity' => [
                        'id' => 'rfnd_partial',
                        'payment_id' => $donation->payment_id,
                        'amount' => 50000,
                    ],
                ],
            ],
        ];

        $body = json_encode($payload);
        $sig = hash_hmac('sha256', $body, 'wh_test_secret');

        $this->call(
            'POST',
            '/payment/webhook',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X_RAZORPAY_SIGNATURE' => $sig,
            ],
            $body
        )->assertStatus(200);

        $donation->refresh();
        $this->assertFalse((bool) $donation->is_refunded);
        $this->assertEquals('completed', $donation->payment_status);
        $this->assertDatabaseCount('refunds', 0);
    }

    #[Test]
    public function unsupported_gateway_fee_bearer_is_rejected(): void
    {
        Config::set('services.donation.gateway_fee_bearer', 'campaign_owner');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported GATEWAY_FEE_BEARER');
        app(FinancialLedgerService::class)->bearer();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────────────────────────────────

    private function mockPayoutGateway(): void
    {
        $mock = $this->createMock(RazorpayGateway::class);
        $mock->method('initiatePayout')
            ->willReturn([
                'gateway_reference' => 'trans_feetest',
                'provider_status' => 'processed',
                'metadata' => [],
            ]);
        $mock->method('createOrder')
            ->willReturn(['id' => 'order_x', 'amount' => 0, 'currency' => 'INR', 'status' => 'created', 'receipt' => 'r']);
        $this->app->instance(RazorpayGateway::class, $mock);
    }

    private function approveSettlement(CampaignSettlement $settlement): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        app(SettlementService::class)->approveSettlement($settlement, $admin);
    }
}
