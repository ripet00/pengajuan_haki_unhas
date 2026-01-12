<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // Menampilkan halaman dashboard admin
    public function dashboard()
    {
        // Get current admin
        $admin = Admin::find(session('admin_id'));
        
        // Redirect Pendamping Paten to their own dashboard
        if ($admin && $admin->role === Admin::ROLE_PENDAMPING_PATEN) {
            return redirect()->route('admin.pendamping-paten.dashboard');
        }
        
        // 1. Ambil Data: Mengambil data user berdasarkan statusnya dari database.
        $pendingUsers = User::where('status', 'pending')->get();
        $activeUsers = User::where('status', 'active')->count();
        $deniedUsers = User::where('status', 'denied')->count();
        $totalUsers = User::count();

        // 2. Kirim ke View: Mengirimkan semua data tersebut ke view 'admin.dashboard_modern'.
        return view('admin.dashboard_modern', compact('pendingUsers', 'activeUsers', 'deniedUsers', 'totalUsers'));
    }

    // Menampilkan halaman manajemen user
    public function userIndex(Request $request)
    {
        $q = User::orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $q->where(function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('phone_number', 'LIKE', "%{$search}%")
                      ->orWhere('faculty', 'LIKE', "%{$search}%");
            });
        }

        // Ambil users dengan pagination dan pertahankan query parameters
        $users = $q->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    // Menampilkan halaman manajemen admin
    public function adminIndex(Request $request)
    {
        $q = Admin::where('role', '!=', Admin::ROLE_PENDAMPING_PATEN)
            ->orderBy('created_at', 'desc');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $q->where(function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('nip_nidn_nidk_nim', 'LIKE', "%{$search}%")
                      ->orWhere('phone_number', 'LIKE', "%{$search}%");
            });
        }

        // Ambil admins dengan pagination dan pertahankan query parameters
        $admins = $q->paginate(15);

        // Get Pendamping Paten list with active paten count
        $pendampingPatenList = Admin::where('role', Admin::ROLE_PENDAMPING_PATEN)
            ->withCount(['assignedPatenSubmissions as active_paten_count' => function ($query) {
                $query->whereIn('status', [
                    \App\Models\SubmissionPaten::STATUS_PENDING_SUBSTANCE_REVIEW,
                    \App\Models\SubmissionPaten::STATUS_REJECTED_SUBSTANCE_REVIEW
                ]);
            }])
            ->orderBy('name')
            ->get();

        return view('admin.admins.index', compact('admins', 'pendampingPatenList'));
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
        
        // 3. Check if status is changing from 'active' to something else
        $wasActive = $user->status === 'active';
        $nowInactive = $request->status !== 'active';
        
        // 4. Update Data User: Perbarui status user di database.
        $user->update([
            'status' => $request->status,
            // Jika statusnya 'active', catat waktu verifikasi dan siapa yang memverifikasi.
            'verified_at' => $request->status === 'active' ? now() : null,
            'verified_by_admin_id' => $request->status === 'active' ? $admin->id : null,
        ]);

        // 5. Force logout user if status changed from active to inactive
        if ($wasActive && $nowInactive) {
            // Delete all sessions for this user to force logout
            DB::table('sessions')->where('user_id', $user->id)->delete();
        }

        // 6. Redirect Kembali: Kembali ke halaman sebelumnya dengan pesan sukses.
        return redirect()->back()->with('success', 'User status updated successfully!');
    }

    // Memproses perubahan status admin (mengaktifkan/menonaktifkan)
    public function updateAdminStatus(Request $request, Admin $admin)
    {
        // 1. Validasi: Pastikan status yang dikirim adalah boolean
        $request->validate([
            'is_active' => 'required|boolean'
        ]);

        // 2. Cegah admin menonaktifkan diri sendiri
        if ($admin->id === session('admin_id')) {
            return redirect()->back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        // 3. Update status admin
        $admin->update([
            'is_active' => $request->is_active
        ]);

        $statusText = $request->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->back()->with('success', "Admin {$admin->name} berhasil {$statusText}!");
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
        $rules = [
            'name' => 'required|string|max:255',
            'nip_nidn_nidk_nim' => 'required|string|unique:admins',
            'phone_number' => 'required|string|unique:admins',
            'country_code' => 'required|string|max:5',
            'role' => 'required|in:super_admin,admin_paten,admin_hakcipta,pendamping_paten',
            'password' => 'required|string|min:8|confirmed',
        ];

        // Add fakultas and program_studi validation if role is Pendamping Paten
        if ($request->role === 'pendamping_paten') {
            $rules['fakultas'] = 'required|string|max:255';
            $rules['program_studi'] = 'required|string|max:255';
        }

        $request->validate($rules);

        // 2. Buat Admin Baru
        $adminData = [
            'name' => $request->name,
            'nip_nidn_nidk_nim' => $request->nip_nidn_nidk_nim,
            'phone_number' => $request->phone_number,
            'country_code' => $request->country_code,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ];

        // Add fakultas and program_studi if role is Pendamping Paten
        if ($request->role === 'pendamping_paten') {
            $adminData['fakultas'] = $request->fakultas;
            $adminData['program_studi'] = $request->program_studi;
        }

        Admin::create($adminData);

        // 3. Redirect ke Dashboard Admin dengan pesan sukses.
        return redirect()->route('admin.admins')->with('success', 'Admin account created successfully!');
    }
}