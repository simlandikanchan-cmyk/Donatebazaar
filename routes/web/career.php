<?php

use App\Http\Controllers\JobPostController;
use Illuminate\Support\Facades\Route;

Route::prefix('career')->name('job_posts.')->group(function () {
    Route::get('/',                      [JobPostController::class, 'index'])->name('index');
    Route::get('/{jobPost:slug}',        [JobPostController::class, 'show'])->name('show');
    Route::post('/{jobPost:slug}/apply', [JobPostController::class, 'apply'])
         ->middleware('throttle:5,1')
         ->name('apply');
});
