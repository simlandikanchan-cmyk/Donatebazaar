<?php

namespace App\Models;

use Carbon\Carbon;
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
        'last_billed_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    // ── Shared billing-date calculator (store() + resume() both use this) ──
    public static function calculateNextBilling(string $frequency): Carbon
    {
        return match ($frequency) {
            'daily' => now()->addDay(),
            'weekly' => now()->addWeek(),
            'monthly' => now()->addMonth(),
            'quarterly' => now()->addMonths(3),
            default => now()->addMonth(), // fallback safety net
        };
    }

    // ── Helpers ──
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    // Display line for the dashboard card (status-aware, never shows a stale billing date)
    public function statusLine(): string
    {
        return match ($this->status) {
            'paused' => 'Paused · Next payment not scheduled',
            'active' => $this->next_billing_date
                ? 'Next: '.$this->next_billing_date->format('d M Y')
                : 'Next payment not scheduled',
            'cancelled' => 'Cancelled',
            'completed' => 'Completed',
            'failed' => 'Failed',
            default => ucfirst($this->status),
        };
    }

    public function cancel(): void
    {
        if ($this->status === 'cancelled') {
            return;
        }

        $this->update(['status' => 'cancelled']);
    }

    public function pause(): void
    {
        if ($this->status !== 'active') {
            return;
        }

        $this->update(['status' => 'paused']);
    }

    public function resume(): void
    {
        if ($this->status !== 'paused') {
            return;
        }

        $this->update([
            'status' => 'active',
            'next_billing_date' => self::calculateNextBilling($this->frequency),
        ]);
    }
}
