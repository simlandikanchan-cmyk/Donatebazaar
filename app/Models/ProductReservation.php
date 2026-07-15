<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReservation extends Model
{
    protected $fillable = [
        'product_id',
        'quantity',
        'session_id',
        'donation_id',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'quantity'   => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(CampaignProduct::class, 'product_id');
    }

    public function donation(): BelongsTo
    {
        return $this->belongsTo(Donation::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}
