<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use HTMLPurifier;
use HTMLPurifier_Config;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    // ── Status constants ──────────────────────────────────────────────────────

    const STATUS_DRAFT = 'draft';

    const STATUS_PENDING = 'pending';

    const STATUS_APPROVED = 'approved';

    const STATUS_REJECTED = 'rejected';

    const STATUS_PUBLISHED = 'published';

    const STATUS_ARCHIVED = 'archived';

    const STATUS_FLAGGED = 'flagged';

    // ── Fillable ──────────────────────────────────────────────────────────────

    protected $fillable = [
        'author_id', 'author_role', 'title', 'slug', 'excerpt', 'content',
        'cover_image', 'read_time_minutes', 'category_id', 'status',
        'reviewed_by', 'reviewed_at', 'rejection_reason',
        'is_featured', 'carousel_order', 'featured_at',
        'views_count', 'likes_count', 'comments_count', 'shares_count', 'reports_count',
        'meta_title', 'meta_description', 'published_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'reviewed_at' => 'datetime',
        'featured_at' => 'datetime',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // ── Slug auto-generation ──────────────────────────────────────────────────

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Blog $blog) {
            if (empty($blog->slug)) {
                $blog->slug = static::generateUniqueSlug($blog->title);
            }
            if (empty($blog->author_role) && $blog->author_id) {
                $blog->author_role = $blog->author->role ?? 'donor';
            }
            if ($blog->read_time_minutes === null) {
                $blog->read_time_minutes = static::estimateReadTime($blog->content);
            }
        });

        static::updating(function (Blog $blog) {
            if ($blog->isDirty('title') && ! $blog->isDirty('slug')) {
                $blog->slug = static::generateUniqueSlug($blog->title, $blog->id);
            }
            if ($blog->isDirty('content')) {
                $blog->content = static::sanitizeHtml($blog->content);
                $blog->read_time_minutes = static::estimateReadTime($blog->content);
            }
        });
    }

    public function setContentAttribute($value)
    {
        $this->attributes['content'] = static::sanitizeHtml($value);
    }

    public static function sanitizeHtml(string $html): string
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.AllowedElements', [
            'a','abbr','acronym','b','blockquote','br','caption','code','del','dd','dl','dt','div','em',
            'h2','h3','h4','h5','h6','i','img','ins','li','ol','p','pre','q','s','span','strong',
            'sub','sup','table','tbody','td','tfoot','th','thead','tr','u','ul',
        ]);
        $config->set('HTML.AllowedAttributes', [
            'a.href','a.title','a.target','abbr.title','acronym.title','blockquote.cite',
            'img.alt','img.src','img.width','img.height','img.style','span.style','p.style',
            'div.style','table.style','td.style','th.style',
        ]);
        $config->set('Attr.AllowedFrameTargets', ['_blank']);
        $config->set('URI.SafeIframeRegexp', '%^(https?:)?//%');
        $config->set('HTML.SafeIframe', true);
        $config->set('HTML.SafeEmbed', true);
        $config->set('CSS.AllowedProperties', [
            'color','background-color','font-weight','font-style','text-decoration',
            'text-align','margin','margin-top','margin-right','margin-bottom','margin-left',
            'padding','padding-top','padding-right','padding-bottom','padding-left',
            'width','height','border','border-radius','display','flex','grid',
            'gap','list-style-type','text-transform','letter-spacing','line-height',
            'font-size','font-family',
        ]);
        $config->set('HTML.Nofollow', true);
        $config->set('HTML.TargetBlank', true);

        $purifier = new HTMLPurifier($config);

        return $purifier->purify($html);
    }

    public static function generateUniqueSlug(string $title, ?int $exceptId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;

        while (
            static::where('slug', $slug)
                ->when($exceptId, fn ($q) => $q->where('id', '!=', $exceptId))
                ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    public static function estimateReadTime(string $content): int
    {
        $wordCount = str_word_count(strip_tags($content));

        return max(1, (int) ceil($wordCount / 200)); // avg 200 wpm
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'blog_tag')->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(BlogComment::class, 'blog_id', 'id')
            ->whereNull('parent_id')
            ->latest();
    }

    public function allComments()
    {
        return $this->hasMany(BlogComment::class);
    }

    public function likes()
    {
        return $this->hasMany(BlogLike::class);
    }

    public function views()
    {
        return $this->hasMany(BlogView::class);
    }

    public function reports()
    {
        return $this->hasMany(BlogReport::class);
    }

    public function statusLogs()
    {
        return $this->hasMany(BlogStatusLog::class)->latest();
    }

    // ── Query Scopes ──────────────────────────────────────────────────────────

    /** Publicly visible blogs */
    public function scopePublic(Builder $q): Builder
    {
        return $q->whereIn('status', [self::STATUS_APPROVED, self::STATUS_PUBLISHED]);
    }

    /** Alias for public */
    public function scopeVisible(Builder $q): Builder
    {
        return $q->public();
    }

    public function scopeDraft(Builder $q): Builder
    {
        return $q->where('status', self::STATUS_DRAFT);
    }

    public function scopePending(Builder $q): Builder
    {
        return $q->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved(Builder $q): Builder
    {
        return $q->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected(Builder $q): Builder
    {
        return $q->where('status', self::STATUS_REJECTED);
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('status', self::STATUS_PUBLISHED);
    }

    public function scopeArchived(Builder $q): Builder
    {
        return $q->where('status', self::STATUS_ARCHIVED);
    }

    public function scopeFlagged(Builder $q): Builder
    {
        return $q->where('status', self::STATUS_FLAGGED);
    }

    public function scopeFeatured(Builder $q): Builder
    {
        return $q->where('is_featured', true)->orderBy('carousel_order');
    }

    public function scopeByAuthor(Builder $q, int $userId): Builder
    {
        return $q->where('author_id', $userId);
    }

    public function scopeByCategory(Builder $q, int|string $category): Builder
    {
        if (is_numeric($category)) {
            return $q->where('category_id', $category);
        }

        return $q->whereHas('category', fn ($c) => $c->where('slug', $category));
    }

    public function scopeByTag(Builder $q, int|string $tag): Builder
    {
        return $q->whereHas('tags', function ($t) use ($tag) {
            is_numeric($tag) ? $t->where('tags.id', $tag) : $t->where('tags.slug', $tag);
        });
    }

    public function scopeSearch(Builder $q, string $term): Builder
    {
        return $q->whereFullText(['title', 'excerpt', 'content'], $term)
            ->orWhere('title', 'like', "%{$term}%");
    }

    public function scopeRecent(Builder $q): Builder
    {
        return $q->orderByDesc('published_at');
    }

    public function scopePopular(Builder $q): Builder
    {
        return $q->orderByDesc('views_count');
    }

    public function scopeTrending(Builder $q): Builder
    {
        // score = views + (likes * 3) + (comments * 2)
        return $q->orderByRaw('(views_count + (likes_count * 3) + (comments_count * 2)) DESC');
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PENDING => 'Under Review',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_PUBLISHED => 'Published',
            self::STATUS_ARCHIVED => 'Archived',
            self::STATUS_FLAGGED => 'Flagged',
            default => ucfirst($this->status),
        };
    }

    // ── Accessors ─────────────────────────────────────────────────────────────

    public function getCoverImageUrlAttribute(): string
    {
        return $this->cover_image
            ? asset('storage/'.$this->cover_image)
            : asset('images/blog-placeholder.jpg');
    }

    public function getExcerptOrAutoAttribute(): string
    {
        return $this->excerpt ?: Str::limit(strip_tags($this->content), 160);
    }

    public function getReadableStatusAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PENDING => 'Under Review',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_PUBLISHED => 'Published',
            self::STATUS_ARCHIVED => 'Archived',
            self::STATUS_FLAGGED => 'Flagged',
            default => ucfirst($this->status),
        };
    }

    public function getIsPubliclyVisibleAttribute(): bool
    {
        return in_array($this->status, [self::STATUS_APPROVED, self::STATUS_PUBLISHED]);
    }

    public function getIsEditableAttribute(): bool
    {
        return in_array(
            $this->status,
            [
                self::STATUS_DRAFT,
                self::STATUS_REJECTED,
                self::STATUS_PENDING,
            ]
        );
    }

    // ── Business Logic ────────────────────────────────────────────────────────

    /**
     * Transition status with automatic audit log.
     */
    public function transitionTo(string $newStatus, ?int $byUserId = null, ?string $note = null): bool
    {
        $old = $this->status;

        $this->status = $newStatus;

        if ($newStatus === self::STATUS_APPROVED || $newStatus === self::STATUS_PUBLISHED) {
            $this->reviewed_by = $byUserId;
            $this->reviewed_at = now();
            $this->published_at = $this->published_at ?? now();
        }

        if ($newStatus === self::STATUS_REJECTED) {
            $this->reviewed_by = $byUserId;
            $this->reviewed_at = now();
            $this->rejection_reason = $note;
        }

        $saved = $this->save();

        if ($saved) {
            $this->statusLogs()->create([
                'changed_by' => $byUserId,
                'from_status' => $old,
                'to_status' => $newStatus,
                'note' => $note,
            ]);
        }

        return $saved;
    }

    /**
     * Record a view (deduplicated per user/IP per day).
     */
    public function recordView(?int $userId, string $ip): void
    {
        $date = today()->toDateString();

        if ($userId) {
            $exists = $this->views()
                ->where('user_id', $userId)
                ->where('viewed_date', $date)
                ->exists();
        } else {
            $exists = $this->views()
                ->whereNull('user_id')
                ->where('ip_address', $ip)
                ->where('viewed_date', $date)
                ->exists();
        }

        if (! $exists) {
            $this->views()->create([
                'user_id' => $userId,
                'ip_address' => $userId ? null : $ip,
                'viewed_date' => $date,
            ]);
            $this->increment('views_count');
        }
    }

    /**
     * Toggle like for authenticated user. Returns true if liked, false if unliked.
     */
    public function toggleLike(int $userId): bool
    {
        $like = $this->likes()->where('user_id', $userId)->first();

        if ($like) {
            $like->delete();
            $this->decrement('likes_count');

            return false;
        }

        $this->likes()->create(['user_id' => $userId]);
        $this->increment('likes_count');

        return true;
    }

    public function isLikedBy(int $userId): bool
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }
}
