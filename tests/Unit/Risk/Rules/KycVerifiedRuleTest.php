<?php

namespace Tests\Unit\Risk\Rules;

use App\Models\CampaignSettlement;
use App\Models\Organization;
use App\Services\Risk\Context\RiskContext;
use App\Services\Risk\Rules\KycVerifiedRule;
use Tests\TestCase;

class KycVerifiedRuleTest extends TestCase
{
    private function rule(): KycVerifiedRule
    {
        return new KycVerifiedRule;
    }

    private function context(?string $verificationStatus): RiskContext
    {
        $org = new Organization(['verification_status' => $verificationStatus]);

        return new RiskContext(
            settlement: CampaignSettlement::factory()->make(),
            organization: $org,
            payoutAccount: null,
            signals: [],
            extra: []
        );
    }

    public function test_identifier_returns_rule_name(): void
    {
        $this->assertSame('KYC_VERIFIED', $this->rule()->identifier());
    }

    public function test_name_returns_rule_name(): void
    {
        $this->assertSame('KYC_VERIFIED', $this->rule()->name());
    }

    public function test_verified_organization_is_not_triggered(): void
    {
        $result = $this->rule()->evaluate($this->context('verified'));

        $this->assertFalse($result->triggered);
        $this->assertFalse($result->forceReview);
        $this->assertSame(['verification_status' => 'verified'], $result->detail);
    }

    public function test_unverified_organization_is_triggered(): void
    {
        $result = $this->rule()->evaluate($this->context('pending'));

        $this->assertTrue($result->triggered);
        $this->assertFalse($result->forceReview);
        $this->assertSame(['verification_status' => 'pending'], $result->detail);
    }

    public function test_null_verification_status_is_triggered(): void
    {
        $result = $this->rule()->evaluate($this->context(null));

        $this->assertTrue($result->triggered);
    }

    public function test_deterministic_evaluation_same_input_same_output(): void
    {
        $ctx = $this->context('pending');

        $result1 = $this->rule()->evaluate($ctx);
        $result2 = $this->rule()->evaluate($ctx);

        $this->assertSame($result1->triggered, $result2->triggered);
        $this->assertSame($result1->forceReview, $result2->forceReview);
        $this->assertSame($result1->detail, $result2->detail);
    }
}
