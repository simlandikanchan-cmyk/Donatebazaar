<?php

namespace App\Models;

use App\Support\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Immutable financial event journal entry.
 *
 * The financial ledger is the reconciliation source of truth. Each row
 * represents a completed financial event (donation captured, gateway fee
 * captured, refund processed, payout completed). Application code must treat
 * rows as write-once: never update or delete them.
 */
class FinancialLedger extends Model
{
    public const EVENT_DONATION_CAPTURED = 'donation_captured';

    public const EVENT_GATEWAY_FEE_CAPTURED = 'gateway_fee_captured';

    public const EVENT_REFUND_PROCESSED = 'refund_processed';

    public const EVENT_PAYOUT_COMPLETED = 'payout_completed';

    protected $table = 'financial_ledger';

    protected $fillable = [
        'event',
        'amount',
        'currency',
        'reference_type',
        'reference_id',
        'gateway_reference',
        'idempotency_key',
        'balance_before',
        'balance_after',
        'metadata',
        'occurred_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'metadata' => 'array',
        'occurred_at' => 'datetime',
    ];

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    public function amountMoney(): Money
    {
        return Money::of($this->amount);
    }
}
