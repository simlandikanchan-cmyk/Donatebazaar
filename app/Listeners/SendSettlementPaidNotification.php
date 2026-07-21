<?php

namespace App\Listeners;

use App\Events\SettlementPaid;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;

class SendSettlementPaidNotification
{
    public function __construct(private NotificationService $notifications) {}

    public function handle(SettlementPaid $event): void
    {
        $settlement = $event->settlement;
        $owner = $settlement->organization?->owner;

        if (! $owner) {
            return;
        }

        try {
            $this->notifications->send('settlement.paid', $owner, [
                'settlement_id' => $settlement->id,
                'amount' => $settlement->net_amount,
                'gateway_reference' => $event->gatewayReference,
            ]);

            Log::info('Notification sent', [
                'event' => 'SettlementPaid',
                'settlement_id' => $settlement->id,
                'correlation_id' => $event->correlationId,
                'trace_id' => $event->traceId,
                'status' => 'sent',
            ]);
        } catch (\Throwable $e) {
            Log::error('Notification failed', [
                'event' => 'SettlementPaid',
                'settlement_id' => $settlement->id,
                'correlation_id' => $event->correlationId,
                'trace_id' => $event->traceId,
                'status' => 'failed',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
