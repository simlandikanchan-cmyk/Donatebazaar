<?php

namespace Tests\Unit\Risk\Rules;

use App\Models\CampaignSettlement;
use App\Models\Organization;
use App\Services\Risk\Context\RiskContext;
use App\Services\Risk\Rules\LargePayoutAmountRule;
use Tests\TestCase;

class LargePayoutAmountRuleTest extends TestCase
{
    private function rule(): LargePayoutAmountRule
    {
        return new LargePayoutAmountRule;
    }

    private function context(float $netAmount, ?float $threshold = null): RiskContext
    {
        $settlement = CampaignSettlement::factory()->make([
            'net_amount' => $netAmount,
        ]);

        return new RiskContext(
            settlement: $settlement,
            organization: Organization::factory()->make(),
            payoutAccount: null,
            signals: [],
            extra: $threshold !== null ? ['threshold' => $threshold] : []
        );
    }

    public function test_identifier_returns_rule_name(): void
    {
        $this->assertSame('LARGE_PAYOUT_AMOUNT', $this->rule()->identifier());
    }

    public function test_name_returns_rule_name(): void
    {
        $this->assertSame('LARGE_PAYOUT_AMOUNT', $this->rule()->name());
    }

    public function test_amount_below_threshold_is_not_triggered(): void
    {
        $result = $this->rule()->evaluate($this->context(50000, 100000));

        $this->assertFalse($result->triggered);
        $this->assertFalse($result->forceReview);
        $this->assertEquals(50000, $result->detail['amount']);
        $this->assertEquals(100000, $result->detail['threshold']);
    }

    public function test_amount_above_threshold_is_triggered(): void
    {
        $result = $this->rule()->evaluate($this->context(150000, 100000));

        $this->assertTrue($result->triggered);
        $this->assertFalse($result->forceReview);
        $this->assertEquals(150000, $result->detail['amount']);
        $this->assertEquals(100000, $result->detail['threshold']);
    }

    public function test_amount_equal_to_threshold_is_triggered(): void
    {
        $result = $this->rule()->evaluate($this->context(100000, 100000));

        $this->assertTrue($result->triggered);
    }

    public function test_uses_default_threshold_when_none_provided(): void
    {
        $result = $this->rule()->evaluate($this->context(100001));

        $this->assertTrue($result->triggered);
        $this->assertSame(100000.00, $result->detail['threshold']);
    }

    public function test_deterministic_evaluation_same_input_same_output(): void
    {
        $ctx = $this->context(150000, 100000);

        $result1 = $this->rule()->evaluate($ctx);
        $result2 = $this->rule()->evaluate($ctx);

        $this->assertSame($result1->triggered, $result2->triggered);
        $this->assertSame($result1->forceReview, $result2->forceReview);
        $this->assertSame($result1->detail, $result2->detail);
    }
}
