<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Admin;
use App\Models\PasswordResetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class PasswordResetController extends Controller
{
    /**
     * Show forgot password form
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle forgot password request submission
     */
    public function submitRequest(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'country_code' => 'required|string|max:5',
        ]);

        // Rate limiting: max 3 attempts per IP per 15 minutes
        $key = 'forgot-password:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'phone_number' => "Terlalu banyak percobaan. Silakan coba lagi dalam " . ceil($seconds / 60) . " menit.",
            ]);
        }

        RateLimiter::hit($key, 900); // 15 minutes

        // Check if user exists and is active
        $user = User::where('phone_number', $request->phone_number)
                    ->where('country_code', $request->country_code)
                    ->where('status', 'active')
                    ->first();

        $admin = Admin::where('phone_number', $request->phone_number)
                      ->where('country_code', $request->country_code)
                      ->where('is_active', true)
                      ->first();

        // Generic response (don't reveal if account exists)
        $genericMessage = 'Jika nomor terdaftar dan aktif, permintaan reset password Anda akan diproses oleh admin. Anda akan dihubungi melalui WhatsApp untuk verifikasi.';

        if (!$user && !$admin) {
            return back()->with('success', $genericMessage);
        }

        $userType = $user ? 'user' : 'admin';
        $userId = $user ? $user->id : $admin->id;

        // Check if there's a recent pending request (within last 24 hours)
        $recentRequest = PasswordResetRequest::where('phone_number', $request->phone_number)
            ->where('country_code', $request->country_code)
            ->where('user_type', $userType)
            ->where('status', 'pending')
            ->where('requested_at', '>=', now()->subDay())
            ->first();

        if ($recentRequest) {
            return back()->with('info', 'Permintaan reset password Anda sedang diproses oleh admin. Harap tunggu konfirmasi melalui WhatsApp.');
        }

        // Create new reset request
        PasswordResetRequest::create([
            'user_type' => $userType,
            'phone_number' => $request->phone_number,
            'country_code' => $request->country_code,
            'user_id' => $userId,
            'status' => 'pending',
            'requested_at' => now(),
            'request_ip' => $request->ip(),
            'request_user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', $genericMessage);
    }

    /**
     * Show reset password form (when user clicks link from admin)
     */
    public function showResetForm(Request $request, string $token)
    {
        $phoneNumber = $request->query('phone');
        $countryCode = $request->query('country_code', '+62');

        if (!$phoneNumber) {
            return redirect('/login')->with('error', 'Link tidak valid. Parameter nomor telepon tidak ditemukan.');
        }

        // Find the latest reset request for this phone
        $resetRequest = PasswordResetRequest::where('phone_number', $phoneNumber)
            ->where('country_code', $countryCode)
            ->where('status', 'sent')
            ->where('used', false)
            ->orderBy('token_created_at', 'desc')
            ->first();

        if (!$resetRequest) {
            return redirect('/login')->with('error', 'Link reset password tidak valid atau sudah digunakan.');
        }

        // Check if expired
        if ($resetRequest->isExpired()) {
            $resetRequest->markAsExpired();
            return redirect('/login')->with('error', 'Link reset password sudah kadaluarsa. Silakan request ulang.');
        }

        // Verify token
        if (!$resetRequest->verifyToken($token)) {
            return redirect('/login')->with('error', 'Link reset password tidak valid.');
        }

        return view('auth.reset-password', [
            'token' => $token,
            'phone_number' => $phoneNumber,
            'country_code' => $countryCode,
            'user_type' => $resetRequest->user_type,
        ]);
    }

    /**
     * Process password reset
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'phone_number' => 'required|string',
            'country_code' => 'required|string|max:5',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Find the reset request
        $resetRequest = PasswordResetRequest::where('phone_number', $request->phone_number)
            ->where('country_code', $request->country_code)
            ->where('status', 'sent')
            ->where('used', false)
            ->orderBy('token_created_at', 'desc')
            ->first();

        if (!$resetRequest) {
            return back()->with('error', 'Link reset password tidak valid atau sudah digunakan.');
        }

        // Check if expired
        if ($resetRequest->isExpired()) {
            $resetRequest->markAsExpired();
            return back()->with('error', 'Link reset password sudah kadaluarsa. Silakan request ulang.');
        }

        // Verify token
        if (!$resetRequest->verifyToken($request->token)) {
            return back()->with('error', 'Link reset password tidak valid.');
        }

        // Update password based on user type
        if ($resetRequest->user_type === 'user') {
            $user = User::where('phone_number', $request->phone_number)
                       ->where('country_code', $request->country_code)
                       ->first();

            if (!$user) {
                return back()->with('error', 'User tidak ditemukan.');
            }

            $user->update([
                'password' => Hash::make($request->password),
            ]);

            // Mark as used
            $resetRequest->markAsUsed($request->ip());

            return redirect('/login')->with('success', 'Password berhasil diubah. Silakan login dengan password baru Anda.');

        } else { // admin
            $admin = Admin::where('phone_number', $request->phone_number)
                         ->where('country_code', $request->country_code)
                         ->first();

            if (!$admin) {
                return back()->with('error', 'Admin tidak ditemukan.');
            }

            $admin->update([
                'password' => Hash::make($request->password),
            ]);

            // Mark as used
            $resetRequest->markAsUsed($request->ip());

            return redirect('/admin/login')->with('success', 'Password berhasil diubah. Silakan login dengan password baru Anda.');
        }
    }
}
