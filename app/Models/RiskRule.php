<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RiskRule extends Model
{
    protected $fillable = [
        'name',
        'category',
        'weight',
        'priority',
        'enabled',
        'force_review',
        'threshold',
        'configuration',
    ];

    protected $casts = [
        'weight' => 'integer',
        'priority' => 'integer',
        'enabled' => 'boolean',
        'force_review' => 'boolean',
        'threshold' => 'json',
        'configuration' => 'json',
    ];

    public function logs(): HasMany
    {
        return $this->hasMany(RiskRuleLog::class, 'rule_name', 'name');
    }
}
