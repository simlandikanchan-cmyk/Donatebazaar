<?php

use App\Http\Controllers\Admin\SettlementController as AdminSettlementController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/settlements')
    ->name('admin.settlements.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::get('/', [AdminSettlementController::class, 'index'])->name('index');
        Route::get('/{settlement}', [AdminSettlementController::class, 'show'])->name('show');
        Route::post('/{settlement}/approve', [AdminSettlementController::class, 'approve'])->name('approve');
        Route::post('/{settlement}/reject', [AdminSettlementController::class, 'reject'])->name('reject');
    });
