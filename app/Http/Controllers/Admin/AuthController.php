<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function login(Request $request) {
        $credentials = $request->validate([
            'nip_nidn_nidk_nim' => 'required|string|max:20',
            'password' => 'required|string|min:8',
        ]);

        $admin = Admin::where('nip_nidn', $credentials['nip_nidn'])->first();

        if (!$admin || !Hash::check($credentials['password'], $admin->password)) {
            return response()->json(['message' => 'Data login salah'], 401);
        }

        Auth::guard('admin')->login($admin);
        return response()->json(['message' => 'Login berhasil', 'admin' => $admin]);
    }

    public function logout() {
        Auth::logout();
        return response()->json(['message' => 'Logout berhasil']);
    }
}
