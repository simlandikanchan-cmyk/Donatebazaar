<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RecurringDonationController;
use App\Http\Controllers\CouponController;
use Illuminate\Support\Facades\Route;

Route::match(['get', 'post'], '/donate/{campaign}', [PaymentController::class, 'redirectToPayment'])
     ->name('donate.redirect')
     ->middleware('auth');

Route::get('/payment/{campaign}', [PaymentController::class, 'paymentPage'])
     ->name('payment.page')
     ->middleware('auth');

Route::post('/payment/verify', [PaymentController::class, 'verify'])
     ->name('payment.verify')
     ->middleware('auth');

Route::post('/payment/webhook', [PaymentController::class, 'webhook'])
     ->name('payment.webhook');

Route::post('/coupon/validate', [CouponController::class, 'check'])
     ->name('coupon.validate')
     ->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/my-recurring-donations', [RecurringDonationController::class, 'index'])->name('recurring.index');
    Route::get('/my-recurring-donations/{recurringDonation}', [RecurringDonationController::class, 'show'])->name('recurring.show');
    Route::post('/campaign/{campaign}/recurring', [RecurringDonationController::class, 'store'])->name('recurring.store');
    Route::patch('/recurring/{recurringDonation}/cancel', [RecurringDonationController::class, 'cancel'])->name('recurring.cancel');
    Route::patch('/recurring/{recurringDonation}/pause',  [RecurringDonationController::class, 'pause'])->name('recurring.pause');
    Route::patch('/recurring/{recurringDonation}/resume', [RecurringDonationController::class, 'resume'])->name('recurring.resume');
});
