<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wallet extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'owner_type',
        'owner_id',
        'currency',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'reserved_balance' => 'decimal:2',
        'pending_settlement_balance' => 'decimal:2',
    ];

    /**
     * Polymorphic owner (Organization or User).
     */
    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Legacy single-owner relation kept for backward compatibility.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    /**
     * Withdrawable amount. Settlement funds are already moved out of
     * `balance` into `pending_settlement_balance` when a payout is
     * requested, so the available amount is simply the balance.
     */
    public function getAvailableBalanceAttribute(): float
    {
        return (float) $this->balance;
    }
}
