<?php

namespace Tests\Unit\Listeners;

use App\Events\SettlementAutoApproved;
use App\Models\CampaignSettlement;
use App\Models\Organization;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SendSettlementAutoApprovedNotificationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function listener_sends_notification_on_auto_approved(): void
    {
        $owner = User::factory()->create();
        $org = Organization::factory()->create(['user_id' => $owner->id]);
        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'auto_approved',
        ]);

        $notificationService = $this->createMock(NotificationService::class);
        $notificationService->expects($this->once())
            ->method('send')
            ->with(
                $this->equalTo('settlement.auto_approved'),
                $this->callback(fn (User $user) => $user->id === $owner->id),
                $this->arrayHasKey('settlement_id')
            );

        $listener = new \App\Listeners\SendSettlementAutoApprovedNotification($notificationService);
        $listener->handle(new SettlementAutoApproved($settlement));
    }

    #[Test]
    public function listener_handles_notification_failure_gracefully(): void
    {
        $owner = User::factory()->create();
        $org = Organization::factory()->create(['user_id' => $owner->id]);
        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'auto_approved',
        ]);

        $notificationService = $this->createMock(NotificationService::class);
        $notificationService->method('send')
            ->willThrowException(new \RuntimeException('Notification service down'));

        $listener = new \App\Listeners\SendSettlementAutoApprovedNotification($notificationService);

        // Should not throw
        $listener->handle(new SettlementAutoApproved($settlement));
        $this->expectNotToPerformAssertions();
    }
}
