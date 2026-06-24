<?php


use App\Modules\Activity\Controllers\ActivityController;

Route::middleware(['web'])->group(function () {

    //UI ROUTE
    Route::get('/feed', [ActivityController::class, 'index']);

    //API ROUTE
    Route::get('/api/feed', [ActivityController::class, 'apiFeed']);

    Route::post('/activity', [ActivityController::class, 'store']);
});