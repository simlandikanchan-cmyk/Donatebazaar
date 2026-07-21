<?php

namespace App\Services\Settlement;

use App\Exceptions\InvalidSettlementTransitionException;
use App\Models\CampaignSettlement;
use App\Models\SettlementStateLog;
use Illuminate\Support\Facades\DB;

/**
 * Single source of truth for all settlement state changes.
 *
 * No other service may update campaign_settlements.status directly.
 * Every transition passes through transition() which:
 *   1. validates the edge exists (canTransition)
 *   2. writes the new status inside a transaction
 *   3. appends a settlement_state_logs row (audit trail)
 *
 * Gateway status (campaign_settlements.gateway_status) is intentionally
 * NOT managed here — it is owned by the Payout Service.
 */
final class SettlementStateMachine
{
    /**
     * Valid transitions: from => [allowed to states].
     * Mirrors the frozen Phase -1 / v3 contract exactly.
     */
    private const TRANSITIONS = [
        'requested' => ['risk_evaluation', 'cancelled'],
        'risk_evaluation' => ['auto_approved', 'manual_review', 'rejected'],
        'auto_approved' => ['processing'],
        'manual_review' => ['approved', 'rejected', 'cancelled'],
        'approved' => ['processing'],
        'processing' => ['paid', 'failed', 'cancelled', 'retry_pending'],
        'failed' => ['retry_pending', 'rejected'],
        'retry_pending' => ['processing'],
        'paid' => [],
        'rejected' => [],
        'cancelled' => [],
    ];

    /** Terminal states — no outgoing edges. */
    private const TERMINAL = ['paid', 'rejected', 'cancelled'];

    public function __construct(
        private readonly ?string $correlationId = null,
        private readonly ?string $traceId = null
    ) {}

    /**
     * Whether a transition from -> to is allowed by the state graph.
     */
    public function canTransition(string $from, string $to): bool
    {
        $from = $this->normalize($from);
        $to = $this->normalize($to);

        return isset(self::TRANSITIONS[$from])
            && in_array($to, self::TRANSITIONS[$from], true);
    }

    /**
     * Throws if the transition is invalid. Used as a guard before side effects.
     */
    public function validate(string $from, string $to): void
    {
        if (! $this->canTransition($from, $to)) {
            throw new InvalidSettlementTransitionException($from, $to);
        }
    }

    public function isTerminal(string $state): bool
    {
        return in_array($this->normalize($state), self::TERMINAL, true);
    }

    /**
     * Apply a transition to a settlement.
     *
     * Centralized, transactional, audited. Returns the settlement.
     *
     * @param  array  $options  actor_type, actor_id, reason
     *
     * @throws InvalidSettlementTransitionException
     */
    public function transition(
        CampaignSettlement $settlement,
        string $to,
        array $options = []
    ): CampaignSettlement {
        $from = $settlement->status;
        $to = $this->normalize($to);

        $this->validate($from, $to);

        $actorType = $options['actor_type'] ?? 'system';
        $actorId = $options['actor_id'] ?? null;
        $reason = $options['reason'] ?? null;

        DB::transaction(function () use ($settlement, $from, $to, $actorType, $actorId, $reason) {
            $settlement->status = $to;
            $settlement->save();

            SettlementStateLog::create([
                'settlement_id' => $settlement->id,
                'from_state' => $from,
                'to_state' => $to,
                'actor_type' => $actorType,
                'actor_id' => $actorId,
                'correlation_id' => $this->correlationId ?? $settlement->correlation_id,
                'trace_id' => $this->traceId ?? $settlement->trace_id,
                'reason' => $reason,
                'created_at' => now(),
            ]);
        });

        return $settlement;
    }

    /**
     * Pure helper: all valid "to" states for a given state.
     */
    public function allowedNextStates(string $from): array
    {
        return self::TRANSITIONS[$this->normalize($from)] ?? [];
    }

    private function normalize(string $state): string
    {
        return strtolower(trim($state));
    }
}
