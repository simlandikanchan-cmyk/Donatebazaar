<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::where('user_id', Auth::id())
            ->latest()
            ->select(
                'id',
                'title',
                'goal_amount',
                'raised_amount',
                'campaign_state',
                'cover_image',
                'rejection_reason',
                'pause_reason'
            )
            ->get();

        // Monthly fundraising data for the chart (current year)
        $monthlyData = Campaign::where('user_id', Auth::id())
            ->whereYear('created_at', now()->year)
            ->get()
            ->groupBy(fn($c) => $c->created_at->format('M'))
            ->map(fn($group) => $group->sum('raised_amount'))
            ->toArray();

        // Fill in all 12 months
        $allMonths = collect([
            'Jan','Feb','Mar','Apr','May','Jun',
            'Jul','Aug','Sep','Oct','Nov','Dec',
        ])->mapWithKeys(fn($m) => [$m => $monthlyData[$m] ?? 0])->toArray();

        return view('user.dashboard', compact('campaigns', 'allMonths'))
            ->with('monthlyData', $allMonths);
    }
}