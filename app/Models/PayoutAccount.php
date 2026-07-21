<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayoutAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'account_holder_name',
        'bank_name',
        'account_number',
        'ifsc_code',
        'upi_id',
        'is_verified',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    /**
     * Organization relation
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Admin user who verified this account.
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Masked account number
     */
    public function getMaskedAccountNumberAttribute()
    {
        return 'XXXXXX'.substr($this->account_number, -4);
    }

    /**
     * Verification status label
     */
    public function getVerificationStatusAttribute()
    {
        return $this->is_verified
            ? 'Verified'
            : 'Pending';
    }

    /**
     * Check if payout account verified
     */
    public function isVerified()
    {
        return $this->is_verified === true;
    }

    /**
     * Get payout method
     */
    public function getPayoutMethodAttribute()
    {
        return $this->upi_id
            ? 'UPI'
            : 'Bank Transfer';
    }
}
