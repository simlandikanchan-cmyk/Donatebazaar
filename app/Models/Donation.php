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

        'original_amount',
        'discount_amount',
        'coupon_id',
        'coupon_code',

        'order_id',
        'payment_gateway',
        'payment_id',
        'signature',

        'payment_status',
        'is_refunded',
        'refunded_at',
        'released_at',
        'paid_at',
        'settlement_status',
        'campaign_settlement_id',

        'currency',

        'receipt_number',

        'is_anonymous',
        'message',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'original_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'refunded_at' => 'datetime',
        'released_at' => 'datetime',
        'is_refunded' => 'boolean',
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

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }
    public function items()
    {
        return $this->hasMany(DonationItem::class);
    }
}
