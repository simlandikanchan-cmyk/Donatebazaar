<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LegalPage extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'content',
        'updated_by',
    ];

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public static function slugs(): array
    {
        return [
            'terms-of-service' => 'Terms of Service',
            'privacy-policy' => 'Privacy Policy',
            'refund-policy' => 'Refund Policy',
            'cookie-policy' => 'Cookie Policy',
            'donor-policy' => 'Donor Policy',
            'campaign-policy' => 'Campaign Policy',
            'kyc-policy' => 'KYC Policy',
            'grievance-policy' => 'Grievance Redressal Policy',
            'acceptable-use-policy' => 'Acceptable Use Policy',
            'payment-policy' => 'Payment Policy',
        ];
    }

    public static function publicPath(string $slug): string
    {
        return match ($slug) {
            'privacy-policy' => '/privacy-policy',
            'terms-of-service' => '/terms-of-service',
            'refund-policy' => '/refund-policy',
            'cookie-policy' => '/cookie-policy',
            'donor-policy' => '/donor-policy',
            'campaign-policy' => '/campaign-policy',
            'kyc-policy' => '/kyc-policy',
            'grievance-policy' => '/grievance-policy',
            'acceptable-use-policy' => '/acceptable-use-policy',
            'payment-policy' => '/payment-policy',
            default => '/'.$slug,
        };
    }
}
