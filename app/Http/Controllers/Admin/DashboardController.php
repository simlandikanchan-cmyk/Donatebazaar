<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Campaign-state query scope shared by the page and the AJAX grid.
     */
    private function scopeState($query, string $state)
    {
        $startOfDay = now()->startOfDay();

        switch ($state) {
            case 'pending':
                return $query->where('campaign_state', 'pending');
            case 'active':
                return $query->where('campaign_state', 'active')
                    ->where(function ($q) use ($startOfDay) {
                        $q->whereNull('end_date')->orWhere('end_date', '>=', $startOfDay);
                    });
            case 'paused':
                return $query->where('campaign_state', 'paused');
            case 'rejected':
                return $query->where('campaign_state', 'rejected');
            case 'inactive':
                return $query->where(function ($q) use ($startOfDay) {
                    $q->whereIn('campaign_state', ['expired', 'completed'])
                      ->orWhere(function ($q2) use ($startOfDay) {
                          $q2->where('campaign_state', 'active')
                             ->whereNotNull('end_date')
                             ->where('end_date', '<', $startOfDay);
                      });
                });
            default: // 'all'
                return $query;
        }
    }

    /**
     * Compute the status counts used by the stat cards, filter tabs and doughnut.
     */
    private function computeCounts(): array
    {
        $startOfDay = now()->startOfDay();

        $cntPending   = Campaign::where('campaign_state', 'pending')->count();
        $cntPaused    = Campaign::where('campaign_state', 'paused')->count();
        $cntRejected  = Campaign::where('campaign_state', 'rejected')->count();
        $cntCompleted = Campaign::where('campaign_state', 'completed')->count();

        $cntActive = Campaign::where('campaign_state', 'active')
            ->where(function ($q) use ($startOfDay) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $startOfDay);
            })->count();

        $cntExpired = Campaign::where('campaign_state', 'expired')
            ->orWhere(function ($q) use ($startOfDay) {
                $q->where('campaign_state', 'active')
                  ->whereNotNull('end_date')
                  ->where('end_date', '<', $startOfDay);
            })->count();

        $totalCampaigns = Campaign::count();
        $approvalRate = $totalCampaigns > 0 ? round(($cntActive / $totalCampaigns) * 100) : 0;

        return compact(
            'totalCampaigns', 'cntPending', 'cntActive', 'cntPaused',
            'cntExpired', 'cntRejected', 'cntCompleted', 'approvalRate'
        );
    }

    public function index()
    {
        $counts = $this->computeCounts();
        extract($counts);

        // Initial grid shows the Active (+ Paused) list, matching the default tab.
        $activeCampaigns = $this->scopeState(
            Campaign::with('user', 'category')->whereIn('campaign_state', ['active', 'paused']),
            'active'
        )->latest()->paginate(12, ['*'], 'cpage');

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

        return view('admin.dashboard', array_merge($counts, compact(
            'activeCampaigns',
            'chartLabels',
            'chartTotal',
            'chartActive'
        )));
    }

    /**
     * AJAX endpoint powering the server-driven campaign grid
     * (filter / search / sort / pagination across every state).
     */
    public function campaigns(Request $request)
    {
        $state  = $request->input('state', 'active');
        $search = trim((string) $request->input('search', ''));
        $sort   = $request->input('sort', '');

        $query = Campaign::with('user', 'category');
        $query = $this->scopeState($query, $state);

        if ($search !== '') {
            $query->where('title', 'like', '%' . $search . '%');
        }

        switch ($sort) {
            case 'amount-desc':
                $query->orderByDesc('goal_amount');
                break;
            case 'amount-asc':
                $query->orderBy('goal_amount');
                break;
            case 'date-asc':
                $query->oldest();
                break;
            case 'date-desc':
            default:
                $query->latest();
                break;
        }

        $perPage = 12;
        $campaigns = $query->paginate($perPage, ['*'], 'cpage');

        $cards = view('admin._campaign_cards', compact('campaigns'))->render();
        $pagination = $campaigns->hasPages()
            ? $campaigns->links('vendor.pagination.admin')->render()
            : '';

        return response()->json([
            'cards'      => $cards,
            'pagination' => $pagination,
            'total'      => $campaigns->total(),
            'counts'     => $this->computeCounts(),
        ]);
    }
}