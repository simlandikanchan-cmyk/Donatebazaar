<?php

namespace App\Services\Reconciliation;

use App\Gateways\RazorpayGateway;
use App\Events\SettlementCancelled;
use App\Events\SettlementFailed;
use App\Events\SettlementPaid;
use App\Events\SettlementProcessingStarted;
use App\Exceptions\GatewayException;
use App\Exceptions\InvalidSettlementTransitionException;
use App\Exceptions\PermanentFailureException;
use App\Exceptions\TemporaryFailureException;
use App\Exceptions\TimeoutException;
use App\Models\CampaignSettlement;
use App\Models\PayoutAttempt;
use App\Models\User;
use App\Services\Settlement\SettlementStateMachine;
use App\Services\SettlementService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReconciliationService
{
    public function __construct(
        private readonly RazorpayGateway $gateway,
        private readonly SettlementStateMachine $stateMachine,
        private readonly int $batchSize = 100,
        private readonly int $processingStuckMinutes = 30,
        private readonly ?SettlementService $settlementService = null
    ) {}

    public function reconcile(): array
    {
        $settlements = $this->getStuckSettlements();
        $results = [];

        foreach ($settlements as $settlement) {
            $startTime = microtime(true);
            $result = $this->reconcileSettlement($settlement);
            $duration = microtime(true) - $startTime;

            Log::info('Reconciliation completed', [
                'settlement_id' => $settlement->id,
                'correlation_id' => $settlement->correlation_id,
                'trace_id' => $settlement->trace_id,
                'gateway_status' => $result->gatewayStatus,
                'local_status' => $result->localStatus,
                'action' => $result->actionTaken,
                'reconciled' => $result->reconciled,
                'duration_ms' => round($duration * 1000, 2),
            ]);

            $results[] = $result;
        }

        return $results;
    }

    public function reconcileSettlement(CampaignSettlement $settlement): ReconciliationResult
    {
        if ($this->isTerminal($settlement)) {
            return ReconciliationResult::skipped(
                settlementId: $settlement->id,
                localStatus: $settlement->status,
                actionTaken: 'already_terminal'
            );
        }

        if (is_null($settlement->gateway_reference)) {
            return ReconciliationResult::skipped(
                settlementId: $settlement->id,
                localStatus: $settlement->status,
                actionTaken: 'missing_gateway_reference'
            );
        }

        try {
            $gatewayStatus = $this->gateway->getPayoutStatus($settlement->gateway_reference);
        } catch (TimeoutException|TemporaryFailureException $e) {
            Log::warning('Reconciliation gateway timeout', [
                'settlement_id' => $settlement->id,
                'error' => $e->getMessage(),
                'correlation_id' => $settlement->correlation_id,
                'trace_id' => $settlement->trace_id,
            ]);

            return ReconciliationResult::failed(
                settlementId: $settlement->id,
                localStatus: $settlement->status,
                error: $e->getMessage(),
                metadata: ['retryable' => true]
            );
        } catch (PermanentFailureException $e) {
            Log::error('Reconciliation gateway permanent failure', [
                'settlement_id' => $settlement->id,
                'error' => $e->getMessage(),
                'correlation_id' => $settlement->correlation_id,
                'trace_id' => $settlement->trace_id,
            ]);

            return ReconciliationResult::failed(
                settlementId: $settlement->id,
                localStatus: $settlement->status,
                error: $e->getMessage(),
                metadata: ['retryable' => false]
            );
        } catch (GatewayException $e) {
            Log::error('Reconciliation gateway error', [
                'settlement_id' => $settlement->id,
                'error' => $e->getMessage(),
                'correlation_id' => $settlement->correlation_id,
                'trace_id' => $settlement->trace_id,
            ]);

            return ReconciliationResult::failed(
                settlementId: $settlement->id,
                localStatus: $settlement->status,
                error: $e->getMessage(),
                metadata: ['retryable' => false]
            );
        }

        $status = strtolower($gatewayStatus['status'] ?? 'unknown');

        if ($status === 'unknown') {
            return ReconciliationResult::skipped(
                settlementId: $settlement->id,
                localStatus: $settlement->status,
                actionTaken: 'unknown_gateway_status'
            );
        }

        return $this->reconcileStatus($settlement, $status, $gatewayStatus);
    }

    private function reconcileStatus(CampaignSettlement $settlement, string $gatewayStatus, array $gatewayData): ReconciliationResult
    {
        $localStatus = $settlement->status;

        if ($gatewayStatus === 'paid' && $localStatus === 'processing') {
            return $this->transitionToPaid($settlement, $gatewayData);
        }

        if ($gatewayStatus === 'failed' && $localStatus === 'processing') {
            return $this->transitionToFailed($settlement, $gatewayData);
        }

        if ($gatewayStatus === 'cancelled' && $localStatus === 'processing') {
            return $this->transitionToCancelled($settlement, $gatewayData);
        }

        return ReconciliationResult::success(
            settlementId: $settlement->id,
            gatewayStatus: $gatewayStatus,
            localStatus: $localStatus,
            metadata: ['action' => 'no_change_needed']
        );
    }

    private function transitionToPaid(CampaignSettlement $settlement, array $gatewayData): ReconciliationResult
    {
        $proceed = DB::transaction(function () use ($settlement) {
            $locked = CampaignSettlement::lockForUpdate()->findOrFail($settlement->id);
            if ($locked->status !== 'processing') {
                return false;
            }

            $this->stateMachine->transition($locked, 'paid', [
                'actor_type' => 'system',
                'reason' => 'Reconciliation: gateway confirmed paid',
            ]);

            $locked->paid_at = now();
            $locked->gateway_status = $gatewayData['status'] ?? null;
            $locked->retry_count = 0;
            $locked->next_retry_at = null;
            $locked->save();

            $donationIds = $locked->settlementItems()->pluck('donation_id');
            \App\Models\Donation::whereIn('id', $donationIds)->update([
                'settlement_status' => 'settled',
                'campaign_settlement_id' => $locked->id,
            ]);

            return true;
        });

        if (! $proceed) {
            return $this->skippedStatusChanged($settlement);
        }

        event(new SettlementPaid($settlement, $settlement->gateway_reference));

        return ReconciliationResult::corrected(
            settlementId: $settlement->id,
            gatewayStatus: 'paid',
            localStatus: 'paid',
            actionTaken: 'transitioned_to_paid',
            metadata: ['gateway_reference' => $settlement->gateway_reference]
        );
    }

    private function transitionToFailed(CampaignSettlement $settlement, array $gatewayData): ReconciliationResult
    {
        $proceed = DB::transaction(function () use ($settlement) {
            $locked = CampaignSettlement::lockForUpdate()->findOrFail($settlement->id);
            if ($locked->status !== 'processing') {
                return false;
            }

            // Gateway confirmed the payout never went through: return the
            // funds that left the wallet at approval time to the balance.
            $this->settlementService()->restoreSettlementFunds($locked, 'reconciliation failed');

            $this->stateMachine->transition($locked, 'failed', [
                'actor_type' => 'system',
                'reason' => 'Reconciliation: gateway reported failed',
            ]);

            $locked->failed_at = now();
            $locked->failed_reason = 'Reconciliation: gateway reported failed';
            $locked->gateway_status = $gatewayData['status'] ?? null;
            $locked->save();

            return true;
        });

        if (! $proceed) {
            return $this->skippedStatusChanged($settlement);
        }

        event(new SettlementFailed($settlement, 'Reconciliation: gateway reported failed'));

        return ReconciliationResult::corrected(
            settlementId: $settlement->id,
            gatewayStatus: 'failed',
            localStatus: 'failed',
            actionTaken: 'transitioned_to_failed'
        );
    }

    private function transitionToCancelled(CampaignSettlement $settlement, array $gatewayData): ReconciliationResult
    {
        $proceed = DB::transaction(function () use ($settlement) {
            $locked = CampaignSettlement::lockForUpdate()->findOrFail($settlement->id);
            if ($locked->status !== 'processing') {
                return false;
            }

            // Gateway confirmed the payout was cancelled: return the funds.
            $this->settlementService()->restoreSettlementFunds($locked, 'reconciliation cancelled');

            $this->stateMachine->transition($locked, 'cancelled', [
                'actor_type' => 'system',
                'reason' => 'Reconciliation: gateway reported cancelled',
            ]);

            return true;
        });

        if (! $proceed) {
            return $this->skippedStatusChanged($settlement);
        }

        event(new SettlementCancelled($settlement, 'Reconciliation: gateway reported cancelled'));

        return ReconciliationResult::corrected(
            settlementId: $settlement->id,
            gatewayStatus: 'cancelled',
            localStatus: 'cancelled',
            actionTaken: 'transitioned_to_cancelled'
        );
    }

    private function skippedStatusChanged(CampaignSettlement $settlement): ReconciliationResult
    {
        return ReconciliationResult::skipped(
            settlementId: $settlement->id,
            localStatus: CampaignSettlement::find($settlement->id)?->status ?? $settlement->status,
            actionTaken: 'skipped_status_changed'
        );
    }

    private function settlementService(): SettlementService
    {
        return $this->settlementService ?? app(SettlementService::class);
    }

    private function getStuckSettlements(): \Illuminate\Database\Eloquent\Collection
    {
        return CampaignSettlement::where('status', 'processing')
            ->where('processed_at', '<=', now()->subMinutes($this->processingStuckMinutes))
            ->whereNotNull('gateway_reference')
            ->orderBy('processed_at')
            ->limit($this->batchSize)
            ->get();
    }

    private function isTerminal(CampaignSettlement $settlement): bool
    {
        return in_array($settlement->status, ['paid', 'rejected', 'cancelled'], true);
    }
}
