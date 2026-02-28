<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::prefix('register')->group(function () {
        Route::get('/', [RegisterController::class, 'show'])->name('register');
        Route::post('/', [RegisterController::class, 'handle']);
    });
    Route::prefix('login')->group(function () {
        Route::get('/', [LoginController::class, 'show'])->name('login');
        Route::post('/', [LoginController::class, 'handle']);
    });
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LogoutController::class, 'handle'])->name('logout');
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
});
