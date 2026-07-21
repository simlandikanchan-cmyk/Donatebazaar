<?php

use App\Http\Controllers\Admin\PayoutAccountController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/payout-accounts')
    ->name('admin.payout-accounts.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::post('/{payoutAccount}/verify', [PayoutAccountController::class, 'verify'])->name('verify');
        Route::post('/{payoutAccount}/unverify', [PayoutAccountController::class, 'unverify'])->name('unverify');
    });
