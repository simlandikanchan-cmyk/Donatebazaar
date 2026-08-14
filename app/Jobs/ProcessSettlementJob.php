<?php

namespace App\Jobs;

use App\Models\CampaignSettlement;
use App\Models\PayoutAttempt;
use App\Services\SettlementService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProcessSettlementJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 120;

    public array $backoff = [60, 300, 900];

    public function __construct(
        public readonly CampaignSettlement $settlement,
        public readonly ?string $correlationId = null,
        public readonly ?string $traceId = null
    ) {}

    public function handle(SettlementService $settlementService): void
    {
        $lock = Cache::lock("settlement:{$this->settlement->id}:processing", 300);

        try {
            $lock->block(5, function () use ($settlementService) {
                $this->process($settlementService);
            });
        } finally {
            optional($lock)->release();
        }
    }

    private function process(SettlementService $settlementService): void
    {
        $settlement = $this->settlement->fresh();

        if ($settlement->isPaid() || $settlement->isFailed()) {
            return;
        }

        $attemptNumber = ($settlement->retry_count ?? 0) + 1;

        // Enforce max retries at the job level — if retry_count already
        // meets the policy limit, do not attempt again. This is a safety net
        // in addition to RetrySettlementJob's check.
        if (($settlement->retry_count ?? 0) >= app(RetryPolicy::class)->maxRetries()) {
            Log::warning('ProcessSettlementJob: max retries already reached', [
                'settlement_id' => $settlement->id,
                'retry_count' => $settlement->retry_count,
            ]);

            return;
        }
        $idempotencyKey = PayoutAttempt::generateIdempotencyKey($settlement, $attemptNumber);

        $existingAttempt = PayoutAttempt::where('idempotency_key', $idempotencyKey)->first();
        if ($existingAttempt && $existingAttempt->status === 'completed') {
            return;
        }

        // Reuse the attempt when a previous run of this job crashed after
        // creating it: saving a second row with the same idempotency key
        // would hit the unique index and leave the settlement stuck.
        $attempt = $existingAttempt ?? PayoutAttempt::forSettlement($settlement, $attemptNumber);
        if ($existingAttempt === null) {
            $attempt->save();
        }

        $attempt->update([
            'started_at' => now(),
            'status' => 'initiated',
        ]);

        try {
            $result = $settlementService->processSettlementPayout($settlement);

            if ($result['success']) {
                $attempt->update([
                    'status' => 'completed',
                    'finished_at' => now(),
                    'gateway_reference' => $settlement->fresh()->gateway_reference,
                ]);
            } else {
                $attempt->update([
                    'status' => 'failed',
                    'finished_at' => now(),
                    'error_message' => $result['message'] ?? null,
                ]);

                if ($result['retryable'] ?? false) {
                    // The service already transitioned the settlement to
                    // retry_pending and scheduled the next attempt: do NOT
                    // rethrow (Laravel would re-run this job and call the
                    // gateway again), just hand over to the retry job.
                    RetrySettlementJob::dispatch($settlement)->delay(
                        app(RetryPolicy::class)->nextRetryAt($attemptNumber)
                    );
                }
            }
        } catch (\Throwable $e) {
            if (! isset($result) || $result['success']) {
                $attempt->update([
                    'status' => 'failed',
                    'finished_at' => now(),
                    'error_message' => $e->getMessage(),
                ]);
            }

            Log::error('Payout job exception', [
                'settlement_id' => $settlement->id,
                'attempt' => $attemptNumber,
                'error' => $e->getMessage(),
                'correlation_id' => $this->correlationId,
                'trace_id' => $this->traceId,
            ]);

            throw $e;
        }
    }
}
