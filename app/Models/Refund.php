<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        'donation_id',
        'donation_payment_id',
        'amount',
        'reason',
        'status',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    /**
     * Donation relation
     */
    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    /**
     * Donation payment relation
     */
    public function payment()
    {
        return $this->belongsTo(
            DonationPayment::class,
            'donation_payment_id'
        );
    }

    /**
     * Check if refund processed
     */
    public function isProcessed()
    {
        return $this->status === 'processed';
    }

    /**
     * Check if refund failed
     */
    public function isFailed()
    {
        return $this->status === 'failed';
    }

    /**
     * Check if refund pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Formatted refund amount
     */
    public function getFormattedAmountAttribute()
    {
        return '₹' . number_format($this->amount, 2);
    }
}