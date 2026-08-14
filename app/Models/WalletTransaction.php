<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTransaction extends Model
{
    protected $fillable = [
        'wallet_id',
        'type',
        'amount',
        'source',
        'reference_type',
        'reference_id',
        'notes',
        'balance_after',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    public const SOURCE_DONATION = 'donation';

    public const SOURCE_REFUND = 'refund';

    public const SOURCE_SETTLEMENT = 'settlement';

    public const SOURCE_SETTLEMENT_REVERSAL = 'settlement_reversal';

    public const SOURCE_GIFT_CARD = 'gift_card';

    public const SOURCE_COUPON = 'coupon';

    public const SOURCE_ADJUSTMENT = 'adjustment';

    public const STATUS_PENDING = 'pending';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_FAILED = 'failed';

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }
}
