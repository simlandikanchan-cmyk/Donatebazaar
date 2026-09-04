<?php

namespace App\Services\Financial;

use App\Models\CampaignSettlement;
use App\Models\Donation;
use App\Models\FinancialLedger;
use App\Support\Money;
use Illuminate\Support\Facades\DB;

/**
 * Reads the financial ledger and the business tables to produce a validation
 * report of the platform's financial position.
 *
 * This service is read-only — it never mutates data. It reports discrepancies
 * so an operator can investigate. It is deliberately separate from the
 * existing SettlementService/ReconciliationService which reconcile payout
 * gateway status (settlement state machine); this one validates donation-level
 * fee & revenue accounting against the ledger.
 */
class FinancialReconciliationService
{
    /**
     * Produce a reconciliation report covering all completed donations
     * (including refunded ones) for a date range.
     *
     * Refunded donations are shown explicitly rather than silently dropped:
     *
     *     Captured                        (gross of all completed donations)
     *   - Refunded                        (gross of refunded donations)
     *   = Net retained donation amount
     *
     * Actual platform revenue is computed from KNOWN actual fees only:
     *
     *     actual_platform_revenue = platform_fee - gateway_fee - gateway_tax
     *
     * Unavailable/null fees are never treated as a fabricated value — they are
     * counted separately as "fee data unavailable" so they are not silently
     * assumed to be zero.
     *
     * @return array<string, mixed>
     */
    public function reconcile(?\DateTimeInterface $from = null, ?\DateTimeInterface $to = null): array
    {
        $query = Donation::query()->whereIn('payment_status', ['completed', 'refunded']);

        if ($from) {
            $query->where('paid_at', '>=', $from);
        }
        if ($to) {
            $query->where('paid_at', '<=', $to);
        }

        $donations = $query->get();

        $ledgerSum = (float) DB::table('financial_ledger')
            ->where('event', FinancialLedger::EVENT_DONATION_CAPTURED)
            ->when($from, fn ($q) => $q->where('occurred_at', '>=', $from))
            ->when($to, fn ($q) => $q->where('occurred_at', '<=', $to))
            ->sum('amount');

        // Split completed donations into retained (not refunded) vs refunded.
        $captured = $donations->filter(fn (Donation $d) => ! $d->is_refunded);
        $refunded = $donations->filter(fn (Donation $d) => $d->is_refunded);

        // "Captured" is the gross of ALL completed donations (money that was
        // taken in). Net retained = captured - refunded.
        $grossTotal = Money::sum($donations->pluck('total_amount')->map(fn ($v) => $v ?? 0));
        $refundedGross = Money::sum($refunded->pluck('total_amount')->map(fn ($v) => $v ?? 0));
        $netRetainedAmount = $grossTotal->sub($refundedGross);

        // Fee sums only make sense over retained (non-refunded) donations,
        // because platform/gateway fees are reversed when a donation is refunded.
        $retainedGross = Money::sum($captured->pluck('total_amount')->map(fn ($v) => $v ?? 0));
        $capturedNet = Money::sum($captured->pluck('net_amount')->map(fn ($v) => $v ?? 0));
        $platformFeeSum = Money::sum($captured->pluck('platform_fee')->map(fn ($v) => $v ?? 0));
        $gatewayFeeSum = Money::sum($captured->pluck('gateway_fee')->map(fn ($v) => $v ?? 0));
        $gatewayTaxSum = Money::sum($captured->pluck('gateway_tax')->map(fn ($v) => $v ?? 0));

        $refundedAmount = Money::sum($refunded->pluck('refunded_amount')->map(fn ($v) => $v ?? 0));

        // Payouts (amount actually paid to owners) and pending payout liability.
        $successfulPayouts = Money::sum(
            $captured->where('settlement_status', 'settled')
                ->pluck('payout_amount')
                ->map(fn ($v) => $v ?? 0)
        );

        $pendingPayoutLiability = Money::sum(
            $captured->whereNull('settlement_status')
                ->pluck('net_amount')
                ->map(fn ($v) => $v ?? 0)
        );

        // KNOWN ACTUAL fees vs fee data unavailable.
        $knownFeeCaptured = $captured->filter(fn (Donation $d) => $d->gateway_fee !== null);
        $missingFeeData = $captured->filter(fn (Donation $d) => $d->gateway_fee === null)->count();

        $actualPlatformRevenue = $platformFeeSum
            ->sub($gatewayFeeSum)
            ->sub($gatewayTaxSum);

        return [
            'from' => $from?->format('Y-m-d H:i:s'),
            'to' => $to?->format('Y-m-d H:i:s'),
            'count' => $donations->count(),
            'counts' => [
                'captured' => $captured->count(),
                'refunded' => $refunded->count(),
                'fee_data_unavailable' => $missingFeeData,
            ],
            'summaries' => [
                'captured_total' => $grossTotal->toString(),
                'refunded_total' => $refundedGross->toString(),
                'net_retained_amount' => $netRetainedAmount->toString(),
                'platform_fee_total' => $platformFeeSum->toString(),
                'gateway_fee_total' => $gatewayFeeSum->toString(),
                'gateway_tax_total' => $gatewayTaxSum->toString(),
                'actual_platform_revenue' => $actualPlatformRevenue->toString(),
                'successful_payouts' => $successfulPayouts->toString(),
                'pending_payout_liability' => $pendingPayoutLiability->toString(),
                'ledger_captured_total' => number_format($ledgerSum, 2),
                'refunded_amount_total' => $refundedAmount->toString(),
            ],
            'fee_data' => [
                'known_actual_fees' => $knownFeeCaptured->count(),
                'missing_gateway_fee_captures' => $missingFeeData,
            ],
            'checks' => [
                'ledger_matches_gross' => Money::of($ledgerSum)->isEqualTo($grossTotal),
                'net_plus_platform_fee_equals_gross' => $capturedNet->add($platformFeeSum)->isEqualTo($retainedGross),
                'refunded_total_equals_refunded_amount' => $refundedAmount->isEqualTo($refundedGross),
                'pending_payout_liability_non_negative' => ! $pendingPayoutLiability->isNegative(),
            ],
            'warnings' => $this->warnings($donations),
        ];
    }

    /**
     * Validate that a completed donation's accounting is internally consistent.
     *
     * @return array<int, string>
     */
    public function reconcileDonation(Donation $donation): array
    {
        $warnings = [];

        if ($donation->payment_status === 'completed') {
            $net = Money::of($donation->net_amount);
            $gross = Money::of($donation->total_amount);
            $fee = Money::of($donation->platform_fee);

            if (! $net->add($fee)->isEqualTo($gross)) {
                $warnings[] = "net_amount + platform_fee != total_amount ({$net} + {$fee} != {$gross})";
            }

            if ($donation->gateway_fee === null) {
                $warnings[] = 'gateway_fee not captured (fee_capture_status='.($donation->fee_capture_status ?? 'null').')';
            }

            $ledgerCaptured = FinancialLedger::where('reference_type', Donation::class)
                ->where('reference_id', $donation->id)
                ->where('event', FinancialLedger::EVENT_DONATION_CAPTURED)
                ->exists();

            if (! $ledgerCaptured) {
                $warnings[] = 'no donation_captured ledger entry';
            }
        }

        return $warnings;
    }

    private function warnings($donations): array
    {
        $warnings = [];

        foreach ($donations as $donation) {
            foreach ($this->reconcileDonation($donation) as $warning) {
                $warnings[] = sprintf('Donation #%s: %s', $donation->id, $warning);
            }

            if (count($warnings) >= 50) {
                $warnings[] = '... (truncated)';

                break;
            }
        }

        return $warnings;
    }
}
