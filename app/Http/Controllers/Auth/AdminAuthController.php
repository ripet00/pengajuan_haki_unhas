<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    /**
     * Get the guard to be used during authentication.
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }

    /**
     * Show admin login form
     */
    public function showLoginForm(Request $request)
    {
        // Get remembered phone number if exists
        $phoneNumber = old('phone_number');
        
        return view('auth.admin.login_new', [
            'remembered_phone' => $phoneNumber
        ]);
    }

    /**
     * Handle admin login request
     */
    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'phone_number' => 'required|string',
            'password' => 'required|string',
            'remember' => 'boolean',
        ]);

        // Find admin by phone number
        $admin = Admin::where('phone_number', $request->phone_number)->first();

        // Verify credentials
        if (!$admin || !Hash::check($request->password, $admin->password)) {
            throw ValidationException::withMessages([
                'phone_number' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Login using Laravel Auth guard
        // This automatically:
        // - Hashes and stores remember token
        // - Regenerates session ID
        // - Sets secure cookies (HttpOnly, Secure, SameSite)
        // - Handles remember me functionality
        $remember = $request->boolean('remember');
        $this->guard()->login($admin, $remember);

        // Redirect based on role
        if ($admin->role === Admin::ROLE_PENDAMPING_PATEN) {
            return redirect()->intended(route('admin.pendamping-paten.dashboard'));
        }
        
        return redirect()->intended('/admin');
    }

    /**
     * Handle admin logout
     */
    public function logout(Request $request)
    {
        // Laravel Auth automatically:
        // - Clears remember token from database
        // - Invalidates session
        // - Regenerates CSRF token
        // - Clears cookies
        $this->guard()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}