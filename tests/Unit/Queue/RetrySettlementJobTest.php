<?php

namespace Tests\Unit\Queue;

use App\Jobs\ProcessSettlementJob;
use App\Jobs\RetryPolicy;
use App\Jobs\RetrySettlementJob;
use App\Models\CampaignSettlement;
use App\Models\Organization;
use App\Models\PayoutAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RetrySettlementJobTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function retry_job_dispatches_process_job_for_eligible_settlement(): void
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
            'status' => 'retry_pending',
            'retry_count' => 1,
            'next_retry_at' => now()->subMinute(),
        ]);

        Queue::fake();

        $job = new RetrySettlementJob($settlement);
        $job->handle(app(RetryPolicy::class), new ProcessSettlementJob($settlement));

        Queue::assertPushed(ProcessSettlementJob::class);
    }

    #[Test]
    public function retry_job_respects_next_retry_at(): void
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
            'status' => 'retry_pending',
            'retry_count' => 1,
            'next_retry_at' => now()->addHour(),
        ]);

        Queue::fake();

        $job = new RetrySettlementJob($settlement);
        $job->handle(app(RetryPolicy::class), new ProcessSettlementJob($settlement));

        Queue::assertNotPushed(ProcessSettlementJob::class);
    }

    #[Test]
    public function retry_job_respects_max_retry_count(): void
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
            'status' => 'retry_pending',
            'retry_count' => 4,
            'next_retry_at' => now()->subMinute(),
        ]);

        Queue::fake();

        $job = new RetrySettlementJob($settlement);
        $job->handle(app(RetryPolicy::class), new ProcessSettlementJob($settlement));

        Queue::assertNotPushed(ProcessSettlementJob::class);
    }

    #[Test]
    public function retry_job_skips_non_retry_pending_settlements(): void
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
            'retry_count' => 0,
        ]);

        Queue::fake();

        $job = new RetrySettlementJob($settlement);
        $job->handle(app(RetryPolicy::class), new ProcessSettlementJob($settlement));

        Queue::assertNotPushed(ProcessSettlementJob::class);
    }
}
