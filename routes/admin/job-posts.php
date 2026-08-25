<?php

use App\Http\Controllers\Admin\JobPostApplicationController;
use App\Http\Controllers\Admin\JobPostController as AdminJobPostController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::resource('job_posts', AdminJobPostController::class);

    Route::get('/job_post_applications', [JobPostApplicationController::class, 'index'])->name('job_post_applications.index');
    Route::get('/job_post_applications/{jobPostApplication}', [JobPostApplicationController::class, 'show'])->name('job_post_applications.show');
    Route::delete('/job_post_applications/{jobPostApplication}', [JobPostApplicationController::class, 'destroy'])->name('job_post_applications.destroy');
    Route::patch('/job_post_applications/{jobPostApplication}/status', [JobPostApplicationController::class, 'updateStatus'])->name('job_post_applications.updateStatus');
    Route::get('/job_post_applications/{jobPostApplication}/cv', [JobPostApplicationController::class, 'downloadCv'])->name('job_post_applications.downloadCv');

});
