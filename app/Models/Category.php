<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'color',
        'is_active'
    ];

    // RELATION
    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    // ACTIVE SCOPE
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // CLEAN NAME FORMAT
    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }
}