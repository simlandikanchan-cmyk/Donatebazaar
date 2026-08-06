<?php

namespace App\Jobs;

use App\Services\Reconciliation\ReconciliationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ReconciliationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public int $tries = 3;

    public int $timeout = 120;

    public array $backoff = [60, 300, 900];

    public function __construct(
        public readonly ?int $batchSize = null,
        public readonly ?int $processingStuckMinutes = null
    ) {}

    public function handle(ReconciliationService $reconciliationService): void
    {
        $startTime = microtime(true);
        $results = $reconciliationService->reconcile();
        $duration = microtime(true) - $startTime;

        $reconciledCount = count(array_filter($results, fn ($r) => $r->reconciled));
        $skippedCount = count(array_filter($results, fn ($r) => ! $r->reconciled));

        Log::info('Reconciliation job completed', [
            'total_processed' => count($results),
            'reconciled' => $reconciledCount,
            'skipped' => $skippedCount,
            'duration_ms' => round($duration * 1000, 2),
        ]);
    }
}
