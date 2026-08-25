<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\RecurringDonation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PublicCampaignController extends Controller
{
    public function publicCampaigns(Request $request)
    {
        $query = Campaign::with(['category', 'user'])
            ->withCount(['donations' => fn ($q) => $q->where('payment_status', 'completed')])
            ->withSum(['donations as donations_sum_total_amount' => fn ($q) => $q->where('payment_status', 'completed')], 'total_amount')
            ->whereIn('campaign_state', ['active', 'completed']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                    ->orWhere('description', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)
                ->select('id')
                ->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        if ($request->filled('location') && $request->location !== 'all') {
            $locationLabel = ucwords(str_replace('_', ' ', $request->location));
            $query->where(function ($q) use ($locationLabel) {
                $q->where('location', 'like', "%{$locationLabel}%")
                    ->orWhere('location_text', 'like', "%{$locationLabel}%");
            });
        }

        if ($request->filled('campaign_type') && $request->campaign_type !== 'all') {
            switch ($request->campaign_type) {
                case 'active':
                    $query->where('campaign_state', 'active');
                    break;
                case 'closed':
                    $query->where('campaign_state', 'completed');
                    break;
                case 'urgent':
                    $query->where('is_urgent', true);
                    break;
                case 'newly_launched':
                    $query->where('created_at', '>=', now()->subDays(7));
                    break;
                case 'most_raised':
                    $query->orderByDesc('raised_amount');
                    break;
            }
        }

        if ($request->filled('funding') && $request->funding !== 'any') {
            switch ($request->funding) {
                case 'lt25':
                    $query->whereRaw('(raised_amount / NULLIF(goal_amount, 0)) * 100 < 25');
                    break;
                case '25to75':
                    $query->whereRaw('(raised_amount / NULLIF(goal_amount, 0)) * 100 BETWEEN 25 AND 75');
                    break;
                case 'gt75':
                    $query->whereRaw('(raised_amount / NULLIF(goal_amount, 0)) * 100 > 75');
                    break;
                case '100':
                    $query->where('goal_amount', '>', 0)->whereColumn('raised_amount', '>=', 'goal_amount');
                    break;
            }
        }

        switch ($request->sort) {
            case 'most_funded': $query->orderByDesc('raised_amount');
                break;
            case 'most_donors': $query->orderByDesc('donations_count');
                break;
            case 'ending_soon': $query->orderBy('end_date');
                break;
            default:            $query->latest();
                break;
        }

        $categories = Cache::remember('active_campaign_categories', 3600, function () {
            return Category::where('is_active', 1)
                ->withCount(['campaigns' => function ($q) {
                    $q->whereIn('campaign_state', ['active', 'completed']);
                }])
                ->get();
        });

        $campaigns = $query->paginate(12)->withQueryString();

        return view('campaigns.all-campaigns', compact('campaigns', 'categories'));
    }

    public function byCategory($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $campaigns = Campaign::with(['category', 'user', 'products'])
            ->withCount(['donations' => fn ($q) => $q->where('payment_status', 'completed')])
            ->withSum(['donations' => fn ($q) => $q->where('payment_status', 'completed')], 'total_amount')
            ->where('category_id', $category->id)
            ->whereIn('campaign_state', ['active', 'completed'])
            ->latest()
            ->paginate(12);

        return view('campaigns.all-campaigns', compact('category', 'campaigns'));
    }

    public function toggleFollow(Campaign $campaign)
    {
        $user = Auth::user();

        if ($campaign->isFollowedBy($user)) {
            $campaign->unfollow($user);
            $message = 'You unfollowed this campaign.';
        } else {
            $campaign->follow($user);
            $message = 'You are now following this campaign — you\'ll be notified about new events.';
        }

        // The public campaign page is cached per user — clear it so the
        // Follow button state and follower count update immediately.
        $categorySlug = optional($campaign->category)->slug;
        Cache::forget("campaign:show:{$categorySlug}:{$campaign->slug}:" . $user->id);
        Cache::forget("campaign:show:{$categorySlug}:{$campaign->slug}:guest");

        return back()->with('success', $message);
    }

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

        $activeSub = null;
        if (Auth::check()) {
            $activeSub = RecurringDonation::where('user_id', Auth::id())
                ->where('campaign_id', $campaign->id)
                ->where('status', 'active')
                ->first();
        }

        $followerCount = $campaign->followers_count ?? 0;
        $isFollowing = Auth::check() ? $campaign->followers->contains('id', Auth::id()) : false;

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

        return view('public.show', compact('campaign', 'moneyRaised', 'productRaised', 'activeSub', 'followerCount', 'isFollowing'));
    }
}