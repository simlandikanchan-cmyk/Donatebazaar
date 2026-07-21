<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\DdrfController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HowItWorksController;
use App\Http\Controllers\ImpactController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/how-it-works', [HowItWorksController::class, 'index'])->name('how.it.works');
Route::get('/about', [AboutController::class, 'index'])->name('about');

Route::get('/disaster-relief', [DdrfController::class, 'index'])->name('ddrf.index');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::get('/robots.txt', function () {
    return response("User-agent: *\nDisallow:\n\nSitemap: ".url('/sitemap.xml')."\n")
        ->header('Content-Type', 'text/plain');
});

Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
    ->middleware('throttle:5,1')
    ->name('newsletter.subscribe');

Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])
    ->name('newsletter.unsubscribe');

Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::get('/privacy-policy', [LegalController::class, 'privacy'])->name('privacy');
Route::get('/terms-of-service', [LegalController::class, 'terms'])->name('terms');
Route::get('/refund-cancellation', [LegalController::class, 'refund'])->name('refund');
Route::get('/cookie-policy', [LegalController::class, 'cookies'])->name('cookies');

Route::get('/faq', [FaqController::class, 'index'])->name('faq');

Route::get('/impact', [ImpactController::class, 'index'])->name('impact');
