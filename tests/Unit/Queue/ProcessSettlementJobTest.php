<?php

namespace Tests\Unit\Queue;

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
            'is_verified' => true,
        ]);

        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'approved',
            'net_amount' => 500.00,
        ]);

        $job = new ProcessSettlementJob($settlement);
        $job->handle(app(SettlementService::class));

        $settlement->refresh();
        $this->assertSame('paid', $settlement->status);
        $this->assertNotNull($settlement->paid_at);
    }

    #[Test]
    public function timeout_creates_payout_attempt_and_schedules_retry(): void
    {
        $org = Organization::factory()->create(['name' => 'FAIL Org']);
        $user = User::factory()->create();
        $org->update(['user_id' => $user->id]);

        PayoutAccount::create([
            'organization_id' => $org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0001234',
            'is_verified' => true,
        ]);

        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'approved',
            'net_amount' => 500.00,
        ]);

        Queue::fake();

        $job = new ProcessSettlementJob($settlement);
        $job->handle(app(SettlementService::class));

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
        $org = Organization::factory()->create(['name' => 'TEMP Org']);
        $user = User::factory()->create();
        $org->update(['user_id' => $user->id]);

        PayoutAccount::create([
            'organization_id' => $org->id,
            'account_holder_name' => 'Test',
            'bank_name' => 'Test Bank',
            'account_number' => '1234567890',
            'ifsc_code' => 'TEST0001234',
            'is_verified' => true,
        ]);

        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'approved',
            'net_amount' => 500.00,
        ]);

        Queue::fake();

        $job = new ProcessSettlementJob($settlement);
        $job->handle(app(SettlementService::class));

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

        $job = new ProcessSettlementJob($settlement);
        $job->handle(app(SettlementService::class));

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
            'is_verified' => true,
        ]);

        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'approved',
            'net_amount' => 500.00,
        ]);

        $job = new ProcessSettlementJob($settlement);
        $job->handle(app(SettlementService::class));

        $settlement->refresh();
        $this->assertSame('paid', $settlement->status);

        $job2 = new ProcessSettlementJob($settlement);
        $job2->handle(app(SettlementService::class));

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
            'is_verified' => true,
        ]);

        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'paid',
            'net_amount' => 500.00,
        ]);

        $job = new ProcessSettlementJob($settlement);
        $job->handle(app(SettlementService::class));

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

        $job = new ProcessSettlementJob($settlement);
        $job->handle(app(SettlementService::class));

        // No new payout attempt should be created
        $this->assertSame($initialPayoutCount, PayoutAttempt::count());
    }
}
