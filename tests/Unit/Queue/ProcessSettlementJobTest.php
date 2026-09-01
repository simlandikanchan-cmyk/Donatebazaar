<?php

namespace Tests\Unit\Queue;

use App\Gateways\RazorpayGateway;
use App\Jobs\ProcessSettlementJob;
use App\Jobs\RetryPolicy;
use App\Jobs\RetrySettlementJob;
use App\Models\CampaignSettlement;
use App\Models\Organization;
use App\Models\PayoutAccount;
use App\Models\PayoutAttempt;
use App\Models\User;
use App\Services\SettlementService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\BadRequestError;
use Razorpay\Api\Errors\GatewayError;
use Razorpay\Api\Transfer;
use Tests\TestCase;

class ProcessSettlementJobTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function successful_job_marks_settlement_paid(): void
    {
        $org = Organization::factory()->create();
        $user = User::factory()->create();
        $org->update(['user_id' => $user->id]);

        PayoutAccount::create([
            'organization_id' => $org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0001234',
            'fund_account_id' => 'fa_ABCDEFG',
            'is_verified' => true,
        ]);

        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'approved',
            'net_amount' => 500.00,
        ]);

        $mock = $this->createMockGatewayForPayout();
        $this->app->instance(RazorpayGateway::class, $mock);
        $settlementService = app(SettlementService::class);

        $job = new ProcessSettlementJob($settlement);
        $job->handle($settlementService);

        $settlement->refresh();
        $this->assertSame('paid', $settlement->status);
        $this->assertNotNull($settlement->paid_at);
    }

    #[Test]
    public function timeout_creates_payout_attempt_and_schedules_retry(): void
    {
        $org = Organization::factory()->create();
        $user = User::factory()->create();
        $org->update(['user_id' => $user->id]);

        PayoutAccount::create([
            'organization_id' => $org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0001234',
            'fund_account_id' => 'fa_ABCDEFG',
            'is_verified' => true,
        ]);

        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'approved',
            'net_amount' => 500.00,
        ]);

        $mock = $this->createMockGatewayForTimeout();
        $this->app->instance(RazorpayGateway::class, $mock);
        $settlementService = app(SettlementService::class);

        Queue::fake();

        $job = new ProcessSettlementJob($settlement);
        $job->handle($settlementService);

        $settlement->refresh();
        $this->assertSame('retry_pending', $settlement->status);
        $this->assertSame(1, $settlement->retry_count);
        $this->assertNotNull($settlement->next_retry_at);

        $this->assertDatabaseHas('payout_attempts', [
            'settlement_id' => $settlement->id,
            'status' => 'failed',
            'error_message' => 'Gateway timeout: unable to process payout.',
        ]);
    }

    #[Test]
    public function temporary_failure_schedules_retry(): void
    {
        $org = Organization::factory()->create();
        $user = User::factory()->create();
        $org->update(['user_id' => $user->id]);

        PayoutAccount::create([
            'organization_id' => $org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0001234',
            'fund_account_id' => 'fa_ABCDEFG',
            'is_verified' => true,
        ]);

        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'approved',
            'net_amount' => 500.00,
        ]);

        $mock = $this->createMockGatewayForTemporaryFailure();
        $this->app->instance(RazorpayGateway::class, $mock);
        $settlementService = app(SettlementService::class);

        Queue::fake();

        $job = new ProcessSettlementJob($settlement);
        $job->handle($settlementService);

        $settlement->refresh();
        $this->assertSame('retry_pending', $settlement->status);
        $this->assertSame(1, $settlement->retry_count);

        Queue::assertPushed(RetrySettlementJob::class);
    }

    #[Test]
    public function permanent_failure_marks_settlement_failed(): void
    {
        $org = Organization::factory()->create();
        $user = User::factory()->create();
        $org->update(['user_id' => $user->id]);

        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'approved',
            'net_amount' => 500.00,
        ]);

        $mock = $this->createMockGatewayForPermanentFailure();
        $this->app->instance(RazorpayGateway::class, $mock);
        $settlementService = app(SettlementService::class);

        $job = new ProcessSettlementJob($settlement);
        $job->handle($settlementService);

        $settlement->refresh();
        $this->assertSame('failed', $settlement->status);
    }

    #[Test]
    public function duplicate_execution_is_idempotent(): void
    {
        $org = Organization::factory()->create();
        $user = User::factory()->create();
        $org->update(['user_id' => $user->id]);

        PayoutAccount::create([
            'organization_id' => $org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0001234',
            'fund_account_id' => 'fa_ABCDEFG',
            'is_verified' => true,
        ]);

        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'approved',
            'net_amount' => 500.00,
        ]);

        $mock = $this->createMockGatewayForPayout();
        $this->app->instance(RazorpayGateway::class, $mock);
        $settlementService = app(SettlementService::class);

        $job = new ProcessSettlementJob($settlement);
        $job->handle($settlementService);

        $settlement->refresh();
        $this->assertSame('paid', $settlement->status);

        $job2 = new ProcessSettlementJob($settlement);
        $job2->handle($settlementService);

        $settlement->refresh();
        $this->assertSame('paid', $settlement->status);
        $this->assertSame(1, PayoutAttempt::where('settlement_id', $settlement->id)->count());
    }

    #[Test]
    public function already_paid_settlement_is_skipped(): void
    {
        $org = Organization::factory()->create();
        $user = User::factory()->create();
        $org->update(['user_id' => $user->id]);

        PayoutAccount::create([
            'organization_id' => $org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0001234',
            'fund_account_id' => 'fa_ABCDEFG',
            'is_verified' => true,
        ]);

        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'paid',
            'net_amount' => 500.00,
        ]);

        $mock = $this->createMockGatewayForPayout();
        $this->app->instance(RazorpayGateway::class, $mock);
        $settlementService = app(SettlementService::class);

        $job = new ProcessSettlementJob($settlement);
        $job->handle($settlementService);

        $this->assertSame('paid', $settlement->status);
    }

    #[Test]
    public function job_does_not_process_when_max_retries_exceeded(): void
    {
        $org = Organization::factory()->create();
        $user = User::factory()->create();
        $org->update(['user_id' => $user->id]);

        PayoutAccount::create([
            'organization_id' => $org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0001234',
            'fund_account_id' => 'fa_ABCDEFG',
            'is_verified' => true,
        ]);

        $policy = app(RetryPolicy::class);
        $maxRetries = $policy->maxRetries();

        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'retry_pending',
            'net_amount' => 500.00,
            'retry_count' => $maxRetries,
        ]);

        $initialPayoutCount = PayoutAttempt::count();

        $mock = $this->createMockGatewayForPayout();
        $this->app->instance(RazorpayGateway::class, $mock);
        $settlementService = app(SettlementService::class);

        $job = new ProcessSettlementJob($settlement);
        $job->handle($settlementService);

        // No new payout attempt should be created
        $this->assertSame($initialPayoutCount, PayoutAttempt::count());
    }

    private function createMockGatewayForPayout(): RazorpayGateway
    {
        $mockApi = new class extends Api {
            public function __construct() {}
            public $transfer;
        };
        $mockApi->transfer = new class($mockApi) extends Transfer {
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

    private function createMockGatewayForTimeout(): RazorpayGateway
    {
        $mockApi = new class extends Api {
            public function __construct() {}
            public $transfer;
        };
        $mockApi->transfer = new class($mockApi) extends Transfer {
            private $api;
            public function __construct($api) { $this->api = $api; }
            public function create($attributes = []) {
                throw new \WpOrg\Requests\Exception\Transport\Curl('Connection timed out', 'cURLEasy', null, 28);
            }
        };

        return new RazorpayGateway(
            keyId: 'test_key',
            keySecret: 'test_secret',
            webhookSecret: 'test_webhook_secret',
            api: $mockApi
        );
    }

    private function createMockGatewayForTemporaryFailure(): RazorpayGateway
    {
        $mockApi = new class extends Api {
            public function __construct() {}
            public $transfer;
        };
        $mockApi->transfer = new class($mockApi) extends Transfer {
            private $api;
            public function __construct($api) { $this->api = $api; }
            public function create($attributes = []) {
                throw new \App\Exceptions\TemporaryFailureException('Provider temporarily unable to process payout.');
            }
        };

        return new RazorpayGateway(
            keyId: 'test_key',
            keySecret: 'test_secret',
            webhookSecret: 'test_webhook_secret',
            api: $mockApi
        );
    }

    private function createMockGatewayForPermanentFailure(): RazorpayGateway
    {
        $mockApi = new class extends Api {
            public function __construct() {}
            public $transfer;
        };
        $mockApi->transfer = new class($mockApi) extends Transfer {
            private $api;
            public function __construct($api) { $this->api = $api; }
            public function create($attributes = []) {
                throw new \App\Exceptions\PermanentFailureException('Invalid payout account.');
            }
        };

        return new RazorpayGateway(
            keyId: 'test_key',
            keySecret: 'test_secret',
            webhookSecret: 'test_webhook_secret',
            api: $mockApi
        );
    }
}
