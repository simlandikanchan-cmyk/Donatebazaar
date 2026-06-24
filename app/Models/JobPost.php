<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\JobPostApplication;

class JobPost extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'type',
        'department',
        'location',
        'is_remote',
        'salary',
        'experience_required',
        'skills',
        'vacancies',
        'featured',
        'meta_title',
        'meta_description',
        'application_deadline',
        'status',
        'published_at',
        'views_count',
        'applications_count',
    ];

    protected $casts = [
        'is_remote'             => 'boolean',
        'featured'              => 'boolean',
        'skills'                => 'array',
        'vacancies'             => 'integer',
        'views_count'           => 'integer',
        'applications_count'    => 'integer',
        'application_deadline'  => 'date',
        'published_at'          => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function applications()
    {
        return $this->hasMany(
            JobPostApplication::class,
            'job_id',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Active jobs only
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Published jobs
     */
    public function scopePublished($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('published_at')
              ->orWhere('published_at', '<=', now());
        });
    }

    /**
     * Featured jobs
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    /**
     * Open jobs
     * - Active
     * - Not expired
     */
    public function scopeOpen($query)
    {
        return $query
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('application_deadline')
                  ->orWhereDate('application_deadline', '>=', now());
            });
    }

    /**
     * Closed / Expired jobs
     */
    public function scopeClosed($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 'closed')
              ->orWhereDate('application_deadline', '<', now());
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Check if job is expired
     */
    public function getIsExpiredAttribute()
    {
        if (!$this->application_deadline) {
            return false;
        }

        return $this->application_deadline->isPast();
    }

    /**
     * Remaining vacancies
     */
    public function getApplicationsRemainingAttribute()
    {
        return max(
            0,
            ($this->vacancies ?? 0) - $this->applications_count
        );
    }
}