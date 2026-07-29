<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Campaign;
use App\Models\Category;
use App\Repositories\CampaignRepository;

class HomeController extends Controller
{
    public function __construct(
        private CampaignRepository $campaignRepo,
    ) {}

    public function index()
    {
        $campaigns = Campaign::with([
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

        $categories = Category::active()
            ->withCount(['campaigns' => function ($query) {
                $query->where('campaign_state', 'active')
                    ->where(function ($q) {
                        $q->whereNull('end_date')
                            ->orWhereDate('end_date', '>=', now());
                    });
            }])
            ->get();

        $latestBlogs = Blog::with(['author', 'category'])
            ->where('status', 'published')
            ->latest('published_at')
            ->take(6)
            ->get();

        return view('home.index', compact('campaigns', 'categories', 'latestBlogs'));
    }
}
