<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFundraiserLevel extends Model
{
    protected $fillable = [
        'user_id',
        'current_level_id',
        'total_campaigns_completed',
        'total_amount_raised',
        'upgrade_requested_at',
        'upgrade_reviewed_by',
        'upgrade_reviewed_at',
        'level_upgraded_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'total_amount_raised'    => 'decimal:2',
        'upgrade_requested_at'   => 'datetime',
        'upgrade_reviewed_at'    => 'datetime',
        'level_upgraded_at'      => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currentLevel()
    {
        return $this->belongsTo(FundraiserLevel::class, 'current_level_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'upgrade_reviewed_by');
    }

    public function history()
    {
        return $this->hasMany(FundraiserLevelHistory::class, 'user_id', 'user_id');
    }

    // ── Helpers ───────────────────────────────────────────────

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isPendingUpgrade(): bool
    {
        return $this->status === 'upgrade_pending';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }
}