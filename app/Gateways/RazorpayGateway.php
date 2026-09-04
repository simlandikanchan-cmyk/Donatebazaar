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
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\BadRequestError;
use Razorpay\Api\Errors\Error as RazorpayError;
use Razorpay\Api\Errors\GatewayError;
use Razorpay\Api\Errors\ServerError;
use Razorpay\Api\Request;
use WpOrg\Requests\Exception as RequestsException;
use WpOrg\Requests\Exception\Transport\Curl as CurlTransportException;

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

        if (empty($account->fund_account_id)) {
            throw PermanentFailureException::permanent(
                'Organization has no linked Razorpay fund account. Payout activation requires fund account configuration.'
            );
        }

        $api = $this->getApi();
        $amountPaise = (int) round($amount * 100);

        $attributes = [
            'amount' => $amountPaise,
            'currency' => 'INR',
            'fund_account_id' => $account->fund_account_id,
            'notes' => [
                'settlement_id' => (string) $settlement->id,
                'organization_id' => (string) $org->id,
                'campaign_id' => (string) $settlement->campaign_id,
            ],
        ];

        if ($idempotencyKey !== null) {
            $api->setHeader('X-Razorpay-Idempotency', $idempotencyKey);
        }

        try {
            $transfer = $api->transfer->create($attributes);
        } catch (BadRequestError $e) {
            throw PermanentFailureException::permanent(
                'Payout request rejected by provider: '.$e->getMessage()
            );
        } catch (GatewayError|ServerError $e) {
            throw TemporaryFailureException::temporary(
                'Provider unable to process payout: '.$e->getMessage()
            );
        } catch (RazorpayError $e) {
            throw new GatewayException(
                'Payout API error: '.$e->getMessage(),
                $e->getCode(),
                $e
            );
        } catch (RequestsException $e) {
            throw new TimeoutException(
                'Gateway timeout: unable to process payout.',
                0,
                $e
            );
        } catch (\Throwable $e) {
            if ($e instanceof PermanentFailureException || $e instanceof TimeoutException || $e instanceof TemporaryFailureException) {
                throw $e;
            }

            throw new GatewayException(
                'Unexpected payout error: '.$e->getMessage(),
                $e->getCode(),
                $e
            );
        } finally {
            if ($idempotencyKey !== null) {
                Request::removeHeader('X-Razorpay-Idempotency');
            }
        }

        return [
            'gateway_reference' => $transfer->id,
            'provider_status' => $transfer->status,
            'metadata' => [
                'account_number' => $account->masked_account_number,
                'ifsc_code' => $account->ifsc_code,
                'amount' => $amount,
                'currency' => 'INR',
                'fund_account_id' => $account->fund_account_id,
            ],
        ];
    }

    public function getPayoutStatus(string $gatewayReference): array
    {
        $api = $this->getApi();

        try {
            $transfer = $api->transfer->fetch($gatewayReference);
        } catch (BadRequestError $e) {
            throw PermanentFailureException::permanent(
                'Invalid payout reference: '.$e->getMessage()
            );
        } catch (GatewayError|ServerError $e) {
            throw TemporaryFailureException::temporary(
                'Provider unable to retrieve payout status: '.$e->getMessage()
            );
        } catch (RazorpayError $e) {
            throw new GatewayException(
                'Payout status lookup failed: '.$e->getMessage(),
                $e->getCode(),
                $e
            );
        } catch (RequestsException $e) {
            throw new TimeoutException(
                'Gateway timeout: unable to retrieve payout status.',
                0,
                $e
            );
        } catch (\Throwable $e) {
            if ($e instanceof PermanentFailureException || $e instanceof TimeoutException || $e instanceof TemporaryFailureException) {
                throw $e;
            }

            throw new GatewayException(
                'Unexpected payout status error: '.$e->getMessage(),
                $e->getCode(),
                $e
            );
        }

        return [
            'id' => $transfer->id,
            'status' => $transfer->status,
            'amount' => (float) ($transfer->amount / 100),
            'currency' => $transfer->currency ?? 'INR',
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

    /**
     * Return the actual gateway processing fee and tax for a payment, if the
     * provider exposes them.
     *
     * Razorpay payment entities expose `fee` and `tax` (in paise) for captured
     * payments. When the fields are absent (e.g. fee not yet known), this
     * returns nulls so the caller can record the fee capture as 'unavailable'
     * rather than inventing an estimate.
     *
     * @return array{fee: float|null, tax: float|null}
     */
    public function fetchPaymentFees(string $paymentId): array
    {
        $payment = $this->fetchPayment($paymentId);

        $fee = isset($payment['fee']) && $payment['fee'] !== null
            ? (float) ((int) $payment['fee'] / 100)
            : null;

        $tax = isset($payment['tax']) && $payment['tax'] !== null
            ? (float) ((int) $payment['tax'] / 100)
            : null;

        return ['fee' => $fee, 'tax' => $tax];
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
