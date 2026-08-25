<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

    public const STATUS_PROCESSED = 'processed';

    public const STATUS_PENDING = 'pending';

    public const STATUS_FAILED = 'failed';

    /**
     * Gateway refund succeeded but the owner wallet reversal has not completed
     * (e.g. insufficient balance). Retry re-attempts the reversal without
     * calling the gateway again.
     */
    public const STATUS_REVERSAL_PENDING = 'reversal_pending';

    protected $fillable = [
        'donation_id',
        'donation_payment_id',
        'gateway_refund_id',
        'amount',
        'reason',
        'status',
        'processed_at',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    /**
     * Donation relation
     */
    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    /**
     * Check if refund processed
     */
    public function isProcessed()
    {
        return $this->status === self::STATUS_PROCESSED;
    }

    /**
     * Check if refund failed
     */
    public function isFailed()
    {
        return $this->status === self::STATUS_FAILED;
    }

    /**
     * Check if refund pending
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if refund is waiting on a wallet reversal retry
     */
    public function isReversalPending()
    {
        return $this->status === self::STATUS_REVERSAL_PENDING;
    }

    /**
     * Formatted refund amount
     */
    public function getFormattedAmountAttribute()
    {
        return '₹'.number_format($this->amount, 2);
    }
}
