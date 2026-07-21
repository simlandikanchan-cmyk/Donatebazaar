<?php

namespace App\Contracts\Gateway;

readonly class PayoutResult
{
    public function __construct(
        public bool $success,
        public ?string $providerStatus,
        public ?string $gatewayReference,
        public bool $retryable,
        public ?string $failureReason,
        public array $metadata = []
    ) {}

    public static function success(string $gatewayReference, string $providerStatus = 'paid', array $metadata = []): self
    {
        return new self(
            success: true,
            providerStatus: $providerStatus,
            gatewayReference: $gatewayReference,
            retryable: false,
            failureReason: null,
            metadata: $metadata
        );
    }

    public static function failure(string $failureReason, bool $retryable = true, ?string $providerStatus = null, array $metadata = []): self
    {
        return new self(
            success: false,
            providerStatus: $providerStatus,
            gatewayReference: null,
            retryable: $retryable,
            failureReason: $failureReason,
            metadata: $metadata
        );
    }
}
