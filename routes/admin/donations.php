<?php

use App\Http\Controllers\Admin\DonationController as AdminDonationController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/donations', [AdminDonationController::class, 'index'])->name('donations.index');
    Route::get('/donations/{donation}', [AdminDonationController::class, 'show'])->name('donations.show');
    Route::delete('/donations/{donation}', [AdminDonationController::class, 'destroy'])->name('donations.destroy');
    Route::post('/donations/{donation}/refund', [AdminDonationController::class, 'refund'])->middleware('throttle:financial')->name('donations.refund');

});
