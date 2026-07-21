<?php

namespace Tests\Unit\Listeners;

use App\Events\SettlementPaid;
use App\Models\CampaignSettlement;
use App\Models\Organization;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SendSettlementPaidNotificationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function listener_sends_notification_on_paid(): void
    {
        $owner = User::factory()->create();
        $org = Organization::factory()->create(['user_id' => $owner->id]);
        $settlement = CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'status' => 'paid',
        ]);

        $notificationService = $this->createMock(NotificationService::class);
        $notificationService->expects($this->once())
            ->method('send')
            ->with(
                $this->equalTo('settlement.paid'),
                $this->callback(fn (User $user) => $user->id === $owner->id),
                $this->arrayHasKey('settlement_id')
            );

        $listener = new \App\Listeners\SendSettlementPaidNotification($notificationService);
        $listener->handle(new SettlementPaid($settlement, 'PAYOUT_123'));
    }
}
