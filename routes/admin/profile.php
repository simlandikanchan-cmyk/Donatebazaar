<?php

use App\Http\Controllers\Admin\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/profile',                      [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile',                    [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',                   [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/avatar',               [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::post('/profile/password',             [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile/sessions/{id}',      [ProfileController::class, 'revokeSession'])->name('profile.sessions.revoke');
    Route::delete('/profile/sessions',           [ProfileController::class, 'revokeAllSessions'])->name('profile.sessions.revoke-all');

});
