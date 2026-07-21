<?php

namespace App\Services\Reconciliation;

readonly class ReconciliationResult
{
    public function __construct(
        public int $settlementId,
        public ?string $gatewayStatus,
        public string $localStatus,
        public ?string $actionTaken,
        public bool $reconciled,
        public array $metadata = []
    ) {}

    public static function success(int $settlementId, string $gatewayStatus, string $localStatus, array $metadata = []): self
    {
        return new self(
            settlementId: $settlementId,
            gatewayStatus: $gatewayStatus,
            localStatus: $localStatus,
            actionTaken: 'none',
            reconciled: true,
            metadata: $metadata
        );
    }

    public static function corrected(int $settlementId, string $gatewayStatus, string $localStatus, string $actionTaken, array $metadata = []): self
    {
        return new self(
            settlementId: $settlementId,
            gatewayStatus: $gatewayStatus,
            localStatus: $localStatus,
            actionTaken: $actionTaken,
            reconciled: true,
            metadata: $metadata
        );
    }

    public static function skipped(int $settlementId, string $localStatus, string $actionTaken, array $metadata = []): self
    {
        return new self(
            settlementId: $settlementId,
            gatewayStatus: null,
            localStatus: $localStatus,
            actionTaken: $actionTaken,
            reconciled: false,
            metadata: $metadata
        );
    }

    public static function failed(int $settlementId, string $localStatus, string $error, array $metadata = []): self
    {
        return new self(
            settlementId: $settlementId,
            gatewayStatus: null,
            localStatus: $localStatus,
            actionTaken: 'failed',
            reconciled: false,
            metadata: array_merge($metadata, ['error' => $error])
        );
    }
}
