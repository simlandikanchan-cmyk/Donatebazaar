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
            ->withSum('donations', 'amount')
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
                $raised = (float) ($campaign->donations_sum_amount ?? $campaign->raised_amount ?? 0);
                $goal   = (float) ($campaign->goal_amount > 0 ? $campaign->goal_amount : 1);
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
                    'id'          => $campaign->id,
                    'title'       => $campaign->title,
                    'slug'        => $campaign->slug,
                    'category'    => $campaign->category?->slug,
                    'description' => $campaign->description,
                    'image'       => $campaign->cover_image
                                        ? asset('storage/' . $campaign->cover_image)
                                        : asset('images/placeholder-relief.jpg'),
                    'location'    => $campaign->location ?? 'India',
                    'raised'      => $raised,
                    'goal'        => $campaign->goal_amount,
                    'percent'     => min(100, round(($raised / $goal) * 100)),
                    'donors'      => $donors,
                    'days_left'   => $daysLeft,
                    'urgency'     => $urgency,
                ];
            });

        // CSR Partners — static until a partners table exists.
        $csrPartners = [
            ['name' => 'Tata Trusts',           'logo' => asset('images/partners/tata.png')],
            ['name' => 'Infosys Foundation',    'logo' => asset('images/partners/infosys.png')],
            ['name' => 'Wipro Cares',           'logo' => asset('images/partners/wipro.png')],
            ['name' => 'HCL Foundation',        'logo' => asset('images/partners/hcl.png')],
            ['name' => 'Reliance Foundation',   'logo' => asset('images/partners/reliance.png')],
            ['name' => 'Azim Premji Foundation','logo' => asset('images/partners/azim-premji.png')],
            ['name' => 'HDFC Bank CSR',         'logo' => asset('images/partners/hdfc.png')],
            ['name' => 'Mahindra Rise',         'logo' => asset('images/partners/mahindra.png')],
            ['name' => 'Godrej & Boyce',        'logo' => asset('images/partners/godrej.png')],
            ['name' => 'Bajaj CSR',             'logo' => asset('images/partners/bajaj.png')],
        ];

        return [
            'disasterCampaigns' => $disasterCampaigns,
            'csrPartners'       => $csrPartners,
            'totalRaised'       => $disasterCampaigns->sum('raised'),
            'totalDonors'       => $disasterCampaigns->sum('donors'),
            'activeCamps'       => $disasterCampaigns->count(),
        ];
    }
}