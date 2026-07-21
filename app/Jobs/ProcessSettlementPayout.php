<?php

namespace App\Jobs;

use App\Models\CampaignSettlement;
use App\Services\SettlementService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessSettlementPayout implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 120;

    public int $tries = 1;

    public function __construct(
        public readonly CampaignSettlement $settlement
    ) {}

    public function handle(SettlementService $settlementService): void
    {
        $result = $settlementService->processSettlementPayout($this->settlement);

        if ($result['success']) {
            Log::info('Payout job completed', [
                'settlement_id' => $this->settlement->id,
            ]);
        } else {
            Log::error('Payout job failed', [
                'settlement_id' => $this->settlement->id,
                'error' => $result['message'],
            ]);
        }
    }
}

