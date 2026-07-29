<?php

use Illuminate\Support\Facades\Route;

Route::get('/states/{country}', function (string $country) {
    if (strtolower($country) !== 'india') {
        return response()->json([]);
    }

    return response()->json(config('india.states', []));
})->name('states');
