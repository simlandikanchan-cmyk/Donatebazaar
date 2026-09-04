<?php

namespace App\Services\Financial;

use App\Models\CampaignSettlement;
use App\Models\Donation;
use App\Models\FinancialLedger;
use App\Models\Refund;
use App\Support\Money;
use Illuminate\Support\Facades\DB;

/**
 * Records immutable entries into the financial ledger.
 *
 * The ledger is the reconciliation source of truth. Every method is idempotent
 * on a business idempotency key so retried events (webhooks, queued jobs) never
 * write duplicate rows. Rows are write-once — this service never updates or
 * deletes ledger rows.
 */
class FinancialLedgerService
{
    /**
     * Record the capture of a donation together with the gateway fee once known.
     *
     * Writes a `donation_captured` event (gross = total_amount) and, when the
     * actual gateway fee is available, a `gateway_fee_captured` event.
     */
    public function recordDonationCaptured(Donation $donation): void
    {
        $this->createEvent([
            'event' => FinancialLedger::EVENT_DONATION_CAPTURED,
            'amount' => $this->moneyOf($donation->total_amount),
            'reference' => $donation,
            'gateway_reference' => $donation->payment_id,
            'idempotency_key' => 'donation_captured:'.$donation->id,
            'metadata' => [
                'platform_fee' => $this->moneyOf($donation->platform_fee)->toString(),
                'net_amount' => $this->moneyOf($donation->net_amount)->toString(),
                'gateway_fee' => $this->moneyOf($donation->gateway_fee ?? 0)->toString(),
                'gateway_fee_bearer' => $this->bearer(),
                'currency' => $donation->currency ?: 'INR',
            ],
            'occurred_at' => $donation->paid_at ?: now(),
        ]);

        if ($donation->gateway_fee !== null) {
            $this->recordGatewayFee($donation);
        }
    }

    /**
     * Record the actual provider gateway fee for a captured donation.
     * Idempotent per donation.
     */
    public function recordGatewayFee(Donation $donation): void
    {
        $fee = Money::of($donation->gateway_fee ?? 0);
        $tax = Money::of($donation->gateway_tax ?? 0);

        $this->createEvent([
            'event' => FinancialLedger::EVENT_GATEWAY_FEE_CAPTURED,
            'amount' => $fee->add($tax),
            'reference' => $donation,
            'gateway_reference' => $donation->payment_id,
            'idempotency_key' => 'gateway_fee:'.$donation->id,
            'metadata' => [
                'fee' => $fee->toString(),
                'tax' => $tax->toString(),
                'bearer' => $this->bearer(),
            ],
            'occurred_at' => $donation->paid_at ?: now(),
        ]);
    }

    /**
     * Record a processed refund.
     *
     * @param  Money  $platformFeeReversed  the platform fee amount reversed out of
     *                                      campaign platform_earnings.
     */
    public function recordRefund(Refund $refund, Donation $donation, Money $platformFeeReversed): void
    {
        $this->createEvent([
            'event' => FinancialLedger::EVENT_REFUND_PROCESSED,
            'amount' => Money::of($refund->amount),
            'reference' => $refund,
            'gateway_reference' => $refund->gateway_refund_id,
            'idempotency_key' => 'refund:'.($refund->gateway_refund_id ?: $refund->id),
            'metadata' => [
                'donation_id' => $donation->id,
                'platform_fee_reversed' => $platformFeeReversed->toString(),
                'currency' => $donation->currency ?: 'INR',
            ],
            'occurred_at' => $refund->processed_at ?: now(),
        ]);
    }

    /**
     * Record a completed payout (settlement) of settled donations.
     */
    public function recordPayout(CampaignSettlement $settlement): void
    {
        $this->createEvent([
            'event' => FinancialLedger::EVENT_PAYOUT_COMPLETED,
            'amount' => Money::of($settlement->net_amount),
            'reference' => $settlement,
            'gateway_reference' => $settlement->gateway_reference,
            'idempotency_key' => 'payout:'.$settlement->id,
            'metadata' => [
                'gross_amount' => Money::of($settlement->gross_amount)->toString(),
                'platform_fee' => Money::of($settlement->platform_fee)->toString(),
                'net_amount' => Money::of($settlement->net_amount)->toString(),
                'currency' => 'INR',
            ],
            'occurred_at' => $settlement->paid_at ?: now(),
        ]);
    }

    /**
     * Return the configured gateway-fee bearer policy.
     *
     * Only the 'platform' bearer is currently implemented: the platform absorbs
     * the gateway fee and the campaign owner always receives net_amount.
     *
     * Any other value (e.g. 'campaign_owner') is REJECTED with an explicit
     * error rather than silently ignored — the accounting expectations must
     * never drift from what the wallet/payout code actually executes.
     *
     * @throws \InvalidArgumentException when the configured bearer is unsupported.
     */
    public function bearer(): string
    {
        $bearer = config('services.donation.gateway_fee_bearer', 'platform');

        if (! in_array($bearer, ['platform'], true)) {
            throw new \InvalidArgumentException(
                "Unsupported GATEWAY_FEE_BEARER '{$bearer}'. Only 'platform' is currently supported."
            );
        }

        return $bearer;
    }

    private function createEvent(array $data): void
    {
        // Unique constraint on idempotency_key guards against duplicate rows.
        FinancialLedger::firstOrCreate(
            ['idempotency_key' => $data['idempotency_key']],
            [
                'event' => $data['event'],
                'amount' => $data['amount']->toString(),
                'currency' => $data['metadata']['currency'] ?? 'INR',
                'reference_type' => $data['reference']->getMorphClass(),
                'reference_id' => $data['reference']->getKey(),
                'gateway_reference' => $data['gateway_reference'],
                'idempotency_key' => $data['idempotency_key'],
                'metadata' => $data['metadata'],
                'occurred_at' => $data['occurred_at'],
            ]
        );
    }

    private function moneyOf(mixed $value): Money
    {
        if ($value instanceof Money) {
            return $value;
        }

        return Money::of($value ?? 0);
    }
}
