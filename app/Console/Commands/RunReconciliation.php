<?php

namespace App\Console\Commands;

use App\Services\Reconciliation\ReconciliationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RunReconciliation extends Command
{
    protected $signature = 'settlements:reconcile {--force : Force reconciliation even if no stuck settlements found}';

    protected $description = 'Reconcile stuck settlement payouts against gateway status.';

    public function handle(): int
    {
        $service = app(ReconciliationService::class);
        $results = $service->reconcile();

        $processed = count($results);

        if ($processed === 0 && ! $this->option('force')) {
            $this->info('No stuck settlements found for reconciliation.');

            return self::SUCCESS;
        }

        $corrected = collect($results)->where('reconciled', true)->count();
        $failed = collect($results)->where('reconciled', false)->count();

        $this->info("Reconciliation complete: {$processed} processed, {$corrected} corrected, {$failed} failed/skipped.");
        Log::info('Scheduled reconciliation completed', [
            'processed' => $processed,
            'corrected' => $corrected,
            'failed' => $failed,
        ]);

        return self::SUCCESS;
    }
}
