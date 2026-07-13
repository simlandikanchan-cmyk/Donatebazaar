<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\HowItWorksController;
use App\Http\Controllers\DdrfController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/how-it-works', [HowItWorksController::class, 'index'])->name('how.it.works');
Route::get('/about', [AboutController::class, 'index'])->name('about');

Route::get('/disaster-relief', [DdrfController::class, 'index'])->name('ddrf.index');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::get('/robots.txt', function () {
    return response("User-agent: *\nDisallow:\n\nSitemap: " . url('/sitemap.xml') . "\n")
        ->header('Content-Type', 'text/plain');
});

Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
     ->middleware('throttle:5,1')
     ->name('newsletter.subscribe');

Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])
     ->name('newsletter.unsubscribe');
