<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\PublicCampaignController;

Route::get('/all-campaigns',
    [CampaignController::class, 'publicCampaigns']
)->name('all.campaigns');

Route::get('/category/{slug}',
    [CampaignController::class, 'byCategory']
)->name('campaigns.byCategory');

Route::get('/campaigns/{slug}',
    [PublicCampaignController::class, 'show']
)->name('campaign.public');