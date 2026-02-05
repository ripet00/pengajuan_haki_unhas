<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if admin is authenticated using Laravel Auth guard
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')->with('error', 'Please login as admin to access this page.');
        }

        // Get authenticated admin
        $admin = Auth::guard('admin')->user();
        
        // Check if admin is active
        if (!$admin || !$admin->is_active) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')->with('error', 'Akun Anda telah dinonaktifkan. Silakan hubungi Super Admin.');
        }

        return $next($request);
    }
}
