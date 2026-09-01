<?php

namespace App\Models;

use App\Traits\HasNotificationPreferences;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, HasNotificationPreferences, \Illuminate\Auth\MustVerifyEmail, Notifiable, HasRoles;

    // -------------------------------------------------------------------------
    // Fillable / Hidden / Casts
    // -------------------------------------------------------------------------

    protected $fillable = [
        'google_id',
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'cover_image',
        'bio',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'otp_expires_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships — Core
    // -------------------------------------------------------------------------

    public function volunteer()
    {
        return $this->hasOne(Volunteer::class);
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * Coupons assigned to this user (user-specific, single-use).
     */
    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    /**
     * Coupon redemptions made by this user.
     */
    public function couponRedemptions()
    {
        return $this->hasMany(CouponRedemption::class);
    }

    /**
     * Event registrations made by this user.
     */
    public function eventRegistrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    // -------------------------------------------------------------------------
    // Relationships — Followed Campaigns
    // -------------------------------------------------------------------------

    public function followedCampaigns(): MorphToMany
    {
        return $this->morphedByMany(
            Campaign::class,
            'following',
            'followers',
            'follower_id',
            'following_id'
        )->withTimestamps();
    }

    // -------------------------------------------------------------------------
    // Relationships — KYC
    // -------------------------------------------------------------------------

    public function kycVerification()
    {
        return $this->hasOne(KycVerification::class);
    }

    /**
     * Canonical per-user KYC rule: a user is KYC-approved iff they have at
     * least one KycVerification row with status = approved for this user.
     *
     * This is intentionally per-user (NOT scoped to any single campaign) so
     * that every publication path (admin approve/publish + user publish/resume)
     * agrees on the same rule.
     */
    public function isKycApproved(): bool
    {
        return KycVerification::where('user_id', $this->id)
            ->where('status', KycVerification::STATUS_APPROVED)
            ->exists();
    }

    /**
     * Alias kept for readability / existing call sites.
     */
    public function hasApprovedKyc(): bool
    {
        return $this->isKycApproved();
    }

    // -------------------------------------------------------------------------
    // Relationships — Fundraiser Level System
    // -------------------------------------------------------------------------

    /**
     * The user_fundraiser_levels pivot row for this user.
     * Returns a UserFundraiserLevel model (NOT a FundraiserLevel).
     */
    public function fundraiserLevel()
    {
        return $this->hasOne(UserFundraiserLevel::class);
    }

    /**
     * The actual FundraiserLevel model for this user.
     */
    public function assignedLevel(): HasOneThrough
    {
        return $this->hasOneThrough(
            FundraiserLevel::class,
            UserFundraiserLevel::class,
            'user_id',
            'id',
            'id',
            'current_level_id'
        );
    }

    /**
     * Accessor: $user->current_fundraiser_level
     */
    public function getCurrentFundraiserLevelAttribute(): ?FundraiserLevel
    {
        return $this->assignedLevel;
    }

    /**
     * The site's configured default fundraiser level, if any.
     */
    public static function defaultLevel(): ?FundraiserLevel
    {
        return FundraiserLevel::where('is_default', true)->first();
    }

    /**
     * Ensure this user has exactly one user_fundraiser_levels row pointing at
     * the site's default level. Idempotent: does nothing if a row already
     * exists (user_id is UNIQUE on that table) or if no default level exists.
     */
    public function ensureDefaultLevel(): void
    {
        if ($this->fundraiserLevel()->exists()) {
            return;
        }

        $default = static::defaultLevel();
        if (! $default) {
            return;
        }

        $this->fundraiserLevel()->create([
            'current_level_id' => $default->id,
            'status' => 'active',
            'level_upgraded_at' => now(),
        ]);
    }

    /**
     * Maximum campaign goal amount allowed.
     *
     * Uses the user's assigned level, falling back to the site's default level.
     * Returns 0.0 when no level exists so callers do not silently grant a
     * fabricated limit.
     */
    public function maxCampaignGoal(): float
    {
        $level = $this->assignedLevel ?? static::defaultLevel();

        return (float) ($level?->max_goal_amount ?? 0.00);
    }

    /**
     * Human readable fundraiser level.
     */
    public function fundraiserLevelName(): string
    {
        $level = $this->assignedLevel ?? static::defaultLevel();

        return $level?->level_name ?? 'Unassigned';
    }

    public function isAccountActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if fundraiser account is suspended.
     */
    public function isFundraiserSuspended(): bool
    {
        return $this->fundraiserLevel?->status === 'suspended';
    }

    /**
     * Check if upgrade request is pending.
     */
    public function hasPendingLevelUpgrade(): bool
    {
        return $this->fundraiserLevel?->status === 'upgrade_pending';
    }

    /**
     * Wallet ledger for this user (one wallet per owner).
     */
    public function wallet()
    {
        return $this->morphOne(Wallet::class, 'owner');
    }
}
