<?php

use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::prefix('blogs')->name('blogs.')->group(function () {

        Route::pattern('blog', '[0-9]+');

        Route::get('/',          [AdminBlogController::class, 'index'])->name('index');
        Route::get('/pending',   [AdminBlogController::class, 'pending'])->name('pending');
        Route::get('/flagged',   [AdminBlogController::class, 'flagged'])->name('flagged');
        Route::get('/analytics', [AdminBlogController::class, 'analytics'])->name('analytics');
        Route::get('/carousel',  [AdminBlogController::class, 'carousel'])->name('carousel');
        Route::get('/create',    [AdminBlogController::class, 'create'])->name('create');
        Route::post('/',         [AdminBlogController::class, 'store'])->name('store');

        Route::post('/restore/{id}',     [AdminBlogController::class, 'restore'])->name('restore');
        Route::delete('/force/{id}',     [AdminBlogController::class, 'forceDestroy'])->name('force-destroy');
        Route::post('/carousel/reorder', [AdminBlogController::class, 'reorder'])->name('carousel.reorder');

        Route::get('/{blog}',          [AdminBlogController::class, 'show'])->name('show');
        Route::get('/{blog}/edit',     [AdminBlogController::class, 'edit'])->name('edit');
        Route::put('/{blog}',          [AdminBlogController::class, 'update'])->name('update');
        Route::delete('/{blog}',       [AdminBlogController::class, 'destroy'])->name('destroy');
        Route::post('/{blog}/approve', [AdminBlogController::class, 'approve'])->name('approve');
        Route::post('/{blog}/reject',  [AdminBlogController::class, 'reject'])->name('reject');
        Route::post('/{blog}/feature', [AdminBlogController::class, 'feature'])->name('feature');
        Route::post('/{blog}/archive', [AdminBlogController::class, 'archive'])->name('archive');
        Route::post('/{blog}/flag',    [AdminBlogController::class, 'flag'])->name('flag');

        Route::post('/reports/{report}/dismiss', [AdminBlogController::class, 'dismissReport'])->name('reports.dismiss');
    });

});
