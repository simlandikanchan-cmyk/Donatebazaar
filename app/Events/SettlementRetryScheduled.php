<?php

namespace App\Events;

use App\Models\CampaignSettlement;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SettlementRetryScheduled
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly CampaignSettlement $settlement,
        public readonly \DateTimeInterface $nextRetryAt,
        public readonly int $retryCount,
        public readonly ?string $correlationId = null,
        public readonly ?string $traceId = null
    ) {}
}
