<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Admin;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Get admin from Laravel Auth guard
        $admin = Auth::guard('admin')->user();
        
        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        // Check if admin is active
        if (!$admin->is_active) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')->with('error', 'Akun Anda telah dinonaktifkan. Silakan hubungi Super Admin.');
        }

        // Super admin has access to everything
        if ($admin->role === 'super_admin') {
            return $next($request);
        }

        // Check if admin's role is in the allowed roles
        if (in_array($admin->role, $roles)) {
            return $next($request);
        }

        // If not authorized, redirect with error
        return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
}
