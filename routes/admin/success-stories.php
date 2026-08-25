<?php

use App\Http\Controllers\Admin\SuccessStoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/success-stories', [SuccessStoryController::class, 'index'])->name('success-stories.index');
    Route::delete('/success-stories/{campaign}', [SuccessStoryController::class, 'destroy'])->name('success-stories.destroy');
    Route::post('/success-stories/{campaign}/toggle', [SuccessStoryController::class, 'toggleFeatured'])->name('success-stories.toggle');

});
