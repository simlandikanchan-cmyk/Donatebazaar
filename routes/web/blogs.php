<?php

use App\Http\Controllers\PublicBlogController;
use App\Http\Controllers\User\BlogController as UserBlogController;
use Illuminate\Support\Facades\Route;

Route::prefix('blog')->name('blogs.')->group(function () {
    Route::get('/',                [PublicBlogController::class, 'index'])->name('index');
    Route::get('/category/{slug}', [PublicBlogController::class, 'byCategory'])->name('category');
    Route::get('/tag/{slug}',      [PublicBlogController::class, 'byTag'])->name('tag');

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::post('/{blog}/like',    [PublicBlogController::class, 'toggleLike'])->name('like');
        Route::post('/{blog}/comment', [PublicBlogController::class, 'comment'])->name('comment');
        Route::post('/{blog}/report',  [PublicBlogController::class, 'report'])->name('report');
    });

    Route::get('/{slug}', [PublicBlogController::class, 'show'])->name('show');
});

Route::middleware('auth')->group(function () {
    Route::prefix('user/dashboard/blogs')->name('user.blogs.')->group(function () {
        Route::get('/',               [UserBlogController::class, 'index'])->name('index');
        Route::get('/create',         [UserBlogController::class, 'create'])->name('create');
        Route::post('/',              [UserBlogController::class, 'store'])->name('store');
        Route::post('/restore/{id}',  [UserBlogController::class, 'restore'])->name('restore');
        Route::get('/{blog}',         [UserBlogController::class, 'show'])->name('show');
        Route::get('/{blog}/edit',    [UserBlogController::class, 'edit'])->name('edit');
        Route::put('/{blog}',         [UserBlogController::class, 'update'])->name('update');
        Route::delete('/{blog}',      [UserBlogController::class, 'destroy'])->name('destroy');
        Route::post('/{blog}/submit', [UserBlogController::class, 'submit'])->name('submit');
    });
});
