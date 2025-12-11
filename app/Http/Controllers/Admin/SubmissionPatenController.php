<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubmissionPaten;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SubmissionPatenController extends Controller
{
    protected function getCurrentAdmin()
    {
        return \App\Models\Admin::find(session('admin_id'));
    }

    /**
     * Display a listing of paten submissions
     */
    public function index(Request $request)
    {
        $q = SubmissionPaten::with('user', 'reviewedByAdmin')->latest();

        // Filter by status
        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $q->where(function($query) use ($search) {
                $query->where('judul_paten', 'LIKE', "%{$search}%")
                      ->orWhere('kategori_paten', 'LIKE', "%{$search}%")
                      ->orWhere('creator_name', 'LIKE', "%{$search}%")
                      ->orWhereHas('user', function($q) use ($search) {
                          $q->where('name', 'LIKE', "%{$search}%");
                      });
            });
        }

        $submissionsPaten = $q->paginate(15);
        
        return view('admin.submissions-paten.index', compact('submissionsPaten'));
    }

    /**
     * Display the specified paten submission
     */
    public function show(SubmissionPaten $submissionPaten)
    {
        $submissionPaten->load(['user', 'reviewedByAdmin', 'biodataReviewedByAdmin', 'biodataPaten.inventors']);
        
        return view('admin.submissions-paten.show', compact('submissionPaten'));
    }

    /**
     * Download the paten submission file
     */
    public function download(SubmissionPaten $submissionPaten)
    {
        $filePath = storage_path('app/public/' . $submissionPaten->file_path);
        
        if (!file_exists($filePath)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        return response()->download($filePath, $submissionPaten->file_name);
    }

    /**
     * Review paten submission (approve/reject)
     */
    public function review(Request $request, SubmissionPaten $submissionPaten)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'required_if:status,rejected',
        ], [
            'status.required' => 'Status harus dipilih.',
            'rejection_reason.required_if' => 'Alasan penolakan harus diisi jika status ditolak.',
        ]);

        $admin = $this->getCurrentAdmin();

        $submissionPaten->update([
            'status' => $request->status,
            'rejection_reason' => $request->status === 'rejected' ? $request->rejection_reason : null,
            'reviewed_at' => now(),
            'reviewed_by_admin_id' => $admin->id,
        ]);

        $statusText = $request->status === 'approved' ? 'disetujui' : 'ditolak';
        
        return redirect()->route('admin.submissions-paten.show', $submissionPaten)
                       ->with('success', "Pengajuan paten berhasil {$statusText}.");
    }

    /**
     * Update review status (for revision)
     */
    public function updateReview(Request $request, SubmissionPaten $submissionPaten)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'required_if:status,rejected',
        ]);

        $admin = $this->getCurrentAdmin();

        $submissionPaten->update([
            'status' => $request->status,
            'rejection_reason' => $request->status === 'rejected' ? $request->rejection_reason : null,
            'reviewed_at' => now(),
            'reviewed_by_admin_id' => $admin->id,
        ]);

        $statusText = $request->status === 'approved' ? 'disetujui' : 'ditolak';
        
        return redirect()->route('admin.submissions-paten.show', $submissionPaten)
                       ->with('success', "Pengajuan paten berhasil {$statusText}.");
    }
}
