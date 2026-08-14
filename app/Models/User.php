<?php

namespace App\Models;

use App\Traits\HasNotificationPreferences;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, HasNotificationPreferences, \Illuminate\Auth\MustVerifyEmail, Notifiable;

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

    protected $guarded = [
        'role',
        'otp_hash',
        'otp_expires_at',
        'otp_attempts',
        'phone_verified_at',
        'is_active',
        'status',
        'fundraiser_level_id',
        'fundraiser_level_status',
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

    public function hasApprovedKyc(): bool
    {
        return KycVerification::where('user_id', $this->id)
            ->where('status', KycVerification::STATUS_APPROVED)
            ->exists();
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
     * Maximum campaign goal amount allowed.
     */
    public function maxCampaignGoal(): float
    {
        return (float) ($this->assignedLevel?->max_goal_amount ?? 25000.00);
    }

    /**
     * Human readable fundraiser level.
     */
    public function fundraiserLevelName(): string
    {
        return $this->assignedLevel?->level_name ?? 'Starter';
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
