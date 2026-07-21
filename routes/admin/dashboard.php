<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

        Route::get('/dashboard',
            [DashboardController::class, 'index']
        )->name('dashboard');

        Route::get('/dashboard/campaigns',
            [DashboardController::class, 'campaigns']
        )->name('dashboard.campaigns');

    });
