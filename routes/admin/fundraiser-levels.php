<?php

use App\Http\Controllers\Admin\FundraiserLevelController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::resource('fundraiser-levels', FundraiserLevelController::class);

});
