<?php

namespace  App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPostApplication extends Model
{
    /**
     * Table name.
     */
    protected $table = 'job_applications';

    /**
     * Status constants.
     */
    const STATUS_PENDING = 'pending';
    const STATUS_SHORTLISTED = 'shortlisted';
    const STATUS_INTERVIEWED = 'interviewed';
    const STATUS_HIRED = 'hired';
    const STATUS_REJECTED = 'rejected';

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'job_id',
        'name',
        'email',
        'phone',
        'cover_letter',
        'cv_path',
        'status',
        'admin_notes',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Default values.
     */
    protected $attributes = [
        'status' => self::STATUS_PENDING,
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class, 'job_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeSearch($query, ?string $term)
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', '%' . $term . '%')
              ->orWhere('email', 'like', '%' . $term . '%')
              ->orWhere('phone', 'like', '%' . $term . '%');
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getCvExtensionAttribute(): ?string
    {
        if (!$this->cv_path) {
            return null;
        }

        return pathinfo($this->cv_path, PATHINFO_EXTENSION);
    }

    public function getInitialsAttribute(): string
    {
        return collect(explode(' ', $this->name))
            ->filter()
            ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
            ->take(2)
            ->implode('');
    }

    public function getIsShortlistedAttribute(): bool
    {
        return $this->status === self::STATUS_SHORTLISTED;
    }

    public function getIsHiredAttribute(): bool
    {
        return $this->status === self::STATUS_HIRED;
    }

    public function getIsRejectedAttribute(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING => 'Pending Review',
            self::STATUS_SHORTLISTED => 'Shortlisted',
            self::STATUS_INTERVIEWED => 'Interviewed',
            self::STATUS_HIRED => 'Hired',
            self::STATUS_REJECTED => 'Rejected',
        ];
    }
}