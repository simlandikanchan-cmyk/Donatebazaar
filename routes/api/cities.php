<?php

use Illuminate\Support\Facades\Route;

Route::get('/cities/{state}', function (string $state) {
    return response()->json(config('india.cities.'.$state, []));
})->name('cities');
