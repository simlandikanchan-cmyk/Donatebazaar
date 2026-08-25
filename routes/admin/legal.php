<?php

use App\Http\Controllers\Admin\LegalPageController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/legal', [LegalPageController::class, 'index'])->name('legal.index');
    Route::get('/legal/{slug}/edit', [LegalPageController::class, 'edit'])->name('legal.edit');
    Route::put('/legal/{slug}', [LegalPageController::class, 'update'])->name('legal.update');
    Route::delete('/legal/{slug}', [LegalPageController::class, 'destroy'])->name('legal.destroy');

});
