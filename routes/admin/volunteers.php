<?php

use App\Http\Controllers\Admin\VolunteerAdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/volunteers',                             [VolunteerAdminController::class, 'index'])->name('volunteers.index');
    Route::get('/volunteers/{volunteer}',                  [VolunteerAdminController::class, 'show'])->name('volunteers.show');

    Route::get('/volunteer-applications',                  [VolunteerAdminController::class, 'applications'])->name('volunteer_applications.index');
    Route::get('/volunteer-applications/{application}',    [VolunteerAdminController::class, 'applicationShow'])->name('volunteer_applications.show');
    Route::post('/volunteer-applications/{application}/approve', [VolunteerAdminController::class, 'applicationApprove'])->name('volunteer_applications.approve');
    Route::post('/volunteer-applications/{application}/reject',  [VolunteerAdminController::class, 'applicationReject'])->name('volunteer_applications.reject');

});
