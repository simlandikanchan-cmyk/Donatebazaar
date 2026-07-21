<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Notification as NotificationFacade;

class LaravelNotificationService implements NotificationService
{
    public function send(string $type, User $user, array $data = []): void
    {
        $notificationClass = $this->resolveNotificationClass($type);

        if ($notificationClass && class_exists($notificationClass)) {
            NotificationFacade::send($user, new $notificationClass($data));
        }
    }

    private function resolveNotificationClass(string $type): ?string
    {
        return match ($type) {
            'settlement.requested' => \App\Notifications\SettlementRequestedNotification::class,
            'settlement.auto_approved' => \App\Notifications\SettlementApprovedNotification::class,
            'settlement.manual_review' => \App\Notifications\SettlementManualReviewNotification::class,
            'settlement.rejected' => \App\Notifications\SettlementRejectedNotification::class,
            'settlement.processing_started' => \App\Notifications\SettlementProcessingStartedNotification::class,
            'settlement.paid' => \App\Notifications\SettlementPaidNotification::class,
            'settlement.failed' => \App\Notifications\SettlementFailedNotification::class,
            'settlement.retry_scheduled' => \App\Notifications\SettlementRetryScheduledNotification::class,
            'settlement.cancelled' => \App\Notifications\SettlementCancelledNotification::class,
            default => null,
        };
    }
}
