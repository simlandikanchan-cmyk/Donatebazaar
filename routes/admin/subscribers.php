<?php

use App\Http\Controllers\Admin\SubscriberController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/subscribers',                  [SubscriberController::class, 'index'])->name('subscribers.index');
    Route::get('/subscribers/export',           [SubscriberController::class, 'export'])->name('subscribers.export');
    Route::post('/subscribers/{subscriber}/unsubscribe', [SubscriberController::class, 'unsubscribe'])->name('subscribers.unsubscribe');
    Route::post('/subscribers/{subscriber}/resubscribe', [SubscriberController::class, 'resubscribe'])->name('subscribers.resubscribe');
    Route::delete('/subscribers/{subscriber}',  [SubscriberController::class, 'destroy'])->name('subscribers.destroy');

});
