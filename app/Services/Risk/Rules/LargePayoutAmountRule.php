<?php

namespace App\Services\Risk\Rules;

use App\Services\Risk\Context\RiskContext;
use App\Services\Risk\RiskRule;
use App\Services\Risk\RiskRuleResult;

/**
 * Example rule: payout amount exceeds a configured large-payout threshold.
 * Threshold is read from the rule's configuration JSON (from DB).
 */
final class LargePayoutAmountRule implements RiskRule
{
    public function identifier(): string
    {
        return 'LARGE_PAYOUT_AMOUNT';
    }

    public function name(): string
    {
        return $this->identifier();
    }

    public function evaluate(RiskContext $context): RiskRuleResult
    {
        $threshold = (float) ($context->extra['threshold'] ?? $this->defaultThreshold());
        $amount = (float) $context->settlement->net_amount;

        if ($amount < $threshold) {
            return RiskRuleResult::notTriggered(['amount' => $amount, 'threshold' => $threshold]);
        }

        return RiskRuleResult::triggered(false, ['amount' => $amount, 'threshold' => $threshold]);
    }

    private function defaultThreshold(): float
    {
        return 100000.00;
    }
}
