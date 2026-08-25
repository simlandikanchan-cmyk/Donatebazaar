<?php

/*
|--------------------------------------------------------------------------
| API Routes — Entry Point
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.')->middleware('throttle:60,1')->group(function () {
    require __DIR__.'/api/health.php';
    require __DIR__.'/api/payments.php';
    require __DIR__.'/api/auth.php';
    require __DIR__.'/api/campaigns.php';
    require __DIR__.'/api/donations.php';
    require __DIR__.'/api/events.php';
    require __DIR__.'/api/users.php';
    require __DIR__.'/api/notifications.php';
    require __DIR__.'/api/states.php';
    require __DIR__.'/api/cities.php';
});
