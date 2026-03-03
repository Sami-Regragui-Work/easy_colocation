<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ColocationController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\SettlementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Auth – no banned check
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

// Auth and banned check
Route::middleware(['auth', 'banned'])->group(function () {
    // Logout
    Route::post('/logout', [LogoutController::class, 'handle'])->name('logout');

    // Settlement
    Route::post('/settlements/{settlement}/pay', [SettlementController::class, 'markPaid'])->name('settlements.markPaid');

    // Colocation
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

        // quit (Member‑only)
        Route::post('/{colocation}/quit', [ColocationController::class, 'quit'])->name('quit');

        // remove a member (Owner‑only)
        Route::post('/{colocation}/members/{member}/remove', [ColocationController::class, 'removeMember'])->name('members.remove');

        // Categories
        Route::prefix('{colocation}/categories')->middleware('manage.categories')->name('categories.')->group(function () {
            // List all categories for colocation
            Route::get('/', [CategoryController::class, 'index'])->name('index')->withoutMiddleware('manage.categories');

            // Create form
            Route::get('/create', [CategoryController::class, 'create'])->name('create');
            // Store new category
            Route::post('/', [CategoryController::class, 'store'])->name('store');

            // Edit form
            Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
            // Update category
            Route::put('/{category}', [CategoryController::class, 'update'])->name('update');

            // Soft delete category (hard deletes expenses)
            Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');

            // Expenses (Member access)
            Route::prefix('{category}/expenses')->middleware('colocation.member')->withoutMiddleware('manage.categories')->name('expenses.')->group(function () {
                // List expenses for category
                Route::get('/', [ExpenseController::class, 'index'])->name('index');

                // Create form
                Route::get('/create', [ExpenseController::class, 'create'])->name('create');
                // Store new expense
                Route::post('/', [ExpenseController::class, 'store'])->name('store');

                // Show single expense
                Route::get('/{expense}', [ExpenseController::class, 'show'])->name('show');

                // Edit form
                Route::get('/{expense}/edit', [ExpenseController::class, 'edit'])->name('edit');
                // Update expense
                Route::put('/{expense}', [ExpenseController::class, 'update'])->name('update');

                // Delete expense
                Route::delete('/{expense}', [ExpenseController::class, 'destroy'])->name('destroy');
            });
        });

        // Invitations
        Route::prefix('{colocation}/invitations')->name('invitations.')->group(function () {
            Route::get('/', [InvitationController::class, 'index'])->name('index');
            Route::post('/', [InvitationController::class, 'invite'])->name('invite');
        });
    });

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        // Profile edit form
        Route::get('/', [UserController::class, 'edit'])->name('edit');

        // Profile update
        Route::patch('/', [UserController::class, 'update'])->name('update');
    });

    // Admin (admin dashboard / user management)
    Route::middleware('admin')->name('admin.')->group(function () {
        // Admin dashboard
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Admin users management
        Route::prefix('admin/users')->name('users.')->group(function () {
            // List all users
            Route::get('/', [UserController::class, 'index'])->name('index');

            // Show single user details
            Route::get('/{user}', [UserController::class, 'show'])->name('show');

            // Ban user (Admin‑only action)
            Route::post('/{user}/ban', [UserController::class, 'ban'])->name('ban');

            // Unban user (Admin‑only action)
            Route::post('/{user}/unban', [UserController::class, 'unban'])->name('unban');
        });
    });
});

// Invitations: guest only, no banned check (accept happens before login check)
Route::middleware(['guest', 'valid.invitation', 'no.active.colocation'])->prefix('invitations/{invitation}')->name('invitations.')->group(function () {
    Route::get('/accept', [InvitationController::class, 'accept'])->name('accept');
    Route::post('/accept', [InvitationController::class, 'process'])->name('process');
});
