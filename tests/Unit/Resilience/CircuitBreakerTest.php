<?php

namespace Tests\Unit\Resilience;

use App\Services\Resilience\CircuitBreaker;
use App\Exceptions\TemporaryFailureException;
use Illuminate\Support\Facades\Cache;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CircuitBreakerTest extends TestCase
{
    protected function tearDown(): void
    {
        Cache::forget('circuit:test-service');

        parent::tearDown();
    }

    #[Test]
    public function circuit_breaker_allows_calls_when_closed(): void
    {
        $cb = new CircuitBreaker('test-service', 3, 60);
        $result = $cb->call(fn () => 'success');

        $this->assertSame('success', $result);
    }

    #[Test]
    public function circuit_breaker_opens_after_failure_threshold(): void
    {
        $cb = new CircuitBreaker('test-service', 3, 60);
        $attempts = 0;

        for ($i = 0; $i < 3; $i++) {
            try {
                $cb->call(fn () => throw new TemporaryFailureException('fail'));
            } catch (TemporaryFailureException $e) {
                $attempts++;
            }
        }

        $this->assertSame(3, $attempts);

        $this->expectException(TemporaryFailureException::class);
        $cb->call(fn () => 'success');
    }

    #[Test]
    public function circuit_breaker_half_opens_after_cooldown(): void
    {
        $cb = new CircuitBreaker('test-service', 2, 1);

        for ($i = 0; $i < 2; $i++) {
            try {
                $cb->call(fn () => throw new TemporaryFailureException('fail'));
            } catch (TemporaryFailureException $e) {
                // expected
            }
        }

        $this->expectException(TemporaryFailureException::class);
        $cb->call(fn () => 'success');

        Cache::put('circuit:test-service', [
            'state' => 'open',
            'failures' => 2,
            'opened_at' => now()->subSeconds(2)->timestamp,
        ], 60);

        $result = $cb->call(fn () => 'recovered');
        $this->assertSame('recovered', $result);
    }

    #[Test]
    public function circuit_breaker_closes_after_successful_half_open(): void
    {
        Cache::put('circuit:test-service', [
            'state' => 'half-open',
            'failures' => 0,
            'opened_at' => null,
        ], 60);

        $cb = new CircuitBreaker('test-service', 2, 60);
        $result = $cb->call(fn () => 'success');

        $this->assertSame('success', $result);
        $this->assertNull(Cache::get('circuit:test-service'));
    }

    #[Test]
    public function circuit_breaker_ignores_non_retryable_exceptions(): void
    {
        $cb = new CircuitBreaker('test-service', 3, 60);

        $this->expectException(\InvalidArgumentException::class);
        $cb->call(fn () => throw new \InvalidArgumentException('permanent'));
    }
}
