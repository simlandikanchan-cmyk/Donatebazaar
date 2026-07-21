<?php

namespace App\Listeners;

use App\Events\SettlementProcessingStarted;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;

class SendSettlementProcessingStartedNotification
{
    public function __construct(private NotificationService $notifications) {}

    public function handle(SettlementProcessingStarted $event): void
    {
        $settlement = $event->settlement;
        $owner = $settlement->organization?->owner;

        if (! $owner) {
            return;
        }

        try {
            $this->notifications->send('settlement.processing_started', $owner, [
                'settlement_id' => $settlement->id,
                'amount' => $settlement->net_amount,
            ]);

            Log::info('Notification sent', [
                'event' => 'SettlementProcessingStarted',
                'settlement_id' => $settlement->id,
                'correlation_id' => $event->correlationId,
                'trace_id' => $event->traceId,
                'status' => 'sent',
            ]);
        } catch (\Throwable $e) {
            Log::error('Notification failed', [
                'event' => 'SettlementProcessingStarted',
                'settlement_id' => $settlement->id,
                'correlation_id' => $event->correlationId,
                'trace_id' => $event->traceId,
                'status' => 'failed',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
