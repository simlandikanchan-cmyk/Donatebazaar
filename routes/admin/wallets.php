<?php

use App\Http\Controllers\Admin\WalletController as AdminWalletController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/wallets')
    ->name('admin.wallets.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::get('/', [AdminWalletController::class, 'index'])->name('index');
        Route::get('/{wallet}', [AdminWalletController::class, 'show'])->name('show');
        Route::delete('/{wallet}', [AdminWalletController::class, 'destroy'])->name('destroy');
        Route::post('/{wallet}/adjust', [AdminWalletController::class, 'adjust'])->middleware('throttle:financial')->name('adjust');
    });
