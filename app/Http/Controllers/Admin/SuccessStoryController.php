<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuccessStoryController extends Controller
{
    public function index(Request $request): View
    {
        $query = Campaign::completed()
            ->with('category:id,name,slug')
            ->when($request->search, function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%');
            })
            ->when($request->filled('featured'), function ($q) use ($request) {
                $q->where('is_featured', $request->featured === '1');
            })
            ->orderByDesc('is_featured')
            ->orderByDesc('raised_amount');

        $campaigns = $query->paginate(20)->withQueryString();

        $stats = [
            'completed' => Campaign::completed()->count(),
            'featured' => Campaign::completed()->where('is_featured', true)->count(),
        ];

        return view('admin.success-stories.index', compact('campaigns', 'stats'));
    }

    public function toggleFeatured(Request $request, Campaign $campaign): RedirectResponse
    {
        $request->validate([
            'is_featured' => 'required|boolean',
        ]);

        $campaign->update([
            'is_featured' => (bool) $request->input('is_featured'),
        ]);

        return back()->with('success',
            $campaign->is_featured ? 'Marked as a featured success story.' : 'Removed from featured success stories.'
        );
    }
}
