<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RiskScore extends Model
{
    protected $fillable = [
        'settlement_id',
        'organization_id',
        'risk_version',
        'risk_score',
        'risk_verdict',
        'evaluated_at',
    ];

    protected $casts = [
        'risk_version' => 'integer',
        'risk_score' => 'integer',
        'evaluated_at' => 'datetime',
    ];

    public const VERDICT_AUTO_APPROVED = 'auto_approved';

    public const VERDICT_MANUAL_REVIEW = 'manual_review';

    public const VERDICT_REJECTED = 'rejected';

    public function settlement(): BelongsTo
    {
        return $this->belongsTo(CampaignSettlement::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function ruleLogs(): HasMany
    {
        return $this->hasMany(RiskRuleLog::class);
    }
}
