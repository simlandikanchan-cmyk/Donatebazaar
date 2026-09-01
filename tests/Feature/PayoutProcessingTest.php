<?php

namespace Tests\Feature;

use App\Exceptions\TemporaryFailureException;
use App\Gateways\RazorpayGateway;
use App\Jobs\ProcessSettlementJob;
use App\Models\Campaign;
use App\Models\CampaignSettlement;
use App\Models\Donation;
use App\Models\Organization;
use App\Models\PayoutAccount;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Notifications\SettlementProcessingStartedNotification;
use App\Notifications\SettlementRetryScheduledNotification;
use App\Services\SettlementService;
use App\Services\WalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Razorpay\Api\Api;
use Razorpay\Api\Transfer;
use Tests\TestCase;

class PayoutProcessingTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;

    private User $admin;

    private Organization $org;

    private WalletService $walletService;

    private SettlementService $settlementService;

    private Donation $donation;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = User::factory()->create();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->org = Organization::factory()->create(['user_id' => $this->owner->id]);

        PayoutAccount::create([
            'organization_id' => $this->org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0000',
            'fund_account_id' => 'fa_ABCDEFG',
            'is_verified' => true,
        ]);

        $mock = $this->mockGatewayForPayout();
        $this->app->instance(RazorpayGateway::class, $mock);

        $this->walletService = app(WalletService::class);
        $this->settlementService = app(SettlementService::class);
        $wallet = $this->walletService->getOrCreateWallet($this->owner);
        $this->walletService->credit($wallet, 500.00, WalletTransaction::SOURCE_ADJUSTMENT, 1, Organization::class);

        $campaign = Campaign::create([
            'title' => 'Payout Test Campaign',
            'slug' => 'payout-test-'.uniqid(),
            'user_id' => $this->owner->id,
            'description' => 'Test campaign',
            'goal_amount' => 10000.00,
        ]);

        $this->donation = Donation::create([
            'user_id' => $this->owner->id,
            'campaign_id' => $campaign->id,
            'donation_type' => 'money',
            'total_amount' => 500.00,
            'platform_fee' => 25.00,
            'net_amount' => 500.00,
        ]);
        $this->donation->payment_status = 'completed';
        $this->donation->is_refunded = false;
        $this->donation->paid_at = now()->subDays(10);
        $this->donation->save();
    }

    private function mockGatewayForPayout(?object $transferMock = null): RazorpayGateway
    {
        $mockApi = new class extends Api {
            public function __construct() {}
            public $transfer;
        };
        $mockApi->transfer = $transferMock ?? new class($mockApi) extends Transfer {
            private $api;
            public function __construct($api) { $this->api = $api; }
            public function create($attributes = []) {
                $entity = new class([]) extends Transfer {
                    public $id;
                    public $status = 'processed';
                    public $amount = 50000;
                    public $currency = 'INR';
                    public function __construct(array $data) {
                        $this->id = 'trans_'.uniqid();
                    }
                    public function toArray(): array {
                        return [
                            'id' => $this->id,
                            'status' => $this->status,
                            'amount' => $this->amount,
                            'currency' => $this->currency,
                            'entity' => 'transfer',
                        ];
                    }
                };
                return $entity;
            }
            public function fetch($id) {
                $entity = new class([]) extends Transfer {
                    public $id = 'trans_ABCDEFG';
                    public $status = 'processed';
                    public $amount = 50000;
                    public $currency = 'INR';
                    public function toArray(): array {
                        return [
                            'id' => $this->id,
                            'status' => $this->status,
                            'amount' => $this->amount,
                            'currency' => $this->currency,
                            'entity' => 'transfer',
                        ];
                    }
                };
                $entity->id = $id;
                return $entity;
            }
        };

        return new RazorpayGateway(
            keyId: 'test_key',
            keySecret: 'test_secret',
            webhookSecret: 'test_webhook_secret',
            api: $mockApi
        );
    }

    private function mockGatewayThatThrowsTemporary(): RazorpayGateway
    {
        $mockApi = new class extends Api {
            public function __construct() {}
            public $transfer;
        };
        $mockApi->transfer = new class($mockApi) extends Transfer {
            private $api;
            public function __construct($api) { $this->api = $api; }
            public function create($attributes = []) {
                throw new TemporaryFailureException('Provider temporarily unable to process payout.');
            }
        };

        return new RazorpayGateway(
            keyId: 'test_key',
            keySecret: 'test_secret',
            webhookSecret: 'test_webhook_secret',
            api: $mockApi
        );
    }

    private function mockGatewayThatThrowsPermanent(): RazorpayGateway
    {
        $mockApi = new class extends Api {
            public function __construct() {}
            public $transfer;
        };
        $mockApi->transfer = new class($mockApi) extends Transfer {
            private $api;
            public function __construct($api) { $this->api = $api; }
            public function create($attributes = []) {
                throw new PermanentFailureException('Invalid payout account.');
            }
        };

        return new RazorpayGateway(
            keyId: 'test_key',
            keySecret: 'test_secret',
            webhookSecret: 'test_webhook_secret',
            api: $mockApi
        );
    }

    private function mockGatewayWithSequence(array $responses): RazorpayGateway
    {
        $mockApi = new class($responses) extends Api {
            public function __construct(private array $responses) {}
            public $transfer;
            private int $callCount = 0;
        };
        $mockApi->transfer = new class($mockApi) extends Transfer {
            private $api;
            private array $responses;
            private int $callCount = 0;
            public function __construct($api) {
                $this->api = $api;
                $this->responses = $api->responses;
            }
            public function create($attributes = []) {
                $response = $this->responses[$this->callCount];
                $this->callCount++;
                if ($response instanceof \Throwable) {
                    throw $response;
                }
                return $response;
            }
            public function fetch($id) {
                $response = $this->responses[$this->callCount] ?? end($this->responses);
                if ($response instanceof \Throwable) {
                    throw $response;
                }
                return $response;
            }
        };

        return new RazorpayGateway(
            keyId: 'test_key',
            keySecret: 'test_secret',
            webhookSecret: 'test_webhook_secret',
            api: $mockApi
        );
    }

    #[Test]
    public function successful_payout_marks_settlement_paid_and_donations_settled(): void
    {
        $mock = $this->mockGatewayForPayout();
        $this->app->instance(RazorpayGateway::class, $mock);

        $settlement = $this->settlementService->requestSettlement($this->org, [$this->donation->id]);
        $this->settlementService->approveSettlement($settlement, $this->admin);

        $result = $this->settlementService->processSettlementPayout($settlement);
        $settlement->refresh();
        $this->donation->refresh();

        $this->assertTrue($result['success']);
        $this->assertEquals('paid', $settlement->status);
        $this->assertNotNull($settlement->paid_at);
        $this->assertNotNull($settlement->processed_at);
        $this->assertNull($settlement->failed_at);
        $this->assertNull($settlement->failed_reason);
        $this->assertNotEmpty($settlement->gateway_reference);
        $this->assertEquals('settled', $this->donation->settlement_status);
        $this->assertEquals($settlement->id, $this->donation->campaign_settlement_id);
    }

    #[Test]
    public function payout_temporary_failure_marks_retry_pending_and_does_not_restore_balance(): void
    {
        $mock = $this->mockGatewayThatThrowsTemporary();
        $this->app->instance(RazorpayGateway::class, $mock);
        $this->settlementService = app(SettlementService::class);

        $failOwner = User::factory()->create();
        $org = Organization::factory()->create(['user_id' => $failOwner->id]);
        PayoutAccount::create([
            'organization_id' => $org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0000',
            'fund_account_id' => 'fa_FAIL',
            'is_verified' => true,
        ]);

        $wallet = $this->walletService->getOrCreateWallet($failOwner);
        $wallet->update(['balance' => 0, 'pending_settlement_balance' => 500]);

        $campaign = Campaign::create([
            'title' => 'Fail Restore Campaign',
            'slug' => 'fail-restore-'.uniqid(),
            'user_id' => $failOwner->id,
            'description' => 'test',
            'goal_amount' => 10000.00,
        ]);
        $settlement = CampaignSettlement::create([
            'campaign_id' => $campaign->id,
            'organization_id' => $org->id,
            'user_id' => $failOwner->id,
            'gross_amount' => 525.00,
            'net_amount' => 500.00,
            'platform_fee' => 25.00,
            'status' => 'approved',
            'approved_by' => $this->admin->id,
            'approved_at' => now(),
        ]);

        $result = $this->settlementService->processSettlementPayout($settlement);

        $settlement->refresh();
        $wallet->refresh();

        $this->assertFalse($result['success']);
        $this->assertTrue($result['retryable']);
        $this->assertEquals('retry_pending', $settlement->status);
        $this->assertNull($settlement->failed_at);
        $this->assertNull($settlement->failed_reason);
        $this->assertSame(1, $settlement->retry_count);
        $this->assertNotNull($settlement->next_retry_at);
        $this->assertEquals(0.00, (float) $wallet->pending_settlement_balance);
        $this->assertEquals(0.00, (float) $wallet->balance);

        $this->assertDatabaseMissing('wallet_transactions', [
            'source' => WalletTransaction::SOURCE_SETTLEMENT_REVERSAL,
            'reference_id' => $settlement->id,
        ]);
    }

    #[Test]
    public function payout_is_idempotent_when_already_paid(): void
    {
        $mock = $this->mockGatewayForPayout();
        $this->app->instance(RazorpayGateway::class, $mock);

        $settlement = $this->settlementService->requestSettlement($this->org, [$this->donation->id]);
        $this->settlementService->approveSettlement($settlement, $this->admin);
        $this->settlementService->processSettlementPayout($settlement);
        $settlement->refresh();
        $paidAt = $settlement->paid_at;
        $reference = $settlement->gateway_reference;

        $result = $this->settlementService->processSettlementPayout($settlement);
        $settlement->refresh();

        $this->assertTrue($result['success']);
        $this->assertEquals('paid', $settlement->status);
        $this->assertEquals($paidAt->format('Y-m-d H:i:s'), $settlement->paid_at->format('Y-m-d H:i:s'));
        $this->assertEquals($reference, $settlement->gateway_reference);
    }

    #[Test]
    public function payout_is_idempotent_when_already_failed(): void
    {
        $mock = $this->mockGatewayThatThrowsTemporary();
        $this->app->instance(RazorpayGateway::class, $mock);
        $this->settlementService = app(SettlementService::class);

        $failOwner = User::factory()->create();
        $org = Organization::factory()->create(['user_id' => $failOwner->id]);
        PayoutAccount::create([
            'organization_id' => $org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0000',
            'fund_account_id' => 'fa_FAIL',
            'is_verified' => true,
        ]);

        $wallet = $this->walletService->getOrCreateWallet($failOwner);
        $wallet->update(['balance' => 0, 'pending_settlement_balance' => 500]);

        $campaign = Campaign::create([
            'title' => 'Fail Idempotent Campaign',
            'slug' => 'fail-idem-'.uniqid(),
            'user_id' => $failOwner->id,
            'description' => 'test',
            'goal_amount' => 10000.00,
        ]);
        $settlement = CampaignSettlement::create([
            'campaign_id' => $campaign->id,
            'organization_id' => $org->id,
            'user_id' => $failOwner->id,
            'gross_amount' => 525.00,
            'net_amount' => 500.00,
            'platform_fee' => 25.00,
            'status' => 'approved',
            'approved_by' => $this->admin->id,
            'approved_at' => now(),
        ]);

        $this->settlementService->processSettlementPayout($settlement);
        $settlement->refresh();
        $this->assertEquals('retry_pending', $settlement->status);

        $result = $this->settlementService->processSettlementPayout($settlement);
        $this->assertFalse($result['success']);
        $this->assertEquals('retry_pending', $settlement->status);
    }

    #[Test]
    public function payout_retry_after_failure_can_succeed_with_fixed_org_name(): void
    {
        $failOwner = User::factory()->create();
        $org = Organization::factory()->create(['user_id' => $failOwner->id]);
        PayoutAccount::create([
            'organization_id' => $org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0000',
            'fund_account_id' => 'fa_FAIL',
            'is_verified' => true,
        ]);

        $wallet = $this->walletService->getOrCreateWallet($failOwner);
        $wallet->update(['balance' => 0, 'pending_settlement_balance' => 500]);

        $campaign = Campaign::create([
            'title' => 'Fail Then Fix Campaign',
            'slug' => 'fail-fix-'.uniqid(),
            'user_id' => $failOwner->id,
            'description' => 'test',
            'goal_amount' => 10000.00,
        ]);
        $settlement = CampaignSettlement::create([
            'campaign_id' => $campaign->id,
            'organization_id' => $org->id,
            'user_id' => $failOwner->id,
            'gross_amount' => 525.00,
            'net_amount' => 500.00,
            'platform_fee' => 25.00,
            'status' => 'approved',
            'approved_by' => $this->admin->id,
            'approved_at' => now(),
        ]);

        $mock = $this->mockGatewayThatThrowsTemporary();
        $this->app->instance(RazorpayGateway::class, $mock);
        $this->settlementService = app(SettlementService::class);

        $this->settlementService->processSettlementPayout($settlement);
        $settlement->refresh();
        $this->assertEquals('retry_pending', $settlement->status);

        $mock = $this->mockGatewayForPayout();
        $this->app->instance(RazorpayGateway::class, $mock);
        $this->settlementService = app(SettlementService::class);

        $wallet->update(['balance' => 500, 'pending_settlement_balance' => 0]);

        $settlement->update([
            'status' => 'retry_pending',
            'failed_at' => null,
            'failed_reason' => null,
            'retry_count' => 1,
        ]);

        $settlement = $settlement->fresh();
        $result = $this->settlementService->processSettlementPayout($settlement);
        $settlement->refresh();

        $this->assertTrue($result['success']);
        $this->assertEquals('paid', $settlement->status);
        $this->assertNotNull($settlement->paid_at);
        $this->assertNotEmpty($settlement->gateway_reference);
    }

    #[Test]
    public function processing_state_accessible_during_payout(): void
    {
        $mock = $this->mockGatewayForPayout();
        $this->app->instance(RazorpayGateway::class, $mock);

        $settlement = $this->settlementService->requestSettlement($this->org, [$this->donation->id]);
        $this->settlementService->approveSettlement($settlement, $this->admin);

        $this->assertFalse($settlement->refresh()->isProcessing());

        $settlement->update(['status' => 'processing', 'processed_at' => now()]);
        $this->assertTrue($settlement->refresh()->isProcessing());
        $this->assertNull($settlement->paid_at);
        $this->assertNull($settlement->failed_at);
    }

    #[Test]
    public function settlement_shows_as_approved_before_queue_processes(): void
    {
        Queue::fake();

        $settlement = $this->settlementService->requestSettlement($this->org, [$this->donation->id]);

        $this->actingAs($this->admin)
            ->post(route('admin.settlements.approve', $settlement))
            ->assertRedirect();

        $settlement->refresh();
        $this->assertEquals('approved', $settlement->status);
        $this->assertNull($settlement->paid_at);
        $this->assertNull($settlement->failed_at);
        $this->assertNull($settlement->gateway_reference);

        Queue::assertPushed(ProcessSettlementJob::class);
    }

    #[Test]
    public function double_approval_throws(): void
    {
        $mock = $this->mockGatewayForPayout();
        $this->app->instance(RazorpayGateway::class, $mock);

        $settlement = $this->settlementService->requestSettlement($this->org, [$this->donation->id]);
        $this->settlementService->approveSettlement($settlement, $this->admin);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Only pending_approval or manual_review settlements can be approved');
        $this->settlementService->approveSettlement($settlement->refresh(), $this->admin);
    }

    #[Test]
    public function failed_payout_sends_retry_notifications(): void
    {
        Notification::fake();

        $failOwner = User::factory()->create();
        $org = Organization::factory()->create(['user_id' => $failOwner->id]);
        PayoutAccount::create([
            'organization_id' => $org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0000',
            'fund_account_id' => 'fa_FAIL',
            'is_verified' => true,
        ]);

        $wallet = $this->walletService->getOrCreateWallet($failOwner);
        $wallet->update(['balance' => 0, 'pending_settlement_balance' => 500]);

        $campaign = Campaign::create([
            'title' => 'Fail Notification Campaign',
            'slug' => 'fail-notif-'.uniqid(),
            'user_id' => $failOwner->id,
            'description' => 'test',
            'goal_amount' => 10000.00,
        ]);
        $settlement = CampaignSettlement::create([
            'campaign_id' => $campaign->id,
            'organization_id' => $org->id,
            'user_id' => $failOwner->id,
            'gross_amount' => 525.00,
            'net_amount' => 500.00,
            'platform_fee' => 25.00,
            'status' => 'approved',
            'approved_by' => $this->admin->id,
            'approved_at' => now(),
        ]);

        $mock = $this->mockGatewayThatThrowsTemporary();
        $this->app->instance(RazorpayGateway::class, $mock);
        $this->settlementService = app(SettlementService::class);

        $this->settlementService->processSettlementPayout($settlement);

        Notification::assertSentTo(
            $failOwner,
            SettlementProcessingStartedNotification::class
        );

        Notification::assertSentTo(
            $failOwner,
            SettlementRetryScheduledNotification::class
        );
    }

    #[Test]
    public function balance_unchanged_when_payout_succeeds(): void
    {
        $mock = $this->mockGatewayForPayout();
        $this->app->instance(RazorpayGateway::class, $mock);

        $wallet = $this->walletService->getOrCreateWallet($this->owner);
        $this->walletService->credit($wallet, 1000.00, WalletTransaction::SOURCE_ADJUSTMENT, 2, Organization::class);

        $settlement = $this->settlementService->requestSettlement($this->org, [$this->donation->id]);
        $this->settlementService->approveSettlement($settlement, $this->admin);

        $wallet->refresh();
        $balanceAfterApprove = (float) $wallet->balance;

        $this->settlementService->processSettlementPayout($settlement);

        $wallet->refresh();
        $this->assertEquals(0.00, (float) $wallet->pending_settlement_balance);
        $this->assertEquals($balanceAfterApprove, (float) $wallet->balance, 'Payout processing must not modify wallet balance');
    }
}
