<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;

class DashboardController extends Controller
{
    public function index()
    {
        // ─────────────────────────────────────────────────────────
        // Counts
        // ─────────────────────────────────────────────────────────

        $totalCampaigns = Campaign::count();

        $cntPending   = Campaign::where('campaign_state', 'pending')->count();
        $cntPaused    = Campaign::where('campaign_state', 'paused')->count();
        $cntRejected  = Campaign::where('campaign_state', 'rejected')->count();
        $cntCompleted = Campaign::where('campaign_state', 'completed')->count();

        // Active = state is active AND not yet expired by date
        // FIX (30-06-2026): end_date has no time component, so comparing
        // against now() (which includes the current time) marked campaigns
        // as expired hours before their end_date calendar day actually
        // ended. Using now()->startOfDay() keeps the campaign active
        // through the entire end_date day.
        $cntActive = Campaign::where('campaign_state', 'active')
            ->where(function ($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now()->startOfDay());
            })->count();

        // Expired = state is 'expired' OR state is 'active' but end_date has passed
        $cntExpired = Campaign::where('campaign_state', 'expired')
            ->orWhere(function ($q) {
                $q->where('campaign_state', 'active')
                  ->whereNotNull('end_date')
                  ->where('end_date', '<', now()->startOfDay());
            })->count();

        // ─────────────────────────────────────────────────────────
        // Campaign Lists
        // ─────────────────────────────────────────────────────────

        $pendingCampaigns = Campaign::with('user', 'category')
            ->where('campaign_state', 'pending')
            ->latest()
            ->get();

        // Active + Paused — exclude date-expired campaigns
        $activeCampaigns = Campaign::with('user', 'category')
            ->whereIn('campaign_state', ['active', 'paused'])
            ->where(function ($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now()->startOfDay());
            })
            ->latest()
            ->paginate(12, ['*'], 'active_page');

        $rejectedCampaigns = Campaign::with('user', 'category')
            ->where('campaign_state', 'rejected')
            ->latest()
            ->get();

        // Inactive = expired state + completed state + active-but-date-passed
        $inactiveCampaigns = Campaign::with('user', 'category')
            ->where(function ($q) {
                $q->whereIn('campaign_state', ['expired', 'completed'])
                  ->orWhere(function ($q2) {
                      $q2->where('campaign_state', 'active')
                         ->whereNotNull('end_date')
                         ->where('end_date', '<', now()->startOfDay());
                  });
            })
            ->latest()
            ->get();

        // ─────────────────────────────────────────────────────────
        // Monthly Chart
        // ─────────────────────────────────────────────────────────

        $monthlyData = Campaign::selectRaw("
                DATE_FORMAT(created_at, '%Y-%m') as month,
                COUNT(*) as total,
                SUM(campaign_state = 'active') as active
            ")
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupByRaw("DATE_FORMAT(created_at, '%Y-%m')")
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $chartLabels = $chartTotal = $chartActive = [];

        for ($i = 11; $i >= 0; $i--) {
            $key = now()->subMonths($i)->format('Y-m');
            $row = $monthlyData->get($key);

            $chartLabels[] = now()->subMonths($i)->format('M');
            $chartTotal[]  = $row ? (int) $row->total : 0;
            $chartActive[] = $row ? (int) $row->active : 0;
        }

        // ─────────────────────────────────────────────────────────
        // Return
        // ─────────────────────────────────────────────────────────

        return view('admin.dashboard', compact(
            'totalCampaigns',
            'cntPending',
            'cntActive',
            'cntPaused',
            'cntExpired',
            'cntRejected',
            'cntCompleted',
            'pendingCampaigns',
            'activeCampaigns',
            'rejectedCampaigns',
            'inactiveCampaigns',
            'chartLabels',
            'chartTotal',
            'chartActive'
        ));
    }
}