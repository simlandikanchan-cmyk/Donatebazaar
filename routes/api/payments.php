<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::post('/payment/verify', [PaymentController::class, 'verify'])->name('payment.verify');
