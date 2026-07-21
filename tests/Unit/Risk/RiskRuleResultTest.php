<?php

namespace Tests\Unit\Risk;

use App\Services\Risk\RiskRuleResult;
use Tests\TestCase;

class RiskRuleResultTest extends TestCase
{
    public function test_triggered_factory_sets_triggered_and_force_review(): void
    {
        $result = RiskRuleResult::triggered(true, ['key' => 'value']);

        $this->assertTrue($result->triggered);
        $this->assertTrue($result->forceReview);
        $this->assertSame(['key' => 'value'], $result->detail);
    }

    public function test_triggered_factory_defaults_force_review_to_false(): void
    {
        $result = RiskRuleResult::triggered();

        $this->assertTrue($result->triggered);
        $this->assertFalse($result->forceReview);
        $this->assertSame([], $result->detail);
    }

    public function test_not_triggered_factory_sets_triggered_false(): void
    {
        $result = RiskRuleResult::notTriggered(['reason' => 'ok']);

        $this->assertFalse($result->triggered);
        $this->assertFalse($result->forceReview);
        $this->assertSame(['reason' => 'ok'], $result->detail);
    }

    public function test_not_triggered_factory_defaults_detail_to_empty_array(): void
    {
        $result = RiskRuleResult::notTriggered();

        $this->assertFalse($result->triggered);
        $this->assertFalse($result->forceReview);
        $this->assertSame([], $result->detail);
    }

    public function test_constructor_creates_immutable_dto(): void
    {
        $result = new RiskRuleResult(true, true, ['meta' => 1]);

        $this->assertTrue($result->triggered);
        $this->assertTrue($result->forceReview);
        $this->assertSame(['meta' => 1], $result->detail);
    }
}
