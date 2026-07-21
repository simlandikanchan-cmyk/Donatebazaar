<?php

namespace App\Listeners;

use App\Events\SettlementAutoApproved;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;

class SendSettlementAutoApprovedNotification
{
    public function __construct(private NotificationService $notifications) {}

    public function handle(SettlementAutoApproved $event): void
    {
        $settlement = $event->settlement;
        $owner = $settlement->organization?->owner;

        if (! $owner) {
            return;
        }

        try {
            $this->notifications->send('settlement.auto_approved', $owner, [
                'settlement_id' => $settlement->id,
                'amount' => $settlement->net_amount,
                'risk_score' => $settlement->risk_score,
                'risk_verdict' => $settlement->risk_verdict,
            ]);

            Log::info('Notification sent', [
                'event' => 'SettlementAutoApproved',
                'settlement_id' => $settlement->id,
                'correlation_id' => $event->correlationId,
                'trace_id' => $event->traceId,
                'status' => 'sent',
            ]);
        } catch (\Throwable $e) {
            Log::error('Notification failed', [
                'event' => 'SettlementAutoApproved',
                'settlement_id' => $settlement->id,
                'correlation_id' => $event->correlationId,
                'trace_id' => $event->traceId,
                'status' => 'failed',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
