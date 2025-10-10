<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\AdminController;

// Default route redirects to login
Route::get('/', function () {
    return redirect('/login');
});

// User Authentication Routes
Route::prefix('/')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [UserAuthController::class, 'showLoginForm'])->name('user.login');
        Route::post('/login', [UserAuthController::class, 'login']);
        
        Route::get('/register', [UserAuthController::class, 'showRegisterForm'])->name('user.register');
        Route::post('/register', [UserAuthController::class, 'register']);
    });
    
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', function () {
            return view('user.dashboard_modern');
        })->name('user.dashboard');
        
        Route::post('/logout', [UserAuthController::class, 'logout'])->name('user.logout');
    });
});

// Admin Authentication Routes
Route::prefix('admin')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [AdminAuthController::class, 'login']);
    });
    
    // Admin dashboard routes (protected by custom middleware later)
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // User management routes
    Route::patch('/users/{user}/status', [AdminController::class, 'updateUserStatus'])->name('admin.users.update-status');
    Route::get('/users', [AdminController::class, 'userIndex'])->name('admin.users.index');
    
    // Admin management routes
    Route::get('/admins', [AdminController::class, 'adminIndex'])->name('admin.admins.index');
    Route::get('/create-admin', [AdminController::class, 'createAdmin'])->name('admin.create');
    Route::post('/create-admin', [AdminController::class, 'storeAdmin'])->name('admin.store');
    
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});
