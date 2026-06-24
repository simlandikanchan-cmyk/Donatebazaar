<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
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
}