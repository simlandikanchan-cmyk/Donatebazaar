<?php

namespace App\Models;

use App\Services\ProductReservationService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampaignProduct extends Model
{
    use SoftDeletes;

    protected $casts = [
        'approved_at' => 'datetime',
        'price'       => 'decimal:2',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

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

    /*
    |--------------------------------------------------------------------------
    | Reservations — stock currently held by donors mid-checkout
    |--------------------------------------------------------------------------
    */

    public function reservations(): HasMany
    {
        return $this->hasMany(ProductReservation::class, 'product_id');
    }

    /**
     * Stock available to a new donor = remaining minus non-expired reservations.
     */
    public function getAvailableQuantityAttribute(): int
    {
        return app(ProductReservationService::class)->availableQuantity($this);
    }
}