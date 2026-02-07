<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Add CSRF protection to web middleware group
        $middleware->web(append: [
            \App\Http\Middleware\VerifyCsrfToken::class,
        ]);
        
        $middleware->alias([
            'admin.auth' => \App\Http\Middleware\AdminAuthMiddleware::class,
            'admin.guest' => \App\Http\Middleware\AdminGuestMiddleware::class,
            'admin.role' => \App\Http\Middleware\CheckAdminRole::class,
            'file.upload' => \App\Http\Middleware\HandleFileUploadErrors::class,
            'check.user.status' => \App\Http\Middleware\CheckUserStatus::class,
        ]);
        
        // Set default redirect for unauthenticated users
        $middleware->redirectGuestsTo('/login');
        
        // Set default redirect for authenticated users
        $middleware->redirectUsersTo('/users/dashboard');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
