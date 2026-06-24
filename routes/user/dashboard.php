<?php

use Illuminate\Support\Facades\Route;
use App\Models\Campaign;

Route::middleware(['auth'])->group(function () {

    Route::get('/user/dashboard', function () {

        $campaigns = Campaign::where(
            'user_id',
            auth()->id()
        )->get();

        $monthlyData = Campaign::where(
            'user_id',
            auth()->id()
        )
        
        ->selectRaw('MONTH(created_at) as month, SUM(raised_amount) as total')
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total', 'month');

        return view(
            'dashboard',
            compact('campaigns', 'monthlyData')
        );

    })->name('dashboard');

});