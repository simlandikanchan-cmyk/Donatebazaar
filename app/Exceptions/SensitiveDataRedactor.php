<?php

namespace App\Exceptions;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

class SensitiveDataRedactor implements ProcessorInterface
{
    protected array $patterns = [
        '/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}\b/' => '[REDACTED_EMAIL]',
        '/\b\d{4}[\-\s]?\d{4}[\-\s]?\d{4}[\-\s]?\d{4}\b/' => '[REDACTED_CARD]',
        '/\b(?:\+?[\d\-\s()]{10,})\b/' => '[REDACTED_PHONE]',
        '/\b(?:password|passwd|pwd|secret|token|api_key|apikey)\s*[=:]\s*["\']?([^"\'&\s]+)["\']?/i' => '$1=[REDACTED]',
        '/\b[A-Za-z0-9]{32,}\b/' => '[REDACTED_KEY]',
    ];

    public function __invoke(LogRecord $record): LogRecord
    {
        $message = $record->message;

        if (isset($record->context)) {
            $message .= ' ' . json_encode($this->redactArray($record->context->toArray()));
        }

        foreach ($this->patterns as $pattern => $replacement) {
            $message = preg_replace($pattern, $replacement, $message);
        }

        return $record->with(message: $message);
    }

    protected function redactArray(array $data): array
    {
        $sensitiveKeys = [
            'password', 'password_confirmation', 'token', 'api_key', 'secret',
            'card_number', 'cvv', 'cvc', 'pin', 'otp',
        ];

        foreach ($data as $key => $value) {
            if (in_array(strtolower($key), $sensitiveKeys, true)) {
                $data[$key] = '[REDACTED]';
            } elseif (is_array($value)) {
                $data[$key] = $this->redactArray($value);
            }
        }

        return $data;
    }
}
