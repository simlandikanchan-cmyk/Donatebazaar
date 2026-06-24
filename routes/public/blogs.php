<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicBlogController;

Route::prefix('blogs')->name('blogs.')->group(function () {

    Route::get('/',
        [PublicBlogController::class, 'index']
    )->name('index');

    Route::get('/category/{slug}',
        [PublicBlogController::class, 'byCategory']
    )->name('category');

    Route::get('/tag/{slug}',
        [PublicBlogController::class, 'byTag']
    )->name('tag');

    Route::middleware(['auth', 'verified'])->group(function () {

        Route::post('/{blog}/like',
            [PublicBlogController::class, 'toggleLike']
        )->name('like');

        Route::post('/{blog}/comment',
            [PublicBlogController::class, 'comment']
        )->name('comment');

        Route::post('/{blog}/report',
            [PublicBlogController::class, 'report']
        )->name('report');
    });

    Route::get('/{slug}',
        [PublicBlogController::class, 'show']
    )->name('show');
});