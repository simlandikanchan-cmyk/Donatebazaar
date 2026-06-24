<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class CategoryProduct extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'stock',
        'image',
        'product_type',
        'is_active',
    ];

    protected $appends = ['image_url'];

    /**
     * Normalise whatever path is stored in the DB and
     * return a fully-qualified public URL.
     *
     * Handles all common storage patterns:
     *   "products/abc.jpg"
     *   "storage/products/abc.jpg"
     *   "public/products/abc.jpg"
     *   "http://..." (already a full URL — returned as-is)
     */
    public function getImageUrlAttribute(): ?string
    {
        $path = $this->image;

        if (empty($path)) {
            return null;
        }

        // Already a full URL — return as-is
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // Strip leading "storage/" or "public/" if mistakenly stored that way
        $path = ltrim($path, '/');
        $path = preg_replace('#^storage/#', '', $path);
        $path = preg_replace('#^public/#',  '', $path);

        return asset('storage/' . $path);
    }

    /**
     * CATEGORY RELATION
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}