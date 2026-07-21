<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiskConfig extends Model
{
    protected $table = 'risk_config';

    protected $fillable = [
        'risk_version',
        'approval_threshold',
        'manual_review_threshold',
        'velocity_limits',
        'aml_version',
        'fraud_threshold',
        'configurable_limits',
    ];

    protected $casts = [
        'risk_version' => 'integer',
        'approval_threshold' => 'integer',
        'manual_review_threshold' => 'integer',
        'velocity_limits' => 'json',
        'fraud_threshold' => 'integer',
        'configurable_limits' => 'json',
    ];

    /**
     * The currently active config (highest version).
     */
    public static function active(): ?self
    {
        return self::orderByDesc('risk_version')->first();
    }
}
