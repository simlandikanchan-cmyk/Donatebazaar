<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SettlementItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_settlement_id',
        'donation_id',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Settlement relation
     */
    public function settlement()
    {
        return $this->belongsTo(
            CampaignSettlement::class,
            'campaign_settlement_id'
        );
    }

    /**
     * Donation relation
     */
    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    /**
     * Formatted amount accessor
     */
    public function getFormattedAmountAttribute()
    {
        return '₹' . number_format($this->amount, 2);
    }
}