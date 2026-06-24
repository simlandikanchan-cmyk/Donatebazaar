<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicEventController extends Controller
{
    /**
     * Public events listing — category filter tabs + event cards grid
     * Route: GET /events
     */
    public function index(): View
    {
        // Only show active events that haven't ended
        $events = Event::with(['campaign.category'])
            ->where('status', Event::STATUS_ACTIVE)
            ->whereDate('event_date', '>=', now()->toDateString())
            ->orderBy('event_date', 'asc')
            ->get();

        // Group events by campaign category_id
        $eventsByCategory = $events->groupBy(fn($e) => $e->campaign?->category?->id)
            ->filter(fn($group, $catId) => $catId !== null); // drop events with no category

        // Fetch only categories that actually have events
        $categoryIds = $eventsByCategory->keys();
        $categories  = Category::whereIn('id', $categoryIds)
            ->orderBy('name')
            ->get();

        $totalEvents  = $events->count();
        $activeEvents = $events->where('status', Event::STATUS_ACTIVE)->count();

        return view('events.index', compact(
            'events',
            'eventsByCategory',
            'categories',
            'totalEvents',
            'activeEvents',
        ));
    }
}