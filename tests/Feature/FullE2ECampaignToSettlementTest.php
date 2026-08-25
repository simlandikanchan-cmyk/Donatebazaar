<?php

namespace Tests\Feature;

use App\Gateways\RazorpayGateway;
use App\Models\Campaign;
use App\Models\CampaignSettlement;
use App\Models\Category;
use App\Models\Donation;
use App\Models\FundraiserLevel;
use App\Models\KycVerification;
use App\Models\Organization;
use App\Models\PayoutAccount;
use App\Models\RiskConfig;
use App\Models\RiskRule;
use App\Models\User;
use App\Models\UserFundraiserLevel;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FullE2ECampaignToSettlementTest extends TestCase
{
    use RefreshDatabase;

    private const DONATION_AMOUNT = 500.00;
    private const PLATFORM_FEE = 25.00;
    private const NET_AMOUNT = 475.00;
    private const TEST_ORDER_ID = 'order_e2e_full_123';
    private const TEST_PAYMENT_ID = 'pay_e2e_full_456';
    private const TEST_SIGNATURE = 'sig_e2e_full_789';
    private const WEBHOOK_SECRET = 'test-webhook-secret-full-e2e';

    private User $fundraiser;
    private User $donor;
    private User $admin;
    private Category $category;
    private FundraiserLevel $level;
    private Organization $org;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('services.razorpay.key', 'rzp_test_key');
        Config::set('services.razorpay.secret', 'rzp_test_secret');
        Config::set('services.razorpay.webhook_secret', self::WEBHOOK_SECRET);

        $this->level = FundraiserLevel::create([
            'level_number' => 1,
            'level_name' => 'Starter',
            'description' => 'Starter level',
            'max_goal_amount' => 500000.00,
            'max_active_campaigns' => 5,
            'min_campaigns_completed' => 0,
            'min_raised_percent' => 0.00,
            'requires_admin_approval' => false,
            'kyc_requirement' => 'full',
            'badge_color' => '#6b7280',
            'is_default' => true,
        ]);

        $this->admin = User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
        ]);
        $this->admin->role = 'admin';
        $this->admin->status = 'active';
        $this->admin->save();

        $this->fundraiser = User::factory()->create([
            'name' => 'Test Fundraiser',
            'email' => 'fundraiser@example.com',
            'email_verified_at' => now(),
        ]);
        $this->fundraiser->role = 'ngo';
        $this->fundraiser->status = 'active';
        $this->fundraiser->save();

        $this->donor = User::factory()->create([
            'name' => 'Test Donor',
            'email' => 'donor@example.com',
            'email_verified_at' => now(),
        ]);
        $this->donor->role = 'donor';
        $this->donor->status = 'active';
        $this->donor->save();

        UserFundraiserLevel::create([
            'user_id' => $this->fundraiser->id,
            'current_level_id' => $this->level->id,
            'total_campaigns_completed' => 0,
            'total_amount_raised' => 0.00,
            'status' => 'active',
        ]);

        $kyc = KycVerification::create([
            'user_id' => $this->fundraiser->id,
            'document_type' => 'pan',
            'document_url' => 'https://example.com/doc.pdf',
        ]);
        $kyc->status = KycVerification::STATUS_APPROVED;
        $kyc->verified_at = now();
        $kyc->save();

        $this->org = Organization::create([
            'user_id' => $this->fundraiser->id,
            'name' => 'Test Org',
            'type' => 'individual',
        ]);

        PayoutAccount::create([
            'organization_id' => $this->org->id,
            'account_holder_name' => 'Test Fundraiser',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST00001234',
            'is_verified' => true,
            'verified_by' => $this->admin->id,
            'verified_at' => now(),
        ]);

        $this->category = Category::create([
            'name' => 'Medical',
            'slug' => 'medical',
            'icon' => 'heart',
            'color' => '#2563eb',
            'is_active' => true,
        ]);
    }

    private function campaignPayload(): array
    {
        Storage::fake('public');

        return [
            'title' => 'Full E2E Test Campaign',
            'description' => 'End-to-end test campaign from creation through settlement.',
            'goal_amount' => '10000',
            'category_id' => $this->category->id,
            'cover_image' => UploadedFile::fake()->image('cover.jpg', 800, 600),
            'location' => 'Test City',
            'start_date' => now()->subDay()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'updates' => [
                ['title' => 'Launch Update', 'body' => 'We are launching this campaign for testing.'],
            ],
        ];
    }

    private function mockGatewayForDonationAndPayout(): RazorpayGateway
    {
        $mock = $this->createMock(RazorpayGateway::class);

        $mock->method('createOrder')
            ->willReturn([
                'id' => self::TEST_ORDER_ID,
                'amount' => (int) round(self::DONATION_AMOUNT * 100),
                'currency' => 'INR',
                'status' => 'created',
                'receipt' => 'rcpt_e2e_full',
            ]);

        $mock->method('verifyPaymentSignature')
            ->willReturnCallback(function () {});

        $mock->method('verifyPaymentDetails')
            ->willReturnCallback(function () {});

        $mock->method('initiatePayout')
            ->willReturn([
                'gateway_reference' => 'PAYOUT_FULL_E2E_' . time(),
                'provider_status' => 'processed',
                'metadata' => [
                    'account_number' => 'XXXXXX0987',
                    'ifsc_code' => 'TEST00001234',
                    'amount' => self::NET_AMOUNT,
                    'currency' => 'INR',
                ],
            ]);

        return $mock;
    }

    private function setupRiskAutoApprove(): void
    {
        RiskConfig::create([
            'risk_version' => 1,
            'approval_threshold' => 50,
            'manual_review_threshold' => 80,
            'velocity_limits' => [],
            'aml_version' => 'v1',
            'fraud_threshold' => 90,
            'configurable_limits' => [],
        ]);

        RiskRule::create([
            'name' => 'KYC_VERIFIED',
            'category' => 'KYC',
            'weight' => 0,
            'priority' => 1,
            'enabled' => true,
            'force_review' => false,
        ]);

        RiskRule::create([
            'name' => 'LARGE_PAYOUT_AMOUNT',
            'category' => 'LIMITS',
            'weight' => 90,
            'priority' => 2,
            'enabled' => false,
            'force_review' => false,
        ]);
    }

    #[Test]
    public function full_flow_campaign_creation_to_settlement_paid(): void
    {
        // ── 1. Fundraiser creates a campaign ──
        $this->actingAs($this->fundraiser)
            ->post('/campaign/store', $this->campaignPayload())
            ->assertRedirect()
            ->assertSessionHas('success');

        $campaign = Campaign::where('user_id', $this->fundraiser->id)->first();
        $this->assertNotNull($campaign);
        $this->assertEquals('Full E2E Test Campaign', $campaign->title);
        $this->assertEquals(Campaign::STATE_PENDING, $campaign->campaign_state);
        $this->assertEquals(0, (float) $campaign->raised_amount);

        // ── 2. Admin approves the campaign ──
        $this->actingAs($this->admin)
            ->post('/admin/campaign/' . $campaign->id . '/approve')
            ->assertRedirect();

        $campaign->refresh();
        $this->assertEquals(Campaign::STATE_ACTIVE, $campaign->campaign_state);

        // ── 3. Donor initiates donation ──
        $this->app->instance(RazorpayGateway::class, $this->mockGatewayForDonationAndPayout());

        $this->actingAs($this->donor)
            ->post('/donate/' . $campaign->id, ['amount' => (string) self::DONATION_AMOUNT])
            ->assertRedirect();

        $this->actingAs($this->donor)
            ->get('/payment/' . $campaign->id)
            ->assertStatus(200);

        $donation = Donation::where('user_id', $this->donor->id)
            ->where('campaign_id', $campaign->id)
            ->first();

        $this->assertNotNull($donation);
        $this->assertEquals('pending', $donation->payment_status);
        $this->assertEquals(self::TEST_ORDER_ID, $donation->order_id);
        $this->assertEquals(self::DONATION_AMOUNT, (float) $donation->total_amount);
        $this->assertEquals(self::PLATFORM_FEE, (float) $donation->platform_fee);
        $this->assertEquals(self::NET_AMOUNT, (float) $donation->net_amount);

        // ── 4. Payment verification (completes the donation) ──
        $this->post('/payment/verify', [
            'razorpay_order_id' => self::TEST_ORDER_ID,
            'razorpay_payment_id' => self::TEST_PAYMENT_ID,
            'razorpay_signature' => self::TEST_SIGNATURE,
            'donation_id' => $donation->id,
        ])->assertStatus(200)
          ->assertJson(['success' => true]);

        $donation->refresh();
        $this->assertEquals('completed', $donation->payment_status);
        $this->assertEquals(self::TEST_PAYMENT_ID, $donation->payment_id);
        $this->assertNotNull($donation->paid_at);
        $this->assertEquals('pending', $donation->settlement_status);

        $campaign->refresh();
        $this->assertEquals(self::DONATION_AMOUNT, (float) $campaign->raised_amount);

        // ── 5. Wallet credited with reserved balance ──
        $wallet = Wallet::where('owner_id', $this->fundraiser->id)
            ->where('owner_type', User::class)
            ->first();

        $this->assertNotNull($wallet);
        $this->assertEquals(self::NET_AMOUNT, (float) $wallet->reserved_balance);
        $this->assertEquals(0.00, (float) $wallet->balance);
        $this->assertEquals(0.00, (float) $wallet->pending_settlement_balance);

        $walletTx = WalletTransaction::where('wallet_id', $wallet->id)
            ->where('source', WalletTransaction::SOURCE_DONATION)
            ->where('reference_id', $donation->id)
            ->where('reference_type', Donation::class)
            ->first();

        $this->assertNotNull($walletTx);
        $this->assertEquals('credit', $walletTx->type);
        $this->assertEquals(self::NET_AMOUNT, (float) $walletTx->amount);

        // ── 6. Release matured reserves (7-day hold expiry) ──
        $donation->paid_at = now()->subDays(10);
        $donation->save();

        $this->artisan('wallet:release-reserves');

        $wallet->refresh();
        $this->assertEquals(0.00, (float) $wallet->reserved_balance);
        $this->assertEquals(self::NET_AMOUNT, (float) $wallet->balance);
        $this->assertEquals(0.00, (float) $wallet->pending_settlement_balance);

        $donation->refresh();
        $this->assertNotNull($donation->released_at);

        $releaseTx = WalletTransaction::where('wallet_id', $wallet->id)
            ->where('source', WalletTransaction::SOURCE_ADJUSTMENT)
            ->where('reference_id', $donation->id)
            ->first();
        $this->assertNotNull($releaseTx);
        $this->assertStringContainsString('matured', $releaseTx->notes);

        // ── 7. Fundraiser views wallet dashboard ──
        $walletResponse = $this->actingAs($this->fundraiser)
            ->get(route('dashboard.wallet'));

        $walletResponse->assertStatus(200);
        $walletResponse->assertSee('Full E2E Test Campaign');
        $walletResponse->assertSee('₹' . number_format(self::NET_AMOUNT, 2));

        // ── 8. Fundraiser requests settlement ──
        $this->actingAs($this->fundraiser)
            ->post(route('dashboard.wallet.request'), ['donation_ids' => [$donation->id]])
            ->assertRedirect(route('dashboard.wallet'))
            ->assertSessionHas('success', 'Payout request submitted. It is now pending admin approval.');

        $settlement = CampaignSettlement::where('organization_id', $this->org->id)->first();
        $this->assertNotNull($settlement);
        $this->assertEquals(self::NET_AMOUNT, (float) $settlement->net_amount);
        $this->assertEquals(self::DONATION_AMOUNT, (float) $settlement->gross_amount);
        $this->assertEquals(self::PLATFORM_FEE, (float) $settlement->platform_fee);
        $this->assertContains($settlement->status, ['pending_approval', 'manual_review', 'auto_approved']);

        $wallet->refresh();
        $this->assertEquals(self::NET_AMOUNT, (float) $wallet->pending_settlement_balance);
        $this->assertEquals(0.00, (float) $wallet->balance);

        // Settlement debit transaction should not exist yet (approval pending)
        $this->assertNull(
            WalletTransaction::where('wallet_id', $wallet->id)
                ->where('source', WalletTransaction::SOURCE_SETTLEMENT)
                ->first()
        );

        // ── 9. Verify donation is locked in settlement ──
        $this->assertNotNull($settlement->settlementItems()->where('donation_id', $donation->id)->first());

        // ── 10. Admin reviews the settlement ──
        $adminResponse = $this->actingAs($this->admin)
            ->get(route('admin.settlements.show', $settlement));

        $adminResponse->assertStatus(200);
        $adminResponse->assertSee('Test Org');
        $adminResponse->assertSee(number_format(self::NET_AMOUNT, 2));

        // ── 11. Admin approves the settlement ──
        $this->actingAs($this->admin)
            ->post(route('admin.settlements.approve', $settlement))
            ->assertRedirect(route('admin.settlements.show', $settlement))
            ->assertSessionHas('success');

        $settlement->refresh();
        $this->assertContains($settlement->status, ['paid', 'approved', 'processing']);
        $this->assertNotNull($settlement->approved_at);
        $this->assertEquals($this->admin->id, $settlement->approved_by);

        // ── 12. Verify wallet after approval ──
        $wallet->refresh();
        $this->assertEquals(0.00, (float) $wallet->pending_settlement_balance);

        $approvalDebit = WalletTransaction::where('wallet_id', $wallet->id)
            ->where('source', WalletTransaction::SOURCE_SETTLEMENT)
            ->where('type', 'debit')
            ->first();
        $this->assertNotNull($approvalDebit);

        // ── 13. Admin settlements list ──
        $listResponse = $this->actingAs($this->admin)
            ->get(route('admin.settlements.index'));
        $listResponse->assertStatus(200);
        $listResponse->assertSee((string) $settlement->id);

        // ── 14. With sync queue, ProcessSettlementJob runs immediately ──
        $settlement->refresh();
        $this->assertEquals('paid', $settlement->status);
        $this->assertNotNull($settlement->paid_at);
        $this->assertNotEmpty($settlement->gateway_reference);

        // ── 15. Donation marked as settled ──
        $donation->refresh();
        $this->assertEquals('settled', $donation->settlement_status);
        $this->assertEquals($settlement->id, $donation->campaign_settlement_id);

        // ── 16. Wallet transaction ledger verification ──
        $this->assertDatabaseHas('wallet_transactions', [
            'wallet_id' => $wallet->id,
            'source' => WalletTransaction::SOURCE_DONATION,
            'reference_id' => $donation->id,
            'reference_type' => Donation::class,
            'type' => 'credit',
            'amount' => self::NET_AMOUNT,
        ]);

        $this->assertDatabaseHas('wallet_transactions', [
            'wallet_id' => $wallet->id,
            'source' => WalletTransaction::SOURCE_ADJUSTMENT,
            'reference_id' => $donation->id,
            'reference_type' => Donation::class,
            'type' => 'credit',
        ]);

        $this->assertDatabaseHas('wallet_transactions', [
            'wallet_id' => $wallet->id,
            'source' => WalletTransaction::SOURCE_SETTLEMENT,
            'reference_id' => $settlement->id,
            'reference_type' => CampaignSettlement::class,
            'type' => 'debit',
            'amount' => self::NET_AMOUNT,
        ]);

        // ── 17. Final wallet state ──
        $wallet->refresh();
        $this->assertEquals(0.00, (float) $wallet->balance);
        $this->assertEquals(0.00, (float) $wallet->reserved_balance);
        $this->assertEquals(0.00, (float) $wallet->pending_settlement_balance);
    }

    #[Test]
    public function full_flow_with_risk_auto_approved(): void
    {
        $this->setupRiskAutoApprove();

        // ── Campaign creation ──
        $this->actingAs($this->fundraiser)
            ->post('/campaign/store', $this->campaignPayload())
            ->assertRedirect();

        $campaign = Campaign::where('user_id', $this->fundraiser->id)->first();

        $this->actingAs($this->admin)
            ->post('/admin/campaign/' . $campaign->id . '/approve')
            ->assertRedirect();

        $campaign->refresh();
        $this->assertEquals(Campaign::STATE_ACTIVE, $campaign->campaign_state);

        // ── Donation flow ──
        $this->app->instance(RazorpayGateway::class, $this->mockGatewayForDonationAndPayout());

        $this->actingAs($this->donor)
            ->post('/donate/' . $campaign->id, ['amount' => (string) self::DONATION_AMOUNT])
            ->assertRedirect();

        $this->actingAs($this->donor)
            ->get('/payment/' . $campaign->id)
            ->assertStatus(200);

        $donation = Donation::where('user_id', $this->donor->id)
            ->where('campaign_id', $campaign->id)
            ->first();

        $this->post('/payment/verify', [
            'razorpay_order_id' => self::TEST_ORDER_ID,
            'razorpay_payment_id' => self::TEST_PAYMENT_ID,
            'razorpay_signature' => self::TEST_SIGNATURE,
            'donation_id' => $donation->id,
        ])->assertStatus(200)
          ->assertJson(['success' => true]);

        $donation->refresh();
        $this->assertEquals('completed', $donation->payment_status);

        // ── Age the donation past the 7-day hold window ──
        $donation->paid_at = now()->subDays(10);
        $donation->save();

        $wallet = Wallet::where('owner_id', $this->fundraiser->id)
            ->where('owner_type', User::class)
            ->first();

        $this->artisan('wallet:release-reserves');

        $wallet->refresh();
        $this->assertEquals(self::NET_AMOUNT, (float) $wallet->balance);
        $this->assertEquals(0.00, (float) $wallet->reserved_balance);

        // ── Settlement request with RiskConfig present ──
        // Use the service directly so the risk engine path is exercised
        $settlement = app(\App\Services\SettlementService::class)
            ->requestSettlement($this->org, [$donation->id]);

        $this->assertNotNull($settlement);
        // With RiskConfig: KYC_VERIFIED rule triggered but weight=0 → score=0 < approval_threshold(50) → auto_approved
        // The AutoProcessAutoApprovedSettlement listener then dispatches ProcessSettlementJob (sync queue runs immediately)
        $this->assertContains($settlement->status, ['auto_approved', 'manual_review', 'paid']);

        // If manual_review, admin approves → job runs (sync queue) → paid
        if (in_array($settlement->status, ['auto_approved', 'manual_review'])) {
            $this->actingAs($this->admin)
                ->post(route('admin.settlements.approve', $settlement))
                ->assertRedirect();
        }

        // With sync queue, ProcessSettlementJob runs immediately — settlement should be paid
        $settlement->refresh();
        $this->assertEquals('paid', $settlement->status);
        $this->assertNotNull($settlement->paid_at);
        $this->assertNotEmpty($settlement->gateway_reference);

        $donation->refresh();
        $this->assertEquals('settled', $donation->settlement_status);
        $this->assertEquals($settlement->id, $donation->campaign_settlement_id);

        $wallet->refresh();
        $this->assertEquals(0.00, (float) $wallet->balance);
        $this->assertEquals(0.00, (float) $wallet->pending_settlement_balance);
    }

    #[Test]
    public function full_flow_settlement_rejected_returns_funds(): void
    {
        // ── Campaign creation ──
        $this->actingAs($this->fundraiser)
            ->post('/campaign/store', $this->campaignPayload())
            ->assertRedirect();

        $campaign = Campaign::where('user_id', $this->fundraiser->id)->first();

        $this->actingAs($this->admin)
            ->post('/admin/campaign/' . $campaign->id . '/approve')
            ->assertRedirect();

        $campaign->refresh();

        // ── Donation flow ──
        $this->app->instance(RazorpayGateway::class, $this->mockGatewayForDonationAndPayout());

        $this->actingAs($this->donor)
            ->post('/donate/' . $campaign->id, ['amount' => (string) self::DONATION_AMOUNT])
            ->assertRedirect();

        $this->actingAs($this->donor)
            ->get('/payment/' . $campaign->id)
            ->assertStatus(200);

        $donation = Donation::where('user_id', $this->donor->id)
            ->where('campaign_id', $campaign->id)
            ->first();

        $this->post('/payment/verify', [
            'razorpay_order_id' => self::TEST_ORDER_ID,
            'razorpay_payment_id' => self::TEST_PAYMENT_ID,
            'razorpay_signature' => self::TEST_SIGNATURE,
            'donation_id' => $donation->id,
        ])->assertStatus(200);

        $donation->paid_at = now()->subDays(10);
        $donation->save();

        $wallet = Wallet::where('owner_id', $this->fundraiser->id)
            ->where('owner_type', User::class)
            ->first();

        $this->artisan('wallet:release-reserves');

        $wallet->refresh();
        $this->assertEquals(self::NET_AMOUNT, (float) $wallet->balance);

        // ── Settlement request ──
        app(\App\Services\SettlementService::class)
            ->requestSettlement($this->org, [$donation->id]);

        $settlement = CampaignSettlement::first();
        $this->assertNotNull($settlement);

        // ── Admin rejects the settlement ──
        $this->actingAs($this->admin)
            ->post(route('admin.settlements.reject', $settlement), [
                'reason' => 'Suspicious activity detected on the donor account',
            ])
            ->assertRedirect(route('admin.settlements.show', $settlement))
            ->assertSessionHas('success');

        $settlement->refresh();
        $this->assertEquals('rejected', $settlement->status);
        $this->assertEquals('Suspicious activity detected on the donor account', $settlement->rejection_reason);
        $this->assertNotNull($settlement->rejected_at);
        $this->assertEquals($this->admin->id, $settlement->rejected_by);

        // ── Funds returned to wallet balance ──
        $wallet->refresh();
        $this->assertEquals(self::NET_AMOUNT, (float) $wallet->balance);
        $this->assertEquals(0.00, (float) $wallet->pending_settlement_balance);

        // ── Settlement reversal ledger entry ──
        $this->assertDatabaseHas('wallet_transactions', [
            'wallet_id' => $wallet->id,
            'source' => WalletTransaction::SOURCE_SETTLEMENT_REVERSAL,
            'reference_id' => $settlement->id,
            'reference_type' => CampaignSettlement::class,
            'type' => 'credit',
            'amount' => self::NET_AMOUNT,
        ]);

        // ── Donation still pending settlement (was rejected, not paid out) ──
        $donation->refresh();
        $this->assertEquals('pending', $donation->settlement_status);
    }
}
