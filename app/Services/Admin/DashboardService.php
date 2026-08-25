<?php

namespace App\Services\Admin;

use App\Models\Campaign;
use App\Models\ContactMessage;
use App\Models\Donation;
use App\Models\JobPost;
use App\Models\JobPostApplication;
use App\Models\User;
use App\Models\Volunteer;
use App\Models\VolunteerApplication;
use App\Models\Wallet;
use App\Repositories\CampaignRepository;
use App\Repositories\DonationRepository;
use App\Repositories\SettlementRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class DashboardService
{
    public function __construct(
        private CampaignRepository $campaignRepo,
        private DonationRepository $donationRepo,
        private SettlementRepository $settlementRepo,
    ) {}

    public function indexData(): array
    {
        $stats = $this->stats();

        $activeCampaigns = $this->scopeState(
            Campaign::with('user', 'category')->whereIn('campaign_state', ['active', 'paused']),
            'active'
        )->latest()->paginate(12, ['*'], 'cpage');

        $monthly = $this->monthlyRows();
        $topCampaigns = $this->topCampaigns();

        return array_merge($stats, [
            'activeCampaigns' => $activeCampaigns,
            'chartLabels' => $this->chartLabels(),
            'chartTotal' => $this->chartTotal($monthly),
            'chartActive' => $this->chartActive($monthly),
            'revLabels' => $this->revLabels(),
            'revData' => $this->revData(),
            'topCampLabels' => $this->topCampLabels($topCampaigns),
            'topCampValues' => $this->topCampValues($topCampaigns),
            'unreadMessages' => $this->unreadMessages(),
            'pendingJobApplicants' => $this->pendingJobApplicants(),
            'pendingSettlements' => $this->settlementRepo->getPendingCount(),
            'totalWalletBalance' => Wallet::sum('balance'),
            'recentActivity' => $this->recentActivity(),
        ]);
    }

    public function campaignList(Request $request): array
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

        $campaigns = $query->paginate(12, ['*'], 'cpage');

        $cards = view('admin._campaign_cards', compact('campaigns'))->render();
        $pagination = $campaigns->hasPages()
            ? $campaigns->links('vendor.pagination.admin')->render()
            : '';

        return [
            'cards' => $cards,
            'pagination' => $pagination,
            'total' => $campaigns->total(),
            'counts' => $this->computeCounts(),
        ];
    }

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
            default:
                return $query;
        }
    }

    private function stats(): array
    {
        return Cache::remember('admin_dashboard_stats', 300, function () {
            $counts = $this->computeCounts();

            $volunteerCount = Volunteer::count();
            $pendingVolunteerApps = VolunteerApplication::where('status', 'pending')->count();

            $totalUsers    = User::count();
            $newUsersToday = User::whereDate('created_at', today())->count();

            $totalDonations = Donation::count();
            $donationsToday = Donation::whereDate('created_at', today())->count();
            $totalRevenue   = Donation::sum('total_amount');

            $activeJobs     = JobPost::where('status', 'active')->count();
            $totalApplicants = JobPostApplication::count();

            $avgDonation  = $totalDonations > 0 ? (int) round($totalRevenue / $totalDonations) : 0;
            $uniqueDonors = Donation::whereNotNull('user_id')->distinct('user_id')->count('user_id');
            $successRate  = ($counts['totalCampaigns'] ?? 0) > 0
                ? round(($this->campaignRepo->countByState('completed') / $counts['totalCampaigns']) * 100)
                : 0;

            return array_merge($counts, compact(
                'volunteerCount', 'pendingVolunteerApps',
                'totalUsers', 'newUsersToday',
                'totalDonations', 'donationsToday', 'totalRevenue',
                'activeJobs', 'totalApplicants',
                'avgDonation', 'uniqueDonors', 'successRate'
            ));
        });
    }

    private function computeCounts(): array
    {
        $stateCounts = $this->campaignRepo->getAdminDashboardStats()['stateCounts'];

        $totalCampaigns = (int) array_sum($stateCounts);
        $cntPending     = (int) ($stateCounts['pending'] ?? 0);
        $cntActive      = (int) ($stateCounts['active'] ?? 0);
        $cntPaused      = (int) ($stateCounts['paused'] ?? 0);
        $cntExpired     = (int) ($stateCounts['expired'] ?? 0);
        $cntRejected    = (int) ($stateCounts['rejected'] ?? 0);
        $cntCompleted   = (int) ($stateCounts['completed'] ?? 0);

        $approvalRate = $totalCampaigns > 0 ? round(($cntActive / $totalCampaigns) * 100) : 0;

        return compact(
            'totalCampaigns', 'cntPending', 'cntActive', 'cntPaused',
            'cntExpired', 'cntRejected', 'cntCompleted', 'approvalRate'
        );
    }

    private function unreadMessages(): int
    {
        return ContactMessage::where('is_read', false)->count();
    }

    private function pendingJobApplicants(): int
    {
        return JobPostApplication::where('status', 'pending')->count();
    }

    private function recentActivity(): \Illuminate\Support\Collection
    {
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

        return collect()
            ->merge($recentDonations)
            ->merge($recentCampaigns)
            ->merge($recentVolApps)
            ->merge($recentMessages)
            ->sortByDesc('time')
            ->take(12)
            ->values();
    }

    private function chartLabels(): array
    {
        $labels = [];
        for ($i = 5; $i >= 0; $i--) {
            $labels[] = now()->subMonths($i)->format('M');
        }

        return $labels;
    }

    private function monthlyRows(): \Illuminate\Support\Collection
    {
        return Campaign::selectRaw("
                DATE_FORMAT(created_at, '%Y-%m') as month,
                COUNT(*) as total,
                SUM(campaign_state = 'active') as active
            ")
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupByRaw("DATE_FORMAT(created_at, '%Y-%m')")
            ->orderBy('month')
            ->get()
            ->keyBy('month');
    }

    private function chartTotal(\Illuminate\Support\Collection $monthly): array
    {
        $values = [];
        for ($i = 5; $i >= 0; $i--) {
            $row = $monthly->get(now()->subMonths($i)->format('Y-m'));
            $values[] = $row ? (int) $row->total : 0;
        }

        return $values;
    }

    private function chartActive(\Illuminate\Support\Collection $monthly): array
    {
        $values = [];
        for ($i = 5; $i >= 0; $i--) {
            $row = $monthly->get(now()->subMonths($i)->format('Y-m'));
            $values[] = $row ? (int) $row->active : 0;
        }

        return $values;
    }

    private function revenueRows(): \Illuminate\Support\Collection
    {
        return Donation::selectRaw("
                DATE_FORMAT(created_at, '%Y-%m') as month,
                SUM(total_amount) as revenue
            ")
            ->where('payment_status', 'completed')
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupByRaw("DATE_FORMAT(created_at, '%Y-%m')")
            ->orderBy('month')
            ->get()
            ->keyBy('month');
    }

    private function revLabels(): array
    {
        $labels = [];
        for ($i = 5; $i >= 0; $i--) {
            $labels[] = now()->subMonths($i)->format('M');
        }

        return $labels;
    }

    private function revData(): array
    {
        $monthly = $this->revenueRows();
        $values = [];
        for ($i = 5; $i >= 0; $i--) {
            $row = $monthly->get(now()->subMonths($i)->format('Y-m'));
            $values[] = $row ? (float) $row->revenue : 0;
        }

        return $values;
    }

    private function topCampaigns(): \Illuminate\Database\Eloquent\Collection
    {
        return Campaign::where('raised_amount', '>', 0)
            ->orderByDesc('raised_amount')
            ->take(5)
            ->get(['title', 'raised_amount']);
    }

    private function topCampLabels(\Illuminate\Database\Eloquent\Collection $topCampaigns): array
    {
        $labels = $topCampaigns->map(fn ($c) => Str::limit($c->title, 28))->toArray();

        return array_reverse($labels);
    }

    private function topCampValues(\Illuminate\Database\Eloquent\Collection $topCampaigns): array
    {
        $values = $topCampaigns->map(fn ($c) => (float) $c->raised_amount)->toArray();

        return array_reverse($values);
    }
}