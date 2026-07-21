<?php

namespace Tests\Unit\Risk;

use App\Models\CampaignSettlement;
use App\Models\Organization;
use App\Services\Risk\Context\RiskContext;
use Tests\TestCase;

class RiskContextTest extends TestCase
{
    public function test_signal_returns_value_when_present(): void
    {
        $context = new RiskContext(
            settlement: CampaignSettlement::factory()->make(),
            organization: Organization::factory()->make(),
            payoutAccount: null,
            signals: ['aml_hit' => true, 'aml_version' => 'v2'],
            extra: []
        );

        $this->assertTrue($context->signal('aml_hit'));
        $this->assertSame('v2', $context->signal('aml_version'));
    }

    public function test_signal_returns_default_when_key_missing(): void
    {
        $context = new RiskContext(
            settlement: CampaignSettlement::factory()->make(),
            organization: Organization::factory()->make(),
            payoutAccount: null,
            signals: [],
            extra: []
        );

        $this->assertNull($context->signal('missing'));
        $this->assertSame('fallback', $context->signal('missing', 'fallback'));
    }

    public function test_extra_accessible_via_property(): void
    {
        $context = new RiskContext(
            settlement: CampaignSettlement::factory()->make(),
            organization: Organization::factory()->make(),
            payoutAccount: null,
            signals: [],
            extra: ['threshold' => 50000]
        );

        $this->assertSame(50000, $context->extra['threshold']);
    }

    public function test_settlement_and_organization_are_readonly(): void
    {
        $settlement = CampaignSettlement::factory()->make();
        $org = Organization::factory()->make();

        $context = new RiskContext(
            settlement: $settlement,
            organization: $org,
            payoutAccount: null,
            signals: [],
            extra: []
        );

        $this->assertSame($settlement, $context->settlement);
        $this->assertSame($org, $context->organization);
        $this->assertNull($context->payoutAccount);
    }
}
