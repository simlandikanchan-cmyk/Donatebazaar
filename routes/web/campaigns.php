<?php

use App\Http\Controllers\CampaignAnalyticsController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\PublicCampaignController;
use App\Http\Controllers\KycUploadController;
use App\Models\Campaign;
use Illuminate\Support\Facades\Route;

Route::get('/all-campaigns',   [CampaignController::class, 'publicCampaigns'])->name('all.campaigns');
Route::get('/category/{slug}', [CampaignController::class, 'byCategory'])->name('campaigns.byCategory');
Route::get('/campaigns/{category}/{slug}', [PublicCampaignController::class, 'show'])->name('campaign.public');

Route::middleware('auth')->group(function () {
    Route::get('/campaign/create',                [CampaignController::class, 'create'])->name('campaign.create');
    Route::post('/campaign/store',                [CampaignController::class, 'store'])->name('campaign.store');
    Route::get('/campaigns',                      [CampaignController::class, 'index'])->name('campaigns.index');
    Route::get('/campaign/{campaign}',            [CampaignController::class, 'show'])->name('campaign.show');
    Route::get('/campaign/{campaign}/edit',       [CampaignController::class, 'edit'])->name('campaign.edit');
    Route::put('/campaign/{campaign}',            [CampaignController::class, 'update'])->name('campaign.update');
    Route::post('/campaign/{campaign}/pause',     [CampaignController::class, 'pause'])->name('campaign.pause');
    Route::post('/campaign/{campaign}/resume',    [CampaignController::class, 'resume'])->name('campaign.resume');
    Route::post('/campaign/{campaign}/follow',    [CampaignController::class, 'toggleFollow'])->name('campaign.follow');
    Route::post('/campaigns/{campaign}/resubmit', [CampaignController::class, 'resubmit'])->name('campaign.resubmit');

    Route::get('/campaign/{campaign}/analytics', [CampaignAnalyticsController::class, 'index'])->name('campaign.analytics');

    Route::get('/kyc/upload/{campaign}',   [KycUploadController::class, 'show'])->name('kyc.upload.form');
    Route::post('/kyc/upload/{campaign}',  [KycUploadController::class, 'store'])->name('kyc.upload');
    Route::get('/kyc/view/{campaign}',     [KycUploadController::class, 'view'])->name('kyc.view');
    Route::get('/kyc/document/{campaign}', [KycUploadController::class, 'serveDocument'])->name('kyc.document');

    Route::get('/user/kyc', function () {
        $campaigns = Campaign::where('user_id', auth()->id())->get();
        return view('kyc.index', compact('campaigns'));
    })->name('user.kyc');
});
