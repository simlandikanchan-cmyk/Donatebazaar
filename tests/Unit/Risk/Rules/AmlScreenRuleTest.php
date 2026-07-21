<?php

namespace Tests\Unit\Risk\Rules;

use App\Models\CampaignSettlement;
use App\Models\Organization;
use App\Services\Risk\Context\RiskContext;
use App\Services\Risk\Rules\AmlScreenRule;
use Tests\TestCase;

class AmlScreenRuleTest extends TestCase
{
    private function rule(): AmlScreenRule
    {
        return new AmlScreenRule;
    }

    private function context(bool $amlHit, ?string $amlVersion = null): RiskContext
    {
        $signals = ['aml_hit' => $amlHit];
        if ($amlVersion !== null) {
            $signals['aml_version'] = $amlVersion;
        }

        return new RiskContext(
            settlement: CampaignSettlement::factory()->make(),
            organization: Organization::factory()->make(),
            payoutAccount: null,
            signals: $signals,
            extra: []
        );
    }

    public function test_identifier_returns_rule_name(): void
    {
        $this->assertSame('AML_SCREEN', $this->rule()->identifier());
    }

    public function test_name_returns_rule_name(): void
    {
        $this->assertSame('AML_SCREEN', $this->rule()->name());
    }

    public function test_aml_hit_is_triggered_and_forces_review(): void
    {
        $result = $this->rule()->evaluate($this->context(true, 'v2'));

        $this->assertTrue($result->triggered);
        $this->assertTrue($result->forceReview);
        $this->assertSame(['aml_hit' => true, 'list_version' => 'v2'], $result->detail);
    }

    public function test_no_aml_hit_is_not_triggered(): void
    {
        $result = $this->rule()->evaluate($this->context(false));

        $this->assertFalse($result->triggered);
        $this->assertFalse($result->forceReview);
        $this->assertSame(['aml_hit' => false], $result->detail);
    }

    public function test_aml_hit_without_version_returns_null_list_version(): void
    {
        $result = $this->rule()->evaluate($this->context(true));

        $this->assertTrue($result->triggered);
        $this->assertTrue($result->forceReview);
        $this->assertNull($result->detail['list_version']);
    }

    public function test_deterministic_evaluation_same_input_same_output(): void
    {
        $ctx = $this->context(true, 'v3');

        $result1 = $this->rule()->evaluate($ctx);
        $result2 = $this->rule()->evaluate($ctx);

        $this->assertSame($result1->triggered, $result2->triggered);
        $this->assertSame($result1->forceReview, $result2->forceReview);
        $this->assertSame($result1->detail, $result2->detail);
    }
}
