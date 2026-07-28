<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignSettlement;
use App\Models\Donation;
use App\Models\Event;
use App\Models\FundraiserLevel;
use App\Models\Organization;
use App\Models\RecurringDonation;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private WalletService $walletService,
    ) {}

    public function index(Request $request): View
    {
        $user = $request->user()->load(['kycVerification', 'fundraiserLevel']);

        $campaigns = Campaign::where('user_id', $user->id)
            ->with('category:id,name')
            ->withCount('donations')
            ->latest()
            ->paginate(20);

        $monthlyData = Cache::remember("dashboard.monthly_data.{$user->id}", 300, function () use ($user) {
            return Campaign::where('user_id', $user->id)
                ->whereYear('created_at', now()->year)
                ->selectRaw('MONTH(created_at) as month, SUM(raised_amount) as total')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');
        });

        $recurringDonations = RecurringDonation::where('user_id', $user->id)
            ->with('campaign:id,title')
            ->latest()
            ->take(10)
            ->get();
        $recurringCount = RecurringDonation::where('user_id', $user->id)->count();

        $campaignIds = $campaigns->pluck('id');
        $recentDonations = collect();
        $totalDonationsCount = 0;
        if ($campaignIds->isNotEmpty()) {
            $donationQuery = Donation::whereIn('campaign_id', $campaignIds)
                ->whereNotNull('paid_at');
            $recentDonations = (clone $donationQuery)
                ->with('campaign:id,title')
                ->latest()
                ->take(6)
                ->get();
            $totalDonationsCount = $donationQuery->count();
        }

        $kyc = $user->kycVerification;
        $level = $user->fundraiserLevel;
        $levelName = $user->fundraiserLevelName();
        $memberSince = $user->created_at;
        $daysActive = $memberSince ? now()->diffInDays($memberSince) : 0;

        $wallet = $this->walletService->getOrCreateWallet($user);

        $topCampaign = Cache::remember("dashboard.top_campaign.{$user->id}", 300, function () use ($user) {
            return Campaign::where('user_id', $user->id)
                ->where('campaign_state', 'active')
                ->with('category:id,name')
                ->withCount('donations')
                ->orderByDesc('raised_amount')
                ->first();
        });

        $nextLevel = null;
        $levelProgress = 0;
        $campaignsCompleted = 0;
        $totalRaisedAll = 0;
        $currentLevelModel = $user->assignedLevel;
        if ($currentLevelModel) {
            $nextLevel = FundraiserLevel::nextAfter($currentLevelModel->level_number);
            if ($nextLevel) {
                $campaignsCompleted = Campaign::where('user_id', $user->id)
                    ->whereIn('campaign_state', ['completed', 'active'])->count();
                $totalRaisedAll = Campaign::where('user_id', $user->id)->sum('raised_amount');
                $campPct = $nextLevel->min_campaigns_completed > 0
                    ? min(100, ($campaignsCompleted / $nextLevel->min_campaigns_completed) * 100)
                    : 100;
                $raisedPct = $nextLevel->min_raised_percent > 0
                    ? min(100, ($totalRaisedAll / $nextLevel->min_raised_percent) * 100)
                    : 100;
                $levelProgress = min(100, round(($campPct + $raisedPct) / 2));
            }
        }

        $recentBlogs = Cache::remember("dashboard.recent_blogs.{$user->id}", 300, function () use ($user) {
            return \App\Models\Blog::where('author_id', $user->id)
                ->latest()
                ->take(3)
                ->get(['id', 'title', 'status', 'views_count', 'created_at', 'published_at']);
        });

        $myEvents = Cache::remember("dashboard.my_events.{$user->id}", 300, function () use ($user) {
            return Event::where('user_id', $user->id)
                ->whereIn('status', ['active', 'pending'])
                ->where('event_date', '>=', now()->subDay())
                ->latest('event_date')
                ->take(5)
                ->get();
        });
        $registeredEvents = Cache::remember("dashboard.registered_events.{$user->id}", 300, function () use ($user) {
            return $user->eventRegistrations()
                ->with('event')
                ->whereHas('event', fn($q) => $q->whereIn('status', ['active', 'pending'])->where('event_date', '>=', now()->subDay()))
                ->latest()
                ->take(5)
                ->get();
        });

        $recentTransactions = Cache::remember("dashboard.recent_tx.{$user->id}", 300, function () use ($wallet) {
            return $wallet->transactions()
                ->latest()
                ->take(5)
                ->get();
        });

        $totalCampaigns = Campaign::where('user_id', $user->id)->count();
        $pendingTasks = Cache::remember("dashboard.pending_tasks.{$user->id}", 300, function () use ($user, $kyc, $campaignIds, $totalCampaigns) {
            $tasks = collect();
            if (!$kyc || $kyc->status !== 'approved') {
                $tasks->push(['label' => 'Complete KYC Verification', 'sub' => 'Submit identity documents to receive payouts', 'url' => url('/user/kyc'), 'icon' => 'shield']);
            }
            $org = Organization::where('user_id', $user->id)->first();
            $hasPayoutAcct = $org && $org->payoutAccounts()->exists();
            if (!$hasPayoutAcct) {
                $tasks->push(['label' => 'Set Up Payout Account', 'sub' => 'Add bank or UPI details to withdraw funds', 'url' => route('dashboard.wallet'), 'icon' => 'bank']);
            }
            if ($totalCampaigns === 0) {
                $tasks->push(['label' => 'Create Your First Campaign', 'sub' => 'Start fundraising for a cause', 'url' => route('campaign.create'), 'icon' => 'plus']);
            }
            if ($campaignIds->isNotEmpty()) {
                $pendingSettlements = CampaignSettlement::whereIn('campaign_id', $campaignIds)
                    ->whereIn('status', ['pending', 'processing', 'pending_approval'])
                    ->count();
                if ($pendingSettlements > 0) {
                    $tasks->push(['label' => "$pendingSettlements Settlement".($pendingSettlements > 1 ? 's' : '')." Pending", 'sub' => 'Awaiting processing or approval', 'url' => route('dashboard.wallet'), 'icon' => 'clock']);
                }
            }
            return $tasks;
        });

        return view('dashboard', compact(
            'campaigns', 'monthlyData', 'recurringDonations', 'recurringCount',
            'kyc', 'recentDonations', 'totalDonationsCount', 'level', 'levelName',
            'memberSince', 'daysActive', 'user', 'wallet',
            'topCampaign', 'nextLevel', 'levelProgress', 'campaignsCompleted', 'totalRaisedAll', 'recentBlogs',
            'myEvents', 'registeredEvents', 'recentTransactions', 'pendingTasks'
        ));
    }

    public function savedCampaigns(Request $request): View
    {
        $user = $request->user();
        $campaigns = $user->followedCampaigns()
            ->with('category:id,name,slug')
            ->withCount('donations')
            ->latest()
            ->paginate(20);

        return view('user.saved-campaigns', compact('campaigns'));
    }

    public function level(Request $request): View
    {
        $user = $request->user();
        $user->loadMissing(['fundraiserLevel.currentLevel', 'fundraiserLevel.history.fromLevel', 'fundraiserLevel.history.toLevel']);

        $userLevel = $user->fundraiserLevel;
        $currentLevel = $userLevel?->currentLevel ?? FundraiserLevel::starter();
        $allLevels = FundraiserLevel::orderBy('level_number')->get();
        $nextLevel = FundraiserLevel::nextAfter($currentLevel->level_number);

        $campaignsCompleted = Campaign::where('user_id', $user->id)
            ->whereIn('campaign_state', [Campaign::STATE_COMPLETED, Campaign::STATE_ACTIVE])
            ->count();

        $totalRaised = Campaign::where('user_id', $user->id)
            ->sum('raised_amount');

        $completionPct = 0;
        if ($nextLevel) {
            $campPct = $nextLevel->min_campaigns_completed > 0
                ? min(100, ($campaignsCompleted / $nextLevel->min_campaigns_completed) * 100)
                : 100;
            $raisedPct = $nextLevel->min_raised_percent > 0
                ? min(100, ($campaignsCompleted > 0 ? 100 : 0))
                : 100;
            $completionPct = min(100, ($campPct + $raisedPct) / 2);
        }

        return view('user.fundraiser-level', compact(
            'userLevel', 'currentLevel', 'allLevels', 'nextLevel',
            'campaignsCompleted', 'totalRaised', 'completionPct'
        ));
    }
}
