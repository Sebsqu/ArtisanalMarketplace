<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/products', [ProductsController::class, 'index'])->name('products');

Route::get('/register', [AuthController::class, 'registerForm'])->name('registerForm');
Route::post('/create-user', [AuthController::class, 'createUser'])->name('createUser');
Route::get('/verify-account/{id}/{token}', [AuthController::class, 'verifyAccount'])->name('verifyAccount');
Route::get('/loginForm', [AuthController::class, 'loginForm'])->name('loginForm');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgotPassword');
Route::post('/send-link', [AuthController::class, 'sendLink'])->name('sendLink');
Route::get('/reset-password/{token}', [AuthController::class, 'resetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('resetPassword');

Route::middleware('IsLoggedIn')->group(function () {
    Route::get('/addProductForm', [ProductsController::class, 'addProductForm'])->name('addProductForm');
    Route::post('/addProduct', [ProductsController::class, 'addProduct'])->name('addProduct');
    Route::post('/favorite/{id}', [ProductsController::class, 'addToFavorite'])->name('addToFavorite');
    
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    // Route::get('/editProduct/{id}', [ProductsController::class, 'editProduct'])->name('editProduct');
});

Route::get('/product/{id}', [ProductsController::class, 'showProduct'])->name('showProduct');
