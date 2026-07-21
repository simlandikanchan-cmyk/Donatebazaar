<?php

namespace Tests\Unit\Resilience;

use App\Jobs\RetryPolicy;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RetryPolicyTest extends TestCase
{
    #[Test]
    public function retry_policy_includes_jitter(): void
    {
        $policy = new RetryPolicy(maxRetries: 4, backoffMinutes: [2, 5, 15, 60], maxJitterSeconds: 30);

        $delays = [];
        for ($i = 0; $i < 20; $i++) {
            $delays[] = $policy->nextRetryAt(1)->diffInSeconds(now());
        }

        $uniqueDelays = array_unique($delays);
        $this->assertGreaterThan(1, count($uniqueDelays), 'Jitter should produce varying delays');
    }

    #[Test]
    public function retry_policy_respects_max_retries(): void
    {
        $policy = new RetryPolicy(maxRetries: 2, backoffMinutes: [1, 5]);

        $this->assertSame(config('settlement.max_retry_attempts', 2), $policy->maxRetries());
    }

    #[Test]
    public function retry_policy_uses_config_value(): void
    {
        $policy = new RetryPolicy();

        $this->assertSame(config('settlement.max_retry_attempts', 4), $policy->maxRetries());
    }
}
