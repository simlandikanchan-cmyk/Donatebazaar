<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Wallet extends Model
{
    protected $fillable = [
        'user_id',
        'owner_type',
        'owner_id',
        'balance',
        'reserved_balance',
        'pending_settlement_balance',
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
     * Withdrawable amount = balance minus any amount locked in a
     * pending settlement request.
     */
    public function getAvailableBalanceAttribute(): float
    {
        return (float) $this->balance - (float) $this->pending_settlement_balance;
    }
}
