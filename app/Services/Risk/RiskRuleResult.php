<?php

namespace App\Services\Risk;

final class RiskRuleResult
{
    public function __construct(
        public readonly bool $triggered,
        public readonly bool $forceReview,
        public readonly array $detail = []
    ) {}

    public static function triggered(bool $forceReview = false, array $detail = []): self
    {
        return new self(true, $forceReview, $detail);
    }

    public static function notTriggered(array $detail = []): self
    {
        return new self(false, false, $detail);
    }
}
