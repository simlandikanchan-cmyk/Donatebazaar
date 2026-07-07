<?php

use App\Models\Campaign;
use App\Models\Donation;
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

        $recurringDonations = \App\Models\RecurringDonation::where('user_id', $user->id)
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

        return view('dashboard', compact(
            'campaigns', 'monthlyData', 'recurringDonations', 'recurringCount',
            'kyc', 'recentDonations', 'totalDonationsCount', 'level', 'levelName',
            'memberSince', 'daysActive', 'user'
        ));
    })->name('dashboard');
});
