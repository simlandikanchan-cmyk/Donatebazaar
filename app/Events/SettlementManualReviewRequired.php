<?php

namespace App\Events;

use App\Models\CampaignSettlement;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SettlementManualReviewRequired
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly CampaignSettlement $settlement,
        public readonly ?string $correlationId = null,
        public readonly ?string $traceId = null
    ) {}
}
