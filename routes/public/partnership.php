<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\PartnershipController;

Route::get('/partnership',
    [PartnershipController::class, 'index']
)->name('partnership');

Route::post('/partnership',
    [PartnershipController::class, 'store']
)->name('partnership.store');