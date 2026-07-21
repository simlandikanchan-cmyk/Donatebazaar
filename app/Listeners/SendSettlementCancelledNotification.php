<?php

namespace App\Listeners;

use App\Events\SettlementCancelled;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;

class SendSettlementCancelledNotification
{
    public function __construct(private NotificationService $notifications) {}

    public function handle(SettlementCancelled $event): void
    {
        $settlement = $event->settlement;
        $owner = $settlement->organization?->owner;

        if (! $owner) {
            return;
        }

        try {
            $this->notifications->send('settlement.cancelled', $owner, [
                'settlement_id' => $settlement->id,
                'amount' => $settlement->net_amount,
                'reason' => $event->reason,
            ]);

            Log::info('Notification sent', [
                'event' => 'SettlementCancelled',
                'settlement_id' => $settlement->id,
                'correlation_id' => $event->correlationId,
                'trace_id' => $event->traceId,
                'status' => 'sent',
            ]);
        } catch (\Throwable $e) {
            Log::error('Notification failed', [
                'event' => 'SettlementCancelled',
                'settlement_id' => $settlement->id,
                'correlation_id' => $event->correlationId,
                'trace_id' => $event->traceId,
                'status' => 'failed',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
