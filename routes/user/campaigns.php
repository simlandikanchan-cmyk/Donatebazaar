<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampaignController;

Route::middleware(['auth'])->group(function () {

    Route::get('/campaign/create',
        [CampaignController::class, 'create']
    )->name('campaign.create');

    Route::post('/campaign/store',
        [CampaignController::class, 'store']
    )->name('campaign.store');

    Route::get('/campaigns',
        [CampaignController::class, 'index']
    )->name('campaigns.index');

    Route::get('/campaign/{campaign}',
        [CampaignController::class, 'show']
    )->name('campaign.show');

});