<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\OtpController;
use Illuminate\Http\Request;
use App\Models\User;

Route::get('/otp-login', [OtpController::class, 'login'])
    ->name('otp.login');

Route::get('/verify-otp', [OtpController::class, 'verifyPage'])
    ->name('otp.verify');

Route::post('/send-otp', [OtpController::class, 'sendOtp'])
    ->name('otp.send');

Route::post('/resend-otp', [OtpController::class, 'resend'])
    ->name('otp.resend');

Route::post('/store-phone', function (Request $request) {
    session(['phone' => $request->phone]);

    return response()->json([
        'success' => true
    ]);
});

Route::post('/firebase-login', function (Request $request) {

    $user = User::firstOrCreate(
        ['phone' => $request->phone],
        ['role' => 'donor']
    );

    auth()->login($user);

    return response()->json([
        'success' => true
    ]);
});