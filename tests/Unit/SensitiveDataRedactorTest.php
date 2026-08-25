<?php

namespace Tests\Unit;

use App\Exceptions\SensitiveDataRedactor;
use DateTimeImmutable;
use Monolog\Level;
use Monolog\LogRecord;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SensitiveDataRedactorTest extends TestCase
{
    private function record(string $message, array $context = []): LogRecord
    {
        return new LogRecord(
            new DateTimeImmutable(),
            'test',
            Level::Error,
            $message,
            $context
        );
    }

    #[Test]
    public function redacts_email_addresses(): void
    {
        $result = (new SensitiveDataRedactor())($this->record('Sent mail', ['email' => 'jane@example.com']));

        $this->assertStringContainsString('[REDACTED_EMAIL]', $result->message);
        $this->assertStringNotContainsString('jane@example.com', $result->message);
    }

    #[Test]
    public function redacts_card_numbers(): void
    {
        $result = (new SensitiveDataRedactor())($this->record('Payment', ['card' => '4111 1111 1111 1111']));

        $this->assertStringContainsString('[REDACTED_CARD]', $result->message);
    }

    #[Test]
    public function redacts_sensitive_context_keys(): void
    {
        $result = (new SensitiveDataRedactor())($this->record('Auth', [
            'password' => 'supersecret',
            'token' => 'abc123tokenvalue',
            'api_key' => 'sk_live_abcdef',
        ]));

        $this->assertStringNotContainsString('supersecret', $result->message);
        $this->assertStringNotContainsString('abc123tokenvalue', $result->message);
        $this->assertStringNotContainsString('sk_live_abcdef', $result->message);
        $this->assertStringContainsString('[REDACTED]', $result->message);
    }

    #[Test]
    public function redacts_secrets_in_message_text(): void
    {
        $result = (new SensitiveDataRedactor())($this->record('token=abcdef1234567890secret'));

        $this->assertStringContainsString('[REDACTED]', $result->message);
        $this->assertStringNotContainsString('abcdef1234567890secret', $result->message);
    }

    #[Test]
    public function handles_empty_context_without_error(): void
    {
        $result = (new SensitiveDataRedactor())($this->record('Nothing sensitive here'));

        $this->assertStringContainsString('Nothing sensitive here', $result->message);
    }
}
