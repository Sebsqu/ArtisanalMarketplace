<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::get('/', function () { return view('app.layout'); });
Route::get('/registerForm', [AuthController::class, 'registerForm'])->name('registerForm');
Route::post('/createUser', [AuthController::class, 'createUser'])->name('createUser');
Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail'])->name('verifyEmail');
Route::get('/loginForm', [AuthController::class, 'loginForm'])->name('loginForm');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/forgot-password', [AuthController::class, 'forgotPasswordForm'])->name('passwordRequest');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('passwordEmail');
Route::get('/reset-password/{token}', [AuthController::class, 'resetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
