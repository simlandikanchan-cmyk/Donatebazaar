<?php

namespace App\Listeners;

use App\Events\SettlementRejected;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;

class SendSettlementRejectedNotification
{
    public function __construct(private NotificationService $notifications) {}

    public function handle(SettlementRejected $event): void
    {
        $settlement = $event->settlement;
        $owner = $settlement->organization?->owner;

        if (! $owner) {
            return;
        }

        try {
            $this->notifications->send('settlement.rejected', $owner, [
                'settlement_id' => $settlement->id,
                'amount' => $settlement->net_amount,
            ]);

            Log::info('Notification sent', [
                'event' => 'SettlementRejected',
                'settlement_id' => $settlement->id,
                'correlation_id' => $event->correlationId,
                'trace_id' => $event->traceId,
                'status' => 'sent',
            ]);
        } catch (\Throwable $e) {
            Log::error('Notification failed', [
                'event' => 'SettlementRejected',
                'settlement_id' => $settlement->id,
                'correlation_id' => $event->correlationId,
                'trace_id' => $event->traceId,
                'status' => 'failed',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
