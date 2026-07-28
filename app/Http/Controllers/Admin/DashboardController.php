<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignSettlement;
use App\Models\ContactMessage;
use App\Models\Donation;
use App\Models\JobPostApplication;
use App\Models\User;
use App\Models\Volunteer;
use App\Models\VolunteerApplication;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

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
        $counts = Campaign::selectRaw("campaign_state, COUNT(*) as count")
            ->groupBy('campaign_state')
            ->pluck('count', 'campaign_state');

        $totalCampaigns = (int) $counts->sum();
        $cntPending     = (int) ($counts['pending'] ?? 0);
        $cntActive      = (int) ($counts['active'] ?? 0);
        $cntPaused      = (int) ($counts['paused'] ?? 0);
        $cntExpired     = (int) ($counts['expired'] ?? 0);
        $cntRejected    = (int) ($counts['rejected'] ?? 0);
        $cntCompleted   = (int) ($counts['completed'] ?? 0);

        $approvalRate = $totalCampaigns > 0 ? round(($cntActive / $totalCampaigns) * 100) : 0;

        return compact(
            'totalCampaigns', 'cntPending', 'cntActive', 'cntPaused',
            'cntExpired', 'cntRejected', 'cntCompleted', 'approvalRate'
        );
    }

    public function index()
    {
        $stats = Cache::remember('admin_dashboard_stats', 300, function () {
            $counts = $this->computeCounts();

            $volunteerCount = Volunteer::count();
            $pendingVolunteerApps = VolunteerApplication::where('status', 'pending')->count();

            $totalUsers    = User::count();
            $newUsersToday = User::whereDate('created_at', today())->count();

            $totalDonations = Donation::count();
            $donationsToday = Donation::whereDate('created_at', today())->count();
            $totalRevenue   = Donation::sum('total_amount');

            $avgDonation  = $totalDonations > 0 ? (int) round($totalRevenue / $totalDonations) : 0;
            $uniqueDonors = Donation::whereNotNull('user_id')->distinct('user_id')->count('user_id');
            $successRate  = ($counts['totalCampaigns'] ?? 0) > 0
                ? round((Campaign::where('campaign_state', 'completed')->count() / $counts['totalCampaigns']) * 100)
                : 0;

            return array_merge($counts, compact(
                'volunteerCount', 'pendingVolunteerApps',
                'totalUsers', 'newUsersToday',
                'totalDonations', 'donationsToday', 'totalRevenue',
                'avgDonation', 'uniqueDonors', 'successRate'
            ));
        });

        extract($stats);

        // ─────────────────────────────────────────────────────────
        // Pending Actions counts
        // ─────────────────────────────────────────────────────────
        $unreadMessages = ContactMessage::where('is_read', false)->count();
        $pendingJobApplicants = JobPostApplication::where('status', 'pending')->count();

        // ─────────────────────────────────────────────────────────
        // Wallet / Settlement stats
        // ─────────────────────────────────────────────────────────
        $pendingSettlements = CampaignSettlement::where('status', 'pending_approval')->count();
        $totalWalletBalance = Wallet::sum('balance');

        // ─────────────────────────────────────────────────────────
        // Recent Activity — merged timeline
        // ─────────────────────────────────────────────────────────
        $recentDonations = Donation::with('campaign')
            ->where('payment_status', 'completed')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($d) => [
                'type' => 'donation',
                'desc' => '₹'.number_format((float) $d->total_amount).' donation to '.($d->campaign->title ?? 'a campaign'),
                'time' => $d->created_at,
                'link' => route('admin.donations.index'),
            ]);

        $recentCampaigns = Campaign::where('campaign_state', 'pending')
            ->latest()
            ->take(4)
            ->get()
            ->map(fn ($c) => [
                'type' => 'campaign',
                'desc' => 'Campaign "'.Str::limit($c->title, 40).'" submitted for review',
                'time' => $c->created_at,
                'link' => route('admin.campaign.index'),
            ]);

        $recentVolApps = VolunteerApplication::latest()
            ->take(4)
            ->get()
            ->map(fn ($v) => [
                'type' => 'volunteer',
                'desc' => 'New volunteer application received',
                'time' => $v->created_at,
                'link' => route('admin.volunteer_applications.index'),
            ]);

        $recentMessages = ContactMessage::latest()
            ->take(4)
            ->get()
            ->map(fn ($m) => [
                'type' => 'message',
                'desc' => 'Message "'.Str::limit($m->subject ?? '(no subject)', 40).'" received',
                'time' => $m->created_at,
                'link' => route('admin.messages'),
            ]);

        $recentActivity = collect()
            ->merge($recentDonations)
            ->merge($recentCampaigns)
            ->merge($recentVolApps)
            ->merge($recentMessages)
            ->sortByDesc('time')
            ->take(12)
            ->values();

        // Initial grid shows the Active (+ Paused) list, matching the default tab.
        $activeCampaigns = $this->scopeState(
            Campaign::with('user', 'category')->whereIn('campaign_state', ['active', 'paused']),
            'active'
        )->latest()->paginate(12, ['*'], 'cpage');

        // ─────────────────────────────────────────────────────────
        // Monthly Chart (last 6 months)
        // ─────────────────────────────────────────────────────────

        $monthlyData = Campaign::selectRaw("
                DATE_FORMAT(created_at, '%Y-%m') as month,
                COUNT(*) as total,
                SUM(campaign_state = 'active') as active
            ")
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupByRaw("DATE_FORMAT(created_at, '%Y-%m')")
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $chartLabels = $chartTotal = $chartActive = [];

        for ($i = 5; $i >= 0; $i--) {
            $key = now()->subMonths($i)->format('Y-m');
            $row = $monthlyData->get($key);

            $chartLabels[] = now()->subMonths($i)->format('M');
            $chartTotal[] = $row ? (int) $row->total : 0;
            $chartActive[] = $row ? (int) $row->active : 0;
        }

        // ─────────────────────────────────────────────────────────
        // Revenue Trend (last 6 months)
        // ─────────────────────────────────────────────────────────

        $revenueData = Donation::selectRaw("
                DATE_FORMAT(created_at, '%Y-%m') as month,
                SUM(total_amount) as revenue
            ")
            ->where('payment_status', 'completed')
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupByRaw("DATE_FORMAT(created_at, '%Y-%m')")
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $revLabels = $revData = [];

        for ($i = 5; $i >= 0; $i--) {
            $key = now()->subMonths($i)->format('Y-m');
            $row = $revenueData->get($key);

            $revLabels[] = now()->subMonths($i)->format('M');
            $revData[] = $row ? (float) $row->revenue : 0;
        }

        // ─────────────────────────────────────────────────────────
        // Top Campaigns by Funds Raised
        // ─────────────────────────────────────────────────────────

        $topCampaigns = Campaign::where('raised_amount', '>', 0)
            ->orderByDesc('raised_amount')
            ->take(5)
            ->get(['title', 'raised_amount']);

        $topCampLabels = $topCampValues = [];

        foreach ($topCampaigns as $c) {
            $topCampLabels[] = Str::limit($c->title, 28);
            $topCampValues[] = (float) $c->raised_amount;
        }

        $topCampLabels = array_reverse($topCampLabels);
        $topCampValues = array_reverse($topCampValues);

        return view('admin.dashboard', array_merge($stats, compact(
            'activeCampaigns',
            'chartLabels',
            'chartTotal',
            'chartActive',
            'revLabels',
            'revData',
            'topCampLabels',
            'topCampValues',
            'unreadMessages',
            'pendingJobApplicants',
            'pendingSettlements',
            'totalWalletBalance',
            'recentActivity'
        )));
    }

    /**
     * AJAX endpoint powering the server-driven campaign grid
     * (filter / search / sort / pagination across every state).
     */
    public function campaigns(Request $request)
    {
        $state = $request->input('state', 'active');
        $search = trim((string) $request->input('search', ''));
        $sort = $request->input('sort', '');

        $query = Campaign::with('user', 'category');
        $query = $this->scopeState($query, $state);

        if ($search !== '') {
            $query->where('title', 'like', '%'.$search.'%');
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
            'cards' => $cards,
            'pagination' => $pagination,
            'total' => $campaigns->total(),
            'counts' => $this->computeCounts(),
        ]);
    }
}
