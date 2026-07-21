<?php

namespace App\Contracts\Gateway;

use App\Models\CampaignSettlement;
use App\Models\Organization;

interface GatewayInterface
{
    public function initiatePayout(Organization $org, float $amount, CampaignSettlement $settlement): PayoutResult;

    public function getPayoutStatus(string $gatewayReference): array;

    public function validateWebhook(string $payload, string $signature, string $secret): bool;

    public function parseWebhook(string $payload): array;
}
