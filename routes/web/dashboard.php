<?php

use App\Models\Campaign;
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

        return view('dashboard', compact('campaigns', 'monthlyData', 'recurringDonations', 'recurringCount'));
    })->name('dashboard');
});
