<?php

namespace App\Services\Risk;

use App\Services\Risk\Context\RiskContext;

interface RiskRule
{
    public function identifier(): string;

    public function evaluate(RiskContext $context): RiskRuleResult;

    public function name(): string;
}
