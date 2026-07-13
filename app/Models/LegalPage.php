<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LegalPage extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'content',
        'updated_by',
    ];

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public static function slugs(): array
    {
        return [
            'privacy' => 'Privacy Policy',
            'terms'   => 'Terms of Service',
            'refund'  => 'Refund & Cancellation Policy',
            'cookies' => 'Cookie Policy',
        ];
    }
}
