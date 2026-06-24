<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CampaignSettlement extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'organization_id',
        'gross_amount',
        'platform_fee',
        'net_amount',
        'status',
        'transfer_reference',
        'paid_at',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * Campaign relation
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Organization relation
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Settlement items
     */
    public function settlementItems()
    {
        return $this->hasMany(SettlementItem::class);
    }

    /**
     * Donations through settlement items
     */
    public function donations()
    {
        return $this->belongsToMany(
            Donation::class,
            'settlement_items'
        )->withPivot('amount')
         ->withTimestamps();
    }

    /**
     * Check if settlement is paid
     */
    public function isPaid()
    {
        return $this->status === 'paid';
    }

    /**
     * Check if settlement is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if settlement failed
     */
    public function isFailed()
    {
        return $this->status === 'failed';
    }

    /**
     * Formatted gross amount
     */
    public function getFormattedGrossAmountAttribute()
    {
        return '₹' . number_format($this->gross_amount, 2);
    }

    /**
     * Formatted platform fee
     */
    public function getFormattedPlatformFeeAttribute()
    {
        return '₹' . number_format($this->platform_fee, 2);
    }

    /**
     * Formatted net amount
     */
    public function getFormattedNetAmountAttribute()
    {
        return '₹' . number_format($this->net_amount, 2);
    }
}