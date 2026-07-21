<?php

namespace App\Events;

use App\Models\CampaignSettlement;
use App\Services\Risk\RiskEvaluationResult;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RiskEvaluationCompleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly CampaignSettlement $settlement,
        public readonly RiskEvaluationResult $result,
        public readonly ?string $correlationId = null,
        public readonly ?string $traceId = null
    ) {}
}
