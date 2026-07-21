<?php

namespace App\Listeners;

use App\Events\SettlementManualReviewRequired;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;

class SendSettlementManualReviewRequiredNotification
{
    public function __construct(private NotificationService $notifications) {}

    public function handle(SettlementManualReviewRequired $event): void
    {
        $settlement = $event->settlement;
        $owner = $settlement->organization?->owner;

        if (! $owner) {
            return;
        }

        try {
            $this->notifications->send('settlement.manual_review', $owner, [
                'settlement_id' => $settlement->id,
                'amount' => $settlement->net_amount,
                'risk_score' => $settlement->risk_score,
            ]);

            Log::info('Notification sent', [
                'event' => 'SettlementManualReviewRequired',
                'settlement_id' => $settlement->id,
                'correlation_id' => $event->correlationId,
                'trace_id' => $event->traceId,
                'status' => 'sent',
            ]);
        } catch (\Throwable $e) {
            Log::error('Notification failed', [
                'event' => 'SettlementManualReviewRequired',
                'settlement_id' => $settlement->id,
                'correlation_id' => $event->correlationId,
                'trace_id' => $event->traceId,
                'status' => 'failed',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
