<?php

use App\Http\Controllers\Admin\FaqController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::resource('faqs', FaqController::class);

});
