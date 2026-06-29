<?php

namespace App\Models;

use App\Models\CampaignProduct;
use App\Models\CampaignUpdate;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Campaign extends Model
{
    use HasFactory, SoftDeletes;

    // -------------------------------------------------------------------------
    // SINGLE SOURCE OF TRUTH (STATE)
    // -------------------------------------------------------------------------

    const STATE_PENDING   = 'pending';
    const STATE_ACTIVE    = 'active';
    const STATE_PAUSED    = 'paused';
    const STATE_EXPIRED   = 'expired';
    const STATE_REJECTED  = 'rejected';
    const STATE_COMPLETED = 'completed';

    // -------------------------------------------------------------------------
    // FILLABLE
    // -------------------------------------------------------------------------

    protected $fillable = [

        'user_id',

        'category_id',

        'title',

        'slug',

        'description',

        'cover_image',

        'video_url',

        'goal_amount',

        'raised_amount',

        'location',

        'start_date',

        'end_date',

        'is_featured',

        'is_urgent',

        'campaign_state',

        'pause_reason',

        'paused_at',

        'rejection_reason',

        'required_level_id',

        'level_override_by',

        'level_override_at',

    ];


     protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'paused_at'  => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // RELATIONSHIPS
    // -------------------------------------------------------------------------

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function logs()
    {
        return $this->hasMany(CampaignLog::class);
    }

    public function products()
    {
        return $this->hasMany(
            CampaignProduct::class
        );
    }

    public function updates()
    {
        return $this->hasMany(
            CampaignUpdate::class
        );
    }

    public function celebrities(): BelongsToMany
    {
        return $this->belongsToMany(
            Celebrity::class,
            'celebrity_campaign'
        )

        ->withPivot([

            'role',

            'message',

            'is_active',

            'endorsed_at'

        ])

        ->withTimestamps();
    }

    public function requiredLevel()
    {
        return $this->belongsTo(
            FundraiserLevel::class,
            'required_level_id'
        );
    }

    public function levelOverrideBy()
    {
        return $this->belongsTo(
            User::class,
            'level_override_by'
        );
    }

    // -------------------------------------------------------------------------
    // SCOPES
    // -------------------------------------------------------------------------

    public function scopePending($q)
    {
        return $q->where(
            'campaign_state',
            self::STATE_PENDING
        );
    }

    public function scopeActive($q)
    {
        return $q->where(
            'campaign_state',
            self::STATE_ACTIVE
        );
    }

    public function scopePaused($q)
    {
        return $q->where(
            'campaign_state',
            self::STATE_PAUSED
        );
    }

    public function scopeExpired($q)
    {
        return $q->where(
            'campaign_state',
            self::STATE_EXPIRED
        );
    }

    public function scopeRejected($q)
    {
        return $q->where(
            'campaign_state',
            self::STATE_REJECTED
        );
    }

    public function scopeCompleted($q)
    {
        return $q->where(
            'campaign_state',
            self::STATE_COMPLETED
        );
    }

    // -------------------------------------------------------------------------
    // STATE CHECKS
    // -------------------------------------------------------------------------

    public function isPending(): bool
    {
        return $this->campaign_state === self::STATE_PENDING;
    }

    public function isActive(): bool
    {
        return $this->campaign_state === self::STATE_ACTIVE;
    }

    public function isPaused(): bool
    {
        return $this->campaign_state === self::STATE_PAUSED;
    }

    public function isExpired(): bool
    {
        return $this->campaign_state === self::STATE_EXPIRED;
    }

    public function isRejected(): bool
    {
        return $this->campaign_state === self::STATE_REJECTED;
    }

    public function isCompleted(): bool
    {
        return $this->campaign_state === self::STATE_COMPLETED;
    }

    public function ownerKycApproved(): bool
{
    return $this->user?->kycVerification?->isApproved() ?? false;
}

    // -------------------------------------------------------------------------
    // BUSINESS LOGIC
    // -------------------------------------------------------------------------

    public function approve(): void
    {
        $this->update([

            'campaign_state' =>
                self::STATE_ACTIVE,

        ]);

        $this->log(
            'approved',
            'Campaign approved and live'
        );
    }

    public function reject(string $reason): void
    {
        $this->update([

            'campaign_state' =>
                self::STATE_REJECTED,

            'rejection_reason' =>
                $reason,

        ]);

        $this->log(
            'rejected',
            $reason
        );
    }

    public function pause(string $reason): void
    {
        $this->update([

            'campaign_state' =>
                self::STATE_PAUSED,

            'pause_reason' =>
                $reason,

            'paused_at' =>
                now(),

        ]);

        $this->log(
            'paused',
            $reason
        );
    }

    public function resume(): void
    {
        if (! $this->ownerKycApproved()) {

            throw new \RuntimeException(
                'KYC not approved.'
            );
        }

        if ($this->isExpired()) {

            throw new \RuntimeException(
                'Cannot resume expired campaign.'
            );
        }

        $this->update([

            'campaign_state' =>
                self::STATE_ACTIVE,

            'pause_reason' =>
                null,

            'paused_at' =>
                null,

        ]);

        $this->log(
            'resumed',
            'Campaign resumed'
        );
    }

    // -------------------------------------------------------------------------
    // RESUBMIT
    // -------------------------------------------------------------------------

    public function resubmit(): void
    {
        $this->update([

            'campaign_state' =>
                self::STATE_PENDING,

            'rejection_reason' =>
                null,

            'pause_reason' =>
                null,

            'paused_at' =>
                null,

        ]);

        $this->log(
            'resubmitted',
            'Campaign resubmitted for admin review'
        );
    }

    // -------------------------------------------------------------------------
    // COMPLETE
    // -------------------------------------------------------------------------

    public function complete(): void
    {
        $this->update([

            'campaign_state' =>
                self::STATE_COMPLETED,

        ]);

        $this->log(
            'completed',
            'Campaign completed'
        );
    }

    // -------------------------------------------------------------------------
    // EXPIRE
    // -------------------------------------------------------------------------

    public function expire(): void
    {
        $this->update([

            'campaign_state' =>
                self::STATE_EXPIRED,

        ]);

        $this->log(
            'expired',
            'Campaign expired automatically'
        );
    }

    // -------------------------------------------------------------------------
    // ACCESSORS
    // -------------------------------------------------------------------------

    public function getProgressAttribute(): int
    {
        if ((float) $this->goal_amount <= 0) {
            return 0;
        }

        return (int) round(

            ($this->raised_amount / $this->goal_amount) * 100

        );
    }

    // -------------------------------------------------------------------------
    // RAISED AMOUNT BREAKDOWN
    // -------------------------------------------------------------------------

    public function moneyRaised(): float
    {
        return (float) $this->donations()
            ->where('payment_status', 'completed')
            ->where('donation_type', 'money')
            ->sum('total_amount');
    }

    public function productRaised(): float
    {
        return (float) $this->donations()
            ->where('payment_status', 'completed')
            ->where('donation_type', 'product')
            ->sum('total_amount');
    }

    // -------------------------------------------------------------------------
    // LOGGER
    // -------------------------------------------------------------------------

    private function log(
        string $action,
        string $message
    ): void {

        try {

            $this->logs()->create([

                'user_id' =>
                    auth()->id(),

                'action' =>
                    $action,

                'message' =>
                    $message,

            ]);

        } catch (\Throwable $e) {

            \Log::warning(

                "Campaign log failed: {$e->getMessage()}"

            );
        }
    }
}