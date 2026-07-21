<?php

namespace App\Listeners;

use App\Events\SettlementFailed;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;

class SendSettlementFailedNotification
{
    public function __construct(private NotificationService $notifications) {}

    public function handle(SettlementFailed $event): void
    {
        $settlement = $event->settlement;
        $owner = $settlement->organization?->owner;

        if (! $owner) {
            return;
        }

        try {
            $this->notifications->send('settlement.failed', $owner, [
                'settlement_id' => $settlement->id,
                'amount' => $settlement->net_amount,
                'failure_reason' => $event->failureReason,
            ]);

            Log::info('Notification sent', [
                'event' => 'SettlementFailed',
                'settlement_id' => $settlement->id,
                'correlation_id' => $event->correlationId,
                'trace_id' => $event->traceId,
                'status' => 'sent',
            ]);
        } catch (\Throwable $e) {
            Log::error('Notification failed', [
                'event' => 'SettlementFailed',
                'settlement_id' => $settlement->id,
                'correlation_id' => $event->correlationId,
                'trace_id' => $event->traceId,
                'status' => 'failed',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
