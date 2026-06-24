<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::post('/donate/{campaign}',
    [PaymentController::class, 'redirectToPayment']
)->name('donate.redirect');

Route::get('/payment/{campaign}',
    [PaymentController::class, 'paymentPage']
)->name('payment.page');

Route::post('/payment/verify',
    [PaymentController::class, 'verify']
)->name('payment.verify');