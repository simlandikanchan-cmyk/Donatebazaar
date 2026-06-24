<?php

namespace App\Services;

use App\Models\Donation;
use App\Models\CampaignSettlement;
use App\Models\SettlementItem;
use Illuminate\Support\Facades\DB;

class SettlementService
{
    /**
     * Create settlement for a campaign
     */
    public function settleCampaign($campaign)
    {
        DB::beginTransaction();

        try {

            // Get all pending donations
            $donations = Donation::where('campaign_id', $campaign->id)
                ->where('payment_status', 'completed')
                ->where('settlement_status', 'pending')
                ->get();

            // No donations
            if ($donations->isEmpty()) {
                return [
                    'success' => false,
                    'message' => 'No pending donations found.'
                ];
            }

            // Calculate totals
            $grossAmount = $donations->sum('total_amount');

            $platformFee = $donations->sum('platform_fee');

            $netAmount = $donations->sum('net_amount');

            // Create settlement
            $settlement = CampaignSettlement::create([
                'campaign_id' => $campaign->id,

                'organization_id' => $campaign->organization_id
                    ?? $campaign->user_id,

                'gross_amount' => $grossAmount,

                'platform_fee' => $platformFee,

                'net_amount' => $netAmount,

                'status' => 'processing',
            ]);

            // Attach donations
            foreach ($donations as $donation) {

                SettlementItem::create([
                    'campaign_settlement_id' => $settlement->id,
                    'donation_id' => $donation->id,
                    'amount' => $donation->net_amount,
                ]);

                // Update donation
                $donation->update([
                    'settlement_status' => 'settled',
                    'campaign_settlement_id' => $settlement->id,
                ]);
            }

            // Update campaign stats
            $campaign->increment('total_settled', $netAmount);

            $campaign->decrement('pending_settlement', $netAmount);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Settlement created successfully.',
                'settlement' => $settlement,
            ];

        } catch (\Exception $e) {

            DB::rollBack();

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Mark settlement as paid
     */
    public function markAsPaid($settlement, $reference = null)
    {
        $settlement->update([
            'status' => 'paid',
            'paid_at' => now(),
            'transfer_reference' => $reference,
        ]);

        return true;
    }

    /**
     * Mark settlement failed
     */
    public function markAsFailed($settlement)
    {
        $settlement->update([
            'status' => 'failed',
        ]);

        return true;
    }
}