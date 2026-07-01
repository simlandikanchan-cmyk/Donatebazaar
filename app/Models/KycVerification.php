<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KycVerification extends Model
{
    /*
    |--------------------------------------------------------------------------
    | KYC STATUS CONSTANTS
    |--------------------------------------------------------------------------
    */

    const STATUS_PENDING  = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    /*
    |--------------------------------------------------------------------------
    | FILLABLE
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'user_id',
        'campaign_id', // FIXED: added so KYC is scoped per-campaign, not just per-user

        'document_type',
        'document_number',
        'document_url',

        'aadhaar_url',
        'pan_url',
        'selfie_url',

        'kyc_account_name',
        'kyc_account_number',
        'kyc_ifsc',
        'kyc_bank_name',

        'status',
        'rejection_reason',

        'verified_by',
        'verified_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

     protected $casts = [
    'verified_at'         => 'datetime',
    'document_number'     => 'encrypted', // PAN/Aadhaar numbers stored encrypted at rest
    'kyc_account_name'    => 'encrypted',
    'kyc_account_number'  => 'encrypted',
    'kyc_ifsc'            => 'encrypted',
    'kyc_bank_name'       => 'encrypted',
];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'verified_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS HELPERS
    |--------------------------------------------------------------------------
    */

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {

            self::STATUS_PENDING  => 'Pending',

            self::STATUS_APPROVED => 'Verified',

            self::STATUS_REJECTED => 'Rejected',

            default => 'Unknown',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {

            self::STATUS_PENDING  => 'warning',

            self::STATUS_APPROVED => 'success',

            self::STATUS_REJECTED => 'danger',

            default => 'secondary',
        };
    }
}