<?php

use App\Http\Controllers\WalletController;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\FundraiserLevel;
use App\Models\RecurringDonation;
use App\Services\WalletService;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'account.active'])->group(function () {
    Route::get('/user/dashboard', function () {
        $user = auth()->user()->load(['kycVerification', 'fundraiserLevel']);

        $campaigns = Campaign::where('user_id', $user->id)
            ->with('category:id,name')
            ->withCount('donations')
            ->latest()
            ->paginate(20);

        $monthlyData = Campaign::where('user_id', $user->id)
            ->whereYear('created_at', now()->year)
            ->selectRaw('MONTH(created_at) as month, SUM(raised_amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $recurringDonations = RecurringDonation::where('user_id', $user->id)
            ->with('campaign:id,title')
            ->latest()
            ->get();
        $recurringCount = $recurringDonations->count();

        $campaignIds = $campaigns->pluck('id');
        $recentDonations = collect();
        $totalDonationsCount = 0;
        if ($campaignIds->isNotEmpty()) {
            $recentDonations = Donation::whereIn('campaign_id', $campaignIds)
                ->with('campaign:id,title')
                ->whereNotNull('paid_at')
                ->latest()
                ->take(6)
                ->get();
            $totalDonationsCount = Donation::whereIn('campaign_id', $campaignIds)
                ->whereNotNull('paid_at')
                ->count();
        }

        $kyc = $user->kycVerification;
        $level = $user->fundraiserLevel;
        $levelName = $user->fundraiserLevelName();
        $memberSince = $user->created_at;
        $daysActive = $memberSince ? now()->diffInDays($memberSince) : 0;

        $wallet = (new WalletService)->getOrCreateWallet($user);

        return view('dashboard', compact(
            'campaigns', 'monthlyData', 'recurringDonations', 'recurringCount',
            'kyc', 'recentDonations', 'totalDonationsCount', 'level', 'levelName',
            'memberSince', 'daysActive', 'user', 'wallet'
        ));
    })->name('dashboard');

    Route::get('/user/dashboard/saved-campaigns', function () {
        $user = auth()->user();
        $campaigns = $user->followedCampaigns()
            ->with('category:id,name,slug')
            ->withCount('donations')
            ->latest()
            ->paginate(20);

        return view('user.saved-campaigns', compact('campaigns'));
    })->name('saved.campaigns');

    Route::get('/user/dashboard/level', function () {
        $user = auth()->user();
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
    })->name('user.level');

    Route::get('/user/dashboard/wallet', [WalletController::class, 'index'])
        ->name('dashboard.wallet');

    Route::post('/user/dashboard/wallet/request-payout', [WalletController::class, 'requestPayout'])
        ->name('dashboard.wallet.request');

    Route::post('/user/dashboard/wallet/payout-account', [WalletController::class, 'savePayoutAccount'])
        ->name('dashboard.wallet.payout-account');
});
