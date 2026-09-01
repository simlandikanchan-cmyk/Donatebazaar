<?php

namespace App\Services;

use App\Events\SettlementAutoApproved;
use App\Events\SettlementCancelled;
use App\Events\SettlementFailed;
use App\Events\SettlementManualReviewRequired;
use App\Events\SettlementPaid;
use App\Events\SettlementProcessingStarted;
use App\Events\RiskEvaluationCompleted;
use App\Events\SettlementRejected;
use App\Events\SettlementRequested;
use App\Events\SettlementRetryScheduled;
use App\Exceptions\InsufficientWalletBalanceException;
use App\Exceptions\PermanentFailureException;
use App\Exceptions\TemporaryFailureException;
use App\Exceptions\TimeoutException;
use App\Gateways\RazorpayGateway;
use App\Jobs\RetryPolicy;
use App\Models\CampaignSettlement;
use App\Models\Donation;
use App\Models\Organization;
use App\Models\PayoutAttempt;
use App\Models\SettlementItem;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Services\Risk\RiskEngine;
use App\Services\Settlement\SettlementStateMachine;
use App\Support\Money;
use Illuminate\Support\Facades\DB;

class SettlementService
{
    public function __construct(
        private readonly WalletService $walletService,
        private readonly SettlementStateMachine $stateMachine,
        private readonly RiskEngine $riskEngine,
        private readonly RazorpayGateway $gateway
    ) {}

    /**
     * Request a new settlement for the given donation IDs.
     *
     * Flow:
     *  1. Validate donations
     *  2. Wallet reserve/lock
     *  3. Create settlement
     *  4. StateMachine -> requested -> risk_evaluation
     *  5. RiskEngine.evaluate()
     *  6. StateMachine transition to verdict
     *  7. Dispatch events
     */
    public function requestSettlement(Organization $org, array $donationIds): CampaignSettlement
    {
        if (empty($donationIds)) {
            throw new \InvalidArgumentException('No donation IDs supplied for settlement.');
        }

        $orgUserId = $org->user_id;
        $wallet = $this->walletService->getOrCreateWallet($this->walletOwnerForOrg($org));

        // Lock donation rows (in id order) + wallet, then create the settlement
        // atomically so two concurrent requests can never lock the same
        // donation twice or debit the wallet twice.
        $settlement = DB::transaction(function () use ($org, $donationIds, $orgUserId, $wallet) {
            $donations = Donation::whereIn('id', $donationIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->with('campaign:user_id,id')
                ->get();

            if ($donations->isEmpty()) {
                throw new \InvalidArgumentException('No eligible donations supplied for settlement.');
            }

            $ineligible = $donations->filter(function ($donation) {
                return $donation->payment_status !== 'completed'
                    || $donation->is_refunded
                    || $donation->settlement_status !== 'pending';
            });

            if ($ineligible->isNotEmpty()) {
                throw new \InvalidArgumentException('One or more donations are not eligible for settlement.');
            }

            $unauthorized = $donations->filter(function ($donation) use ($orgUserId) {
                return $donation->campaign?->user_id !== $orgUserId;
            });

            if ($unauthorized->isNotEmpty()) {
                throw new \InvalidArgumentException(
                    'One or more donation IDs do not belong to your organization.'
                );
            }

            $lockedIds = SettlementItem::whereIn('donation_id', $donations->pluck('id'))
                ->whereHas('settlement', function ($q) {
                    $q->whereNotIn('status', ['paid', 'rejected', 'failed', 'cancelled']);
                })
                ->pluck('donation_id')
                ->all();

            if (! empty($lockedIds)) {
                throw new \InvalidArgumentException(
                    'Some donations are already locked in a pending or approved settlement.'
                );
            }

            $total = Money::sum($donations->pluck('net_amount'));

            $this->walletService->releaseReservesForDonations($wallet, $donations->all());

            $locked = Wallet::lockForUpdate()->findOrFail($wallet->id);
            if (Money::of($locked->balance)->isLessThan($total)) {
                throw new InsufficientWalletBalanceException(
                    "Available balance insufficient for settlement: have {$locked->balance}, need {$total}."
                );
            }

            $locked->balance = Money::of($locked->balance)->sub($total)->toString();
            $locked->pending_settlement_balance = Money::of($locked->pending_settlement_balance)->add($total)->toString();
            $locked->save();

            $settlement = CampaignSettlement::create([
                'campaign_id' => $donations->first()->campaign_id,
                'organization_id' => $org->id,
                'gross_amount' => Money::sum($donations->pluck('total_amount'))->toString(),
                'platform_fee' => Money::sum($donations->pluck('platform_fee'))->toString(),
                'net_amount' => $total->toString(),
                'status' => 'requested',
            ]);

            foreach ($donations as $donation) {
                SettlementItem::create([
                    'campaign_settlement_id' => $settlement->id,
                    'donation_id' => $donation->id,
                    'amount' => $donation->net_amount,
                ]);
            }

            return $settlement;
        });

        $this->stateMachine->transition($settlement, 'risk_evaluation', [
            'actor_type' => 'system',
            'reason' => 'Risk evaluation started',
        ]);

        $riskResult = $this->riskEngine->evaluate($settlement);

        $this->stateMachine->transition($settlement, $riskResult->verdict, [
            'actor_type' => 'system',
            'reason' => 'Risk evaluation completed: '.$riskResult->verdict,
        ]);

        event(new RiskEvaluationCompleted($settlement, $riskResult->verdict, $riskResult->score));

        if ($riskResult->isRejected()) {
            $this->refundSettlementFunds($settlement, 'rejected by risk evaluation');
        }

        event(new SettlementRequested($settlement));

        if ($riskResult->isAutoApproved()) {
            event(new SettlementAutoApproved($settlement));
        } elseif ($riskResult->isManualReview()) {
            event(new SettlementManualReviewRequired($settlement));
        } elseif ($riskResult->isRejected()) {
            event(new SettlementRejected($settlement));
        }

        return $settlement->fresh();
    }

    /**
     * Admin approves a settlement.
     * Debits pending_settlement_balance and transitions to approved.
     */
    public function approveSettlement(CampaignSettlement $settlement, User $admin): void
    {
        if (! $settlement->isPendingApproval() && $settlement->status !== 'manual_review') {
            throw new \InvalidArgumentException('Only pending_approval or manual_review settlements can be approved.');
        }

        $org = $settlement->organization;
        if (! $org) {
            throw new \InvalidArgumentException('Settlement has no organization.');
        }

        if (! $org->payoutAccounts()->where('is_verified', true)->exists()) {
            throw new \InvalidArgumentException(
                'Cannot approve: the organization has no verified payout account.'
            );
        }

        $wallet = $this->walletService->getOrCreateWallet($this->walletOwnerForOrg($org));
        $amount = Money::of($settlement->net_amount);

        DB::transaction(function () use ($wallet, $settlement, $admin, $amount) {
            $lockedSettlement = CampaignSettlement::lockForUpdate()->findOrFail($settlement->id);

            if (! $lockedSettlement->isPendingApproval() && $lockedSettlement->status !== 'manual_review') {
                throw new \InvalidArgumentException('Settlement is no longer pending approval.');
            }

            $locked = Wallet::lockForUpdate()->findOrFail($wallet->id);

            if (Money::of($locked->pending_settlement_balance)->isLessThan($amount)) {
                throw new InsufficientWalletBalanceException('Pending settlement balance mismatch.');
            }

            $locked->pending_settlement_balance = Money::of($locked->pending_settlement_balance)->sub($amount)->toString();
            $locked->save();

            $this->walletService->record(
                $locked,
                'debit',
                $amount,
                WalletTransaction::SOURCE_SETTLEMENT,
                $settlement->id,
                CampaignSettlement::class,
                'Settlement approved #'.$settlement->id
            );

            $this->stateMachine->transition($lockedSettlement, 'approved', [
                'actor_type' => 'admin',
                'actor_id' => $admin->id,
                'reason' => 'Approved by admin',
            ]);

            $lockedSettlement->approved_by = $admin->id;
            $lockedSettlement->approved_at = now();
            $lockedSettlement->save();
        });
    }

    /**
     * Admin rejects a settlement.
     * Returns funds to balance and transitions to rejected.
     */
    public function rejectSettlement(CampaignSettlement $settlement, User $admin, string $reason): void
    {
        if (! $settlement->isPendingApproval() && $settlement->status !== 'manual_review') {
            throw new \InvalidArgumentException('Only pending_approval or manual_review settlements can be rejected.');
        }

        if (trim($reason) === '') {
            throw new \InvalidArgumentException('A rejection reason is required.');
        }

        $org = $settlement->organization;
        if (! $org) {
            throw new \InvalidArgumentException('Settlement has no organization.');
        }

        DB::transaction(function () use ($settlement, $admin, $reason) {
            $lockedSettlement = CampaignSettlement::lockForUpdate()->findOrFail($settlement->id);

            if (! $lockedSettlement->isPendingApproval() && $lockedSettlement->status !== 'manual_review') {
                throw new \InvalidArgumentException('Settlement is no longer pending approval.');
            }

            // Return funds from pending_settlement_balance back to balance,
            // and record the reversal ledger entry. Uses the same
            // idempotency guard as refundSettlementFunds(): if the pending
            // balance can no longer cover the amount, it throws
            // InsufficientWalletBalanceException, preventing duplicate
            // reversals on retry or concurrent calls.
            $this->refundSettlementFunds($lockedSettlement, 'rejected by admin: '.$reason);

            $this->stateMachine->transition($lockedSettlement, 'rejected', [
                'actor_type' => 'admin',
                'actor_id' => $admin->id,
                'reason' => $reason,
            ]);

            $lockedSettlement->rejected_by = $admin->id;
            $lockedSettlement->rejected_at = now();
            $lockedSettlement->rejection_reason = $reason;
            $lockedSettlement->save();
        });
    }

    /**
     * Process an approved settlement payout.
     *
     * Split into two transactions so the gateway HTTP call never holds
     * DB locks: phase 1 claims the settlement (approved/auto_approved/
     * retry_pending -> processing), the gateway is called, then phase 2
     * atomically records the outcome (paid / failed / retry_pending).
     *
     * Idempotent: a settlement already `paid` or `failed` short-circuits,
     * a settlement stuck in `processing` (crash between phases) resumes
     * with the same idempotency key, and concurrent workers serialize on
     * the settlement row lock.
     */
    public function processSettlementPayout(CampaignSettlement $settlement): array
    {
        // The caller may hold a stale instance; fast-path checks must run
        // against the persisted state (the authoritative, row-locked checks
        // happen inside the transactions below).
        $settlement->refresh();

        if ($settlement->isPaid()) {
            return ['success' => true, 'message' => 'Settlement already paid.'];
        }

        if ($settlement->isFailed()) {
            return ['success' => false, 'message' => 'Settlement previously failed.'];
        }

        if (! in_array($settlement->status, ['approved', 'auto_approved', 'retry_pending', 'processing'], true)) {
            throw new \InvalidArgumentException('Only approved, auto_approved, retry_pending or processing settlements can be processed.');
        }

        $org = $settlement->organization;
        if (! $org) {
            throw new \InvalidArgumentException('Settlement has no organization.');
        }

        $wallet = $this->walletService->getOrCreateWallet($this->walletOwnerForOrg($org));
        $amount = Money::of($settlement->net_amount);

        // PHASE 1 — claim the settlement. Commits before the gateway call.
        $alreadyPaid = DB::transaction(function () use ($settlement, $wallet, $amount) {
            $locked = CampaignSettlement::lockForUpdate()->findOrFail($settlement->id);

            if ($locked->isPaid()) {
                return true;
            }

            if (! in_array($locked->status, ['approved', 'auto_approved', 'retry_pending', 'processing'], true)) {
                throw new \InvalidArgumentException(
                    'Settlement is no longer processable (status: '.$locked->status.').'
                );
            }

            // Resume path: a previous worker crashed between phase 1 and phase 2.
            if ($locked->status === 'processing') {
                return false;
            }

            if ($locked->isAutoApproved()) {
                $w = Wallet::lockForUpdate()->findOrFail($wallet->id);

                if (Money::of($w->pending_settlement_balance)->isLessThan($amount)) {
                    throw new InsufficientWalletBalanceException('Pending settlement balance mismatch.');
                }

                $w->pending_settlement_balance = Money::of($w->pending_settlement_balance)->sub($amount)->toString();
                $w->save();

                $this->walletService->record(
                    $w,
                    'debit',
                    $amount,
                    WalletTransaction::SOURCE_SETTLEMENT,
                    $locked->id,
                    CampaignSettlement::class,
                    'Auto-approved settlement #'.$locked->id
                );
            }

            $this->stateMachine->transition($locked, 'processing', [
                'actor_type' => 'system',
                'reason' => 'Payout processing started',
            ]);

            $locked->processed_at = now();
            $locked->save();

            event(new SettlementProcessingStarted($locked));

            return false;
        });

        if ($alreadyPaid) {
            return ['success' => true, 'message' => 'Settlement already paid.'];
        }

        // GATEWAY — no DB transaction and no DB locks held.
        $attemptNumber = ((int) ($settlement->retry_count ?? 0)) + 1;
        $idempotencyKey = PayoutAttempt::generateIdempotencyKey($settlement, $attemptNumber);

        try {
            $gatewayResult = $this->gateway->initiatePayout($org, $amount->toFloat(), $settlement, $idempotencyKey);
            $outcome = ['success' => true, 'result' => $gatewayResult];
        } catch (PermanentFailureException|TimeoutException|TemporaryFailureException|GatewayException $e) {
            $outcome = [
                'success' => false,
                'retryable' => $e instanceof TimeoutException || $e instanceof TemporaryFailureException,
                'exception' => $e,
            ];
        }

        // PHASE 2 — atomically record the outcome.
        return DB::transaction(function () use ($settlement, $outcome) {
            $locked = CampaignSettlement::lockForUpdate()->findOrFail($settlement->id);

            if ($locked->isPaid()) {
                return ['success' => true, 'message' => 'Settlement already paid.'];
            }

            if ($locked->status !== 'processing') {
                // The settlement changed while the gateway call was in flight
                // (e.g. admin cancelled it) — do not write an outcome.
                return ['success' => false, 'message' => 'Settlement is no longer processing.', 'retryable' => false];
            }

            if ($outcome['success']) {
                $result = $outcome['result'];
                $providerStatus = strtolower($result['provider_status'] ?? '');

                if (in_array($providerStatus, ['processed', 'paid'], true)) {
                    $this->stateMachine->transition($locked, 'paid', [
                        'actor_type' => 'system',
                        'reason' => 'Payout completed',
                    ]);

                    $locked->paid_at = now();
                    $locked->gateway_reference = $result['gateway_reference'];
                    $locked->gateway_status = $result['provider_status'] ?? null;
                    $locked->retry_count = 0;
                    $locked->next_retry_at = null;
                    $locked->save();

                    $donationIds = $locked->settlementItems()->pluck('donation_id');
                    Donation::whereIn('id', $donationIds)->update([
                        'settlement_status' => 'settled',
                        'campaign_settlement_id' => $locked->id,
                    ]);

                    event(new SettlementPaid($locked, $result['gateway_reference']));

                    return ['success' => true, 'message' => 'Payout completed successfully.'];
                }

                $locked->gateway_reference = $result['gateway_reference'];
                $locked->gateway_status = $result['provider_status'] ?? null;
                $locked->save();

                return [
                    'success' => false,
                    'message' => 'Payout pending with provider: '.$providerStatus,
                    'pending' => true,
                ];
            }

            $e = $outcome['exception'];
            $retryable = $outcome['retryable'];

            if (! $retryable) {
                $this->restoreSettlementFunds($locked, 'failed payout');
            }

            $nextRetryAt = null;
            if ($retryable) {
                $nextRetryAt = app(RetryPolicy::class)->nextRetryAt(((int) ($locked->retry_count ?? 0)) + 1);
            }

            $this->stateMachine->transition($locked, $retryable ? 'retry_pending' : 'failed', [
                'actor_type' => 'system',
                'reason' => $e->getMessage(),
            ]);

            $locked->failed_at = $retryable ? null : now();
            $locked->failed_reason = $retryable ? null : $e->getMessage();
            $locked->retry_count = ((int) ($locked->retry_count ?? 0)) + 1;
            $locked->next_retry_at = $nextRetryAt;
            $locked->save();

            if ($retryable) {
                event(new SettlementRetryScheduled($locked, $nextRetryAt, $locked->retry_count));
            } else {
                event(new SettlementFailed($locked, $e->getMessage()));
            }

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'retryable' => $retryable,
            ];
        });
    }

    public function cancelSettlement(CampaignSettlement $settlement, ?User $actor = null, ?string $reason = null): void
    {
        DB::transaction(function () use ($settlement, $actor, $reason) {
            $locked = CampaignSettlement::lockForUpdate()->findOrFail($settlement->id);

            if (! $this->stateMachine->canTransition($locked->status, 'cancelled')) {
                throw new \InvalidArgumentException('Settlement cannot be cancelled from its current state.');
            }

            $from = $locked->status;

            // Only funds still held in pending_settlement_balance are returned.
            // Once funds have left the wallet (approved/processing/retry_pending),
            // the money is recovered via the gateway (reconciliation) instead.
            if (in_array($from, ['requested', 'risk_evaluation', 'auto_approved', 'manual_review'], true)) {
                $this->refundSettlementFunds($locked, 'cancelled');
            }

            $this->stateMachine->transition($locked, 'cancelled', [
                'actor_type' => $actor ? 'admin' : 'system',
                'actor_id' => $actor?->id,
                'reason' => $reason,
            ]);
        });

        event(new SettlementCancelled($settlement->fresh(), $reason));
    }

    /**
     * Move settlement funds from pending_settlement_balance back to balance.
     * Idempotent-safe: throws when the pending balance can no longer cover the amount.
     */
    protected function refundSettlementFunds(CampaignSettlement $settlement, string $reason): void
    {
        $org = $settlement->organization;

        if (! $org) {
            return;
        }

        $wallet = $this->walletService->getOrCreateWallet($this->walletOwnerForOrg($org));
        $amount = Money::of($settlement->net_amount);

        DB::transaction(function () use ($wallet, $settlement, $amount, $reason) {
            $locked = Wallet::lockForUpdate()->findOrFail($wallet->id);

            if (Money::of($locked->pending_settlement_balance)->isLessThan($amount)) {
                throw new InsufficientWalletBalanceException(
                    'Pending settlement balance mismatch while refunding settlement #'.$settlement->id.'.'
                );
            }

            $locked->pending_settlement_balance = Money::of($locked->pending_settlement_balance)->sub($amount)->toString();
            $locked->balance = Money::of($locked->balance)->add($amount)->toString();
            $locked->save();

            $this->walletService->record(
                $locked,
                'credit',
                $amount,
                WalletTransaction::SOURCE_SETTLEMENT_REVERSAL,
                $settlement->id,
                CampaignSettlement::class,
                'Settlement refunded — '.$reason.' #'.$settlement->id
            );
        });
    }

    /**
     * Restore settlement funds that have already left the wallet
     * (approved / processing / retry_pending) back to the balance.
     *
     * Composes inside the caller's transaction — the caller must hold the
     * settlement row lock so the restore is atomic with the state change.
     */
    public function restoreSettlementFunds(CampaignSettlement $settlement, string $reason): void
    {
        $org = $settlement->organization;

        if (! $org) {
            return;
        }

        if ($settlement->restored_at !== null) {
            return;
        }

        $wallet = $this->walletService->getOrCreateWallet($this->walletOwnerForOrg($org));
        $amount = Money::of($settlement->net_amount);

        DB::transaction(function () use ($wallet, $settlement, $amount, $reason) {
            $locked = Wallet::lockForUpdate()->findOrFail($wallet->id);
            $lockedSettlement = CampaignSettlement::lockForUpdate()->findOrFail($settlement->id);

            if ($lockedSettlement->restored_at !== null) {
                return;
            }

            $locked->balance = Money::of($locked->balance)->add($amount)->toString();
            $locked->save();

            $this->walletService->record(
                $locked,
                'credit',
                $amount,
                WalletTransaction::SOURCE_SETTLEMENT_REVERSAL,
                $settlement->id,
                CampaignSettlement::class,
                'Settlement funds restored — '.$reason.' #'.$settlement->id
            );

            $lockedSettlement->restored_at = now();
            $lockedSettlement->save();
        });
    }

    protected function walletOwnerForOrg(Organization $org): User
    {
        $owner = $org->owner;

        if (! $owner) {
            throw new \InvalidArgumentException('Organization has no owning user; cannot resolve wallet.');
        }

        return $owner;
    }
}
