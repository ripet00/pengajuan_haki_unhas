<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $pendingUsers = User::where('status', 'pending')->get();
        $activeUsers = User::where('status', 'active')->count();
        $deniedUsers = User::where('status', 'denied')->count();
        $totalUsers = User::count();

        return view('admin.dashboard_modern', compact('pendingUsers', 'activeUsers', 'deniedUsers', 'totalUsers'));
    }

    public function userIndex()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function adminIndex()
    {
        $admins = Admin::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.admins.index', compact('admins'));
    }

    public function updateUserStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|in:active,pending,denied'
        ]);

        $admin = Admin::find(session('admin_id'));
        
        $user->update([
            'status' => $request->status,
            'verified_at' => $request->status === 'active' ? now() : null,
            'verified_by_admin_id' => $request->status === 'active' ? $admin->id : null,
        ]);

        return redirect()->back()->with('success', 'User status updated successfully!');
    }

    public function createAdmin()
    {
        return view('admin.create-admin');
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nip_nidn_nidk_nim' => 'required|string|unique:admins',
            'phone_number' => 'required|string|unique:admins',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Admin::create([
            'name' => $request->name,
            'nip_nidn_nidk_nim' => $request->nip_nidn_nidk_nim,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Admin account created successfully!');
    }
}
