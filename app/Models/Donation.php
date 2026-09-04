<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'campaign_id',
        'user_id',
        'donor_name',
        'donor_email',
        'donor_phone',
        'donation_type',
        'total_amount',
        'original_amount',
        'discount_amount',
        'platform_fee',
        'gateway_fee',
        'gateway_tax',
        'gateway_fee_bearer',
        'fee_capture_status',
        'net_amount',
        'order_id',
        'payment_gateway',
        'currency',
        'receipt_number',
        'coupon_id',
        'coupon_code',
        'is_anonymous',
        'message',
        'refund_idempotency_key',
        'refunded_amount',
        'payout_amount',
    ];

    protected $guarded = [
        'payment_status',
        'settlement_status',
        'campaign_settlement_id',
        'paid_at',
        'is_refunded',
        'refunded_at',
        'released_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'gateway_fee' => 'decimal:2',
        'gateway_tax' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'original_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'refunded_amount' => 'decimal:2',
        'payout_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'refunded_at' => 'datetime',
        'released_at' => 'datetime',
        'is_refunded' => 'boolean',
        'is_anonymous' => 'boolean',
    ];

    protected function paymentStatus(): Attribute
    {
        return Attribute::make(
            set: function (string|PaymentStatus $value): string {
                if ($value instanceof PaymentStatus) {
                    return $value->value;
                }
                $enum = PaymentStatus::tryFrom($value);
                if ($enum === null) {
                    throw new \InvalidArgumentException("Invalid payment status: '$value'");
                }

                return $enum->value;
            },
        );
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }

    public function items()
    {
        return $this->hasMany(DonationItem::class);
    }

    public function ledger()
    {
        return $this->morphMany(FinancialLedger::class, 'reference');
    }
}
