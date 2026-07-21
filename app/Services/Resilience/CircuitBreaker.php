<?php

namespace App\Services\Resilience;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CircuitBreaker
{
    private const DEFAULT_FAILURE_THRESHOLD = 5;

    private const DEFAULT_COOLDOWN_SECONDS = 60;

    public function __construct(
        private readonly string $service,
        private readonly int $failureThreshold = self::DEFAULT_FAILURE_THRESHOLD,
        private readonly int $cooldownSeconds = self::DEFAULT_COOLDOWN_SECONDS
    ) {}

    public function call(callable $callback): mixed
    {
        $cacheKey = "circuit:{$this->service}";
        $state = Cache::get($cacheKey, ['state' => 'closed', 'failures' => 0, 'opened_at' => null]);

        if ($state['state'] === 'open') {
            if (now()->timestamp - $state['opened_at'] >= $this->cooldownSeconds) {
                $this->halfOpen($cacheKey);
            } else {
                Log::warning('Circuit breaker open — skipping call', [
                    'service' => $this->service,
                    'opened_at' => $state['opened_at'],
                ]);

                throw new \App\Exceptions\TemporaryFailureException("Circuit breaker open for {$this->service}");
            }
        }

        try {
            $result = $callback();

            if ($state['state'] === 'half-open') {
                $this->close($cacheKey);
            }

            return $result;
        } catch (\App\Exceptions\TemporaryFailureException|\App\Exceptions\TimeoutException $e) {
            $this->recordFailure($cacheKey, $state);

            throw $e;
        }
    }

    private function recordFailure(string $cacheKey, array $state): void
    {
        $state['failures']++;

        if ($state['failures'] >= $this->failureThreshold) {
            $state['state'] = 'open';
            $state['opened_at'] = now()->timestamp;

            Log::critical('Circuit breaker opened', [
                'service' => $this->service,
                'failures' => $state['failures'],
            ]);
        }

        Cache::put($cacheKey, $state, $this->cooldownSeconds * 2);
    }

    private function close(string $cacheKey): void
    {
        Cache::forget($cacheKey);

        Log::info('Circuit breaker closed', [
            'service' => $this->service,
        ]);
    }

    private function halfOpen(string $cacheKey): void
    {
        Cache::put($cacheKey, ['state' => 'half-open', 'failures' => 0, 'opened_at' => null], 60);

        Log::info('Circuit breaker half-open', [
            'service' => $this->service,
        ]);
    }
}
