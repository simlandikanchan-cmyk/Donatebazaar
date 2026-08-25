<?php

use App\Http\Controllers\Admin\PartnershipAdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::prefix('partnerships')->controller(PartnershipAdminController::class)->group(function () {
        Route::get('export', 'exportCsv')->name('partnership.export');
        Route::post('bulk-update', 'bulkUpdate')->name('partnership.bulk-update');
        Route::post('bulk-delete', 'bulkDelete')->name('partnership.bulk-delete');
    });

    Route::get('/partnerships', [PartnershipAdminController::class, 'index'])->name('partnership.index');
    Route::get('/partnerships/{id}', [PartnershipAdminController::class, 'show'])->name('partnership.show');
    Route::post('/partnerships/{id}', [PartnershipAdminController::class, 'update'])->name('partnership.update');
    Route::delete('/partnerships/{id}', [PartnershipAdminController::class, 'destroy'])->name('partnership.destroy');

});
