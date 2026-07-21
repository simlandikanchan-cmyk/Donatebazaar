<?php

namespace App\Services\Risk\Rules;

use App\Services\Risk\Context\RiskContext;
use App\Services\Risk\RiskRule;
use App\Services\Risk\RiskRuleResult;

/**
 * Example rule: AML / sanctions screen. A hit is a hard block (force_review
 * AND the engine will reject). The hit flag comes from precomputed signals.
 */
final class AmlScreenRule implements RiskRule
{
    public function identifier(): string
    {
        return 'AML_SCREEN';
    }

    public function name(): string
    {
        return $this->identifier();
    }

    public function evaluate(RiskContext $context): RiskRuleResult
    {
        $hit = (bool) $context->signal('aml_hit', false);

        return $hit
            ? RiskRuleResult::triggered(true, ['aml_hit' => true, 'list_version' => $context->signal('aml_version')])
            : RiskRuleResult::notTriggered(['aml_hit' => false]);
    }
}
