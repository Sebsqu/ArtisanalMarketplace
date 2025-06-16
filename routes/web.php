<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Products\ProductsController;

Route::get('/', [HomeController::class, 'index']);

Route::get('/products', [ProductsController::class, 'index'])->name('products');
