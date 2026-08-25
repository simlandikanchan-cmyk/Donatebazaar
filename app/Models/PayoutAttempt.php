<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayoutAttempt extends Model
{
    protected $fillable = [
        'settlement_id',
        'payout_account_id',
        'attempt_number',
        'idempotency_key',
        'gateway',
        'gateway_reference',
        'status',
        'request_payload_hash',
        'response_payload_hash',
        'started_at',
        'finished_at',
        'error_message',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'attempt_number' => 'integer',
    ];

    public function settlement(): BelongsTo
    {
        return $this->belongsTo(CampaignSettlement::class);
    }

    public function payoutAccount(): BelongsTo
    {
        return $this->belongsTo(PayoutAccount::class);
    }

    public static function forSettlement(CampaignSettlement $settlement, int $attemptNumber): self
    {
        return new static([
            'settlement_id' => $settlement->id,
            'payout_account_id' => $settlement->payout_account_id,
            'attempt_number' => $attemptNumber,
            'idempotency_key' => static::generateIdempotencyKey($settlement, $attemptNumber),
            'gateway' => 'razorpay',
            'status' => 'queued',
        ]);
    }

    public static function generateIdempotencyKey(CampaignSettlement $settlement, int $attemptNumber): string
    {
        // One logical payout attempt = one stable key. If the settlement already
        // carries a persisted key (set when first entering processing), every
        // retry — whether the same job instance or a freshly dispatched one —
        // reuses it so the gateway de-duplicates and never pays twice.
        if (! empty($settlement->payout_idempotency_key)) {
            return $settlement->payout_idempotency_key;
        }

        return 'payout_'.$settlement->id.'_attempt_'.$attemptNumber.'_'.md5($settlement->net_amount);
    }
}
