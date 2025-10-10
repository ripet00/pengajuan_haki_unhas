<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15|unique:users,phone_number',
            'faculty' => 'nullable|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $validated['paddword'] = Hash::make($validated['password']);
        $validated['status'] = 'pending';

        $user = User::create($validated);
        
        return response()->json(['message' => 'Registrasi berhasil, menunggu verifikasi Admin.', 'user' => $user], 201);
    }

    // Login user
    public function login(Request $request) {
        $credentials = $request->validate([
            'phone_number' => 'required|string|max:15',
            'password' => 'required|string|min:8',
        ]);

        $user = User::where('phone_number', $credentials['phone_numbers'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Phone number atau password salah'], 401);
        }
        if ($user->status !=='approved') {
            return response()->json(['message' => 'Akun Anda belum diverifikasi oleh Admin'], 403);
        }
        Auth::login($user);
        return response()->json(['message' => 'Login berhasil', 'user' => $user]);
    }

    public function logout() {
        Auth::logout();
        return response()->json(['message' => 'Logout berhasil']);
    }
}
