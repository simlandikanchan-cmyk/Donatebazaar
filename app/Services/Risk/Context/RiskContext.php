<?php

namespace App\Services\Risk\Context;

use App\Models\CampaignSettlement;
use App\Models\Organization;
use App\Models\PayoutAccount;

/**
 * Immutable input bundle handed to every RiskRule.
 *
 * Rules read only. Dynamic signals (velocity counters, AML hits) are
 * pre-computed by the engine and passed in, so rules stay pure and testable.
 */
final class RiskContext
{
    public function __construct(
        public readonly CampaignSettlement $settlement,
        public readonly Organization $organization,
        public readonly ?PayoutAccount $payoutAccount,
        public readonly array $signals = [],   // velocity, aml, fraud, etc.
        public readonly array $extra = []      // rule-specific configuration overrides
    ) {}

    public function signal(string $key, $default = null)
    {
        return $this->signals[$key] ?? $default;
    }
}
