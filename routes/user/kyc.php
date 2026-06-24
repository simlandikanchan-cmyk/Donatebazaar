<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KycUploadController;

Route::middleware(['auth'])->group(function () {

    Route::get('/kyc/upload/{campaign}',
        [KycUploadController::class, 'show']
    )->name('kyc.upload.form');

    Route::post('/kyc/upload/{campaign}',
        [KycUploadController::class, 'store']
    )->name('kyc.upload');

    Route::get('/kyc/view/{campaign}',
        [KycUploadController::class, 'view']
    )->name('kyc.view');

});