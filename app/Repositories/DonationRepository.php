<?php

namespace App\Repositories;

use App\Models\Donation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DonationRepository
{
    public function findById(int $id): ?Donation
    {
        return Donation::find($id);
    }

    public function findByOrderId(string $orderId): ?Donation
    {
        return Donation::where('order_id', $orderId)->first();
    }

    public function findPendingByOrderId(string $orderId): ?Donation
    {
        return Donation::where('order_id', $orderId)
            ->where('payment_status', 'pending')
            ->first();
    }

    public function findWithLock(string $orderId): ?Donation
    {
        return Donation::where('order_id', $orderId)
            ->lockForUpdate()
            ->first();
    }

    public function getUserDonations(int $userId): LengthAwarePaginator
    {
        return Donation::where('user_id', $userId)
            ->with(['campaign' => function ($q) {
                $q->select('id', 'title', 'slug', 'cover_image', 'goal_amount', 'raised_amount', 'category_id')
                    ->with('category:id,name,slug');
            }, 'refunds'])
            ->orderByRaw("FIELD(payment_status, 'completed', 'pending', 'failed', 'refunded')")
            ->latest('created_at')
            ->paginate(15);
    }

    public function getUserStats(int $userId): object
    {
        return Donation::where('user_id', $userId)
            ->selectRaw('COALESCE(SUM(CASE WHEN payment_status = ? THEN total_amount ELSE 0 END), 0) as total_donated', ['completed'])
            ->selectRaw('COUNT(CASE WHEN payment_status = ? THEN 1 END) as completed_count', ['completed'])
            ->selectRaw('COUNT(CASE WHEN payment_status = ? THEN 1 END) as pending_count', ['pending'])
            ->selectRaw('COUNT(CASE WHEN is_refunded = 1 THEN 1 END) as refunded_count')
            ->first();
    }

    public function getCompletedBase(int $campaignId)
    {
        return Donation::where('campaign_id', $campaignId)
            ->where('payment_status', 'completed');
    }

    public function getCampaignAnalytics(int $campaignId): array
    {
        $base = $this->getCompletedBase($campaignId);

        $totalRaised = (clone $base)->sum('total_amount');
        $donationCount = (clone $base)->count();
        $avgDonation = $donationCount > 0 ? $totalRaised / $donationCount : 0;
        $maxDonation = (clone $base)->max('total_amount') ?? 0;
        $minDonation = (clone $base)->min('total_amount') ?? 0;
        $platformFees = (clone $base)->sum('platform_fee');
        $uniqueDonors = (clone $base)
            ->whereNotNull('donor_email')
            ->distinct('donor_email')
            ->count('donor_email');

        $productDonations = (clone $base)->where('donation_type', 'product');
        $productCount = (clone $productDonations)->count();
        $productAmount = (clone $productDonations)->sum('total_amount');

        $moneyDonations = (clone $base)->where('donation_type', 'money');
        $moneyCount = (clone $moneyDonations)->count();
        $moneyAmount = (clone $moneyDonations)->sum('total_amount');

        $trendData = (clone $base)
            ->select(
                DB::raw('DATE(paid_at) as date'),
                DB::raw('SUM(total_amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->where('paid_at', '>=', now()->subDays(60))
            ->groupBy(DB::raw('DATE(paid_at)'))
            ->orderBy('date')
            ->get();

        $topDonors = (clone $base)
            ->where('is_anonymous', false)
            ->whereNotNull('donor_name')
            ->select('donor_name', 'donor_email', DB::raw('SUM(total_amount) as total'), DB::raw('COUNT(*) as donations'))
            ->groupBy('donor_name', 'donor_email')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        $recentDonations = (clone $base)
            ->where(function ($q) {
                $q->whereNull('is_anonymous')->orWhere('is_anonymous', false);
            })
            ->select('donor_name', 'total_amount', 'donation_type', 'paid_at', 'payment_gateway')
            ->latest('paid_at')
            ->take(15)
            ->get();

        $donationsByDayOfWeek = (clone $base)
            ->select(
                DB::raw('DAYOFWEEK(paid_at) as day_num'),
                DB::raw('SUM(total_amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy(DB::raw('DAYOFWEEK(paid_at)'))
            ->orderBy('day_num')
            ->get()
            ->keyBy('day_num');

        $totalDonors = (clone $base)
            ->whereNotNull('donor_email')
            ->distinct('donor_email')
            ->count('donor_email');

        return compact(
            'totalRaised', 'donationCount', 'avgDonation', 'maxDonation', 'minDonation',
            'platformFees', 'uniqueDonors', 'totalDonors',
            'productCount', 'productAmount', 'moneyCount', 'moneyAmount',
            'trendData', 'topDonors', 'recentDonations', 'donationsByDayOfWeek'
        );
    }

    public function getRecentByCampaignIds(array $campaignIds, int $limit = 6): Collection
    {
        return Donation::whereIn('campaign_id', $campaignIds)
            ->whereNotNull('paid_at')
            ->with('campaign:id,title')
            ->latest()
            ->take($limit)
            ->get();
    }

    public function countCompletedByCampaignIds(array $campaignIds): int
    {
        return Donation::whereIn('campaign_id', $campaignIds)
            ->whereNotNull('paid_at')
            ->count();
    }

    public function countByPaymentStatus(string $status): int
    {
        return Donation::where('payment_status', $status)->count();
    }

    public function getAdminDashboardStats(): object
    {
        return Donation::selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN payment_status = 'completed' THEN total_amount ELSE 0 END) as total_revenue")
            ->selectRaw("SUM(CASE WHEN payment_status = 'completed' THEN 1 ELSE 0 END) as completed_count")
            ->selectRaw("SUM(CASE WHEN payment_status = 'failed' THEN 1 ELSE 0 END) as failed_count")
            ->selectRaw("SUM(CASE WHEN is_refunded = 1 THEN 1 ELSE 0 END) as refunded_count")
            ->first();
    }

    public function searchWithUser(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = Donation::query()->with(['campaign:id,title', 'user:id,name,email']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('donor_name', 'like', "%{$search}%")
                    ->orWhere('donor_email', 'like', "%{$search}%")
                    ->orWhere('order_id', 'like', "%{$search}%")
                    ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', "%{$search}%"));
            });
        }

        if (!empty($filters['status'])) {
            $query->where('payment_status', $filters['status']);
        }

        if (!empty($filters['campaign_id'])) {
            $query->where('campaign_id', $filters['campaign_id']);
        }

        return $query->latest()->paginate($perPage);
    }

    public function insert(array $data): bool
    {
        return DB::table('donations')->insert($data);
    }

    public function updateByOrderId(string $orderId, array $data): int
    {
        return DB::table('donations')
            ->where('order_id', $orderId)
            ->where('payment_status', 'pending')
            ->update($data);
    }

    public function bulkUpdateByIds(array $ids, array $data): int
    {
        return Donation::whereIn('id', $ids)->update($data);
    }

    public function bulkUpdateByOrderIds(array $orderIds, array $data): int
    {
        return Donation::whereIn('order_id', $orderIds)->update($data);
    }
}
