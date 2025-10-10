<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserManagementController extends Controller
{
// Menampilkan semua user
public function index()
{
$users = User::with('verifiedByAdmin')->latest()->get();
return response()->json($users);
}


// Verifikasi user oleh admin
public function verifyUser($id)
{
$user = User::findOrFail($id);
$user->update([
'status' => 'approved',
'verified_at' => now(),
'verified_by_admin_id' => Auth::id(),
]);


return response()->json(['message' => 'User berhasil diverifikasi', 'data' => $user]);
}


// Tolak user
public function rejectUser($id)
{
$user = User::findOrFail($id);
$user->update(['status' => 'rejected']);


return response()->json(['message' => 'User ditolak', 'data' => $user]);
}
}