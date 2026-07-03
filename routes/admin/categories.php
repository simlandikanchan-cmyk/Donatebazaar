<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CategoryProductController as AdminCategoryProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::resource('categories', CategoryController::class);
    Route::resource('category-products', AdminCategoryProductController::class);

});
