<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\PublicEventController;
use Illuminate\Support\Facades\Route;

Route::get('/events', [PublicEventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/events/{event}/register', [EventRegistrationController::class, 'register'])->name('events.register');
Route::post('/events/{event}/register', [EventRegistrationController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('events.register.store');

Route::middleware('auth')->group(function () {
    Route::get('/campaign/{campaign}/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/campaign/{campaign}/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
});
