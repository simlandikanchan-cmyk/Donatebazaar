<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonationItem extends Model
{
    protected $fillable = [
        'donation_id',
        'product_id',
        'user_product_id',
        'quantity',
        'price',
    ];

    protected $casts = [
        'price'    => 'decimal:2',
        'quantity' => 'integer',
    ];

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    public function product()
    {
        return $this->belongsTo(CampaignProduct::class, 'product_id');
    }

    public function userProduct()
    {
        return $this->belongsTo(UserProduct::class, 'user_product_id');
    }
}