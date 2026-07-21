<?php

namespace Tests\Unit\Listeners;

use App\Events\SettlementRequested;
use App\Models\CampaignSettlement;
use App\Models\Organization;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SendSettlementRequestedNotificationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function listener_sends_notification_on_settlement_requested(): void
    {
        $owner = User::factory()->create();
        $org = Organization::factory()->create(['user_id' => $owner->id]);
        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'requested',
        ]);

        $notificationService = $this->createMock(NotificationService::class);
        $notificationService->expects($this->once())
            ->method('send')
            ->with(
                $this->equalTo('settlement.requested'),
                $this->callback(fn (User $user) => $user->id === $owner->id),
                $this->arrayHasKey('settlement_id')
            );

        $listener = new \App\Listeners\SendSettlementRequestedNotification($notificationService);
        $listener->handle(new SettlementRequested($settlement));
    }

    #[Test]
    public function listener_skips_when_no_owner(): void
    {
        $org = Organization::factory()->create(['user_id' => null]);
        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'requested',
        ]);

        $notificationService = $this->createMock(NotificationService::class);
        $notificationService->expects($this->never())->method('send');

        $listener = new \App\Listeners\SendSettlementRequestedNotification($notificationService);
        $listener->handle(new SettlementRequested($settlement));
    }
}
