<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\Blog;

class HomeController extends Controller
{
    public function index()
    {
        // Active campaigns (non-expired)
        // - raised_amount is already a cached column on campaigns (kept in sync
        //   elsewhere when donations complete) — read it directly instead of
        //   recomputing via withSum, so it stays consistent with
        //   total_settled / pending_settlement / platform_earnings.
        // - donors_count still has to be computed from donations (no cached column
        //   for it on campaigns).
        // - user.kycVerification eager-loaded so the Blade can check the *user's*
        //   KYC status (kyc_verifications table is per-user, not per-campaign).
        //   Requires a `kycVerification()` relation on the User model — see note below.
        // - Ordering: is_featured campaigns surface first (matches the section's
        //   "Featured Campaigns" name); falls back to latest active campaigns so
        //   the section is never empty if nothing has been marked featured yet.
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

        // Categories with active campaign count
        // (date condition matches the $campaigns query above exactly:
        //  no-expiry campaigns count, and today still counts as active)
        $categories = Category::active()
            ->withCount(['campaigns' => function ($query) {
                $query->where('campaign_state', 'active')
                      ->where(function ($q) {
                          $q->whereNull('end_date')
                            ->orWhereDate('end_date', '>=', now());
                      });
            }])
            ->get();

        // Latest published blogs for homepage carousel
        $latestBlogs = Blog::with(['author', 'category'])
            ->where('status', 'published')
            ->latest('published_at')
            ->take(6)
            ->get();

        return view('home.index', compact('campaigns', 'categories', 'latestBlogs'));
    }
}