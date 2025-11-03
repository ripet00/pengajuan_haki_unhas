<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\SubmissionController as AdminSubmissionController;
use App\Http\Controllers\User\SubmissionController as UserSubmissionController;

// Default route redirects based on authentication status
Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/users/dashboard');
    }
    if (session('admin_id')) {
        return redirect('/admin');
    }
    return redirect('/login');
});

// User Authentication Routes
Route::prefix('/')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [UserAuthController::class, 'showLoginForm'])->name('login'); // Laravel default name
        Route::post('/login', [UserAuthController::class, 'login']);
        
        Route::get('/register', [UserAuthController::class, 'showRegisterForm'])->name('user.register');
        Route::post('/register', [UserAuthController::class, 'register']);
    });
});

// User protected routes with /users prefix
Route::prefix('users')->middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('user.dashboard_modern');
    })->name('user.dashboard');
    
    Route::post('/logout', [UserAuthController::class, 'logout'])->name('user.logout');
    
    // User submission routes
    Route::get('submissions', [UserSubmissionController::class, 'index'])->name('user.submissions.index');
    Route::get('submissions/create', [UserSubmissionController::class, 'create'])->name('user.submissions.create');
    Route::post('submissions', [UserSubmissionController::class, 'store'])->middleware('file.upload')->name('user.submissions.store');
    Route::get('submissions/{submission}', [UserSubmissionController::class, 'show'])->name('user.submissions.show');
    Route::get('submissions/{submission}/download', [UserSubmissionController::class, 'download'])->name('user.submissions.download');
    Route::post('submissions/{submission}/resubmit', [UserSubmissionController::class, 'resubmit'])->middleware('file.upload')->name('user.submissions.resubmit');
});

// Admin Authentication Routes
Route::prefix('admin')->group(function () {
    Route::middleware('admin.guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [AdminAuthController::class, 'login']);
    });
    
    // Admin protected routes
    Route::middleware('admin.auth')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        // User management routes
        Route::patch('/users/{user}/status', [AdminController::class, 'updateUserStatus'])->name('admin.users.update-status');
        Route::get('/users', [AdminController::class, 'userIndex'])->name('admin.users.index');
        
        // Admin management routes
        Route::get('/admins', [AdminController::class, 'adminIndex'])->name('admin.admins.index');
        Route::get('/create-admin', [AdminController::class, 'createAdmin'])->name('admin.create');
        Route::post('/create-admin', [AdminController::class, 'storeAdmin'])->name('admin.store');
        
        // Admin submission routes
        Route::get('submissions', [AdminSubmissionController::class, 'index'])->name('admin.submissions.index');
        Route::get('submissions/{submission}', [AdminSubmissionController::class, 'show'])->name('admin.submissions.show');
        Route::get('submissions/{submission}/download', [AdminSubmissionController::class, 'download'])->name('admin.submissions.download');
        Route::post('submissions/{submission}/review', [AdminSubmissionController::class, 'review'])->name('admin.submissions.review');
        
        // Jenis Karya management routes
        Route::resource('jenis-karyas', \App\Http\Controllers\Admin\JenisKaryaController::class)->names([
            'index' => 'admin.jenis-karyas.index',
            'create' => 'admin.jenis-karyas.create',
            'store' => 'admin.jenis-karyas.store',
            'show' => 'admin.jenis-karyas.show',
            'edit' => 'admin.jenis-karyas.edit',
            'update' => 'admin.jenis-karyas.update',
            'destroy' => 'admin.jenis-karyas.destroy',
        ]);
        
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    });
});
