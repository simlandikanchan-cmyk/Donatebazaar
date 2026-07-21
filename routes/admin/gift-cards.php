<?php

use App\Http\Controllers\Admin\GiftCardController as AdminGiftCardController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/gift-cards')->name('admin.gift-cards.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminGiftCardController::class, 'index'])->name('index');
    Route::get('/{giftCard}', [AdminGiftCardController::class, 'show'])->name('show');
    Route::post('/{giftCard}/status', [AdminGiftCardController::class, 'updateStatus'])->name('status');
    Route::post('/{giftCard}/resend', [AdminGiftCardController::class, 'resend'])->name('resend');
    Route::delete('/{giftCard}', [AdminGiftCardController::class, 'destroy'])->name('destroy');
});
