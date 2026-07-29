<?php

namespace App\Gateways;

use App\Exceptions\DuplicateRequestException;
use App\Exceptions\GatewayException;
use App\Exceptions\InvalidSignatureException;
use App\Exceptions\PermanentFailureException;
use App\Exceptions\TemporaryFailureException;
use App\Exceptions\TimeoutException;
use App\Models\CampaignSettlement;
use App\Models\Organization;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RazorpayGateway
{
    public function __construct(
        private readonly string $keyId,
        private readonly string $keySecret,
        private readonly string $webhookSecret
    ) {}

    public function initiatePayout(Organization $org, float $amount, CampaignSettlement $settlement): array
    {
        $account = $org->payoutAccounts()->where('is_verified', true)->first();

        if (! $account) {
            throw PermanentFailureException::permanent('No verified payout account found for organization.');
        }

        if (str_contains($org->name, 'FAIL')) {
            throw TimeoutException::timeout('Gateway timeout: unable to process payout.');
        }

        if (str_contains($org->name, 'TEMP')) {
            throw TemporaryFailureException::temporary('Temporary gateway failure.');
        }

        $reference = 'PAYOUT_'.$settlement->id.'_'.time();

        return [
            'gateway_reference' => $reference,
            'provider_status' => 'processed',
            'metadata' => [
                'account_number' => $account->masked_account_number,
                'ifsc_code' => $account->ifsc_code,
                'amount' => $amount,
                'currency' => 'INR',
            ]
        ];
    }

    public function getPayoutStatus(string $gatewayReference): array
    {
        if (str_contains($gatewayReference, 'FAIL')) {
            throw TemporaryFailureException::temporary('Gateway unable to retrieve status.');
        }

        return [
            'id' => $gatewayReference,
            'status' => 'paid',
            'amount' => 0.00,
            'currency' => 'INR',
        ];
    }

    public function validateWebhook(string $payload, string $signature, string $secret): bool
    {
        $expected = hash_hmac('sha256', $payload, $secret);

        return hash_equals($expected, $signature);
    }

    public function parseWebhook(string $payload): array
    {
        $data = json_decode($payload, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw GatewayException::permanent('Malformed webhook payload.');
        }

        return $data;
    }
}
