<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsletterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;
use Spatie\Health\Http\Controllers\HealthCheckResultsController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
    ->name('newsletter.subscribe');

Route::get('/health', HealthCheckResultsController::class)->name('health');

Route::post('/chatbot', [ChatbotController::class, 'chat']);
