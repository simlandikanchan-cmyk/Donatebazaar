<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FundraiserLevel extends Model
{
    protected $fillable = [
        'level_number',
        'level_name',
        'description',
        'max_goal_amount',
        'max_active_campaigns',
        'min_campaigns_completed',
        'min_raised_percent',
        'requires_admin_approval',
        'kyc_requirement',
        'badge_color',
    ];

    protected $casts = [
        'max_goal_amount'         => 'decimal:2',
        'min_raised_percent'      => 'decimal:2',
        'requires_admin_approval' => 'boolean',
    ];

    // ── Relationships ──────────────────────────────────────────

    public function userLevels()
    {
        return $this->hasMany(UserFundraiserLevel::class, 'current_level_id');
    }

    // ── Scopes ────────────────────────────────────────────────

    public static function starter(): self
    {
        return static::where('level_number', 1)->firstOrFail();
    }

    public static function nextAfter(int $currentLevelNumber): ?self
    {
        return static::where('level_number', $currentLevelNumber + 1)->first();
    }
}