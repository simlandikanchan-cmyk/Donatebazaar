<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganizationApplication extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',

        // Step 1 - Organization Info
        'organization_type',
        'name',
        'registration_number',
        'registration_date',
        'address',
        'city',
        'state',
        'pincode',
        'causes',
        'founder_name',
        'founder_linkedin',
        'mission_statement',

        // Step 2 - Contact Person
        'contact_name',
        'contact_phone',
        'contact_email',
        'contact_role',
        'contact_linkedin',
        'contact_whatsapp',

        // Step 3 - Certifications & Legal
        'has_80g',
        '80g_number',
        '80g_expiry',
        'has_fcra',
        'fcra_number',
        'has_12a',
        '12a_number',
        'has_csr_eligible',
        'pan_number',
        'darpan_id',

        // Step 4 - Documents & Profile
        'website',
        'social_facebook',
        'social_instagram',
        'social_twitter',
        'social_youtube',
        'budget_range',
        'donor_strength',
        'employee_strength',
        'has_crowdfunded',
        'campaign_timeline',
        'campaigns_completed',

        // Documents
        'doc_registration_cert',
        'doc_80g_certificate',
        'doc_fcra_certificate',
        'doc_annual_report',
        'doc_audited_statement',
        'doc_pan_card',

        // Bank Details
        'bank_name',
        'bank_account_number',
        'bank_ifsc',
        'bank_account_type',

        // References
        'reference_1_name',
        'reference_1_org',
        'reference_1_phone',
        'reference_2_name',
        'reference_2_org',
        'reference_2_phone',

        // Status & Scoring
        'status',
        'priority_score',
        'rejection_reason',
        'admin_notes',
        'submitted_at',
        'reviewed_at',
        'reviewed_by',
        'current_step',
    ];

    protected $casts = [

        'causes'               => 'array',
        'registration_date'    => 'date',
        '80g_expiry'           => 'date',
        'submitted_at'         => 'datetime',
        'reviewed_at'          => 'datetime',
        'has_80g'              => 'boolean',
        'has_fcra'             => 'boolean',
        'has_12a'              => 'boolean',
        'has_csr_eligible'     => 'boolean',
        'has_crowdfunded'      => 'boolean',
        'campaigns_completed'  => 'integer',
        'priority_score'       => 'integer',
        'current_step'         => 'integer',


         // sensitive fields — encrypted at rest
    'pan_number'           => 'encrypted',
    'bank_name'            => 'encrypted',
    'bank_account_number'  => 'encrypted',
    'bank_ifsc'            => 'encrypted',
    'bank_account_type'    => 'encrypted'

    ];

    // ── Relationships ──────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // ── Helpers ────────────────────────────────────────────────────────────






    
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }






    public function isPending(): bool
    {
        return $this->status === 'pending';
    }





    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }






    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }



}