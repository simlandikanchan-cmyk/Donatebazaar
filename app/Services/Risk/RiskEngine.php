<?php

namespace App\Services\Risk;

use App\Models\CampaignSettlement;
use App\Models\RiskConfig;
use App\Models\RiskRuleLog;
use App\Models\RiskScore;
use App\Services\Risk\Context\RiskContext;
use Illuminate\Support\Facades\DB;

/**
 * Public entry point for risk evaluation.
 *
 * Orchestrates: registry -> evaluator -> score calculator -> verdict resolver,
 * then persists the evaluation (risk_scores + risk_rule_logs) and returns a
 * RiskEvaluationResult. Contains NO hardcoded scores/thresholds — everything
 * comes from risk_rules + risk_config.
 */
final class RiskEngine
{
    public function __construct(
        private readonly RiskRuleRegistry $registry,
        private readonly ScoreCalculator $calculator,
        private readonly VerdictResolver $verdictResolver
    ) {}

    public function evaluate(CampaignSettlement $settlement, ?RiskContext $context = null): RiskEvaluationResult
    {
        $config = RiskConfig::active();
        if (! $config) {
            // Fail safe: no config means cannot auto-approve -> manual review.
            return new RiskEvaluationResult(100, true, RiskScore::VERDICT_MANUAL_REVIEW, []);
        }

        $context = $context ?? $this->buildContext($settlement);

        $enabledRuleModels = $this->registry->loadEnabled();

        $evaluated = [];
        $requiresManualReview = false;
        $amlBlock = false;

        foreach ($enabledRuleModels as $ruleModel) {
            $rule = $this->registry->get($ruleModel->name);

            // Rule not implemented yet -> treat as not triggered, but record it.
            if (! $rule) {
                $evaluated[] = new EvaluatedRule($ruleModel, RiskRuleResult::notTriggered(['reason' => 'no_implementation']));

                continue;
            }

            $result = $rule->evaluate($context);
            $evaluated[] = new EvaluatedRule($ruleModel, $result);

            if ($result->forceReview) {
                $requiresManualReview = true;
            }
            if ($ruleModel->category === 'COMPLIANCE' && $result->forceReview && $result->triggered) {
                $amlBlock = true;
            }
        }

        $calc = $this->calculator->calculate($evaluated);
        $verdict = $this->verdictResolver->resolve($calc['score'], $requiresManualReview, $amlBlock, $config);

        return $this->persist($settlement, $config, $calc, $verdict, $evaluated);
    }

    private function buildContext(CampaignSettlement $settlement): RiskContext
    {
        $org = $settlement->organization;
        $payout = $settlement->payoutAccount;

        return new RiskContext(
            settlement: $settlement,
            organization: $org,
            payoutAccount: $payout,
            signals: [
                'aml_hit' => false,
                'aml_version' => RiskConfig::active()?->aml_version,
            ],
            extra: []
        );
    }

    private function persist(
        CampaignSettlement $settlement,
        RiskConfig $config,
        array $calc,
        string $verdict,
        array $evaluated
    ): RiskEvaluationResult {
        return DB::transaction(function () use ($settlement, $config, $calc, $verdict, $evaluated) {
            $riskScore = RiskScore::create([
                'settlement_id' => $settlement->id,
                'organization_id' => $settlement->organization_id,
                'risk_version' => $config->risk_version,
                'risk_score' => $calc['score'],
                'risk_verdict' => $verdict,
                'evaluated_at' => now(),
            ]);

            foreach ($evaluated as $e) {
                /** @var EvaluatedRule $e */
                RiskRuleLog::create([
                    'risk_score_id' => $riskScore->id,
                    'rule_name' => $e->model->name,
                    'category' => $e->model->category,
                    'triggered' => $e->result->triggered,
                    'points' => $e->result->triggered ? (int) $e->model->weight : 0,
                    'force_review' => $e->result->forceReview,
                    'detail' => $e->result->detail,
                ]);
            }

            // Mirror summary onto the settlement (read-hot denormalization).
            $settlement->risk_score = $calc['score'];
            $settlement->risk_verdict = $verdict;
            $settlement->risk_version = $config->risk_version;
            $settlement->evaluated_at = now();
            $settlement->save();

            return new RiskEvaluationResult(
                score: $calc['score'],
                requiresManualReview: $verdict !== RiskScore::VERDICT_AUTO_APPROVED,
                verdict: $verdict,
                triggeredRules: $calc['triggered_rules']
            );
        });
    }
}

/**
 * Immutable result returned to callers (Settlement Service).
 */
final class RiskEvaluationResult
{
    public function __construct(
        public readonly int $score,
        public readonly bool $requiresManualReview,
        public readonly string $verdict,
        public readonly array $triggeredRules
    ) {}

    public function isAutoApproved(): bool
    {
        return $this->verdict === RiskScore::VERDICT_AUTO_APPROVED;
    }

    public function isManualReview(): bool
    {
        return $this->verdict === RiskScore::VERDICT_MANUAL_REVIEW;
    }

    public function isRejected(): bool
    {
        return $this->verdict === RiskScore::VERDICT_REJECTED;
    }
}
