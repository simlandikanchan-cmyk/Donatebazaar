<?php

namespace App\Repositories;

use App\Models\Campaign;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CampaignRepository
{
    public function findById(int $id): ?Campaign
    {
        return Campaign::find($id);
    }

    public function findWithCategory(int $id): ?Campaign
    {
        return Campaign::with('category:id,name,slug')
            ->find($id);
    }

    public function getUserCampaigns(int $userId, int $perPage = 20): LengthAwarePaginator
    {
        return Campaign::where('user_id', $userId)
            ->with('category:id,name')
            ->withCount('donations')
            ->latest()
            ->paginate($perPage);
    }

    public function getUserMonthlyData(int $userId): Collection
    {
        return Cache::remember("dashboard.monthly_data.{$userId}", 300, function () use ($userId) {
            return Campaign::where('user_id', $userId)
                ->whereYear('created_at', now()->year)
                ->selectRaw('MONTH(created_at) as month, SUM(raised_amount) as total')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');
        });
    }

    public function getTopCampaign(int $userId): ?Campaign
    {
        return Cache::remember("dashboard.top_campaign.{$userId}", 300, function () use ($userId) {
            return Campaign::where('user_id', $userId)
                ->where('campaign_state', 'active')
                ->with('category:id,name')
                ->withCount('donations')
                ->orderByDesc('raised_amount')
                ->first();
        });
    }

    public function countByUserAndStates(int $userId, array $states): int
    {
        return Campaign::where('user_id', $userId)
            ->whereIn('campaign_state', $states)
            ->count();
    }

    public function sumRaisedByUser(int $userId): float
    {
        return (float) Campaign::where('user_id', $userId)->sum('raised_amount');
    }

    public function countByUser(int $userId): int
    {
        return Campaign::where('user_id', $userId)->count();
    }

    public function countByState(string $state): int
    {
        return Campaign::where('campaign_state', $state)->count();
    }

    public function getAdminPaginated(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = Campaign::with(['user:id,name,email,phone', 'category:id,name']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', "%{$search}%"));
            });
        }

        if (!empty($filters['state'])) {
            $query->where('campaign_state', $filters['state']);
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        return $query->latest()->paginate($perPage);
    }

    public function getAdminStateCounts(): array
    {
        return [
            'active' => Campaign::active()->count(),
            'pending' => Campaign::pending()->count(),
            'paused' => Campaign::paused()->count(),
            'rejected' => Campaign::rejected()->count(),
            'expired' => Campaign::expired()->count(),
            'completed' => Campaign::completed()->count(),
            'draft' => Campaign::draft()->count(),
        ];
    }

    public function getAdminDashboardStats(): array
    {
        $stateCounts = Campaign::selectRaw("campaign_state, COUNT(*) as count")
            ->groupBy('campaign_state')
            ->pluck('count', 'campaign_state')
            ->toArray();

        $monthlyTrend = Campaign::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total, SUM(campaign_state = 'active') as active")
            ->groupByRaw("DATE_FORMAT(created_at, '%Y-%m')")
            ->orderBy('month')
            ->take(12)
            ->get();

        return compact('stateCounts', 'monthlyTrend');
    }

    public function getPublicCampaigns(array $filters, int $perPage = 12): LengthAwarePaginator
    {
        $query = Campaign::with('category:id,name,slug')
            ->withCount('donations')
            ->active()
            ->latest();

        if (!empty($filters['category'])) {
            $query->whereHas('category', fn($q) => $q->where('slug', $filters['category']));
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['location'])) {
            $query->where('location', 'like', "%{$filters['location']}%");
        }

        return $query->paginate($perPage);
    }

    public function getActiveCampaignsForHomepage(int $limit = 9): Collection
    {
        return Campaign::with('category')
            ->active()
            ->withCount('donations')
            ->latest()
            ->take($limit)
            ->get();
    }

    public function getFeaturedCampaigns(int $limit = 6): Collection
    {
        return Campaign::with('category')
            ->active()
            ->where('is_featured', true)
            ->withCount('donations')
            ->latest()
            ->take($limit)
            ->get();
    }

    public function getByCategory(string $slug, int $perPage = 12): LengthAwarePaginator
    {
        return Campaign::with('category:id,name,slug')
            ->withCount('donations')
            ->active()
            ->whereHas('category', fn($q) => $q->where('slug', $slug))
            ->latest()
            ->paginate($perPage);
    }

    public function getUniqueLocations(): array
    {
        return Campaign::active()
            ->whereNotNull('location')
            ->selectRaw('DISTINCT location')
            ->pluck('location')
            ->toArray();
    }

    public function countActiveByUser(int $userId): int
    {
        return Campaign::where('user_id', $userId)
            ->where('campaign_state', Campaign::STATE_ACTIVE)
            ->count();
    }

    public function countCompletedByUser(int $userId): int
    {
        return Campaign::where('user_id', $userId)
            ->where('campaign_state', Campaign::STATE_COMPLETED)
            ->count();
    }

    public function scopeCampaignsByUser($query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }
}
