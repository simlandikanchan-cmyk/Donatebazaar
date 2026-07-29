<?php

use App\Http\Controllers\NotificationPreferenceController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('notification-types', [NotificationPreferenceController::class, 'getTypes'])->name('notification.types');
    Route::get('notification-preferences', [NotificationPreferenceController::class, 'index'])->name('notification.preferences.index');
    Route::post('notification-preferences', [NotificationPreferenceController::class, 'store'])->name('notification.preferences.store');
    Route::post('notification-preferences/reset-all', [NotificationPreferenceController::class, 'resetAll'])->name('notification.preferences.reset-all');
    Route::put('notification-preferences/{type}/{channel}', [NotificationPreferenceController::class, 'update'])->name('notification.preferences.update');
    Route::delete('notification-preferences/{type}/{channel}', [NotificationPreferenceController::class, 'destroy'])->name('notification.preferences.destroy');
});
