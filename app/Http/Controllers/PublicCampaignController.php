<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;

class PublicCampaignController extends Controller
{
    public function show($category, $slug)
    {
        $campaign = Campaign::with([
                'category',
                'user',
                'donations' => function ($q) {
                    $q->where('payment_status', 'completed')
                      ->latest()
                      ->take(10);
                },
                'events',
            ])
            ->where('slug', $slug)
            ->whereHas('category', fn($q) => $q->where('slug', $category))
            ->firstOrFail();

        /*
        |----------------------------------------------------------------------
        | CRITICAL FIX — real-time expiry check
        | The donate button was redirecting to /all-campaigns because
        | backToCampaign() uses $campaign->slug, but more importantly
        | the campaign_state was 'active' even though end_date had passed.
        | This forces the correct state before the view renders.
        |----------------------------------------------------------------------
        */
        if (
            $campaign->campaign_state === 'active' &&
            $campaign->end_date &&
            \Carbon\Carbon::parse($campaign->end_date)->isPast()
        ) {
            // Update DB so next request is instant
            $campaign->update(['campaign_state' => 'expired']);
            $campaign->campaign_state = 'expired';
        }

        /*
        |----------------------------------------------------------------------
        | Only show active campaigns to the public
        | Adjust states as needed for your business logic
        |----------------------------------------------------------------------
        */
        if (!in_array($campaign->campaign_state, [
            'active',
            'completed',
            'expired',
        ])) {
            abort(404);
        }

        return view('public.show', compact('campaign'));
    }
}