<?php

namespace App\Listeners;

use App\Events\SettlementRetryScheduled;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;

class SendSettlementRetryScheduledNotification
{
    public function __construct(private NotificationService $notifications) {}

    public function handle(SettlementRetryScheduled $event): void
    {
        $settlement = $event->settlement;
        $owner = $settlement->organization?->owner;

        if (! $owner) {
            return;
        }

        try {
            $this->notifications->send('settlement.retry_scheduled', $owner, [
                'settlement_id' => $settlement->id,
                'amount' => $settlement->net_amount,
                'next_retry_at' => $event->nextRetryAt->format('Y-m-d H:i:s'),
                'retry_count' => $event->retryCount,
            ]);

            Log::info('Notification sent', [
                'event' => 'SettlementRetryScheduled',
                'settlement_id' => $settlement->id,
                'correlation_id' => $event->correlationId,
                'trace_id' => $event->traceId,
                'status' => 'sent',
            ]);
        } catch (\Throwable $e) {
            Log::error('Notification failed', [
                'event' => 'SettlementRetryScheduled',
                'settlement_id' => $settlement->id,
                'correlation_id' => $event->correlationId,
                'trace_id' => $event->traceId,
                'status' => 'failed',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
