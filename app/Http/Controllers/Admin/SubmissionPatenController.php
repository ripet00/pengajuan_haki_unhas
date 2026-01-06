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
            'file_review' => 'nullable|file|mimes:docx,doc',
        ], [
            'status.required' => 'Status harus dipilih.',
            'rejection_reason.required_if' => 'Alasan penolakan harus diisi jika status ditolak.',
            'file_review.mimes' => 'File harus berformat DOCX atau DOC.',
        ]);

        $admin = $this->getCurrentAdmin();

        // Prepare update data
        $updateData = [
            'status' => $request->status,
            'rejection_reason' => $request->status === 'rejected' ? $request->rejection_reason : null,
            'reviewed_at' => now(),
            'reviewed_by_admin_id' => $admin->id,
        ];

        // Handle file upload
        if ($request->hasFile('file_review')) {
            // Delete old file if exists
            if ($submissionPaten->file_review_path && Storage::disk('public')->exists($submissionPaten->file_review_path)) {
                Storage::disk('public')->delete($submissionPaten->file_review_path);
            }

            $file = $request->file('file_review');
            $fileName = 'review_' . $submissionPaten->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('review_files/paten', $fileName, 'public');

            $updateData['file_review_path'] = $filePath;
            $updateData['file_review_name'] = $file->getClientOriginalName();
            $updateData['file_review_uploaded_at'] = now();
        }

        $submissionPaten->update($updateData);

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
            'file_review' => 'nullable|file|mimes:docx,doc',
        ], [
            'rejection_reason.required_if' => 'Alasan penolakan harus diisi jika status ditolak.',
            'file_review.mimes' => 'File harus berformat DOCX atau DOC.',
        ]);

        $admin = $this->getCurrentAdmin();

        // Prepare update data
        $updateData = [
            'status' => $request->status,
            'rejection_reason' => $request->status === 'rejected' ? $request->rejection_reason : null,
            'reviewed_at' => now(),
            'reviewed_by_admin_id' => $admin->id,
        ];

        // Handle file upload
        if ($request->hasFile('file_review')) {
            // Delete old file if exists
            if ($submissionPaten->file_review_path && Storage::disk('public')->exists($submissionPaten->file_review_path)) {
                Storage::disk('public')->delete($submissionPaten->file_review_path);
            }

            $file = $request->file('file_review');
            $fileName = 'review_' . $submissionPaten->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('review_files/paten', $fileName, 'public');

            $updateData['file_review_path'] = $filePath;
            $updateData['file_review_name'] = $file->getClientOriginalName();
            $updateData['file_review_uploaded_at'] = now();
        }

        $submissionPaten->update($updateData);

        $statusText = $request->status === 'approved' ? 'disetujui' : 'ditolak';
        
        return redirect()->route('admin.submissions-paten.show', $submissionPaten)
                       ->with('success', "Pengajuan paten berhasil {$statusText}.");
    }

    /**
     * Delete paten submission (only pending or rejected)
     */
    public function destroy(SubmissionPaten $submissionPaten)
    {
        // Only allow deletion if status is pending or rejected
        if (!in_array($submissionPaten->status, ['pending', 'rejected'])) {
            return back()->with('error', 'Hanya pengajuan dengan status Pending atau Ditolak yang dapat dihapus.');
        }

        // Check if biodata exists - prevent deletion if biodata uploaded
        if ($submissionPaten->biodataPaten) {
            return back()->with('error', 'Tidak dapat menghapus pengajuan karena biodata sudah diupload.');
        }

        try {
            // Delete submission file from storage
            if ($submissionPaten->file_path && Storage::disk('public')->exists($submissionPaten->file_path)) {
                Storage::disk('public')->delete($submissionPaten->file_path);
            }

            // Delete review file from storage if exists
            if ($submissionPaten->file_review_path && Storage::disk('public')->exists($submissionPaten->file_review_path)) {
                Storage::disk('public')->delete($submissionPaten->file_review_path);
            }

            // Delete submission from database
            $submissionPaten->delete();

            return redirect()->route('admin.submissions-paten.index')->with('success', 'Pengajuan paten berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting submission paten: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus pengajuan.');
        }
    }
}
