<?php

use App\Http\Controllers\Admin\PartnershipAdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/partnerships',            [PartnershipAdminController::class, 'index'])->name('partnership.index');
    Route::get('/partnerships/{id}',       [PartnershipAdminController::class, 'show'])->name('partnership.show');
    Route::post('/partnerships/{id}',      [PartnershipAdminController::class, 'update'])->name('partnership.update');
    Route::get('/partnership/delete/{id}', [PartnershipAdminController::class, 'deletePage'])->name('partnership.deletePage');
    Route::delete('/partnership/{id}',     [PartnershipAdminController::class, 'delete'])->name('partnership.delete');

});
