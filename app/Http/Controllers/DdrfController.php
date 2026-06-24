<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;

class DdrfController extends Controller
{
    public function index()
    {
        // ── Live disaster relief campaigns from DB ──
        $disasterCampaigns = Campaign::where('campaign_state', 'active')
            ->where(function ($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now());
            })
            ->whereHas('category', function ($q) {
                $q->where('slug', 'disaster-relief');
            })
            ->with(['category', 'donations'])
            ->latest()
            ->get()
            ->map(function ($campaign) {
                $raised   = (float) ($campaign->raised_amount ?? 0);
                $goal     = (float) ($campaign->goal_amount > 0 ? $campaign->goal_amount : 1);
                $donors   = $campaign->donations->count();
                $daysLeft = $campaign->end_date
                    ? max(0, now()->diffInDays($campaign->end_date, false))
                    : null;

                // Auto-detect urgency from days left
                $urgency = 'active';
                if ($daysLeft !== null) {
                    if ($daysLeft <= 3) $urgency = 'critical';
                    elseif ($daysLeft <= 7) $urgency = 'urgent';
                }

                return [
                    'id'          => $campaign->id,
                    'title'       => $campaign->title,
                    'slug'        => $campaign->slug,
                    'category'    => $campaign->category->slug, 
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

        // ── CSR Partners (keep static until you have a partners table) ──
        $csrPartners = [
            ['name' => 'Tata Trusts',          'logo' => asset('images/partners/tata.png')],
            ['name' => 'Infosys Foundation',   'logo' => asset('images/partners/infosys.png')],
            ['name' => 'Wipro Cares',           'logo' => asset('images/partners/wipro.png')],
            ['name' => 'HCL Foundation',        'logo' => asset('images/partners/hcl.png')],
            ['name' => 'Reliance Foundation',   'logo' => asset('images/partners/reliance.png')],
            ['name' => 'Azim Premji Foundation','logo' => asset('images/partners/azim-premji.png')],
            ['name' => 'HDFC Bank CSR',         'logo' => asset('images/partners/hdfc.png')],
            ['name' => 'Mahindra Rise',         'logo' => asset('images/partners/mahindra.png')],
            ['name' => 'Godrej & Boyce',        'logo' => asset('images/partners/godrej.png')],
            ['name' => 'Bajaj CSR',             'logo' => asset('images/partners/bajaj.png')],
        ];

        // ── Hero stat bar — now driven by real data ──
        $totalRaised  = $disasterCampaigns->sum('raised');
        $totalDonors  = $disasterCampaigns->sum('donors');
        $activeCamps  = $disasterCampaigns->count();

        return view('ddrf', compact(
            'disasterCampaigns',
            'csrPartners',
            'totalRaised',
            'totalDonors',
            'activeCamps'
        ));
    }
}