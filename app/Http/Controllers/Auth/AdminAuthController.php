<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin; // Mengimpor model Admin untuk berinteraksi dengan tabel 'admins'
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Mengimpor Hash untuk memeriksa password
use Illuminate\Support\Str; // Mengimpor Str untuk generate random token
use Illuminate\Validation\ValidationException; // Untuk menangani error validasi

class AdminAuthController extends Controller
{
    // Menampilkan halaman login admin
    public function showLoginForm(Request $request)
    {
        // Check if admin has remember me cookie and auto-login
        $rememberToken = $request->cookie('admin_remember_token');
        $phoneNumber = $request->cookie('admin_phone_number');
        
        if ($rememberToken && $phoneNumber) {
            $admin = Admin::where('phone_number', $phoneNumber)
                         ->where('remember_token', $rememberToken)
                         ->first();
            
            if ($admin) {
                // Auto login the admin
                session(['admin_id' => $admin->id]);
                
                // Redirect based on role
                if ($admin->role === Admin::ROLE_PENDAMPING_PATEN) {
                    return redirect(route('admin.pendamping-paten.dashboard'));
                }
                
                return redirect('/admin');
            } else {
                // Invalid remember token, clear cookies
                cookie()->queue(cookie()->forget('admin_remember_token'));
                cookie()->queue(cookie()->forget('admin_phone_number'));
            }
        }
        
        return view('auth.admin.login_new', [
            'remembered_phone' => $phoneNumber ?? old('phone_number')
        ]); // Mengembalikan view/tampilan login untuk admin
    }

    // Memproses data login yang dikirim dari form
    public function login(Request $request)
    {
        // 1. Validasi Input: Memastikan nomor telepon dan password tidak kosong.
        $request->validate([
            'phone_number' => 'required|string',
            'password' => 'required|string',
            'remember' => 'boolean',
        ]);

        // 2. Cari Admin: Mencari admin di database berdasarkan nomor telepon.
        $admin = Admin::where('phone_number', $request->phone_number)->first();

        // 3. Verifikasi Kredensial:
        // Cek apakah admin ditemukan DAN password yang diinput sesuai dengan hash di database.
        if (!$admin || !Hash::check($request->password, $admin->password)) {
            // Jika tidak cocok, lempar error validasi dengan pesan.
            throw ValidationException::withMessages([
                'phone_number' => ['The provided credentials are incorrect.'],
            ]);
        }

        // 4. Buat Sesi (Session): Jika berhasil, simpan ID admin ke dalam session.
        // Ini adalah cara sederhana untuk menandai bahwa admin sudah login, tanpa menggunakan guard bawaan Laravel.
        session(['admin_id' => $admin->id]);

        // 5. Handle Remember Me: Jika checkbox remember me dicentang
        if ($request->boolean('remember')) {
            // Create a remember token that expires in 30 days
            $rememberToken = Str::random(60);
            
            // Save remember token to admin record
            $admin->update(['remember_token' => $rememberToken]);
            
            // Set a long-lived cookie (30 days)
            cookie()->queue('admin_remember_token', $rememberToken, 60 * 24 * 30); // 30 days
            cookie()->queue('admin_phone_number', $admin->phone_number, 60 * 24 * 30); // 30 days
        }

        // 6. Redirect ke Dashboard: Arahkan admin ke halaman dashboard sesuai role
        // Pendamping Paten diarahkan ke dashboard khusus mereka
        if ($admin->role === Admin::ROLE_PENDAMPING_PATEN) {
            return redirect()->intended(route('admin.pendamping-paten.dashboard'));
        }
        
        return redirect()->intended('/admin');
    }

    // Memproses logout admin
    public function logout(Request $request)
    {
        // Get current admin to clear remember token
        $adminId = session('admin_id');
        if ($adminId) {
            $admin = Admin::find($adminId);
            if ($admin) {
                $admin->update(['remember_token' => null]);
            }
        }
        
        // 1. Hapus Sesi: Hapus data 'admin_id' dari session.
        $request->session()->forget('admin_id');

        // 2. Invalidate Sesi: Batalkan sesi saat ini untuk mencegah pembajakan sesi.
        $request->session()->invalidate();

        // 3. Regenerate Token: Buat token CSRF baru.
        $request->session()->regenerateToken();

        // 4. Clear Remember Me Cookies
        cookie()->queue(cookie()->forget('admin_remember_token'));
        cookie()->queue(cookie()->forget('admin_phone_number'));

        // 5. Redirect ke Halaman Login: Arahkan kembali ke halaman login admin.
        return redirect('/admin/login');
    }
}