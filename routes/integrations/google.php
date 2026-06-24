<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;

Route::get('/auth/google',
    [GoogleController::class, 'redirect']
)->name('auth.google');

Route::get('/auth/google/callback',
    [GoogleController::class, 'callback']
)->name('auth.google.callback');