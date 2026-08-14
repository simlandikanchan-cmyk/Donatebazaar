<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'restored_at',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'processed_at' => 'datetime',
        'failed_at' => 'datetime',
        'restored_at' => 'datetime',
        'rejection_reason' => 'string',
        'failed_reason' => 'string',
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
     * Admin who approved the settlement.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Payout account snapshot for this settlement.
     */
    public function payoutAccount()
    {
        return $this->belongsTo(PayoutAccount::class, 'payout_account_id');
    }

    /**
     * Payout attempts for this settlement (one per retry).
     */
    public function payoutAttempt()
    {
        return $this->hasMany(PayoutAttempt::class, 'settlement_id');
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
     * Check if settlement is awaiting admin approval
     */
    public function isPendingApproval()
    {
        return $this->status === 'pending_approval';
    }

    /**
     * Check if settlement was approved by admin (payout queued).
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if settlement was auto-approved by the risk engine.
     */
    public function isAutoApproved()
    {
        return $this->status === 'auto_approved';
    }

    /**
     * Check if settlement is being processed (payout in flight).
     */
    public function isProcessing()
    {
        return $this->status === 'processing';
    }

    /**
     * Check if settlement is waiting for retry.
     */
    public function isRetryPending()
    {
        return $this->status === 'retry_pending';
    }

    /**
     * Check if settlement was rejected by admin
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
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
        return '₹'.number_format($this->gross_amount, 2);
    }

    /**
     * Formatted platform fee
     */
    public function getFormattedPlatformFeeAttribute()
    {
        return '₹'.number_format($this->platform_fee, 2);
    }

    /**
     * Formatted net amount
     */
    public function getFormattedNetAmountAttribute()
    {
        return '₹'.number_format($this->net_amount, 2);
    }
}
