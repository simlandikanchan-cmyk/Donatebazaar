<?php

namespace Tests\Unit\Risk;

use App\Models\RiskConfig;
use App\Models\RiskScore;
use App\Services\Risk\VerdictResolver;
use Tests\TestCase;

class VerdictResolverTest extends TestCase
{
    private function resolver(): VerdictResolver
    {
        return new VerdictResolver;
    }

    private function config(int $approvalThreshold = 30, int $manualReviewThreshold = 60): RiskConfig
    {
        return new RiskConfig([
            'risk_version' => 1,
            'approval_threshold' => $approvalThreshold,
            'manual_review_threshold' => $manualReviewThreshold,
        ]);
    }

    public function test_aml_block_returns_rejected(): void
    {
        $verdict = $this->resolver()->resolve(10, false, true, $this->config());

        $this->assertSame(RiskScore::VERDICT_REJECTED, $verdict);
    }

    public function test_force_review_returns_manual_review(): void
    {
        $verdict = $this->resolver()->resolve(10, true, false, $this->config());

        $this->assertSame(RiskScore::VERDICT_MANUAL_REVIEW, $verdict);
    }

    public function test_score_above_manual_review_threshold_returns_manual_review(): void
    {
        $verdict = $this->resolver()->resolve(70, false, false, $this->config(30, 60));

        $this->assertSame(RiskScore::VERDICT_MANUAL_REVIEW, $verdict);
    }

    public function test_score_below_approval_threshold_returns_auto_approved(): void
    {
        $verdict = $this->resolver()->resolve(10, false, false, $this->config(30, 60));

        $this->assertSame(RiskScore::VERDICT_AUTO_APPROVED, $verdict);
    }

    public function test_score_between_thresholds_returns_manual_review(): void
    {
        $verdict = $this->resolver()->resolve(40, false, false, $this->config(30, 60));

        $this->assertSame(RiskScore::VERDICT_MANUAL_REVIEW, $verdict);
    }

    public function test_aml_block_overrides_auto_approval(): void
    {
        $verdict = $this->resolver()->resolve(10, false, true, $this->config(30, 60));

        $this->assertSame(RiskScore::VERDICT_REJECTED, $verdict);
    }

    public function test_versioned_config_changes_verdict(): void
    {
        $configV1 = $this->config(30, 60);
        $configV2 = $this->config(10, 20);

        $verdictV1 = $this->resolver()->resolve(25, false, false, $configV1);
        $verdictV2 = $this->resolver()->resolve(25, false, false, $configV2);

        $this->assertSame(RiskScore::VERDICT_AUTO_APPROVED, $verdictV1);
        $this->assertSame(RiskScore::VERDICT_MANUAL_REVIEW, $verdictV2);
    }
}
