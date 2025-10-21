<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin; // Mengimpor model Admin untuk berinteraksi dengan tabel 'admins'
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Mengimpor Hash untuk memeriksa password
use Illuminate\Validation\ValidationException; // Untuk menangani error validasi

class AdminAuthController extends Controller
{
    // Menampilkan halaman login admin
    public function showLoginForm()
    {
        return view('auth.admin.login_new'); // Mengembalikan view/tampilan login untuk admin
    }

    // Memproses data login yang dikirim dari form
    public function login(Request $request)
    {
        // 1. Validasi Input: Memastikan nomor telepon dan password tidak kosong.
        $request->validate([
            'phone_number' => 'required|string',
            'password' => 'required|string',
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

        // 5. Redirect ke Dashboard: Arahkan admin ke halaman dashboard admin.
        return redirect()->intended('/admin');
    }

    // Memproses logout admin
    public function logout(Request $request)
    {
        // 1. Hapus Sesi: Hapus data 'admin_id' dari session.
        $request->session()->forget('admin_id');

        // 2. Invalidate Sesi: Batalkan sesi saat ini untuk mencegah pembajakan sesi.
        $request->session()->invalidate();

        // 3. Regenerate Token: Buat token CSRF baru.
        $request->session()->regenerateToken();

        // 4. Redirect ke Halaman Login: Arahkan kembali ke halaman login admin.
        return redirect('/admin/login');
    }
}