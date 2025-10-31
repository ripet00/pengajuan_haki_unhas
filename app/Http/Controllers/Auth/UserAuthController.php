<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserAuthController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/users/dashboard';

    public function showLoginForm(Request $request)
    {
        // Check if user was redirected from a protected page
        $intended = $request->session()->get('url.intended');
        $message = null;
        
        if ($intended && str_contains($intended, '/dashboard')) {
            $message = 'Silakan login terlebih dahulu untuk mengakses dashboard.';
        }
        
        // Get remembered phone number from old input or Auth remember me
        $rememberedPhone = old('phone_number');
        
        return view('auth.user.login_new', compact('message', 'rememberedPhone'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'password' => 'required|string',
            'remember' => 'boolean',
        ]);

        $user = User::where('phone_number', $request->phone_number)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'phone_number' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Check user status
        if ($user->status === 'pending') {
            throw ValidationException::withMessages([
                'phone_number' => ['Your account is still pending approval. Please wait for admin confirmation.'],
            ]);
        }

        if ($user->status === 'denied') {
            throw ValidationException::withMessages([
                'phone_number' => ['Your account has been denied. Please contact administrator.'],
            ]);
        }

        // Only allow active users to login
        if ($user->status !== 'active') {
            throw ValidationException::withMessages([
                'phone_number' => ['Your account is not active. Please contact administrator.'],
            ]);
        }

        // Login with remember me functionality
        $remember = $request->boolean('remember');
        Auth::login($user, $remember);

        return redirect()->intended('/users/dashboard');
    }

    public function showRegisterForm()
    {
        return view('auth.user.register_new');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|unique:users',
            'faculty' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'faculty' => $request->faculty,
            'password' => Hash::make($request->password),
            'status' => 'pending', // Default status
        ]);

        // Don't auto-login since user is pending approval
        return redirect('/login')->with('success', 'Account created successfully! Please wait for admin approval before you can login.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}