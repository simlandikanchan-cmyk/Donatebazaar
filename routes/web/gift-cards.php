<?php

use App\Http\Controllers\GiftCardController;
use Illuminate\Support\Facades\Route;

Route::prefix('gift-cards')->name('gift-cards.')->group(function () {
    Route::get('/', [GiftCardController::class, 'index'])->name('index');
    Route::post('/order', [GiftCardController::class, 'createOrder'])->name('order');
    Route::post('/verify', [GiftCardController::class, 'verify'])->name('verify');
    Route::get('/redeem', [GiftCardController::class, 'redeemPage'])->name('redeem');
    Route::post('/validate-code', [GiftCardController::class, 'validateCode'])
        ->middleware('throttle:gift-card')
        ->name('validate-code');
    Route::post('/redeem', [GiftCardController::class, 'redeem'])
        ->middleware('throttle:gift-card')
        ->name('redeem.submit');
    Route::get('/success/{code}', [GiftCardController::class, 'redeemSuccess'])->name('redeem.success');
});
