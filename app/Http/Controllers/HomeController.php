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
        $campaigns = Campaign::with(['user', 'category', 'donations'])
            ->where('campaign_state', 'active')
            ->where(function ($q) {
                $q->whereNull('end_date')
                  ->orWhereDate('end_date', '>=', now());
            })
            ->latest()
            ->take(12)
            ->get();

        // Categories with active campaign count
        $categories = Category::active()
            ->withCount(['campaigns' => function ($query) {
                $query->where('campaign_state', 'active')
                      ->whereNotNull('end_date')
                      ->whereDate('end_date', '>', now());
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