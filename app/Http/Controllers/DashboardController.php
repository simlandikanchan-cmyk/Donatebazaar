<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignSettlement;
use App\Models\FundraiserLevel;
use App\Models\Organization;
use App\Models\RecurringDonation;
use App\Repositories\CampaignRepository;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private WalletService $walletService,
        private CampaignRepository $campaignRepo,
        private WalletRepository $walletRepo,
        private UserRepository $userRepo,
    ) {}

    public function index(Request $request): View
    {
        $user = $request->user()->load(['kycVerification', 'fundraiserLevel']);

        $campaigns = $this->campaignRepo->getUserCampaigns($user->id);
        $monthlyData = $this->campaignRepo->getUserMonthlyData($user->id);

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
            $recentDonations = $this->walletRepo->getRecentTransactions(0);
            $recentDonations = $campaignIds->isNotEmpty()
                ? \App\Models\Donation::whereIn('campaign_id', $campaignIds)->whereNotNull('paid_at')->with('campaign:id,title')->latest()->take(6)->get()
                : collect();
            $totalDonationsCount = \App\Models\Donation::whereIn('campaign_id', $campaignIds)->whereNotNull('paid_at')->count();
        }

        $kyc = $user->kycVerification;
        $level = $user->fundraiserLevel;
        $levelName = $user->fundraiserLevelName();
        $memberSince = $user->created_at;
        $daysActive = $memberSince ? now()->diffInDays($memberSince) : 0;

        $wallet = $this->walletService->getOrCreateWallet($user);

        $topCampaign = $this->campaignRepo->getTopCampaign($user->id);

        $nextLevel = null;
        $levelProgress = 0;
        $campaignsCompleted = 0;
        $totalRaisedAll = 0;
        $currentLevelModel = $user->assignedLevel;
        if ($currentLevelModel) {
            $nextLevel = FundraiserLevel::nextAfter($currentLevelModel->level_number);
            if ($nextLevel) {
                $campaignsCompleted = $this->campaignRepo->countByUserAndStates($user->id, ['completed', 'active']);
                $totalRaisedAll = $this->campaignRepo->sumRaisedByUser($user->id);
                $campPct = $nextLevel->min_campaigns_completed > 0
                    ? min(100, ($campaignsCompleted / $nextLevel->min_campaigns_completed) * 100)
                    : 100;
                $raisedPct = $nextLevel->min_raised_percent > 0
                    ? min(100, ($totalRaisedAll / $nextLevel->min_raised_percent) * 100)
                    : 100;
                $levelProgress = min(100, round(($campPct + $raisedPct) / 2));
            }
        }

        $recentBlogs = $this->userRepo->getBlogsByAuthor($user->id);
        $myEvents = $this->userRepo->getEventsByUser($user->id);
        $registeredEvents = $this->userRepo->getUserRegisteredEvents($user);
        $recentTransactions = $this->walletRepo->getRecentTransactions($wallet->id);
        $totalCampaigns = $this->campaignRepo->countByUser($user->id);

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

        $campaignsCompleted = $this->campaignRepo->countByUserAndStates($user->id, [Campaign::STATE_COMPLETED, Campaign::STATE_ACTIVE]);
        $totalRaised = $this->campaignRepo->sumRaisedByUser($user->id);

        $completionPct = 0;
        if ($nextLevel) {
            $campPct = $nextLevel->min_campaigns_completed > 0
                ? min(100, ($campaignsCompleted / $nextLevel->min_campaigns_completed) * 100)
                : 100;
            $raisedPct = $nextLevel->min_raised_percent > 0
                ? min(100, ($totalRaised / $nextLevel->min_raised_percent) * 100)
                : 100;
            $completionPct = min(100, round(($campPct + $raisedPct) / 2));
        }

        $history = $userLevel?->history()->with(['fromLevel', 'toLevel'])->latest()->get() ?? collect();

        return view('user.level', compact(
            'user', 'currentLevel', 'allLevels', 'nextLevel',
            'campaignsCompleted', 'totalRaised', 'completionPct', 'history'
        ));
    }
}
