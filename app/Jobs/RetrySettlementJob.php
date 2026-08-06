<?php

namespace App\Jobs;

use App\Models\CampaignSettlement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RetrySettlementJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public int $tries = 3;

    public int $timeout = 120;

    public array $backoff = [60, 300, 900];

    public function __construct(
        public readonly CampaignSettlement $settlement
    ) {}

    public function handle(RetryPolicy $policy, ProcessSettlementJob $job): void
    {
        $settlement = $this->settlement->fresh();

        if (! $settlement->isRetryPending()) {
            return;
        }

        if ($settlement->next_retry_at > now()) {
            return;
        }

        $retryCount = $settlement->retry_count ?? 0;
        if ($retryCount >= $policy->maxRetries()) {
            Log::warning('Max retry attempts exceeded', [
                'settlement_id' => $settlement->id,
                'retry_count' => $retryCount,
            ]);

            return;
        }

        $job::dispatch($settlement);
    }
}
