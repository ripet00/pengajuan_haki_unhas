<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetRequest;
use App\Models\User;
use App\Models\Admin as AdminModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetManagementController extends Controller
{
    /**
     * Display list of password reset requests
     */
    public function index(Request $request)
    {
        $adminId = session('admin_id');
        
        if (!$adminId) {
            return redirect('/admin/login');
        }

        $admin = AdminModel::find($adminId);
        
        if (!$admin) {
            return redirect('/admin/login');
        }

        $query = PasswordResetRequest::with(['user', 'admin', 'approvedBy', 'rejectedBy'])
            ->orderBy('requested_at', 'desc');

        // Filter based on admin role
        if ($admin->role !== AdminModel::ROLE_SUPER_ADMIN) {
            // Non-super admins can only see user reset requests
            $query->where('user_type', 'user');
        }

        // Apply status filter
        $status = $request->get('status', 'all');
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Apply user type filter (only for super admin)
        if ($admin->role === AdminModel::ROLE_SUPER_ADMIN) {
            $userType = $request->get('user_type', 'all');
            if ($userType !== 'all') {
                $query->where('user_type', $userType);
            }
        }

        $requests = $query->paginate(20);

        return view('admin.password-reset.index', [
            'requests' => $requests,
            'status' => $status,
            'userType' => $request->get('user_type', 'all'),
            'admin' => $admin,
        ]);
    }

    /**
     * Show detail of a specific reset request
     */
    public function show($id)
    {
        $adminId = session('admin_id');
        
        if (!$adminId) {
            return redirect('/admin/login');
        }

        $admin = AdminModel::find($adminId);
        
        if (!$admin) {
            return redirect('/admin/login');
        }

        $request = PasswordResetRequest::with(['user', 'admin', 'approvedBy', 'rejectedBy'])
            ->findOrFail($id);

        // Check permissions
        if ($admin->role !== AdminModel::ROLE_SUPER_ADMIN && $request->user_type === 'admin') {
            abort(403, 'Anda tidak memiliki akses untuk melihat request reset password admin.');
        }

        // Get account details
        $account = null;
        if ($request->user_type === 'user') {
            $account = User::find($request->user_id);
        } else {
            $account = AdminModel::find($request->user_id);
        }

        // Get history of all requests from this phone number
        $history = PasswordResetRequest::where('phone_number', $request->phone_number)
            ->where('country_code', $request->country_code)
            ->where('user_type', $request->user_type)
            ->where('id', '!=', $id)
            ->orderBy('requested_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.password-reset.show', [
            'request' => $request,
            'account' => $account,
            'history' => $history,
            'admin' => $admin,
        ]);
    }

    /**
     * Generate token and approve request
     */
    public function approve(Request $request, $id)
    {
        $adminId = session('admin_id');
        
        if (!$adminId) {
            return redirect('/admin/login');
        }

        $admin = AdminModel::find($adminId);
        
        if (!$admin) {
            return redirect('/admin/login');
        }

        $validated = $request->validate([
            'verification_method' => 'required|in:call,wa,other',
            'verification_notes' => 'nullable|string|max:1000',
        ]);

        $resetRequest = PasswordResetRequest::findOrFail($id);

        // Check permissions
        if ($admin->role !== AdminModel::ROLE_SUPER_ADMIN && $resetRequest->user_type === 'admin') {
            abort(403, 'Hanya Super Admin yang dapat meng-approve reset password untuk admin lain.');
        }

        // Check if already approved or used
        if ($resetRequest->status !== 'pending') {
            return back()->with('error', 'Request ini sudah diproses sebelumnya.');
        }

        // Generate secure token
        $plainToken = bin2hex(random_bytes(32)); // 64 character hex string
        $tokenHash = Hash::make($plainToken);

        // Update request
        $resetRequest->update([
            'status' => 'sent',
            'token_hash' => $tokenHash,
            'token_created_at' => now(),
            'token_expires_at' => now()->addHour(), // 60 minutes
            'approved_by_admin_id' => $admin->id,
            'approved_at' => now(),
            'verification_method' => $validated['verification_method'],
            'verification_notes' => $validated['verification_notes'],
            'admin_ip' => $request->ip(),
        ]);

        // Generate reset URL
        $resetUrl = url('/password/reset/' . $plainToken . '?phone=' . urlencode($resetRequest->phone_number) . '&country_code=' . urlencode($resetRequest->country_code));

        // Return with token and URL
        return redirect()->route('admin.password-reset.show', $id)
            ->with('success', 'Token berhasil di-generate!')
            ->with('reset_url', $resetUrl)
            ->with('plain_token', $plainToken);
    }

    /**
     * Reject a reset request
     */
    public function reject(Request $request, $id)
    {
        $adminId = session('admin_id');
        
        if (!$adminId) {
            return redirect('/admin/login');
        }

        $admin = AdminModel::find($adminId);
        
        if (!$admin) {
            return redirect('/admin/login');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $resetRequest = PasswordResetRequest::findOrFail($id);

        // Check permissions
        if ($admin->role !== AdminModel::ROLE_SUPER_ADMIN && $resetRequest->user_type === 'admin') {
            abort(403, 'Hanya Super Admin yang dapat menolak request reset password untuk admin lain.');
        }

        // Check if already processed
        if ($resetRequest->status !== 'pending') {
            return back()->with('error', 'Request ini sudah diproses sebelumnya.');
        }

        // Update request
        $resetRequest->update([
            'status' => 'rejected',
            'rejected_by_admin_id' => $admin->id,
            'rejected_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return redirect()->route('admin.password-reset.index')
            ->with('success', 'Request reset password berhasil ditolak.');
    }

    /**
     * Delete a reset request (for cleanup)
     */
    public function destroy($id)
    {
        $adminId = session('admin_id');
        
        if (!$adminId) {
            return redirect('/admin/login');
        }

        $admin = AdminModel::find($adminId);
        
        if (!$admin) {
            return redirect('/admin/login');
        }

        // Only super admin can delete
        if ($admin->role !== AdminModel::ROLE_SUPER_ADMIN) {
            abort(403, 'Hanya Super Admin yang dapat menghapus request.');
        }

        $resetRequest = PasswordResetRequest::findOrFail($id);
        $resetRequest->delete();

        return redirect()->route('admin.password-reset.index')
            ->with('success', 'Request berhasil dihapus.');
    }
}
