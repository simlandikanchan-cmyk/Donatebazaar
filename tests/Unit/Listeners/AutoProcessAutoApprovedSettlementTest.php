<?php

namespace Tests\Unit\Listeners;

use App\Events\SettlementAutoApproved;
use App\Jobs\ProcessSettlementJob;
use App\Listeners\AutoProcessAutoApprovedSettlement;
use App\Models\CampaignSettlement;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AutoProcessAutoApprovedSettlementTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function listener_queues_payout_job_for_auto_approved_settlement(): void
    {
        Queue::fake();

        $owner = User::factory()->create();
        $org = Organization::factory()->create(['user_id' => $owner->id]);
        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'auto_approved',
        ]);

        $listener = new AutoProcessAutoApprovedSettlement();
        $listener->handle(new SettlementAutoApproved($settlement));

        Queue::assertPushed(ProcessSettlementJob::class, function ($job) use ($settlement) {
            return $job->settlement->id === $settlement->id && $job->delay !== null;
        });
    }

    #[Test]
    public function listener_skips_settlements_not_in_auto_approved_status(): void
    {
        Queue::fake();

        $owner = User::factory()->create();
        $org = Organization::factory()->create(['user_id' => $owner->id]);
        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'manual_review',
        ]);

        $listener = new AutoProcessAutoApprovedSettlement();
        $listener->handle(new SettlementAutoApproved($settlement));

        Queue::assertNotPushed(ProcessSettlementJob::class);
    }
}
