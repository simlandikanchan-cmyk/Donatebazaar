<?php

namespace App\Jobs;

class RetryPolicy
{
    public function __construct(
        private readonly int $maxRetries = 4,
        private readonly array $backoffMinutes = [1, 5, 15, 60],
        private readonly int $maxJitterSeconds = 30
    ) {}

    public function maxRetries(): int
    {
        return config('settlement.max_retry_attempts', $this->maxRetries);
    }

    public function nextRetryAt(int $attemptNumber): \DateTimeInterface
    {
        $delay = $this->backoffMinutes[$attemptNumber - 1] ?? 60;
        $jitter = random_int(0, $this->maxJitterSeconds);

        return now()->addMinutes($delay)->addSeconds($jitter);
    }
}
