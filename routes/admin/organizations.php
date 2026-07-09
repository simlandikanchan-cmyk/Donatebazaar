<?php

use App\Http\Controllers\Admin\OrganizationController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/organizations',
        [OrganizationController::class, 'index']
    )->name('organizations.index');

    Route::get('/organizations/create',
        [OrganizationController::class, 'create']
    )->name('organizations.create');

    Route::post('/organizations/store',
        [OrganizationController::class, 'store']
    )->name('organizations.store');

});
