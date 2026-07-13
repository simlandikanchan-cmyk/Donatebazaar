<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Campaign;
use App\Models\Event;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');

        if (!$q || trim($q) === '') {
            return view('search.results', [
                'query'    => $q,
                'campaigns' => collect(),
                'blogs'    => collect(),
                'events'   => collect(),
                'total'    => 0,
            ]);
        }

        $q = trim($q);

        $campaigns = Campaign::whereIn('campaign_state', [
                Campaign::STATE_ACTIVE, Campaign::STATE_COMPLETED,
            ])
            ->where(function ($w) use ($q) {
                $w->where('title', 'like', "%{$q}%")
                  ->orWhere('description', 'like', "%{$q}%")
                  ->orWhere('location', 'like', "%{$q}%");
            })
            ->with('category:id,slug')
            ->latest()
            ->take(10)
            ->get();

        $blogs = Blog::public()
            ->where(function ($w) use ($q) {
                $w->where('title', 'like', "%{$q}%")
                  ->orWhere('content', 'like', "%{$q}%")
                  ->orWhere('excerpt', 'like', "%{$q}%");
            })
            ->latest()
            ->take(10)
            ->get();

        $events = Event::whereIn('status', [Event::STATUS_ACTIVE, Event::STATUS_COMPLETED])
            ->where(function ($w) use ($q) {
                $w->where('title', 'like', "%{$q}%")
                  ->orWhere('description', 'like', "%{$q}%")
                  ->orWhere('location', 'like', "%{$q}%");
            })
            ->with('campaign:id,title')
            ->latest('event_date')
            ->take(10)
            ->get();

        $total = $campaigns->count() + $blogs->count() + $events->count();

        return view('search.results', compact('q', 'campaigns', 'blogs', 'events', 'total'));
    }
}
