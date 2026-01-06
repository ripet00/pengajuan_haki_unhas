<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Admin;

class AdminAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $adminId = session('admin_id');
        
        if (!$adminId) {
            return redirect()->route('admin.login')->with('error', 'Please login as admin to access this page.');
        }

        // Check if admin is active
        $admin = Admin::find($adminId);
        if (!$admin || !$admin->is_active) {
            session()->forget('admin_id');
            return redirect()->route('admin.login')->with('error', 'Akun Anda telah dinonaktifkan. Silakan hubungi Super Admin.');
        }

        return $next($request);
    }
}
