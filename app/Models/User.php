<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Models\KycVerification;
use App\Models\Campaign;
use App\Models\Volunteer;
use App\Models\Donation;
use App\Models\UserFundraiserLevel;
use App\Models\FundraiserLevel;
use App\Models\EventRegistration;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // -------------------------------------------------------------------------
    // Fillable / Hidden / Casts
    // -------------------------------------------------------------------------

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'cover_image',
        'bio',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
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
     * Event registrations made by this user.
     */
    public function eventRegistrations()
    {
        return $this->hasMany(EventRegistration::class);
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
        return optional($this->kycVerification)->status === 'approved';
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
    public function assignedLevel(): \Illuminate\Database\Eloquent\Relations\HasOneThrough
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
}