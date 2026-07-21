<?php

namespace Tests\Unit\Risk;

use App\Models\CampaignSettlement;
use App\Models\Organization;
use App\Models\RiskConfig;
use App\Models\RiskRule;
use App\Models\RiskScore;
use App\Services\Risk\Context\RiskContext;
use App\Services\Risk\RiskEngine;
use App\Services\Risk\RiskRuleRegistry;
use App\Services\Risk\Rules\AmlScreenRule;
use App\Services\Risk\Rules\KycVerifiedRule;
use App\Services\Risk\Rules\LargePayoutAmountRule;
use App\Services\Risk\ScoreCalculator;
use App\Services\Risk\VerdictResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RiskEngineTest extends TestCase
{
    use RefreshDatabase;

    private function seedConfig(int $version = 1, int $approval = 30, int $manualReview = 60): RiskConfig
    {
        return RiskConfig::create([
            'risk_version' => $version,
            'approval_threshold' => $approval,
            'manual_review_threshold' => $manualReview,
        ]);
    }

    private function engine(): RiskEngine
    {
        $registry = new RiskRuleRegistry(app());
        $registry->register('KYC_VERIFIED', KycVerifiedRule::class);
        $registry->register('LARGE_PAYOUT_AMOUNT', LargePayoutAmountRule::class);
        $registry->register('AML_SCREEN', AmlScreenRule::class);

        return new RiskEngine($registry, new ScoreCalculator, new VerdictResolver);
    }

    private function settlement(float $netAmount = 950.00, ?string $orgStatus = null): CampaignSettlement
    {
        $org = Organization::factory()->create();
        if ($orgStatus !== null) {
            $org->verification_status = $orgStatus;
        }

        return CampaignSettlement::factory()->create([
            'organization_id' => $org->id,
            'net_amount' => $netAmount,
        ]);
    }

    public function test_single_rule_evaluation_scores_and_verdicts(): void
    {
        $this->seedConfig(approval: 100, manualReview: 40);
        RiskRule::create([
            'name' => 'KYC_VERIFIED',
            'category' => 'KYC',
            'weight' => 50,
            'priority' => 1,
            'enabled' => true,
        ]);

        $settlement = $this->settlement(950.00, 'pending');

        $result = $this->engine()->evaluate($settlement);

        $this->assertSame(50, $result->score);
        $this->assertTrue($result->requiresManualReview);
        $this->assertSame(RiskScore::VERDICT_MANUAL_REVIEW, $result->verdict);
        $this->assertCount(1, $result->triggeredRules);
        $this->assertSame('KYC_VERIFIED', $result->triggeredRules[0]);
    }

    public function test_multiple_rules_aggregate_scores(): void
    {
        $this->seedConfig(approval: 100, manualReview: 30);
        RiskRule::create([
            'name' => 'KYC_VERIFIED',
            'category' => 'KYC',
            'weight' => 15,
            'priority' => 1,
            'enabled' => true,
        ]);
        RiskRule::create([
            'name' => 'LARGE_PAYOUT_AMOUNT',
            'category' => 'LIMITS',
            'weight' => 25,
            'priority' => 2,
            'enabled' => true,
        ]);

        $settlement = $this->settlement(200000.00, 'pending');

        $result = $this->engine()->evaluate($settlement);

        $this->assertSame(40, $result->score);
        $this->assertTrue($result->requiresManualReview);
        $this->assertSame(RiskScore::VERDICT_MANUAL_REVIEW, $result->verdict);
        $this->assertCount(2, $result->triggeredRules);
    }

    public function test_disabled_rule_is_not_evaluated(): void
    {
        $this->seedConfig();
        RiskRule::create([
            'name' => 'KYC_VERIFIED',
            'category' => 'KYC',
            'weight' => 20,
            'priority' => 1,
            'enabled' => false,
        ]);

        $settlement = $this->settlement(950.00, 'pending');

        $result = $this->engine()->evaluate($settlement);

        $this->assertSame(0, $result->score);
        $this->assertSame(RiskScore::VERDICT_AUTO_APPROVED, $result->verdict);
        $this->assertEmpty($result->triggeredRules);
    }

    public function test_force_review_rule_results_in_rejected(): void
    {
        $this->seedConfig(approval: 10, manualReview: 20);
        RiskRule::create([
            'name' => 'AML_SCREEN',
            'category' => 'COMPLIANCE',
            'weight' => 50,
            'priority' => 1,
            'enabled' => true,
        ]);

        $settlement = $this->settlement(950.00, 'verified');

        $context = new RiskContext(
            settlement: $settlement,
            organization: $settlement->organization,
            payoutAccount: null,
            signals: ['aml_hit' => true, 'aml_version' => 'v1'],
            extra: []
        );

        $result = $this->engine()->evaluate($settlement, $context);

        $this->assertTrue($result->requiresManualReview);
        $this->assertSame(RiskScore::VERDICT_REJECTED, $result->verdict);
        $this->assertCount(1, $result->triggeredRules);
        $this->assertSame('AML_SCREEN', $result->triggeredRules[0]);
    }

    public function test_score_aggregation_with_mixed_rules(): void
    {
        $this->seedConfig(approval: 100, manualReview: 100);
        RiskRule::create([
            'name' => 'KYC_VERIFIED',
            'category' => 'KYC',
            'weight' => 15,
            'priority' => 1,
            'enabled' => true,
        ]);
        RiskRule::create([
            'name' => 'LARGE_PAYOUT_AMOUNT',
            'category' => 'LIMITS',
            'weight' => 0,
            'priority' => 2,
            'enabled' => true,
        ]);

        $settlement = $this->settlement(950.00, null);

        $result = $this->engine()->evaluate($settlement);

        $this->assertSame(15, $result->score);
        $this->assertCount(1, $result->triggeredRules);
        $this->assertSame('KYC_VERIFIED', $result->triggeredRules[0]);
    }

    public function test_empty_rule_set_returns_auto_approved(): void
    {
        $this->seedConfig();

        $settlement = $this->settlement();

        $result = $this->engine()->evaluate($settlement);

        $this->assertSame(0, $result->score);
        $this->assertFalse($result->requiresManualReview);
        $this->assertSame(RiskScore::VERDICT_AUTO_APPROVED, $result->verdict);
        $this->assertEmpty($result->triggeredRules);
    }

    public function test_versioned_risk_config_changes_verdict(): void
    {
        RiskConfig::create([
            'risk_version' => 1,
            'approval_threshold' => 30,
            'manual_review_threshold' => 60,
        ]);
        RiskConfig::create([
            'risk_version' => 2,
            'approval_threshold' => 10,
            'manual_review_threshold' => 20,
        ]);

        RiskRule::create([
            'name' => 'KYC_VERIFIED',
            'category' => 'KYC',
            'weight' => 15,
            'priority' => 1,
            'enabled' => true,
        ]);

        $settlement = $this->settlement(950.00, 'pending');

        $result = $this->engine()->evaluate($settlement);

        $this->assertSame(2, RiskScore::first()?->risk_version);
        $this->assertSame(15, $result->score);
        $this->assertSame(RiskScore::VERDICT_MANUAL_REVIEW, $result->verdict);
    }

    public function test_no_config_returns_manual_review(): void
    {
        RiskRule::create([
            'name' => 'KYC_VERIFIED',
            'category' => 'KYC',
            'weight' => 20,
            'priority' => 1,
            'enabled' => true,
        ]);

        $settlement = $this->settlement(950.00, 'pending');

        $result = $this->engine()->evaluate($settlement);

        $this->assertSame(100, $result->score);
        $this->assertTrue($result->requiresManualReview);
        $this->assertSame(RiskScore::VERDICT_MANUAL_REVIEW, $result->verdict);
    }

    public function test_registry_discovery_loads_enabled_rules(): void
    {
        $this->seedConfig(approval: 100, manualReview: 10);
        RiskRule::create([
            'name' => 'KYC_VERIFIED',
            'category' => 'KYC',
            'weight' => 20,
            'priority' => 1,
            'enabled' => true,
        ]);
        RiskRule::create([
            'name' => 'UNREGISTERED_RULE',
            'category' => 'KYC',
            'weight' => 10,
            'priority' => 2,
            'enabled' => true,
        ]);

        $settlement = $this->settlement(950.00, null);

        $result = $this->engine()->evaluate($settlement);

        $this->assertSame(20, $result->score);
        $this->assertSame(RiskScore::VERDICT_MANUAL_REVIEW, $result->verdict);
        $this->assertCount(1, $result->triggeredRules);
        $this->assertSame('KYC_VERIFIED', $result->triggeredRules[0]);
    }

    public function test_duplicate_rule_model_rows_are_evaluated_independently(): void
    {
        $this->seedConfig(approval: 100, manualReview: 100);
        RiskRule::create([
            'name' => 'KYC_VERIFIED',
            'category' => 'KYC',
            'weight' => 20,
            'priority' => 1,
            'enabled' => true,
        ]);
        RiskRule::create([
            'name' => 'KYC_VERIFIED',
            'category' => 'KYC',
            'weight' => 10,
            'priority' => 2,
            'enabled' => true,
        ]);

        $settlement = $this->settlement(950.00, null);

        $result = $this->engine()->evaluate($settlement);

        $this->assertSame(30, $result->score);
        $this->assertCount(2, $result->triggeredRules);
    }

    public function test_deterministic_evaluation_same_input_same_output(): void
    {
        $this->seedConfig();
        RiskRule::create([
            'name' => 'KYC_VERIFIED',
            'category' => 'KYC',
            'weight' => 20,
            'priority' => 1,
            'enabled' => true,
        ]);

        $settlement = $this->settlement(950.00, null);

        $result1 = $this->engine()->evaluate($settlement);
        $result2 = $this->engine()->evaluate($settlement->fresh());

        $this->assertSame($result1->score, $result2->score);
        $this->assertSame($result1->verdict, $result2->verdict);
        $this->assertSame($result1->requiresManualReview, $result2->requiresManualReview);
    }

    public function test_persists_risk_score_and_rule_logs(): void
    {
        $this->seedConfig(approval: 100, manualReview: 10);
        RiskRule::create([
            'name' => 'KYC_VERIFIED',
            'category' => 'KYC',
            'weight' => 20,
            'priority' => 1,
            'enabled' => true,
        ]);

        $settlement = $this->settlement(950.00, null);

        $this->engine()->evaluate($settlement);

        $this->assertDatabaseHas('risk_scores', [
            'settlement_id' => $settlement->id,
            'risk_score' => 20,
            'risk_verdict' => RiskScore::VERDICT_MANUAL_REVIEW,
        ]);

        $riskScore = RiskScore::first();
        $this->assertNotNull($riskScore);

        $this->assertDatabaseHas('risk_rule_logs', [
            'risk_score_id' => $riskScore->id,
            'rule_name' => 'KYC_VERIFIED',
            'triggered' => true,
            'points' => 20,
        ]);
    }

    public function test_mirrors_summary_onto_settlement(): void
    {
        $this->seedConfig(approval: 100, manualReview: 10);
        RiskRule::create([
            'name' => 'KYC_VERIFIED',
            'category' => 'KYC',
            'weight' => 20,
            'priority' => 1,
            'enabled' => true,
        ]);

        $settlement = $this->settlement(950.00, null);

        $this->engine()->evaluate($settlement);

        $this->assertDatabaseHas('campaign_settlements', [
            'id' => $settlement->id,
            'risk_score' => 20,
            'risk_verdict' => RiskScore::VERDICT_MANUAL_REVIEW,
        ]);
    }
}
