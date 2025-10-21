<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Menampilkan halaman dashboard admin
    public function dashboard()
    {
        // 1. Ambil Data: Mengambil data user berdasarkan statusnya dari database.
        $pendingUsers = User::where('status', 'pending')->get();
        $activeUsers = User::where('status', 'active')->count();
        $deniedUsers = User::where('status', 'denied')->count();
        $totalUsers = User::count();

        // 2. Kirim ke View: Mengirimkan semua data tersebut ke view 'admin.dashboard_modern'.
        return view('admin.dashboard_modern', compact('pendingUsers', 'activeUsers', 'deniedUsers', 'totalUsers'));
    }

    // Menampilkan halaman manajemen user
    public function userIndex()
    {
        // Ambil semua user, urutkan berdasarkan yang terbaru, dan bagi per 15 data per halaman (pagination).
        $users = User::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    // Menampilkan halaman manajemen admin
    public function adminIndex()
    {
        $admins = Admin::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.admins.index', compact('admins'));
    }

    // Memproses perubahan status user (misal: menyetujui atau menolak)
    public function updateUserStatus(Request $request, User $user)
    {
        // 1. Validasi: Pastikan status yang dikirim adalah salah satu dari 'active', 'pending', atau 'denied'.
        $request->validate([
            'status' => 'required|in:active,pending,denied'
        ]);

        // 2. Ambil data admin yang sedang login dari session.
        $admin = Admin::find(session('admin_id'));
        
        // 3. Update Data User: Perbarui status user di database.
        $user->update([
            'status' => $request->status,
            // Jika statusnya 'active', catat waktu verifikasi dan siapa yang memverifikasi.
            'verified_at' => $request->status === 'active' ? now() : null,
            'verified_by_admin_id' => $request->status === 'active' ? $admin->id : null,
        ]);

        // 4. Redirect Kembali: Kembali ke halaman sebelumnya dengan pesan sukses.
        return redirect()->back()->with('success', 'User status updated successfully!');
    }

    // Menampilkan halaman form untuk membuat admin baru
    public function createAdmin()
    {
        return view('admin.create-admin');
    }

    // Menyimpan data admin baru dari form
    public function storeAdmin(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:255',
            'nip_nidn_nidk_nim' => 'required|string|unique:admins', // Harus unik
            'phone_number' => 'required|string|unique:admins', // Harus unik
            'password' => 'required|string|min:8|confirmed',
        ]);

        // 2. Buat Admin Baru
        Admin::create([
            'name' => $request->name,
            'nip_nidn_nidk_nim' => $request->nip_nidn_nidk_nim,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password), // Enkripsi password
        ]);

        // 3. Redirect ke Dashboard Admin dengan pesan sukses.
        return redirect()->route('admin.dashboard')->with('success', 'Admin account created successfully!');
    }
}