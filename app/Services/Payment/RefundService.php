<?php

namespace App\Services\Payment;

use App\Gateways\RazorpayGateway;
use App\Mail\DonationRefundMail;
use App\Models\Donation;
use App\Models\Refund;
use App\Models\User;
use App\Services\Financial\FinancialLedgerService;
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
        private WalletService $walletService,
        private FinancialLedgerService $ledger
    ) {}

    public function processAdminRefund(Donation $donation, User $admin, string $reason): Refund
    {
        $lock = Cache::lock('donation_refund_'.$donation->id, self::LOCK_TTL);

        if (! $lock->get()) {
            throw new \RuntimeException('A refund is already being processed for this donation.');
        }

        try {
            $donation->refresh();

            // Before initiating a refund, enforce the settlement-state safety
            // guard. Donations that have already been paid out (or are actively
            // being paid out) must NOT be refunded through the normal flow.
            $this->assertRefundAllowed($donation);

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
            $this->assertRefundAmountSupported($donation, $refund);

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

            $platformFeeReversed = $this->reversePlatformEarnings($donation, $refund);

            $donation->refunded_amount = Money::of($donation->refunded_amount ?? 0)
                ->add($refund->amount)
                ->toString();
            $donation->save();

            $refund->status = Refund::STATUS_PROCESSED;
            $refund->processed_at = now();
            $refund->notes = null;
            $refund->save();

            $this->ledger->recordRefund($refund, $donation, $platformFeeReversed);
        });
    }

    /**
     * A refund must match the full donation amount. Partial refunds are not
     * currently supported — reject any refund amount that differs from the
     * original donation so a stray/accidental partial value cannot slip through.
     */
    private function assertRefundAmountSupported(Donation $donation, Refund $refund): void
    {
        $donationTotal = Money::of($donation->total_amount);
        $refundAmount = Money::of($refund->amount);

        if (! $refundAmount->isEqualTo($donationTotal)) {
            throw new \InvalidArgumentException(
                'PARTIAL REFUNDS ARE NOT CURRENTLY SUPPORTED. Refund amount must equal the full donation amount ('
                .$donationTotal.') but got '.$refundAmount.'.'
            );
        }
    }

    /**
     * Determine whether a donation may be refunded based on its settlement
     * state. Prevents the normal refund flow from reversing money that has
     * already been (or is being) paid out to the campaign owner.
     *
     * Settlement states:
     *   A. Not in a settlement              -> refund allowed.
     *   B. Request/approved/retry_pending   -> refund allowed (locally reversible
     *                                           under the current wallet state).
     *   C. Processing (payout in flight)    -> refund BLOCKED.
     *   D. Paid                             -> refund BLOCKED (needs manual
     *                                           recovery / gateway reversal).
     *
     * Refunds here never auto-reverse a completed payout nor call any
     * unverified Razorpay reversal API. A paid-out donation requires a manual
     * recovery workflow.
     */
    private function assertRefundAllowed(Donation $donation): void
    {
        $settlement = \App\Models\SettlementItem::where('donation_id', $donation->id)
            ->whereHas('settlement', function ($q) {
                $q->whereIn('status', ['paid', 'processing', 'approved', 'auto_approved', 'manual_review', 'pending_approval', 'retry_pending']);
            })
            ->with('settlement')
            ->get()
            ->pluck('settlement')
            ->first();

        if (! $settlement) {
            return;
        }

        if ($settlement->isProcessing()) {
            throw new \App\Exceptions\RefundNotAllowedException(
                'Refund is blocked because the settlement payout for this donation is currently processing.'
            );
        }

        if ($settlement->isPaid()) {
            throw new \App\Exceptions\RefundNotAllowedException(
                'Donation has already been paid out and requires a manual recovery or reversal process.'
            );
        }

        // States B (requested/approved/retry_pending) are locally reversible and
        // are allowed — the existing reversal logic debits the owner wallet and
        // reverses platform earnings within the same transaction.
    }

    /**
     * Reverse the platform fee that was booked to campaign platform_earnings at
     * donation time. Clamped to the currently booked amount so a refund can
     * never drive platform_earnings below zero.
     *
     * @return Money the actual amount reversed (may be less than the donation's
     *               platform fee if the booked earnings were already reduced).
     */
    private function reversePlatformEarnings(Donation $donation, Refund $refund): Money
    {
        $campaign = \App\Models\Campaign::lockForUpdate()->find($donation->campaign_id);

        if (! $campaign) {
            return Money::zero();
        }

        $feeToReverse = Money::of($donation->platform_fee);

        if (! $feeToReverse->isPositive()) {
            return Money::zero();
        }

        $booked = Money::of($campaign->platform_earnings ?? 0);

        if (! $booked->isPositive()) {
            return Money::zero();
        }

        $reversed = $booked->isLessThan($feeToReverse) ? $booked : $feeToReverse;

        $campaign->decrement('platform_earnings', $reversed->toFloat());

        return $reversed;
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

                // Never create a local refund for a donation that has already
                // been paid out (or is actively being paid out) — reversing the
                // owner wallet would corrupt balances. Such refunds require a
                // manual recovery workflow. Blocked webhook refunds are logged
                // and skipped (no local mutation, no gateway retry storm).
                try {
                    $this->assertRefundAllowed($donation);
                } catch (\App\Exceptions\RefundNotAllowedException $e) {
                    Log::channel('payments')->warning('Webhook refund skipped (settlement state blocks local reversal)', [
                        'donation_id' => $donation->id,
                        'payment_id' => $paymentId,
                        'refund_id' => $refundId,
                        'reason' => $e->getMessage(),
                    ]);

                    return;
                }

                // Reject any partial refund amount before mutating anything.
                // Only full (100%) refunds are supported.
                if (! Money::of($amount)->isEqualTo(Money::of($donation->total_amount))) {
                    Log::channel('payments')->warning('Webhook refund rejected: PARTIAL REFUNDS ARE NOT CURRENTLY SUPPORTED', [
                        'donation_id' => $donation->id,
                        'payment_id' => $paymentId,
                        'refund_id' => $refundId,
                        'refund_amount' => $amount,
                        'donation_total' => $donation->total_amount,
                    ]);

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
