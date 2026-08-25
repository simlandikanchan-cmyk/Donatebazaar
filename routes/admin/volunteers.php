<?php

use App\Http\Controllers\Admin\VolunteerAdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/volunteers', [VolunteerAdminController::class, 'index'])->name('volunteers.index');
    Route::get('/volunteers/{volunteer}', [VolunteerAdminController::class, 'show'])->name('volunteers.show');
    Route::delete('/volunteers/{volunteer}', [VolunteerAdminController::class, 'destroy'])->name('volunteers.destroy');

    Route::get('/volunteer-applications', [VolunteerAdminController::class, 'applications'])->name('volunteer_applications.index');
    Route::get('/volunteer-applications/{application}', [VolunteerAdminController::class, 'applicationShow'])->name('volunteer_applications.show');
    Route::post('/volunteer-applications/{application}/approve', [VolunteerAdminController::class, 'applicationApprove'])->name('volunteer_applications.approve');
    Route::post('/volunteer-applications/{application}/reject', [VolunteerAdminController::class, 'applicationReject'])->name('volunteer_applications.reject');

    Route::get('/volunteer-assignments', [VolunteerAdminController::class, 'assignments'])->name('volunteer_assignments.index');
    Route::get('/volunteer-assignments/create', [VolunteerAdminController::class, 'assignmentCreate'])->name('volunteer_assignments.create');
    Route::post('/volunteer-assignments', [VolunteerAdminController::class, 'assignmentStore'])->name('volunteer_assignments.store');
    Route::get('/volunteer-assignments/{assignment}/edit', [VolunteerAdminController::class, 'assignmentEdit'])->name('volunteer_assignments.edit');
    Route::put('/volunteer-assignments/{assignment}', [VolunteerAdminController::class, 'assignmentUpdate'])->name('volunteer_assignments.update');
    Route::delete('/volunteer-assignments/{assignment}', [VolunteerAdminController::class, 'assignmentDestroy'])->name('volunteer_assignments.destroy');

});
