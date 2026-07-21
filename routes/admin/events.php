<?php

use App\Http\Controllers\Admin\EventController as AdminEventController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/events', [AdminEventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [AdminEventController::class, 'create'])->name('events.create');
    Route::post('/events', [AdminEventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}', [AdminEventController::class, 'show'])->name('events.show');
    Route::get('/events/{event}/edit', [AdminEventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [AdminEventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [AdminEventController::class, 'destroy'])->name('events.destroy');
    Route::post('/events/{event}/approve', [AdminEventController::class, 'approve'])->name('events.approve');
    Route::post('/events/{event}/reject', [AdminEventController::class, 'reject'])->name('events.reject');
    Route::post('/events/{event}/publish', [AdminEventController::class, 'publish'])->name('events.publish');
    Route::post('/events/{event}/draft', [AdminEventController::class, 'draft'])->name('events.draft');
    Route::post('/events/{event}/toggle-setting', [AdminEventController::class, 'toggleSetting'])->name('events.toggleSetting');

    Route::post('/events/{event}/assign-volunteer', [AdminEventController::class, 'assignVolunteer'])
        ->name('events.assignVolunteer');

});
