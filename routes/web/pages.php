<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\HowItWorksController;
use App\Http\Controllers\DdrfController;
use App\Http\Controllers\NewsletterController;
use Illuminate\Support\Facades\Route;

Route::get('/how-it-works', [HowItWorksController::class, 'index'])->name('how.it.works');
Route::get('/about', [AboutController::class, 'index'])->name('about');

Route::get('/disaster-relief', [DdrfController::class, 'index'])->name('ddrf.index');

Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
     ->middleware('throttle:5,1')
     ->name('newsletter.subscribe');
