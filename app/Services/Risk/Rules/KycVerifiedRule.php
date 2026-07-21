<?php

namespace App\Services\Risk\Rules;

use App\Services\Risk\Context\RiskContext;
use App\Services\Risk\RiskRule;
use App\Services\Risk\RiskRuleResult;

/**
 * Example rule: organisation KYC verified.
 * Threshold/points come from the risk_rules DB row, not here.
 */
final class KycVerifiedRule implements RiskRule
{
    public function identifier(): string
    {
        return 'KYC_VERIFIED';
    }

    public function name(): string
    {
        return $this->identifier();
    }

    public function evaluate(RiskContext $context): RiskRuleResult
    {
        $verified = $context->organization->verification_status === 'verified';

        return $verified
            ? RiskRuleResult::notTriggered(['verification_status' => $context->organization->verification_status])
            : RiskRuleResult::triggered(false, ['verification_status' => $context->organization->verification_status]);
    }
}
