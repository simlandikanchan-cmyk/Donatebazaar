<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampaignProduct extends Model
{
    use SoftDeletes;

    protected $fillable = [

        'campaign_id',

        'category_product_id',

        'user_id',

        'name',

        'description',

        'price',

        'quantity',

        'remaining_quantity',

        'image',

        'source',

        'approval_status',

        'approved_by',

        'approved_at',

        'is_active',

    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function categoryProduct()
    {
        return $this->belongsTo(
            CategoryProduct::class
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }
}