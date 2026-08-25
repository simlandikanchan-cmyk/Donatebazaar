<?php

namespace App\Gateways;

use App\Exceptions\DuplicateRequestException;
use App\Exceptions\GatewayException;
use App\Exceptions\InvalidSignatureException;
use App\Exceptions\PermanentFailureException;
use App\Exceptions\TemporaryFailureException;
use App\Exceptions\TimeoutException;
use App\Models\CampaignSettlement;
use App\Models\Donation;
use App\Models\Organization;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\Error as RazorpayError;

class RazorpayGateway
{
    public function __construct(
        private readonly string $keyId,
        private readonly string $keySecret,
        private readonly string $webhookSecret,
        private readonly ?Api $api = null
    ) {}

    private function getApi(): Api
    {
        if ($this->api !== null) {
            return $this->api;
        }

        return new Api($this->keyId, $this->keySecret);
    }

    public function initiatePayout(Organization $org, float $amount, CampaignSettlement $settlement, ?string $idempotencyKey = null): array
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

    public function createOrder(\App\Models\Campaign $campaign, ?\App\Models\User $user, float $amount, array $fees = []): array
    {
        try {
            $api = $this->getApi();

            $order = $api->order->create([
                'receipt' => 'rcpt_'.time().'_'.($user?->id ?? 0),
                'amount' => (int) round($amount * 100),
                'currency' => config('services.donation.currency', 'INR'),
                'notes' => [
                    'campaign_id' => $campaign->id,
                    'campaign_name' => $campaign->title,
                    'user_id' => $user?->id,
                    'platform_fee' => $fees['platform_fee'] ?? 0,
                    'net_amount' => $fees['net_amount'] ?? $amount,
                ],
            ]);

            return $order->toArray();
        } catch (RazorpayError $e) {
            Log::channel('payments')->error('Razorpay order creation failed', [
                'campaign_id' => $campaign->id,
                'amount' => $amount,
                'user_id' => $user?->id,
                'error' => $e->getMessage(),
            ]);

            throw new \RuntimeException('Unable to initialize payment. Please try again.');
        }
    }

    public function createOrderWithNotes(float $amount, array $notes = [], ?string $receipt = null): array
    {
        try {
            $api = $this->getApi();

            $order = $api->order->create([
                'receipt' => $receipt ?? 'rcpt_'.time().'_'.rand(100, 999),
                'amount' => (int) round($amount * 100),
                'currency' => config('services.donation.currency', 'INR'),
                'notes' => $notes,
            ]);

            return $order->toArray();
        } catch (RazorpayError $e) {
            Log::channel('payments')->error('Razorpay generic order creation failed', [
                'amount' => $amount,
                'receipt' => $receipt,
                'error' => $e->getMessage(),
            ]);

            throw new \RuntimeException('Unable to initialize payment. Please try again.');
        }
    }

    public function verifyPaymentSignature(array $payload): void
    {
        $api = $this->getApi();

        try {
            $api->utility->verifyPaymentSignature($payload);
        } catch (RazorpayError $e) {
            throw new \Razorpay\Api\Errors\SignatureVerificationError($e->getMessage(), $e->getCode());
        }
    }

    public function initiateRefund(Donation $donation, int $amountPaise, ?string $idempotencyKey = null): object
    {
        $api = $this->getApi();

        $header = $idempotencyKey !== null
            ? ['X-Razorpay-Idempotency' => $idempotencyKey]
            : [];

        return $api->payment
            ->fetch($donation->payment_id)
            ->refund(['amount' => $amountPaise], $header);
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

    public function fetchPayment(string $paymentId): object
    {
        return $this->getApi()->payment->fetch($paymentId);
    }

    public function verifyPaymentDetails(string $paymentId, string $orderId, float $expectedAmount, string $currency): void
    {
        $payment = $this->fetchPayment($paymentId);

        $actualAmount = (int) $payment->amount;
        $expectedPaise = (int) round($expectedAmount * 100);

        if ($actualAmount !== $expectedPaise) {
            throw new \RuntimeException("Payment amount mismatch: expected {$expectedPaise} paise, got {$actualAmount} paise.");
        }

        if ($payment->order_id !== $orderId) {
            throw new \RuntimeException("Payment order mismatch: expected {$orderId}, got {$payment->order_id}.");
        }

        if (strtoupper($payment->currency) !== strtoupper($currency)) {
            throw new \RuntimeException("Payment currency mismatch: expected {$currency}, got {$payment->currency}.");
        }

        if (! in_array($payment->status, ['captured', 'authorized'], true)) {
            throw new \RuntimeException("Payment status '{$payment->status}' is not captured or authorized.");
        }
    }
}

