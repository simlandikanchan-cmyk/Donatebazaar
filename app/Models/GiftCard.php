<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GiftCard extends Model
{
    protected $fillable = [
        'code', 'amount', 'theme',
        'sender_name', 'sender_email',
        'recipient_name', 'recipient_email',
        'message', 'send_at', 'status',
        'payment_id', 'order_id', 'payment_status',
        'redeemed_by', 'redeemed_on_campaign',
        'redeemed_at', 'expires_at', 'admin_note',
    ];

    protected $casts = [
        'send_at'     => 'datetime',
        'redeemed_at' => 'datetime',
        'expires_at'  => 'datetime',
        'amount'      => 'decimal:2',
    ];

    public static function generateCode(): string
    {
        do {
            $code = 'DNBZ-'
                . strtoupper(Str::random(4))
                . '-'
                . strtoupper(Str::random(4));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    public function redeemedBy()
    {
        return $this->belongsTo(User::class, 'redeemed_by');
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'redeemed_on_campaign');
    }

    public function isRedeemed(): bool { return $this->status === 'redeemed'; }
    public function isExpired(): bool  { return $this->expires_at && $this->expires_at->isPast(); }
    public function isPaid(): bool     { return $this->payment_status === 'completed'; }
}