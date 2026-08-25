<?php

namespace App\Repositories;

use App\Models\CampaignSettlement;
use App\Models\SettlementItem;
use App\Models\SettlementStateLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SettlementRepository
{
    public function getPendingCount(): int
    {
        return CampaignSettlement::whereIn('status', ['pending_approval', 'manual_review'])->count();
    }

    public function getAdminPaginated(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = CampaignSettlement::with(['campaign:id,title,user_id', 'campaign.user:id,name']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderByRaw("FIELD(status, 'manual_review', 'pending_approval') DESC")
            ->latest()
            ->paginate($perPage);
    }

    public function findWithRelations(int $id): ?CampaignSettlement
    {
        return CampaignSettlement::with([
            'campaign.user:id,name,email',
            'items.donation:id,total_amount,donor_name,payment_status,paid_at',
            'payoutAttempt' => fn($q) => $q->latest()->limit(5),
            'stateLogs' => fn($q) => $q->latest(),
        ])->find($id);
    }

    public function getPendingByCampaignId(int $campaignId): Collection
    {
        return CampaignSettlement::where('campaign_id', $campaignId)
            ->whereIn('status', ['pending', 'processing', 'pending_approval'])
            ->get();
    }

    public function getItemsBySettlementId(int $settlementId): Collection
    {
        return SettlementItem::where('campaign_settlement_id', $settlementId)
            ->with('donation:id,total_amount,donor_name,payment_status,paid_at')
            ->get();
    }

    public function findPendingWithLock(int $id): ?CampaignSettlement
    {
        return CampaignSettlement::where('id', $id)
            ->whereIn('status', ['pending', 'pending_approval'])
            ->lockForUpdate()
            ->first();
    }

    public function getClaimableAmount(int $campaignId): float
    {
        return (float) SettlementItem::whereHas('settlement', function ($q) use ($campaignId) {
            $q->where('campaign_id', $campaignId)
                ->whereIn('status', ['pending', 'pending_approval', 'approved', 'processing']);
        })->sum('amount');
    }

    public function createStateLog(array $data): SettlementStateLog
    {
        return SettlementStateLog::create($data);
    }
}
