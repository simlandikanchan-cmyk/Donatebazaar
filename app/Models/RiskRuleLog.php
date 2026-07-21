<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiskRuleLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'risk_score_id',
        'rule_name',
        'category',
        'triggered',
        'points',
        'force_review',
        'detail',
    ];

    protected $casts = [
        'triggered' => 'boolean',
        'points' => 'integer',
        'force_review' => 'boolean',
        'detail' => 'json',
    ];

    public function riskScore(): BelongsTo
    {
        return $this->belongsTo(RiskScore::class);
    }
}
