<?php

namespace App\Listeners;

use App\Jobs\RetrySettlementJob;
use App\Models\CampaignSettlement;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Log;

class ProcessFailedJob
{
    public function handle(JobFailed $event): void
    {
        $payload = $event->job->payload();

        if (! isset($payload['data']['command'])) {
            return;
        }

        $command = unserialize($payload['data']['command']);

        if ($command instanceof \App\Jobs\ProcessSettlementJob) {
            $settlement = $command->settlement;

            if (! $settlement instanceof CampaignSettlement) {
                return;
            }

            $retryCount = $settlement->retry_count ?? 0;
            $maxRetries = config('settlement.max_retry_attempts', 4);

            if ($retryCount < $maxRetries) {
                RetrySettlementJob::dispatch($settlement)->delay(
                    app(\App\Jobs\RetryPolicy::class)->nextRetryAt($retryCount + 1)
                );
            }
        }
    }
}
