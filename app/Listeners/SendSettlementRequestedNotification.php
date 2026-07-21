<?php

namespace App\Listeners;

use App\Events\SettlementRequested;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;

class SendSettlementRequestedNotification
{
    public function __construct(private NotificationService $notifications) {}

    public function handle(SettlementRequested $event): void
    {
        $settlement = $event->settlement;
        $owner = $settlement->organization?->owner;

        if (! $owner) {
            return;
        }

        try {
            $this->notifications->send('settlement.requested', $owner, [
                'settlement_id' => $settlement->id,
                'amount' => $settlement->net_amount,
            ]);

            Log::info('Notification sent', [
                'event' => 'SettlementRequested',
                'settlement_id' => $settlement->id,
                'correlation_id' => $event->correlationId,
                'trace_id' => $event->traceId,
                'status' => 'sent',
            ]);
        } catch (\Throwable $e) {
            Log::error('Notification failed', [
                'event' => 'SettlementRequested',
                'settlement_id' => $settlement->id,
                'correlation_id' => $event->correlationId,
                'trace_id' => $event->traceId,
                'status' => 'failed',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
