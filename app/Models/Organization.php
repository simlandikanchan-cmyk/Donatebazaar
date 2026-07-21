<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'slug',
        'description',
        'logo',
        'website',
        'contact_email',
        'contact_phone',
        'registration_number',
        'pan_number',
        'gst_number',
        'bank_name',
        'account_holder_name',
        'bank_account_number',
        'ifsc_code',
        'commission_rate',
        'verification_status',
        'verified_at',
        'is_active',
        'wallet_hold_days',
    ];

    protected $casts = [
        'wallet_hold_days' => 'integer',
        'is_active' => 'boolean',
        'verified_at' => 'datetime',
    ];

    /**
     * Owner (User)
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Organization Campaigns
     */
    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    /**
     * Organization Applications (NEW)
     */
    public function applications()
    {
        return $this->hasMany(OrganizationApplication::class);
    }

    /**
     * Wallet ledger for this organization (one wallet per owner).
     */
    public function wallet()
    {
        return $this->morphOne(Wallet::class, 'owner');
    }

    /**
     * Payout bank/UPI accounts for this organization.
     */
    public function payoutAccounts()
    {
        return $this->hasMany(PayoutAccount::class);
    }
}
