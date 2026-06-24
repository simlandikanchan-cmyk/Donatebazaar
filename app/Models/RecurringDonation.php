<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecurringDonation extends Model
{
    protected $fillable = [
        'user_id',
        'campaign_id',
        'amount',
        'frequency',
        'status',
        'razorpay_subscription_id',
        'razorpay_plan_id',
        'next_billing_date',
        'last_billed_at',
        'billing_count',
    ];

    protected $casts = [
        'next_billing_date' => 'datetime',
        'last_billed_at'    => 'datetime',
        'amount'            => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    // ── Helpers ──
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    public function pause(): void
    {
        $this->update(['status' => 'paused']);
    }

    public function resume(): void
    {
        $this->update([
            'status'            => 'active',
            'next_billing_date' => now()->addDays($this->frequency === 'weekly' ? 7 : 30),
        ]);
    }
}