<?php

use App\Http\Controllers\Admin\CampaignController as AdminCampaignController;
use App\Http\Controllers\Admin\CampaignKycController;
use App\Http\Controllers\Admin\CampaignProductController;
use App\Http\Controllers\Admin\KycController as AdminKycController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/campaign', [AdminCampaignController::class, 'index'])->name('campaign.index');
    Route::get('/campaign/{campaign}', [AdminCampaignController::class, 'show'])->name('campaign.show');
    Route::get('/campaign/{campaign}/edit', [AdminCampaignController::class, 'edit'])->name('campaign.edit');
    Route::put('/campaign/{campaign}/update', [AdminCampaignController::class, 'update'])->name('campaign.update');
    Route::post('/campaign/{campaign}/approve', [AdminCampaignController::class, 'approve'])->name('campaign.approve');
    Route::post('/campaign/{campaign}/reject', [AdminCampaignController::class, 'reject'])->name('campaign.reject');
    Route::post('/campaign/{campaign}/pause', [AdminCampaignController::class, 'pause'])->name('campaign.pause');
    Route::post('/campaign/{campaign}/resume', [AdminCampaignController::class, 'resume'])->name('campaign.resume');
    Route::get('/campaign/{campaign}/quick', [AdminCampaignController::class, 'quick'])->name('campaign.quick');

    Route::post('/campaigns/bulk-approve', [AdminCampaignController::class, 'bulkApprove'])->name('campaigns.bulk-approve');
    Route::post('/campaigns/bulk-reject', [AdminCampaignController::class, 'bulkReject'])->name('campaigns.bulk-reject');
    Route::post('/campaigns/bulk-pause', [AdminCampaignController::class, 'bulkPause'])->name('campaigns.bulk-pause');

    Route::post('/campaign/{campaign}/request-kyc', [CampaignKycController::class, 'requestKyc'])->name('campaign.request-kyc');
    Route::post('/kyc/{kyc}/approve', [AdminKycController::class, 'approve'])->name('kyc.approve');
    Route::post('/kyc/{kyc}/reject', [AdminKycController::class, 'reject'])->name('kyc.reject');
    Route::get('/kyc/{kyc}/document', [AdminKycController::class, 'showDocument'])->name('kyc.document');

    Route::get('/campaign-products', [CampaignProductController::class, 'index'])->name('campaign-products.index');
    Route::get('/campaign-products/export', [CampaignProductController::class, 'exportCsv'])->name('campaign-products.export');
    Route::post('/campaign-products/bulk-approve', [CampaignProductController::class, 'bulkApprove'])->name('campaign-products.bulk-approve');
    Route::post('/campaign-products/bulk-reject', [CampaignProductController::class, 'bulkReject'])->name('campaign-products.bulk-reject');
    Route::post('/campaign-products/{product}/approve', [CampaignProductController::class, 'approve'])->name('campaign-products.approve');
    Route::post('/campaign-products/{product}/reject', [CampaignProductController::class, 'reject'])->name('campaign-products.reject');
    Route::delete('/campaign-products/{product}', [CampaignProductController::class, 'destroy'])->name('campaign-products.destroy');

});
