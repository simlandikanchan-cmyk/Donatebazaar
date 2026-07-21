<?php

namespace Tests\Unit\Risk;

use App\Models\RiskRule;
use App\Services\Risk\EvaluatedRule;
use App\Services\Risk\RiskRuleResult;
use App\Services\Risk\ScoreCalculator;
use Tests\TestCase;

class ScoreCalculatorTest extends TestCase
{
    private function calculator(): ScoreCalculator
    {
        return new ScoreCalculator;
    }

    private function evaluated(string $name, bool $triggered, int $weight = 10, bool $forceReview = false): EvaluatedRule
    {
        $model = new RiskRule([
            'name' => $name,
            'category' => 'KYC',
            'weight' => $weight,
            'priority' => 1,
            'enabled' => true,
            'force_review' => $forceReview,
        ]);

        return new EvaluatedRule($model, $triggered ? RiskRuleResult::triggered($forceReview) : RiskRuleResult::notTriggered());
    }

    public function test_empty_rule_set_returns_zero_score(): void
    {
        $result = $this->calculator()->calculate([]);

        $this->assertSame(0, $result['score']);
        $this->assertSame([], $result['triggered_rules']);
    }

    public function test_single_triggered_rule_adds_its_weight(): void
    {
        $evaluated = [$this->evaluated('RULE_A', true, 15)];

        $result = $this->calculator()->calculate($evaluated);

        $this->assertSame(15, $result['score']);
        $this->assertSame(['RULE_A'], $result['triggered_rules']);
    }

    public function test_single_not_triggered_rule_returns_zero(): void
    {
        $evaluated = [$this->evaluated('RULE_A', false, 15)];

        $result = $this->calculator()->calculate($evaluated);

        $this->assertSame(0, $result['score']);
        $this->assertSame([], $result['triggered_rules']);
    }

    public function test_multiple_triggered_rules_sum_weights(): void
    {
        $evaluated = [
            $this->evaluated('RULE_A', true, 10),
            $this->evaluated('RULE_B', true, 20),
            $this->evaluated('RULE_C', true, 30),
        ];

        $result = $this->calculator()->calculate($evaluated);

        $this->assertSame(60, $result['score']);
        $this->assertSame(['RULE_A', 'RULE_B', 'RULE_C'], $result['triggered_rules']);
    }

    public function test_mixed_triggered_and_not_triggered_rules(): void
    {
        $evaluated = [
            $this->evaluated('RULE_A', true, 10),
            $this->evaluated('RULE_B', false, 20),
            $this->evaluated('RULE_C', true, 5),
        ];

        $result = $this->calculator()->calculate($evaluated);

        $this->assertSame(15, $result['score']);
        $this->assertSame(['RULE_A', 'RULE_C'], $result['triggered_rules']);
    }

    public function test_score_is_clamped_to_max_100(): void
    {
        $evaluated = [
            $this->evaluated('RULE_A', true, 50),
            $this->evaluated('RULE_B', true, 60),
        ];

        $result = $this->calculator()->calculate($evaluated);

        $this->assertSame(100, $result['score']);
    }

    public function test_score_is_clamped_to_min_0(): void
    {
        $evaluated = [
            $this->evaluated('RULE_A', true, -10),
        ];

        $result = $this->calculator()->calculate($evaluated);

        $this->assertSame(0, $result['score']);
    }
}
