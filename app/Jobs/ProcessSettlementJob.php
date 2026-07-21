<?php

namespace App\Jobs;

use App\Exceptions\TemporaryFailureException;
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

    public int $timeout = 120;

    public int $tries = 1;

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
        $idempotencyKey = PayoutAttempt::generateIdempotencyKey($settlement, $attemptNumber);

        $existingAttempt = PayoutAttempt::where('idempotency_key', $idempotencyKey)->first();
        if ($existingAttempt && $existingAttempt->status === 'completed') {
            return;
        }

        $attempt = PayoutAttempt::forSettlement($settlement, $attemptNumber);
        $attempt->save();

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
                    'gateway_reference' => $settlement->gateway_reference,
                ]);
            } else {
                $attempt->update([
                    'status' => 'failed',
                    'finished_at' => now(),
                    'error_message' => $result['message'] ?? null,
                ]);

                if ($result['retryable'] ?? false) {
                    RetrySettlementJob::dispatch($settlement)->delay(
                        app(\App\Jobs\RetryPolicy::class)->nextRetryAt($attemptNumber)
                    );

                    throw new TemporaryFailureException($result['message']);
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
