<?php

namespace App\Services\Risk;

use App\Models\RiskRule as RiskRuleModel;

/**
 * Computes the integer risk score from rule results + DB-configured weights.
 *
 * Pure function: no DB writes, no hardcoded numbers.
 * score = clamp(sum(points for triggered rules), 0, 100)
 */
final class ScoreCalculator
{
    public function calculate(array $evaluated): array
    {
        $score = 0;
        $triggeredRules = [];

        /** @var EvaluatedRule $e */
        foreach ($evaluated as $e) {
            if ($e->result->triggered) {
                $score += (int) $e->model->weight;
                $triggeredRules[] = $e->model->name;
            }
        }

        $score = max(0, min(100, $score));

        return [
            'score' => $score,
            'triggered_rules' => $triggeredRules,
        ];
    }
}

/**
 * Internal pair of a DB rule row + its runtime result.
 */
final class EvaluatedRule
{
    public function __construct(
        public readonly RiskRuleModel $model,
        public readonly RiskRuleResult $result
    ) {}
}
