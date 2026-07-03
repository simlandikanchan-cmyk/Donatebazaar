<?php

use App\Http\Controllers\VolunteerController;
use Illuminate\Support\Facades\Route;

Route::get('/volunteer/apply', [VolunteerController::class, 'apply'])->name('volunteer.apply');

Route::middleware('auth')->group(function () {
    Route::get('/campaign/{id}/volunteers', [VolunteerController::class, 'campaignVolunteers'])->name('volunteers.campaign');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::post('/admin/volunteer/{id}/status', [VolunteerController::class, 'updateStatus'])
         ->name('admin.volunteer.status');
});
