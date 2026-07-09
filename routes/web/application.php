<?php

use App\Http\Controllers\Admin\ApplicationController;
use Illuminate\Support\Facades\Route;

/*
 * Public "Become an Organization" application flow.
 * Auth required — the controller binds the draft to the logged-in user.
 */
Route::middleware('auth')->group(function () {
    Route::get('application/step1', [ApplicationController::class, 'step1'])->name('application.step1');
    Route::post('application/step1', [ApplicationController::class, 'step1Post'])->name('application.step1.post');

    Route::get('application/step2', [ApplicationController::class, 'step2'])->name('application.step2');
    Route::post('application/step2', [ApplicationController::class, 'step2Post'])->name('application.step2.post');

    Route::get('application/step3', [ApplicationController::class, 'step3'])->name('application.step3');
    Route::post('application/step3', [ApplicationController::class, 'step3Post'])->name('application.step3.post');

    Route::get('application/step4', [ApplicationController::class, 'step4'])->name('application.step4');
    Route::post('application/submit', [ApplicationController::class, 'submit'])->name('application.submit');
});
