<?php

namespace App\Services\Payment;

use App\Gateways\RazorpayGateway;
use App\Mail\AdminPaymentFailedMail;
use App\Mail\DonationFailedMail;
use App\Mail\DonationRefundMail;
use App\Models\Donation;
use App\Models\Refund;
use App\Services\Payment\DonationCompletionService;
use App\Services\Payment\RefundService;
use App\Services\WalletService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentWebhookService
{
    private const LOCK_TTL = 60;

    public function __construct(
        private RazorpayGateway $gateway,
        private RefundService $refundService,
        private WalletService $walletService,
        private DonationCompletionService $completionService
    ) {}

    public function handleWebhook(Request $request): JsonResponse
    {
        $secret = config('services.razorpay.webhook_secret');
        $signature = $request->header('X-Razorpay-Signature');
        $payload = $request->getContent();

        if (! $secret) {
            Log::channel('payments')->critical('RAZORPAY_WEBHOOK_SECRET is not set — all webhooks are being dropped');

            return response()->json(['status' => 'misconfigured'], 500);
        }

        if (! $signature) {
            Log::channel('payments')->warning('Webhook received without X-Razorpay-Signature header', [
                'ip' => $request->ip(),
            ]);

            return response()->json(['status' => 'invalid'], 400);
        }

        $expected = hash_hmac('sha256', $payload, $secret);

        if (! hash_equals($expected, $signature)) {
            Log::channel('payments')->warning('Webhook signature mismatch', ['ip' => $request->ip()]);

            return response()->json(['status' => 'invalid'], 400);
        }

        $data = json_decode($payload, true);
        $event = $data['event'] ?? null;

        Log::channel('payments')->info('Webhook received', ['event' => $event]);

        match ($event) {
            'payment.captured' => $this->handlePaymentCaptured($data),
            'payment.failed' => $this->handlePaymentFailed($data),
            'refund.processed' => $this->handleRefundProcessed($data),
            'refund.failed' => $this->handleRefundFailed($data),
            default => null,
        };

        return response()->json(['status' => 'ok'], 200);
    }

    private function handlePaymentCaptured(array $payload): void
    {
        $paymentId = $payload['payload']['payment']['entity']['id'] ?? null;
        $orderId = $payload['payload']['payment']['entity']['order_id'] ?? null;
        $amountPaise = $payload['payload']['payment']['entity']['amount'] ?? null;
        $currency = $payload['payload']['payment']['entity']['currency'] ?? null;

        if (! $paymentId || ! $orderId || ! $amountPaise || ! $currency) {
            return;
        }

        $lock = Cache::lock('webhook_lock_'.$paymentId, self::LOCK_TTL);

        if (! $lock->get()) {
            return;
        }

        $donationToMail = null;
        $ownerForNotif = null;

        try {
            DB::transaction(function () use ($paymentId, $orderId, $amountPaise, $currency, &$donationToMail, &$ownerForNotif) {
                $donation = Donation::lockForUpdate()
                    ->where('order_id', $orderId)
                    ->first();

                if (! $donation || $donation->payment_status === 'completed') {
                    return;
                }

                $expectedPaise = (int) round((float) $donation->total_amount * 100);
                if ((int) $amountPaise !== $expectedPaise) {
                    Log::channel('payments')->warning('Webhook amount mismatch', [
                        'donation_id' => $donation->id,
                        'expected' => $expectedPaise,
                        'actual' => (int) $amountPaise,
                        'payment_id' => $paymentId,
                    ]);

                    return;
                }

                if (strtoupper($currency) !== strtoupper((string) $donation->currency)) {
                    Log::channel('payments')->warning('Webhook currency mismatch', [
                        'donation_id' => $donation->id,
                        'expected' => $donation->currency,
                        'actual' => $currency,
                        'payment_id' => $paymentId,
                    ]);

                    return;
                }

                $donation->payment_id = $paymentId;

                $result = $this->completionService->complete($donation, $paymentId);

                $donationToMail = $result['donation'];
                $ownerForNotif = $result['owner'];
            });

            if ($donationToMail) {
                $this->sendOwnerNotification($ownerForNotif, $donationToMail);
            }
        } finally {
            $lock->release();
        }
    }

    private function sendOwnerNotification($owner, Donation $donation): void
    {
        if (! $owner) {
            return;
        }

        try {
            $owner->notify(new \App\Notifications\DonationReceived(
                amount: (float) $donation->net_amount,
                donorName: $donation->donor_name ?? $donation->donor_email ?? 'Anonymous',
                campaignTitle: $donation->campaign->title,
                campaignId: $donation->campaign_id,
            ));
        } catch (\Throwable $e) {
        Log::channel('payments')->error('Owner notification failed', [
            'donation_id' => $donation->id,
            'owner_id' => $owner->id,
            'error' => $e->getMessage(),
        ]);
        }
    }

    private function handlePaymentFailed(array $payload): void
    {
        $orderId = $payload['payload']['payment']['entity']['order_id'] ?? null;

        if (! $orderId) {
            return;
        }

        $donation = Donation::where('order_id', $orderId)
            ->where('payment_status', 'pending')
            ->first();

        if ($donation) {
            DB::table('donations')
                ->where('id', $donation->id)
                ->update(['payment_status' => 'failed']);

            if ($donation->donor_email) {
                try {
                    Mail::to($donation->donor_email)->queue(new DonationFailedMail($donation));
                } catch (\Throwable $e) {
                    Log::channel('payments')->error('Failed to queue donation failed email', [
                        'donation_id' => $donation->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            try {
                Mail::to(config('mail.from.address'))->queue(new AdminPaymentFailedMail($donation));
            } catch (\Throwable $e) {
                Log::channel('payments')->error('Failed to queue admin payment failed email', [
                    'donation_id' => $donation->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Log::channel('payments')->info('Webhook: payment failed', ['order_id' => $orderId]);
    }

    private function handleRefundProcessed(array $payload): void
    {
        $this->refundService->processWebhookRefund($payload);
    }

    private function handleRefundFailed(array $payload): void
    {
        $refund = $payload['payload']['refund']['entity'] ?? [];
        $refundId = $refund['id'] ?? null;
        $paymentId = $refund['payment_id'] ?? null;

        if (! $refundId || ! $paymentId) {
            return;
        }

        $lock = Cache::lock('webhook_lock_'.$refundId, self::LOCK_TTL);

        if (! $lock->get()) {
            return;
        }

        try {
            DB::transaction(function () use ($refundId, $paymentId, $refund) {
                $donation = Donation::where('payment_id', $paymentId)->first();
                if (! $donation) {
                    return;
                }

                $refundRecord = Refund::where('gateway_refund_id', $refundId)->first();

                if ($refundRecord) {
                    $refundRecord->update(['status' => 'failed']);
                } else {
                    Refund::create([
                        'donation_id' => $donation->id,
                        'donation_payment_id' => null,
                        'amount' => (float) ($refund['amount'] ?? 0) / 100,
                        'reason' => 'Refund failed at gateway',
                        'status' => 'failed',
                        'processed_at' => null,
                        'gateway_refund_id' => $refundId,
                    ]);
                }

                Log::info('Webhook: refund failed', [
                    'donation_id' => $donation->id,
                    'refund_id' => $refundId,
                    'payment_id' => $paymentId,
                ]);
            });
        } finally {
            $lock->release();
        }
    }
}
