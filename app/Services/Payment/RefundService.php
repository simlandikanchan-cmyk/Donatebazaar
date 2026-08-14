<?php

namespace App\Services\Payment;

use App\Gateways\RazorpayGateway;
use App\Mail\DonationRefundMail;
use App\Models\Donation;
use App\Models\Refund;
use App\Models\User;
use App\Services\WalletService;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RefundService
{
    private const LOCK_TTL = 60;

    public function __construct(
        private RazorpayGateway $gateway,
        private WalletService $walletService
    ) {}

    public function processAdminRefund(Donation $donation, User $admin, string $reason): Refund
    {
        $lock = Cache::lock('donation_refund_'.$donation->id, self::LOCK_TTL);

        if (! $lock->get()) {
            throw new \RuntimeException('A refund is already being processed for this donation.');
        }

        try {
            $donation->refresh();

            if ($donation->is_refunded) {
                throw new \RuntimeException('This donation has already been refunded.');
            }

            if ($donation->payment_status !== 'completed') {
                throw new \RuntimeException('Only completed donations can be refunded.');
            }

            $paymentId = $donation->payment_id;

            if (empty($paymentId) || ! preg_match('/^pay_[A-Za-z0-9]{14,}$/', $paymentId)) {
                Log::warning('Refund attempt blocked: invalid payment_id format', [
                    'donation_id' => $donation->id,
                    'payment_id' => $paymentId,
                ]);

                throw new \RuntimeException('This donation has no valid payment id and cannot be refunded.');
            }

            try {
                $razorpayRefund = $this->gateway->initiateRefund($donation, (int) round($donation->total_amount * 100));
            } catch (\Razorpay\Api\Errors\Error $e) {
                Log::error('Admin refund failed at gateway', [
                    'donation_id' => $donation->id,
                    'payment_id' => $donation->payment_id,
                    'message' => $e->getMessage(),
                ]);

                Refund::create([
                    'donation_id' => $donation->id,
                    'donation_payment_id' => null,
                    'gateway_refund_id' => null,
                    'amount' => $donation->total_amount,
                    'reason' => 'Admin refund failed at gateway: '.$e->getMessage(),
                    'status' => 'failed',
                    'processed_at' => null,
                ]);

                throw new \RuntimeException('Refund failed at the payment gateway: '.$e->getMessage());
            }

            $refundRecord = null;
            $alreadyRefunded = false;

            DB::transaction(function () use ($donation, $razorpayRefund, $admin, $reason, &$refundRecord, &$alreadyRefunded) {
                $locked = Donation::lockForUpdate()->where('id', $donation->id)->first();

                if ($locked->payment_status !== 'completed' || $locked->is_refunded) {
                    $alreadyRefunded = true;

                    return;
                }

                $locked->payment_status = 'refunded';
                $locked->is_refunded = true;
                $locked->refunded_at = now();
                $locked->save();

                $refundRecord = Refund::create([
                    'donation_id' => $locked->id,
                    'donation_payment_id' => null,
                    'gateway_refund_id' => $razorpayRefund->id,
                    'amount' => $donation->total_amount,
                    'reason' => $reason,
                    'status' => 'processed',
                    'processed_at' => now(),
                ]);

                try {
                    $owner = $this->walletService->ownerForDonation($locked);
                    if ($owner) {
                        $wallet = $this->walletService->getOrCreateWallet($owner);
                        $this->walletService->debit(
                            $wallet,
                            (float) $locked->net_amount,
                            WalletTransaction::SOURCE_REFUND,
                            $refundRecord->id,
                            Refund::class,
                            'Refund #'.$refundRecord->id
                        );
                    }
                } catch (\Throwable $e) {
                    Log::error('Wallet debit failed for admin refund', [
                        'donation_id' => $locked->id,
                        'message' => $e->getMessage(),
                    ]);
                }
            });

            if ($alreadyRefunded) {
                return $refundRecord ?? throw new \RuntimeException('Refund already processed.');
            }

            if ($refundRecord && $donation->donor_email) {
                try {
                    Mail::to($donation->donor_email)->send(new DonationRefundMail($donation, $refundRecord));
                } catch (\Throwable $e) {
                    Log::error('Failed to send refund notification email', [
                        'donation_id' => $donation->id,
                        'message' => $e->getMessage(),
                    ]);
                }
            }

            return $refundRecord;

        } finally {
            $lock->release();
        }
    }

    public function processWebhookRefund(array $payload): void
    {
        $refund = $payload['payload']['refund']['entity'] ?? [];
        $refundId = $refund['id'] ?? null;
        $paymentId = $refund['payment_id'] ?? null;
        $amountPaise = $refund['amount'] ?? null;

        if (! $refundId || ! $paymentId || $amountPaise === null) {
            return;
        }

        $amount = (float) $amountPaise / 100;

        $lock = Cache::lock('webhook_lock_'.$refundId, self::LOCK_TTL);

        if (! $lock->get()) {
            return;
        }

        try {
            $refundRecord = null;
            $donation = null;

            DB::transaction(function () use ($refundId, $paymentId, $amount, &$refundRecord, &$donation) {
                if (Refund::where('gateway_refund_id', $refundId)->exists()) {
                    return;
                }

                $donation = Donation::lockForUpdate()
                    ->where('payment_id', $paymentId)
                    ->first();

                if (! $donation || $donation->payment_status !== 'completed' || $donation->is_refunded) {
                    return;
                }

                $donation->payment_status = 'refunded';
                $donation->is_refunded = true;
                $donation->refunded_at = now();
                $donation->save();

                $refundRecord = Refund::create([
                    'donation_id' => $donation->id,
                    'donation_payment_id' => null,
                    'amount' => $amount,
                    'reason' => null,
                    'status' => 'processed',
                    'processed_at' => now(),
                    'gateway_refund_id' => $refundId,
                ]);

                try {
                    $owner = $this->walletService->ownerForDonation($donation);
                    if ($owner) {
                        $wallet = $this->walletService->getOrCreateWallet($owner);
                        $this->walletService->debit(
                            $wallet,
                            (float) $donation->net_amount,
                            WalletTransaction::SOURCE_REFUND,
                            $refundRecord->id,
                            Refund::class,
                            'Refund #'.$refundRecord->id
                        );
                    }
                } catch (\Throwable $e) {
                    Log::error('Wallet debit failed for refund', [
                        'donation_id' => $donation->id,
                        'refund_id' => $refundId,
                        'message' => $e->getMessage(),
                    ]);
                }

                Log::info('Webhook: refund processed', [
                    'donation_id' => $donation->id,
                    'refund_id' => $refundId,
                    'payment_id' => $paymentId,
                    'amount' => $amount,
                ]);
            });

            if ($refundRecord && $donation && $donation->donor_email) {
                Mail::to($donation->donor_email)->send(new DonationRefundMail($donation, $refundRecord));
            }
        } finally {
            $lock->release();
        }
    }
}
