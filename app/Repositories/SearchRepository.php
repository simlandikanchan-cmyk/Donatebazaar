<?php

namespace App\Repositories;

use App\Models\Blog;
use App\Models\Campaign;
use App\Models\Event;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SearchRepository
{
    public function searchCampaigns(string $query, int $perPage = 20): LengthAwarePaginator
    {
        return Campaign::active()
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('short_description', 'like', "%{$query}%");
            })
            ->with('category:id,name,slug')
            ->withCount('donations')
            ->paginate($perPage);
    }

    public function searchBlogs(string $query, int $perPage = 20): LengthAwarePaginator
    {
        return Blog::published()
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('content', 'like', "%{$query}%");
            })
            ->with('author:id,name')
            ->latest('published_at')
            ->paginate($perPage);
    }

    public function searchEvents(string $query, int $perPage = 20): LengthAwarePaginator
    {
        return Event::where('status', 'active')
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->latest('event_date')
            ->paginate($perPage);
    }

    public function globalSearch(string $query): array
    {
        $campaigns = Campaign::active()
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('short_description', 'like', "%{$query}%");
            })
            ->with('category:id,name,slug')
            ->withCount('donations')
            ->take(5)
            ->get();

        $blogs = Blog::published()
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('content', 'like', "%{$query}%");
            })
            ->with('author:id,name')
            ->take(5)
            ->get();

        $events = Event::where('status', 'active')
            ->where('event_date', '>=', now())
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->take(5)
            ->get();

        return compact('campaigns', 'blogs', 'events');
    }
}
