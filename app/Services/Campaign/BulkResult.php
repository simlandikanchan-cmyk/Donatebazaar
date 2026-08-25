<?php

namespace App\Services\Campaign;

final class BulkResult
{
    public function __construct(
        public readonly int $done,
        public readonly int $skipped
    ) {}

    public function hasResults(): bool
    {
        return $this->done > 0 || $this->skipped > 0;
    }

    public function getMessage(string $action): string
    {
        if ($this->skipped > 0 && $this->done === 0) {
            return "No campaigns {$action} ({$this->skipped} skipped).";
        }

        $msg = "{$this->done} campaign(s) {$action}.";
        if ($this->skipped > 0) {
            $msg .= " {$this->skipped} skipped.";
        }

        return $msg;
    }

    public function getType(): string
    {
        if ($this->skipped > 0 && $this->done === 0) {
            return 'warning';
        }

        return 'success';
    }
}
