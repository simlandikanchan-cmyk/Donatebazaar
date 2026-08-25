<?php

namespace App\Listeners;

use App\Events\SettlementAutoApproved;
use App\Jobs\ProcessSettlementJob;
use Illuminate\Support\Facades\Log;

class AutoProcessAutoApprovedSettlement
{
    public function handle(SettlementAutoApproved $event): void
    {
        $settlement = $event->settlement->fresh();

        if (! $settlement->isAutoApproved()) {
            return;
        }

        ProcessSettlementJob::dispatch(
            $settlement,
            $event->correlationId,
            $event->traceId
        )->delay(now()->addSeconds(60));

        Log::info('Auto-approved settlement queued for payout', [
            'settlement_id' => $settlement->id,
            'correlation_id' => $event->correlationId,
            'trace_id' => $event->traceId,
        ]);
    }
}
