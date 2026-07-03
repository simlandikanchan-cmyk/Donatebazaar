<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\Frontend\PartnershipController;
use Illuminate\Support\Facades\Route;

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
     ->middleware('throttle:5,1')
     ->name('contact.store');

Route::get('/partnership', [PartnershipController::class, 'index'])->name('partnership');
Route::post('/partnership', [PartnershipController::class, 'store'])
     ->middleware('throttle:5,1')
     ->name('partnership.store');
