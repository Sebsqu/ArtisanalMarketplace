<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;

Route::get('/', [HomeController::class, 'index']);

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

Route::get('/products', [ProductsController::class, 'index'])->name('products');
Route::get('/product/{id}', [ProductsController::class, 'showProduct'])->name('showProduct');

Route::get('/user/{id}', [UserController::class, 'showUser'])->name('showUser');

Route::middleware('IsLoggedIn')->group(function () {
    Route::get('/addProductForm', [ProductsController::class, 'addProductForm'])->name('addProductForm');
    Route::post('/addProduct', [ProductsController::class, 'addProduct'])->name('addProduct');
    Route::post('/favorite/{id}', [ProductsController::class, 'addToFavorite'])->name('addToFavorite');
    Route::post('/rate-product/{id}', [ProductsController::class, 'rateProduct'])->name('rateProduct');
    Route::post('/addToCart/{id}', [ProductsController::class, 'addToCart'])->name('addToCart');
    Route::get('/cart', [ProductsController::class, 'cart'])->name('cart');
    Route::delete('/cart/{id}', [ProductsController::class, 'removeItem'])->name('removeItem');
    Route::get('/checkout', [ProductsController::class, 'checkout'])->name('checkout');
    Route::post('/place-order', [ProductsController::class, 'placeOrder'])->name('placeOrder');

    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/editProduct/{id}', [UserController::class, 'editProduct'])->name('editProduct');
    Route::post('/saveEditProduct/{id}', [UserController::class, 'saveEditProduct'])->name('saveEditProduct');
    Route::get('/user-settings/{id}', [UserController::class, 'userSettingsForm'])->name('userSettingsForm');
    Route::post('/save-user/{id}', [UserController::class, 'saveUser'])->name('saveUser');
    Route::get('/favorites', [UserController::class, 'favorites'])->name('favorites');
    Route::post('/rate-user/{id}', [UserController::class, 'rateUser'])->name('rateUser');
    Route::get('/ordersHistory', [UserController::class, 'ordersHistory'])->name('ordersHistory');
});

Route::middleware('IsAdmin')->group(function () {
    Route::get('/adminDashboard', [AdminController::class, 'adminDashboard'])->name('adminDashboard');
    Route::get('/adminEditProduct/{id}', [AdminController::class, 'adminEditProduct'])->name('adminEditProduct');
    Route::post('/adminSaveEditProduct/{id}', [AdminController::class, 'adminSaveEditProduct'])->name('adminSaveEditProduct');
    Route::get('/adminUsers', [AdminController::class, 'adminUsers'])->name('adminUsers');
    Route::get('/adminEditUser/{id}', [AdminController::class, 'adminEditUser'])->name('adminEditUser');
    Route::post('/adminSaveEditUser/{id}', [AdminController::class, 'adminSaveEditUser'])->name('adminSaveEditUser');
    Route::get('/adminOrders', [AdminController::class, 'adminOrders'])->name('adminOrders');
});


