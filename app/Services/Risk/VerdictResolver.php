<?php

namespace App\Services\Risk;

use App\Models\RiskConfig;
use App\Models\RiskScore;

/**
 * Resolves the final verdict from score + config + force_review flags.
 *
 * Verdict precedence (frozen contract):
 *   AML hard block (force_review rule triggered AND category=COMPLIANCE)
 *     -> rejected
 *   requires_manual_review OR score >= manual_review_threshold
 *     -> manual_review
 *   score < approval_threshold
 *     -> auto_approved
 *   else
 *     -> manual_review
 */
final class VerdictResolver
{
    public function resolve(int $score, bool $requiresManualReview, bool $amlBlock, RiskConfig $config): string
    {
        if ($amlBlock) {
            return RiskScore::VERDICT_REJECTED;
        }

        if ($requiresManualReview || $score >= $config->manual_review_threshold) {
            return RiskScore::VERDICT_MANUAL_REVIEW;
        }

        if ($score < $config->approval_threshold) {
            return RiskScore::VERDICT_AUTO_APPROVED;
        }

        return RiskScore::VERDICT_MANUAL_REVIEW;
    }
}
