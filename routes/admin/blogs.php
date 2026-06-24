<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BlogController;

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

        Route::prefix('blogs')
            ->name('blogs.')
            ->group(function () {

                Route::get('/',
                    [BlogController::class, 'index']
                )->name('index');

                Route::get('/pending',
                    [BlogController::class, 'pending']
                )->name('pending');

            });

    });