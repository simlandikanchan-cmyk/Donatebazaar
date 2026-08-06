<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Cache\Store;

class PublicCampaignController extends Controller
{
    public function show($category, $slug)
    {
        $cacheKey = "campaign:show:{$category}:{$slug}:" . (auth()->id() ?? 'guest');
        $tags = ["campaign:{$slug}"];

        try {
            $campaign = Cache::tags($tags)->remember($cacheKey, 300, function () use ($category, $slug) {
                return Campaign::with([
                    'category',
                    'user',
                    'donations' => function ($q) {
                        $q->where('payment_status', 'completed')
                            ->latest()
                            ->take(10);
                    },
                    'donations.user',
                    'products' => function ($q) {
                        $q->where('is_active', 1)->where('approval_status', 'approved');
                    },
                    'products.categoryProduct',
                    'products.reservations',
                    'updates',
                    'followers' => fn ($q) => $q->take(100),
                    'events' => function ($q) {
                        $q->where('status', 'active')
                            ->where('show_on_campaign', true)
                            ->where('event_date', '>=', now()->toDateString())
                            ->orderBy('event_date')
                            ->take(6);
                    },
                ])
                    ->withSum('donations', 'total_amount')
                    ->withCount('followers')
                    ->where('slug', $slug)
                    ->where(function ($q) use ($category) {
                        $q->whereHas('category', fn ($q) => $q->where('slug', $category))
                            ->orWhereNull('category_id');
                    })
                    ->firstOrFail();
            });
        } catch (\Throwable $e) {
            $campaign = Cache::remember($cacheKey, 300, function () use ($category, $slug) {
                return Campaign::with([
                    'category',
                    'user',
                    'donations' => function ($q) {
                        $q->where('payment_status', 'completed')
                            ->latest()
                            ->take(10);
                    },
                    'donations.user',
                    'products' => function ($q) {
                        $q->where('is_active', 1)->where('approval_status', 'approved');
                    },
                    'products.categoryProduct',
                    'products.reservations',
                    'updates',
                    'followers' => fn ($q) => $q->take(100),
                    'events' => function ($q) {
                        $q->where('status', 'active')
                            ->where('show_on_campaign', true)
                            ->where('event_date', '>=', now()->toDateString())
                            ->orderBy('event_date')
                            ->take(6);
                    },
                ])
                    ->withSum('donations', 'total_amount')
                    ->withCount('followers')
                    ->where('slug', $slug)
                    ->where(function ($q) use ($category) {
                        $q->whereHas('category', fn ($q) => $q->where('slug', $category))
                            ->orWhereNull('category_id');
                    })
                    ->firstOrFail();
            });
        }

        $moneyRaised = (float) $campaign->donations
            ->where('donation_type', 'money')
            ->sum('total_amount');

        $productRaised = (float) $campaign->donations
            ->where('donation_type', 'product')
            ->sum('total_amount');

        /*
        |----------------------------------------------------------------------
        | CRITICAL FIX — real-time expiry check
        | The donate button was redirecting to /all-campaigns because
        | backToCampaign() uses $campaign->slug, but more importantly
        | the campaign_state was 'active' even though end_date had passed.
        | This forces the correct state before the view renders.
        |
        | FIX (30-06-2026): end_date has no time component, so Carbon was
        | treating it as midnight (00:00:00) and marking campaigns expired
        | hours before the day actually ended. Using endOfDay() so the
        | campaign stays active through the entire end_date calendar day.
        |----------------------------------------------------------------------
        */
        if (
            $campaign->campaign_state === 'active' &&
            $campaign->end_date &&
            Carbon::parse($campaign->end_date)->endOfDay()->isPast()
        ) {
            $campaign->update(['campaign_state' => 'expired']);
            $campaign->campaign_state = 'expired';
            Cache::forget($cacheKey);
        }

        /*
        |----------------------------------------------------------------------
        | Only show active campaigns to the public
        | Adjust states as needed for your business logic
        |----------------------------------------------------------------------
        */
        if (! in_array($campaign->campaign_state, [
            'active',
            'completed',
            'expired',
        ])) {
            abort(404);
        }

        return view('public.show', compact('campaign', 'moneyRaised', 'productRaised'));
    }
}
