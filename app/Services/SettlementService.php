<?php

namespace App\Services;

use App\Events\RiskEvaluationCompleted;
use App\Events\SettlementAutoApproved;
use App\Events\SettlementCancelled;
use App\Events\SettlementFailed;
use App\Events\SettlementManualReviewRequired;
use App\Events\SettlementPaid;
use App\Events\SettlementProcessingStarted;
use App\Events\SettlementRejected;
use App\Events\SettlementRequested;
use App\Events\SettlementRetryScheduled;
use App\Exceptions\GatewayException;
use App\Exceptions\InsufficientWalletBalanceException;
use App\Exceptions\PermanentFailureException;
use App\Exceptions\TemporaryFailureException;
use App\Exceptions\TimeoutException;
use App\Models\CampaignSettlement;
use App\Models\Donation;
use App\Models\Organization;
use App\Models\SettlementItem;
use App\Models\SettlementStateLog;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Services\Risk\RiskEngine;
use App\Services\Risk\RiskEvaluationResult;
use App\Services\Settlement\SettlementStateMachine;
use App\Gateways\RazorpayGateway;
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
        $donations = Donation::whereIn('id', $donationIds)
            ->where('payment_status', 'completed')
            ->where('is_refunded', false)
            ->where('settlement_status', 'pending')
            ->get();

        if ($donations->isEmpty()) {
            throw new \InvalidArgumentException('No eligible donations supplied for settlement.');
        }

        $lockedIds = SettlementItem::whereIn('donation_id', $donations->pluck('id'))
            ->whereHas('settlement', function ($q) {
                $q->whereIn('status', ['pending_approval', 'approved']);
            })
            ->pluck('donation_id')
            ->all();

        if (! empty($lockedIds)) {
            throw new \InvalidArgumentException(
                'Some donations are already locked in a pending or approved settlement.'
            );
        }

        $total = (float) $donations->sum('net_amount');
        $wallet = $this->walletService->getOrCreateWallet($this->walletOwnerForOrg($org));

        DB::transaction(function () use ($wallet, $donations, $total) {
            $this->walletService->releaseReservesForDonations($wallet, $donations->all());

            $locked = $wallet->fresh();
            if ((float) $locked->balance < $total) {
                throw new InsufficientWalletBalanceException(
                    "Available balance insufficient for settlement: have {$locked->balance}, need {$total}."
                );
            }

            $locked->balance = (float) $locked->balance - $total;
            $locked->pending_settlement_balance = (float) $locked->pending_settlement_balance + $total;
            $locked->save();
        });

        $settlement = CampaignSettlement::create([
            'campaign_id' => $donations->first()->campaign_id,
            'organization_id' => $org->id,
            'gross_amount' => (float) $donations->sum('total_amount'),
            'platform_fee' => (float) $donations->sum('platform_fee'),
            'net_amount' => $total,
            'status' => 'requested',
        ]);

        foreach ($donations as $donation) {
            SettlementItem::create([
                'campaign_settlement_id' => $settlement->id,
                'donation_id' => $donation->id,
                'amount' => $donation->net_amount,
            ]);
        }

        $this->stateMachine->transition($settlement, 'risk_evaluation', [
            'actor_type' => 'system',
            'reason' => 'Risk evaluation started',
        ]);

        $riskResult = $this->riskEngine->evaluate($settlement);

        $this->stateMachine->transition($settlement, $riskResult->verdict, [
            'actor_type' => 'system',
            'reason' => 'Risk evaluation completed: '.$riskResult->verdict,
        ]);

        event(new SettlementRequested($settlement));
        event(new RiskEvaluationCompleted($settlement, $riskResult));

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
        $amount = (float) $settlement->net_amount;

        DB::transaction(function () use ($wallet, $settlement, $admin, $amount) {
            $locked = $wallet->fresh();

            if ((float) $locked->pending_settlement_balance < $amount) {
                throw new InsufficientWalletBalanceException('Pending settlement balance mismatch.');
            }

            $locked->pending_settlement_balance = (float) $locked->pending_settlement_balance - $amount;
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

            $this->stateMachine->transition($settlement, 'approved', [
                'actor_type' => 'admin',
                'actor_id' => $admin->id,
                'reason' => 'Approved by admin',
            ]);

            $settlement->update([
                'approved_by' => $admin->id,
                'approved_at' => now(),
            ]);
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

        $wallet = $this->walletService->getOrCreateWallet($this->walletOwnerForOrg($org));
        $amount = (float) $settlement->net_amount;

        DB::transaction(function () use ($wallet, $settlement, $admin, $reason, $amount) {
            $locked = $wallet->fresh();

            $locked->pending_settlement_balance = (float) $locked->pending_settlement_balance - $amount;
            $locked->balance = (float) $locked->balance + $amount;
            $locked->save();

            $this->stateMachine->transition($settlement, 'rejected', [
                'actor_type' => 'admin',
                'actor_id' => $admin->id,
                'reason' => $reason,
            ]);

            $settlement->update([
                'rejected_by' => $admin->id,
                'rejected_at' => now(),
                'rejection_reason' => $reason,
            ]);
        });
    }

    /**
     * Process an approved settlement payout.
     * Idempotent. On permanent failure restores wallet and marks failed.
     * On retryable failure transitions to retry_pending without restoring wallet.
     */
    public function processSettlementPayout(CampaignSettlement $settlement): array
    {
        if ($settlement->isPaid()) {
            return ['success' => true, 'message' => 'Settlement already paid.'];
        }

        if ($settlement->isFailed()) {
            return ['success' => false, 'message' => 'Settlement previously failed.'];
        }

        if (! $settlement->isApproved() && ! $settlement->isRetryPending()) {
            throw new \InvalidArgumentException('Only approved or retry_pending settlements can be processed.');
        }

        $org = $settlement->organization;
        if (! $org) {
            throw new \InvalidArgumentException('Settlement has no organization.');
        }

        $wallet = $this->walletService->getOrCreateWallet($this->walletOwnerForOrg($org));
        $amount = (float) $settlement->net_amount;

        return DB::transaction(function () use ($wallet, $settlement, $org, $amount) {
            $this->stateMachine->transition($settlement, 'processing', [
                'actor_type' => 'system',
                'reason' => 'Payout processing started',
            ]);

            $settlement->update(['processed_at' => now()]);

            event(new SettlementProcessingStarted($settlement));

            try {
                $result = $this->gateway->initiatePayout($org, $amount, $settlement);

                $this->stateMachine->transition($settlement, 'paid', [
                    'actor_type' => 'system',
                    'reason' => 'Payout completed',
                ]);

                $settlement->update([
                    'paid_at' => now(),
                    'gateway_reference' => $result['gateway_reference'],
                    'retry_count' => 0,
                    'next_retry_at' => null,
                ]);

                $donationIds = $settlement->settlementItems()->pluck('donation_id');
                Donation::whereIn('id', $donationIds)->update([
                    'settlement_status' => 'settled',
                    'campaign_settlement_id' => $settlement->id,
                ]);

                event(new SettlementPaid($settlement, $result['gateway_reference']));

                return ['success' => true, 'message' => 'Payout completed successfully.'];
            } catch (PermanentFailureException|TimeoutException|TemporaryFailureException $e) {
                $retryable = $e instanceof TimeoutException || $e instanceof TemporaryFailureException;

                if (! $retryable) {
                    $locked = $wallet->fresh();
                    $locked->balance = (float) $locked->balance + $amount;
                    $locked->save();

                    $this->walletService->record(
                        $locked,
                        'credit',
                        $amount,
                        WalletTransaction::SOURCE_SETTLEMENT_REVERSAL,
                        $settlement->id,
                        CampaignSettlement::class,
                        'Settlement reversal #'.$settlement->id.' — '.$e->getMessage()
                    );
                }

                $nextRetryAt = null;
                if ($retryable) {
                    $nextRetryAt = app(\App\Jobs\RetryPolicy::class)->nextRetryAt(($settlement->retry_count ?? 0) + 1);
                }

                $this->stateMachine->transition($settlement, $retryable ? 'retry_pending' : 'failed', [
                    'actor_type' => 'system',
                    'reason' => $e->getMessage(),
                ]);

                $settlement->update([
                    'failed_at' => $retryable ? null : now(),
                    'failed_reason' => $retryable ? null : $e->getMessage(),
                    'retry_count' => ($settlement->retry_count ?? 0) + 1,
                    'next_retry_at' => $nextRetryAt,
                ]);

                if ($retryable) {
                    event(new SettlementRetryScheduled($settlement, $nextRetryAt, $settlement->retry_count));
                } else {
                    event(new SettlementFailed($settlement, $e->getMessage()));
                }

                return [
                    'success' => false,
                    'message' => $e->getMessage(),
                    'retryable' => $retryable,
                ];
            }
        });
    }

    public function cancelSettlement(CampaignSettlement $settlement, ?User $actor = null, ?string $reason = null): void
    {
        if (! $this->stateMachine->canTransition($settlement->status, 'cancelled')) {
            throw new \InvalidArgumentException('Settlement cannot be cancelled from its current state.');
        }

        $this->stateMachine->transition($settlement, 'cancelled', [
            'actor_type' => $actor ? 'admin' : 'system',
            'actor_id' => $actor?->id,
            'reason' => $reason,
        ]);

        event(new SettlementCancelled($settlement, $reason));
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
