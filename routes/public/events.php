<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

Route::get('/events/{event}',
    [EventController::class, 'show']
)->name('events.show');

Route::get('/events/{event}/edit',
    [EventController::class, 'edit']
)->name('events.edit');

Route::put('/events/{event}',
    [EventController::class, 'update']
)->name('events.update');