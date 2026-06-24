<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::middleware(['auth'])->group(function () {

    Route::get('/profile',
        [ProfileController::class, 'show']
    )->name('profile.show');

    Route::get('/profile/edit',
        [ProfileController::class, 'edit']
    )->name('profile.edit');

    Route::patch('/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');

    Route::delete('/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');

    Route::post('/profile/avatar',
        [ProfileController::class, 'updateAvatar']
    )->name('profile.avatar');

});