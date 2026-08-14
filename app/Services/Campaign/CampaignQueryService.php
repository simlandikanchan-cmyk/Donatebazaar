<?php

namespace App\Services\Campaign;

use App\Models\Campaign;
use App\Models\Category;
use App\Repositories\CampaignRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CampaignQueryService
{
    public function __construct(
        private CampaignRepository $campaignRepository
    ) {}

    public function getAdminList(Request $request): array
    {
        $status = $request->input('status', 'all');
        $search = $request->input('search');
        $sort = $request->input('sort', 'created_at');
        $dir = $request->input('direction', 'desc');

        $allowedSorts = ['title', 'goal_amount', 'raised_amount', 'created_at'];
        if (! in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }
        $dir = $dir === 'asc' ? 'asc' : 'desc';

        $query = Campaign::with(['user:id,name,email', 'category:id,name']);

        if ($status !== 'all') {
            $query->where('campaign_state', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%"));
            });
        }

        $campaigns = $query->orderBy($sort, $dir)->paginate(15);

        return [
            'campaigns' => $campaigns,
            'status' => $status,
            'search' => $search,
            'sort' => $sort,
            'dir' => $dir,
            'cntActive' => Campaign::active()->count(),
            'cntPending' => Campaign::pending()->count(),
            'cntPaused' => Campaign::paused()->count(),
            'cntRejected' => Campaign::rejected()->count(),
            'cntExpired' => Campaign::expired()->count(),
            'cntCompleted' => Campaign::completed()->count(),
        ];
    }

    public function getAdminDetail(Campaign $campaign): Campaign
    {
        return $campaign->load([
            'user.kycVerification',
            'category',
            'events',
            'logs',
        ]);
    }

    public function getEditData(Campaign $campaign): array
    {
        $campaign->load([
            'user',
            'category',
            'events',
            'logs',
        ]);

        $categories = Category::orderBy('name')->get();

        return [
            'campaign' => $campaign,
            'categories' => $categories,
        ];
    }

    public function getQuickViewData(Campaign $campaign): array
    {
        $campaign->load([
            'user.kycVerification',
            'category',
            'events',
            'logs',
        ]);

        return compact('campaign');
    }
}
