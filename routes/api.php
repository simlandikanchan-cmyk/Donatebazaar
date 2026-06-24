<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Modules\Activity\Services\ActivityService;

Route::get('/add-test-activity', function (ActivityService $service) {

    $activity = $service->createActivity([
        'user_id' => null,
        'campaign_id' => 1,
        'type' => 'test',
        'title' => 'API Test Activity',
        'description' => 'This is coming from code',
        'visibility' => 'public',
        'meta' => json_encode(['amount' => 500])
    ]);

    return $activity;
});

Route::post('/payment/verify', [PaymentController::class, 'verify']);