<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
        'campaign_id',
        'discount_type',
        'discount_value',
        'min_amount',
        'max_discount',
        'usage_limit',
        'used_count',
        'expires_at',
        'is_active',
        'redeemed_at',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_amount' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'expires_at' => 'datetime',
        'redeemed_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function redemptions(): HasMany
    {
        return $this->hasMany(CouponRedemption::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /*
    |--------------------------------------------------------------------------
    | Discount math
    |
    | Always called server-side from the validated amount. Never trust a
    | client-supplied discount value.
    |--------------------------------------------------------------------------
    */

    public function computeDiscount(float $amount): float
    {
        if ($this->discount_type === 'percent') {
            $discount = ($amount * (float) $this->discount_value) / 100;

            if ($this->max_discount !== null) {
                $discount = min($discount, (float) $this->max_discount);
            }
        } else {
            $discount = (float) $this->discount_value;
        }

        // Never discount more than (amount - 1) so at least ₹1 is always paid.
        $discount = min($discount, $amount - 1);
        $discount = max($discount, 0);

        return round($discount, 2);
    }

    /*
    |--------------------------------------------------------------------------
    | Eligibility check
    |
    | Returns [valid: bool, message: string].
    |--------------------------------------------------------------------------
    */

    public function isValidFor(?User $user, ?Campaign $campaign, float $amount): array
    {
        if (! $this->is_active) {
            return [false, 'This coupon is not active.'];
        }

        if ($this->expires_at && Carbon::parse($this->expires_at)->endOfDay()->isPast()) {
            return [false, 'This coupon has expired.'];
        }

        if ($this->user_id && $user && (int) $this->user_id !== (int) $user->id) {
            return [false, 'This coupon is not assigned to your account.'];
        }

        if ($this->campaign_id && $campaign && (int) $this->campaign_id !== (int) $campaign->id) {
            return [false, 'This coupon is not valid for this campaign.'];
        }

        if ($this->min_amount !== null && $amount < (float) $this->min_amount) {
            return [
                false,
                'Minimum donation of ₹'.number_format((float) $this->min_amount, 2).' required for this coupon.',
            ];
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return [false, 'This coupon has reached its usage limit.'];
        }

        // Single-use assigned coupon already redeemed
        if ($this->user_id && $this->redeemed_at) {
            return [false, 'This coupon has already been used.'];
        }

        // Per-user single use (public codes)
        if ($user && $this->redemptions()->where('user_id', $user->id)->exists()) {
            return [false, 'You have already used this coupon.'];
        }

        return [true, 'Coupon applied.'];
    }
}
