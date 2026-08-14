<?php

namespace App\Services\Payment;

use App\Gateways\RazorpayGateway;
use App\Mail\DonationReceiptMail;
use App\Mail\DonationRefundMail;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Refund;
use App\Services\Payment\RefundService;
use App\Services\WalletService;
use App\Models\WalletTransaction;
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
        private WalletService $walletService
    ) {}

    public function handleWebhook(Request $request): JsonResponse
    {
        $secret = config('services.razorpay.webhook_secret');
        $signature = $request->header('X-Razorpay-Signature');
        $payload = $request->getContent();

        if (! $secret) {
            Log::critical('RAZORPAY_WEBHOOK_SECRET is not set — all webhooks are being dropped');

            return response()->json(['status' => 'misconfigured'], 500);
        }

        if (! $signature) {
            Log::warning('Webhook received without X-Razorpay-Signature header', [
                'ip' => $request->ip(),
            ]);

            return response()->json(['status' => 'invalid'], 400);
        }

        $expected = hash_hmac('sha256', $payload, $secret);

        if (! hash_equals($expected, $signature)) {
            Log::warning('Webhook signature mismatch', ['ip' => $request->ip()]);

            return response()->json(['status' => 'invalid'], 400);
        }

        $data = json_decode($payload, true);
        $event = $data['event'] ?? null;

        Log::info('Webhook received', ['event' => $event]);

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

        if (! $paymentId || ! $orderId) {
            return;
        }

        $lock = Cache::lock('webhook_lock_'.$paymentId, self::LOCK_TTL);

        if (! $lock->get()) {
            return;
        }

        $donationToMail = null;
        $ownerForNotif = null;

        try {
            DB::transaction(function () use ($paymentId, $orderId, &$donationToMail, &$ownerForNotif) {
                $donation = Donation::lockForUpdate()
                    ->where('order_id', $orderId)
                    ->first();

                if (! $donation || $donation->payment_status === 'completed') {
                    return;
                }

                $donation->payment_id = $paymentId;
                $donation->payment_status = 'completed';
                $donation->paid_at = now();
                $donation->save();

                Campaign::lockForUpdate()
                    ->findOrFail($donation->campaign_id)
                    ->increment('platform_earnings', $donation->platform_fee);

                $this->decrementProductStock($donation);
                $this->consumeReservations($donation);
                $this->redeemCoupon($donation);

                $owner = $this->walletService->ownerForDonation($donation);
                if ($owner) {
                    $wallet = $this->walletService->getOrCreateWallet($owner);
                    $this->walletService->credit(
                        $wallet,
                        (float) $donation->net_amount,
                        WalletTransaction::SOURCE_DONATION,
                        $donation->id,
                        Donation::class,
                        'Donation #'.$donation->id
                    );
                }

                Log::info('Webhook: payment captured', [
                    'donation_id' => $donation->id,
                    'donation_type' => $donation->donation_type,
                    'payment_id' => $paymentId,
                    'platform_fee' => $donation->platform_fee,
                    'net_amount' => $donation->net_amount,
                ]);

                $donationToMail = $donation->fresh();
                $ownerForNotif = $donation->campaign->user;
            });

            if ($donationToMail) {
                $this->sendReceiptEmail($donationToMail);
            }

            if ($ownerForNotif && $donationToMail) {
                $ownerForNotif->notify(new \App\Notifications\DonationReceived(
                    amount: (float) $donationToMail->net_amount,
                    donorName: $donationToMail->donor_name ?? $donationToMail->donor_email ?? 'Anonymous',
                    campaignTitle: $donationToMail->campaign->title,
                    campaignId: $donationToMail->campaign_id,
                ));
            }
        } finally {
            $lock->release();
        }
    }

    private function handlePaymentFailed(array $payload): void
    {
        $orderId = $payload['payload']['payment']['entity']['order_id'] ?? null;

        if (! $orderId) {
            return;
        }

        DB::table('donations')
            ->where('order_id', $orderId)
            ->where('payment_status', 'pending')
            ->update(['payment_status' => 'failed']);

        Log::info('Webhook: payment failed', ['order_id' => $orderId]);
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

    private function decrementProductStock(Donation $donation): void
    {
        if ($donation->donation_type !== 'product') {
            return;
        }

        $items = DonationItem::where('donation_id', $donation->id)->get();

        foreach ($items as $item) {
            \App\Models\CampaignProduct::where('id', $item->product_id)
                ->where('remaining_quantity', '>=', $item->quantity)
                ->decrement('remaining_quantity', $item->quantity);
        }

        Log::info('Product stock decremented after payment', [
            'donation_id' => $donation->id,
            'items' => $items->count(),
        ]);
    }

    private function consumeReservations(Donation $donation): void
    {
        if ($donation->donation_type !== 'product') {
            return;
        }

        $this->productReservationService->consume($donation);

        Log::info('Product reservations consumed after payment', [
            'donation_id' => $donation->id,
        ]);
    }

    private function redeemCoupon(Donation $donation): void
    {
        if (! $donation->coupon_id) {
            return;
        }

        $coupon = \App\Models\Coupon::lockForUpdate()->find($donation->coupon_id);

        if (! $coupon) {
            return;
        }

        if (\App\Models\CouponRedemption::where('donation_id', $donation->id)->exists()) {
            return;
        }

        [$valid] = $coupon->isValidFor(
            $donation->user,
            $donation->campaign,
            (float) $donation->original_amount
        );

        if (! $valid) {
            return;
        }

        $coupon->increment('used_count');

        if ($coupon->user_id) {
            $coupon->redeemed_at = now();
            $coupon->save();
        }

        \App\Models\CouponRedemption::create([
            'coupon_id' => $coupon->id,
            'user_id' => $donation->user_id,
            'donation_id' => $donation->id,
            'discount_amount' => $donation->discount_amount,
            'created_at' => now(),
        ]);

        Log::info('Coupon redeemed', [
            'coupon_id' => $coupon->id,
            'donation_id' => $donation->id,
            'discount' => $donation->discount_amount,
        ]);
    }

    private function sendReceiptEmail(Donation $donation): void
    {
        if (empty($donation->donor_email)) {
            return;
        }

        try {
            Mail::to($donation->donor_email)
                ->send(new DonationReceiptMail($donation));

            Log::info('Donation receipt email sent', [
                'donation_id' => $donation->id,
                'email' => $donation->donor_email,
            ]);
        } catch (\Throwable $e) {
            Log::error('Donation receipt email failed', [
                'donation_id' => $donation->id,
                'email' => $donation->donor_email,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
