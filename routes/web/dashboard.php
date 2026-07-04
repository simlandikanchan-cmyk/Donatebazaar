<?php

use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/user/dashboard', function () {
        $campaigns = Campaign::where('user_id', auth()->id())->get();

        $monthlyData = Campaign::where('user_id', auth()->id())
            ->selectRaw('MONTH(created_at) as month, SUM(raised_amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $recurringDonations = \App\Models\RecurringDonation::where('user_id', auth()->id())->latest()->get();
        $recurringCount = $recurringDonations->count();

        $campaignIds = $campaigns->pluck('id');
        $recentDonations = collect();
        if ($campaignIds->isNotEmpty()) {
            $recentDonations = Donation::whereIn('campaign_id', $campaignIds)
                ->latest()
                ->take(6)
                ->get();
        }

        $kyc = auth()->user()->kycVerification;

        return view('dashboard', compact('campaigns', 'monthlyData', 'recurringDonations', 'recurringCount', 'kyc', 'recentDonations'));
    })->name('dashboard');
});
