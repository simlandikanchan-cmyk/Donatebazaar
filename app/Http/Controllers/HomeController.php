<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Campaign;
use App\Models\Category;
use App\Repositories\CampaignRepository;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function __construct(
        private CampaignRepository $campaignRepo,
    ) {}

    public function index()
    {
        $campaigns = Cache::remember('homepage.active_campaigns', 120, function () {
            return Campaign::with([
                'user:id,name,avatar',
                'user.kycVerification',
                'category',
            ])
                ->withCount(['donations as donors_count' => function ($q) {
                    $q->where('payment_status', 'completed');
                }])
                ->where('campaign_state', 'active')
                ->where(function ($q) {
                    $q->whereNull('end_date')
                        ->orWhereDate('end_date', '>=', now());
                })
                ->orderByDesc('is_featured')
                ->latest()
                ->take(12)
                ->get();
        });

        $categories = Cache::remember('homepage.categories', 300, function () {
            return Category::active()
                ->withCount(['campaigns' => function ($query) {
                    $query->where('campaign_state', 'active')
                        ->where(function ($q) {
                            $q->whereNull('end_date')
                                ->orWhereDate('end_date', '>=', now());
                        });
                }])
                ->get();
        });

        $latestBlogs = Cache::remember('homepage.latest_blogs', 300, function () {
            return Blog::with(['author', 'category'])
                ->where('status', 'published')
                ->latest('published_at')
                ->take(6)
                ->get();
        });

        return view('home.index', compact('campaigns', 'categories', 'latestBlogs'));
    }
}
