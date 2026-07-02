<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [

        'campaign_id',
        'user_id',
        'donor_name',
        'donor_email',
        'donor_phone',

        'donation_type',

        'total_amount',
        'platform_fee',
        'net_amount',

        'order_id',
        'payment_gateway',

        'currency',

        'receipt_number',

        'is_anonymous',
        'message',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'net_amount'   => 'decimal:2',
        'paid_at'      => 'datetime',
        'refunded_at'  => 'datetime',
        'is_refunded'  => 'boolean',
        'is_anonymous' => 'boolean',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}