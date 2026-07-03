<?php

use App\Http\Controllers\CategoryProductController;
use Illuminate\Support\Facades\Route;

Route::get('/category/{id}/products', [CategoryProductController::class, 'getProducts'])
     ->name('category.products');
