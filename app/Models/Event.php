<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Campaign;
use App\Models\EventRegistration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Status Constants
    |--------------------------------------------------------------------------
    */

    public const STATUS_PENDING   = 'pending';
    public const STATUS_ACTIVE    = 'active';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_EXPIRED   = 'expired';

    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'campaign_id',
        'user_id',
        'title',
        'cover_image',
        'slug',
        'description',
        'location',
        'event_date',
        'start_time',
        'end_time',
        'goal_amount',
        'raised_amount',
        'max_participants',
        'registered_count',
        'status',

        'allow_registrations',
        'show_on_campaign',
        'send_notification',
    ];

    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'event_date'       => 'date',
        'goal_amount'      => 'decimal:2',
        'raised_amount'    => 'decimal:2',
        'max_participants' => 'integer',
        'registered_count' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Default Attributes
    |--------------------------------------------------------------------------
    */

    protected $attributes = [
        'raised_amount'    => 0,
        'registered_count' => 0,
        'status'           => self::STATUS_PENDING,
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Event registrations
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED;
    }

    /*
    |--------------------------------------------------------------------------
    | Business Logic
    |--------------------------------------------------------------------------
    */

    public function hasEnded(): bool
    {
        return $this->event_date &&
            Carbon::parse($this->event_date)->isPast();
    }

    public function isFull(): bool
    {
        if (!$this->max_participants) {
            return false;
        }

        return $this->registered_count >=
            $this->max_participants;
    }

    public function remainingSpots(): int
    {
        if (!$this->max_participants) {
            return 0;
        }

        return max(
            0,
            $this->max_participants -
            $this->registered_count
        );
    }

    /**
     * Check if user already registered
     */
    public function isUserRegistered($userId): bool
    {
        return $this->registrations()
            ->where('user_id', $userId)
            ->where('status', 'registered')
            ->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where(
            'status',
            self::STATUS_ACTIVE
        );
    }

    public function scopePending($query)
    {
        return $query->where(
            'status',
            self::STATUS_PENDING
        );
    }

    public function scopeExpired($query)
    {
        return $query->where(
            'status',
            self::STATUS_EXPIRED
        );
    }
}