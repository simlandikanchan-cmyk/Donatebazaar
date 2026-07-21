<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SettlementStateLog extends Model
{
    protected $fillable = [
        'settlement_id',
        'from_state',
        'to_state',
        'actor_type',
        'actor_id',
        'correlation_id',
        'trace_id',
        'reason',
        'created_at',
    ];

    public $timestamps = false;

    protected $casts = [
        'actor_id' => 'integer',
    ];

    public function settlement(): BelongsTo
    {
        return $this->belongsTo(CampaignSettlement::class);
    }
}
