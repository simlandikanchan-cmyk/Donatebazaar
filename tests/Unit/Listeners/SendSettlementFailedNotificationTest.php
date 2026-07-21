<?php

namespace Tests\Unit\Listeners;

use App\Events\SettlementFailed;
use App\Models\CampaignSettlement;
use App\Models\Organization;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SendSettlementFailedNotificationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function listener_sends_notification_on_failed(): void
    {
        $owner = User::factory()->create();
        $org = Organization::factory()->create(['user_id' => $owner->id]);
        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'failed',
        ]);

        $notificationService = $this->createMock(NotificationService::class);
        $notificationService->expects($this->once())
            ->method('send')
            ->with(
                $this->equalTo('settlement.failed'),
                $this->callback(fn (User $user) => $user->id === $owner->id),
                $this->arrayHasKey('settlement_id')
            );

        $listener = new \App\Listeners\SendSettlementFailedNotification($notificationService);
        $listener->handle(new SettlementFailed($settlement, 'Gateway timeout'));
    }
}
