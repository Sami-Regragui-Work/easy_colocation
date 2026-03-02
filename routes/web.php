<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ColocationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Auth

Route::middleware('guest')->group(function () {
    // Register
    Route::prefix('register')->group(function () {
        Route::get('/', [RegisterController::class, 'show'])->name('register');
        Route::post('/', [RegisterController::class, 'handle']);
    });

    // Login
    Route::prefix('login')->group(function () {
        Route::get('/', [LoginController::class, 'show'])->name('login');
        Route::post('/', [LoginController::class, 'handle']);
    });
});

Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [LogoutController::class, 'handle'])->name('logout');

    // Fallback route
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
});

// Colocation
Route::middleware('auth')->group(function () {
    Route::prefix('colocations')->name('colocations.')->group(function () {
        // Listing (Member or Owner)
        Route::get('/', [ColocationController::class, 'index'])->name('index');

        // Create (without role or admin)
        Route::get('/create', [ColocationController::class, 'create'])->name('create');
        Route::post('/', [ColocationController::class, 'store'])->name('store');

        // Show (Member or Owner)
        Route::get('/{colocation}', [ColocationController::class, 'show'])->name('show');

        // Edit (Owner‑only)
        Route::get('/{colocation}/edit', [ColocationController::class, 'edit'])->name('edit');
        Route::put('/{colocation}', [ColocationController::class, 'update'])->name('update');

        // Delete (Owner‑only)
        Route::delete('/{colocation}', [ColocationController::class, 'destroy'])->name('destroy');

        // Cancel (Owner‑only)
        Route::post('/{colocation}/cancel', [ColocationController::class, 'cancel'])->name('cancel');

        // Invite member (Owner‑only)
        Route::post('/{colocation}/invite', [ColocationController::class, 'invite'])->name('invite');

        // quit (Member‑only)
        Route::post('/{colocation}/quit', [ColocationController::class, 'quit'])->name('quit');

        // remove a member (Owner‑only)
        Route::post('/{colocation}/members/{member}/remove', [ColocationController::class, 'removeMember'])->name('removeMember');

        // Categories
        Route::prefix('{colocation}')->middleware('can:manage-categories')->name('categories.')->group(function () {
            // List all categories for colocation
            Route::get('categories', [CategoryController::class, 'index'])->name('index');

            // Create form
            Route::get('categories/create', [CategoryController::class, 'create'])->name('create');
            // Store new category
            Route::post('categories', [CategoryController::class, 'store'])->name('store');

            // Edit form
            Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
            // Update category
            Route::put('categories/{category}', [CategoryController::class, 'update'])->name('update');

            // Soft delete category (hard deletes expenses)
            Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('destroy');
        });
    });
});
