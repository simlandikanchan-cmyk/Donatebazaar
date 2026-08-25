<?php

namespace App\Services\Payment;

use App\Gateways\RazorpayGateway;
use App\Mail\DonationRefundMail;
use App\Models\Donation;
use App\Models\Refund;
use App\Models\User;
use App\Services\WalletService;
use App\Models\WalletTransaction;
use App\Support\Money;
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

            // A prior attempt reached the gateway but failed to reverse the
            // owner wallet (insufficient balance etc.). Retry must NOT call the
            // gateway again — the gateway already refunded. Re-attempt only the
            // wallet reversal against the persisted idempotency key. This is
            // checked before the "already refunded" guard so a reversal_pending
            // refund can be completed on retry.
            $pendingRefund = Refund::where('donation_id', $donation->id)
                ->where('status', Refund::STATUS_REVERSAL_PENDING)
                ->latest('id')
                ->first();

            if ($pendingRefund) {
                $this->retryWalletReversal($donation, $pendingRefund, (string) $donation->refund_idempotency_key);

                return $pendingRefund->fresh();
            }

            if ($donation->is_refunded) {
                throw new \RuntimeException('This donation has already been refunded.');
            }

            if ($donation->payment_status !== 'completed') {
                throw new \RuntimeException('Only completed donations can be refunded.');
            }

            $paymentId = $donation->payment_id;

            if (empty($paymentId) || ! preg_match('/^pay_[A-Za-z0-9]{14,}$/', $paymentId)) {
                Log::channel('payments')->warning('Refund attempt blocked: invalid payment_id format', [
                    'donation_id' => $donation->id,
                    'payment_id' => $paymentId,
                ]);

                throw new \RuntimeException('This donation has no valid payment id and cannot be refunded.');
            }

            if (empty($donation->refund_idempotency_key)) {
                $donation->refund_idempotency_key = 'ref_'.\Illuminate\Support\Str::random(32);
                $donation->save();
            }
            $idempotencyKey = $donation->refund_idempotency_key;

            try {
                $razorpayRefund = $this->gateway->initiateRefund($donation, (int) round($donation->total_amount * 100), $idempotencyKey);
            } catch (\Razorpay\Api\Errors\Error $e) {
                Log::channel('payments')->error('Admin refund failed at gateway', [
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
                    'status' => Refund::STATUS_FAILED,
                    'processed_at' => null,
                ]);

                throw new \RuntimeException('Refund failed at the payment gateway: '.$e->getMessage());
            }

            $refundRecord = null;

            DB::transaction(function () use ($donation, $razorpayRefund, $reason, $idempotencyKey, &$refundRecord) {
                $locked = Donation::lockForUpdate()->where('id', $donation->id)->first();

                // Defensive guard: if a refund for this gateway refund id already
                // exists (e.g. a prior attempt committed the Refund but failed to
                // flip the donation flags), reuse it instead of inserting a
                // duplicate that would violate the unique gateway_refund_id index.
                $existing = Refund::where('gateway_refund_id', $razorpayRefund->id)->first();
                if ($existing) {
                    if ($existing->status === Refund::STATUS_PROCESSED) {
                        $this->healDonationRefundedState($locked);
                        $refundRecord = $existing;

                        return;
                    }

                    // Prior attempt left the reversal pending — re-attempt it now.
                    $this->attemptWalletReversal($locked, $existing, $idempotencyKey);
                    $refundRecord = $existing;

                    return;
                }

                if ($locked->payment_status !== 'completed' || $locked->is_refunded) {
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
                    'status' => Refund::STATUS_REVERSAL_PENDING,
                    'processed_at' => null,
                ]);

                try {
                    $this->attemptWalletReversal($locked, $refundRecord, $idempotencyKey);
                } catch (\Throwable $e) {
                    Log::channel('payments')->critical('Wallet reversal failed for admin refund; refund is NOT fully processed — retry will re-attempt the reversal', [
                        'donation_id' => $locked->id,
                        'refund_id' => $refundRecord->id,
                        'idempotency_key' => $idempotencyKey,
                        'error' => $e->getMessage(),
                    ]);
                    $refundRecord->refresh();
                    $refundRecord->notes = 'Wallet reversal failed: '.$e->getMessage();
                    $refundRecord->save();
                }
            });

            if ($refundRecord && $donation->donor_email) {
                try {
                    Mail::to($donation->donor_email)->queue(new DonationRefundMail($donation, $refundRecord));
                } catch (\Throwable $e) {
                    Log::channel('payments')->error('Failed to queue refund notification email', [
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

    /**
     * Re-attempt a failed wallet reversal for an existing refund. Never calls
     * the gateway — the refund already happened in a previous attempt.
     */
    private function retryWalletReversal(Donation $donation, Refund $refund, string $idempotencyKey): void
    {
        try {
            DB::transaction(function () use ($donation, $refund, $idempotencyKey) {
                $locked = Donation::lockForUpdate()->findOrFail($donation->id);
                $this->attemptWalletReversal($locked, $refund, $idempotencyKey);
            });
        } catch (\Throwable $e) {
            Log::channel('payments')->critical('Wallet reversal still failing for refund retry', [
                'donation_id' => $donation->id,
                'refund_id' => $refund->id,
                'idempotency_key' => $idempotencyKey,
                'error' => $e->getMessage(),
            ]);
            $refund->refresh();
            $refund->notes = 'Wallet reversal failed: '.$e->getMessage();
            $refund->save();

            throw new \RuntimeException('Refund reversal is still pending: '.$e->getMessage());
        }
    }

    /**
     * Execute the wallet reversal for a refund and, only when it succeeds,
     * mark the refund as fully processed. Runs in its own transaction so a
     * failed reversal leaves the refund in the reversal_pending state.
     */
    private function attemptWalletReversal(Donation $donation, Refund $refund, string $idempotencyKey): void
    {
        DB::transaction(function () use ($donation, $refund, $idempotencyKey) {
            $netAmount = Money::of($donation->net_amount);

            if ($netAmount->isPositive()) {
                $owner = $this->walletService->ownerForDonation($donation);
                if ($owner) {
                    $wallet = $this->walletService->getOrCreateWallet($owner);
                    $this->walletService->debit(
                        $wallet,
                        $donation->net_amount,
                        WalletTransaction::SOURCE_REFUND,
                        $donation->id,
                        Donation::class,
                        'Refund #'.$refund->id
                    );
                }
            }

            $refund->status = Refund::STATUS_PROCESSED;
            $refund->processed_at = now();
            $refund->notes = null;
            $refund->save();
        });
    }

    /**
     * If the gateway refunded the payment but a prior DB write failed before the
     * donation flags were flipped, heal the flags so the donation reflects the
     * gateway fact.
     */
    private function healDonationRefundedState(Donation $donation): void
    {
        if ($donation->payment_status !== 'completed' || $donation->is_refunded) {
            return;
        }

        $donation->payment_status = 'refunded';
        $donation->is_refunded = true;
        $donation->refunded_at = now();
        $donation->save();
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
                $existing = Refund::where('gateway_refund_id', $refundId)->first();

                if ($existing && $existing->status === Refund::STATUS_PROCESSED) {
                    return;
                }

                $donation = Donation::lockForUpdate()
                    ->where('payment_id', $paymentId)
                    ->first();

                if (! $donation) {
                    return;
                }

                // Repeated webhook for a refund whose wallet reversal previously
                // failed — re-attempt the reversal without touching the gateway.
                if ($existing) {
                    $this->attemptWalletReversal($donation, $existing, (string) $donation->refund_idempotency_key);
                    $refundRecord = $existing;

                    return;
                }

                if ($donation->payment_status !== 'completed' || $donation->is_refunded) {
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
                    'status' => Refund::STATUS_REVERSAL_PENDING,
                    'processed_at' => null,
                    'gateway_refund_id' => $refundId,
                ]);

                try {
                    $this->attemptWalletReversal($donation, $refundRecord, (string) $donation->refund_idempotency_key);
                } catch (\Throwable $e) {
                    Log::channel('payments')->critical('Wallet reversal failed for webhook refund; refund is NOT fully processed — retry will re-attempt the reversal', [
                        'donation_id' => $donation->id,
                        'refund_id' => $refundId,
                        'error' => $e->getMessage(),
                    ]);
                    $refundRecord->refresh();
                    $refundRecord->notes = 'Wallet reversal failed: '.$e->getMessage();
                    $refundRecord->save();
                }

                Log::channel('payments')->info('Webhook: refund processed', [
                    'donation_id' => $donation->id,
                    'refund_id' => $refundId,
                    'payment_id' => $paymentId,
                    'amount' => $amount,
                ]);
            });

            if ($refundRecord && $donation && $donation->donor_email) {
                Mail::to($donation->donor_email)->queue(new DonationRefundMail($donation, $refundRecord));
            }
        } finally {
            $lock->release();
        }
    }
}
