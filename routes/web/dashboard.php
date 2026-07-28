<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'account.active'])->group(function () {
    Route::get('/user/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/user/dashboard/saved-campaigns', [DashboardController::class, 'savedCampaigns'])
        ->name('saved.campaigns');

    Route::get('/user/dashboard/level', [DashboardController::class, 'level'])
        ->name('user.level');

    Route::get('/user/dashboard/wallet', [WalletController::class, 'index'])
        ->name('dashboard.wallet');

    Route::post('/user/dashboard/wallet/request-payout', [WalletController::class, 'requestPayout'])
        ->name('dashboard.wallet.request');

    Route::post('/user/dashboard/wallet/payout-account', [WalletController::class, 'savePayoutAccount'])
        ->name('dashboard.wallet.payout-account');
});
