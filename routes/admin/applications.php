<?php

use App\Http\Controllers\Admin\ApplicationController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/applications',              [ApplicationController::class, 'index'])->name('applications');
    Route::get('/applications/{id}',         [ApplicationController::class, 'show'])->name('applications.show');
    Route::post('/applications/{id}/approve',[ApplicationController::class, 'approve'])->name('applications.approve');
    Route::post('/applications/{id}/reject', [ApplicationController::class, 'reject'])->name('applications.reject');

});
