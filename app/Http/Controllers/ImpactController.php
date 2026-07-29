<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Repositories\CampaignRepository;

class ImpactController extends Controller
{
    public function __construct(
        private CampaignRepository $campaignRepo,
    ) {}

    public function index()
    {
        $completedCampaigns = Campaign::where('campaign_state', Campaign::STATE_COMPLETED)
            ->with('category:id,name,slug')
            ->latest('updated_at')
            ->paginate(12);

        $totalRaised = Campaign::where('campaign_state', Campaign::STATE_COMPLETED)
            ->sum('raised_amount');

        $totalCampaigns = Campaign::where('campaign_state', Campaign::STATE_COMPLETED)
            ->count();

        $totalDonors = Donation::whereHas('campaign', function ($q) {
            $q->where('campaign_state', Campaign::STATE_COMPLETED);
        })
            ->distinct('user_id')
            ->count('user_id');

        $livesImpacted = (int) ($totalRaised / 500);

        $statesCovered = count($this->campaignRepo->getUniqueLocations());

        $featured = Campaign::where('campaign_state', Campaign::STATE_COMPLETED)
            ->with('category:id,name,slug')
            ->orderByDesc('is_featured')
            ->orderByDesc('raised_amount')
            ->first();

        return view('impact.index', compact(
            'completedCampaigns',
            'totalRaised',
            'totalCampaigns',
            'totalDonors',
            'livesImpacted',
            'statesCovered',
            'featured',
        ));
    }
}
