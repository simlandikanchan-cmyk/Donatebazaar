<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Celebrity extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'avatar',
        'cover_image',
        'bio',
        'profession',
        'nationality',
        'date_of_birth',
        'instagram_url',
        'twitter_url',
        'facebook_url',
        'youtube_url',
        'website_url',
        'followers_count',
        'is_verified',
        'is_featured',
        'status',
        'user_id',
    ];

    protected $casts = [
        'is_verified'    => 'boolean',
        'is_featured'    => 'boolean',
        'date_of_birth'  => 'date',
        'followers_count'=> 'integer',
    ];

    // ─── Relationships ───────────────────────────────────────────────────────────

    /**
     * The platform User account linked to this celebrity (optional).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Campaigns this celebrity is associated with.
     * Access pivot fields: $celebrity->campaigns->first()->pivot->role
     */
    public function campaigns(): BelongsToMany
    {
        return $this->belongsToMany(Campaign::class, 'celebrity_campaign')
                    ->withPivot(['role', 'message', 'is_active', 'endorsed_at'])
                    ->withTimestamps();
    }

    /**
     * Only active campaign associations.
     */
    public function activeCampaigns(): BelongsToMany
    {
        return $this->campaigns()->wherePivot('is_active', true);
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────────

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}