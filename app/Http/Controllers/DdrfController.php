<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DdrfController extends Controller
{
    public function index()
    {
        // Cache the whole computed payload for 5 minutes — this page gets
        // heavy traffic and the underlying query (campaigns + donation counts)
        // is expensive to recompute on every request.
        $data = Cache::remember('ddrf.page_data', 300, function () {
            return $this->buildPageData();
        });

        return view('ddrf', $data);
    }

    /**
     * Build all data needed for the DDRF landing page.
     */
    protected function buildPageData(): array
    {
        $disasterCampaigns = Campaign::where('campaign_state', 'active')
            ->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            })
            ->whereHas('category', function ($q) {
                $q->where('slug', 'disaster-relief');
            })
            // withCount/withSum run as a single efficient aggregate query
            // (COUNT/SUM at the DB level) instead of loading every donation
            // row into PHP memory just to count or sum them.
            ->withCount('donations')
            ->withSum('donations', 'total_amount')
            ->with(['category:id,slug,name'])
            ->select([
                'id', 'title', 'slug', 'description', 'cover_image',
                'location', 'goal_amount', 'raised_amount',
                'end_date', 'category_id',
            ])
            ->latest()
            ->get()
            ->map(function ($campaign) {
                // Prefer the live sum of actual donations over the
                // denormalized raised_amount column, falling back to it
                // only if there are no donation rows yet (e.g. brand new
                // campaign where the sum is null).
                $raised = (float) ($campaign->donations_sum_total_amount ?? $campaign->raised_amount ?? 0);
                $goal = (float) ($campaign->goal_amount > 0 ? $campaign->goal_amount : 1);
                $donors = (int) ($campaign->donations_count ?? 0);

                $daysLeft = $campaign->end_date
                    ? max(0, now()->diffInDays($campaign->end_date, false))
                    : null;

                $urgency = 'active';
                if ($daysLeft !== null) {
                    if ($daysLeft <= 3) {
                        $urgency = 'critical';
                    } elseif ($daysLeft <= 7) {
                        $urgency = 'urgent';
                    }
                }

                return [
                    'id' => $campaign->id,
                    'title' => $campaign->title,
                    'slug' => $campaign->slug,
                    'category' => $campaign->category?->slug,
                    'description' => $campaign->description,
                    'image' => $campaign->cover_image
                                        ? asset('storage/'.$campaign->cover_image)
                                        : asset('images/placeholder-relief.jpg'),
                    'location' => $campaign->location ?? 'India',
                    'raised' => $raised,
                    'goal' => $campaign->goal_amount,
                    'percent' => min(100, round(($raised / $goal) * 100)),
                    'donors' => $donors,
                    'days_left' => $daysLeft,
                    'urgency' => $urgency,
                ];
            });

        // CSR Partners — static until a partners table exists.
        $csrPartners = [
            ['name' => 'Tata Trusts',           'logo' => 'https://upload.wikimedia.org/wikipedia/commons/8/8e/Tata_logo.svg'],
            ['name' => 'Infosys Foundation',    'logo' => 'https://upload.wikimedia.org/wikipedia/commons/9/95/Infosys_logo.svg'],
            ['name' => 'Wipro Cares',           'logo' => 'https://upload.wikimedia.org/wikipedia/commons/8/80/Wipro_Logo_Black.svg'],
            ['name' => 'HCL Foundation',        'logo' => 'https://upload.wikimedia.org/wikipedia/commons/8/8a/HCL_Technologies_logo.svg'],
            ['name' => 'Reliance Foundation',   'logo' => 'https://upload.wikimedia.org/wikipedia/commons/c/ca/Reliance_Foundation_Logo.svg'],
            ['name' => 'Azim Premji Foundation', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/c/cb/PremjiInvestLogo.png'],
            ['name' => 'HDFC Bank CSR',         'logo' => 'https://upload.wikimedia.org/wikipedia/commons/2/28/HDFC_Bank_Logo.svg'],
            ['name' => 'Mahindra Rise',         'logo' => 'https://upload.wikimedia.org/wikipedia/commons/d/da/Mahindra_Automotive_new_logo.png'],
            ['name' => 'Godrej & Boyce',        'logo' => 'https://upload.wikimedia.org/wikipedia/commons/9/9c/Godrej_Industries_Group_Logo.png'],
            ['name' => 'Bajaj CSR',             'logo' => 'https://upload.wikimedia.org/wikipedia/commons/7/7e/Bajaj_Group_logo.svg'],
        ];

        return [
            'disasterCampaigns' => $disasterCampaigns,
            'csrPartners' => $csrPartners,
            'totalRaised' => $disasterCampaigns->sum('raised'),
            'totalDonors' => $disasterCampaigns->sum('donors'),
            'activeCamps' => $disasterCampaigns->count(),
        ];
    }
}
