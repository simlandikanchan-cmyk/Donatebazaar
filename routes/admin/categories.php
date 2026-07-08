<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CategoryProductController as AdminCategoryProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::resource('categories', CategoryController::class);

    Route::prefix('category-products')->controller(AdminCategoryProductController::class)->group(function () {
        Route::get('export', 'exportCsv')->name('category-products.export');
        Route::post('bulk-toggle', 'bulkToggle')->name('category-products.bulk-toggle');
        Route::post('bulk-delete', 'bulkDelete')->name('category-products.bulk-delete');
    });
    Route::resource('category-products', AdminCategoryProductController::class);

});
