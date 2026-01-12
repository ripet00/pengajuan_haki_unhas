<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewSubmissionRequest;
use App\Models\Submission;
use App\Models\SubmissionHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
{
    protected function getCurrentAdmin()
    {
        return \App\Models\Admin::find(session('admin_id'));
    }

    // list submissions (filter optional)
    public function index(Request $request)
    {
        $q = Submission::with('user', 'reviewedByAdmin', 'jenisKarya')->latest();

        // Filter by status
        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $q->where(function($query) use ($search) {
                $query->where('title', 'LIKE', "%{$search}%")
                      ->orWhere('categories', 'LIKE', "%{$search}%")
                      ->orWhere('file_name', 'LIKE', "%{$search}%")
                      ->orWhere('creator_name', 'LIKE', "%{$search}%")
                      ->orWhere('youtube_link', 'LIKE', "%{$search}%")
                      ->orWhereHas('user', function($userQuery) use ($search) {
                          $userQuery->where('name', 'LIKE', "%{$search}%")
                                   ->orWhere('phone_number', 'LIKE', "%{$search}%")
                                   ->orWhere('faculty', 'LIKE', "%{$search}%");
                      });
            });
        }

        $submissions = $q->paginate(20);
        return view('admin.submissions.index', compact('submissions'));
    }

    // view one submission (and download/open file)
    public function show(Submission $submission)
    {
        // Load the admin and jenis karya relationships manually if needed
        if ($submission->reviewed_by_admin_id) {
            $submission->load('reviewedByAdmin');
        }
        $submission->load(['jenisKarya', 'biodata', 'histories.admin']);
        
        // Get submissions with similar titles (case-insensitive)
        $similarTitles = $submission->getSimilarTitles();
        
        return view('admin.submissions.show', compact('submission', 'similarTitles'));
    }

    // review action: set approved or denied with a comment
    public function review(ReviewSubmissionRequest $request, Submission $submission)
    {
        // only allow review if current status is pending
        if ($submission->status !== 'pending') {
            return back()->withErrors(['status' => 'Submission sudah direview sebelumnya.']);
        }

        $validatedData = $request->validated();
        $adminId = session('admin_id');
        
        $submission->status = $validatedData['status'];
        $submission->reviewed_at = now();
        $submission->reviewed_by_admin_id = $adminId; // Use session admin_id
        $submission->rejection_reason = $validatedData['rejection_reason'] ?? null;
        $submission->revisi = false; // after admin review, reset revisi flag
        $submission->save();

        // Save history
        SubmissionHistory::create([
            'submission_id' => $submission->id,
            'admin_id' => $adminId,
            'action' => $validatedData['status'] === 'approved' ? 'approved' : 'rejected',
            'notes' => $validatedData['rejection_reason'] ?? null,
        ]);

        return redirect()->route('admin.submissions.show', $submission)->with('success', 'Review tersimpan.');
    }

    // update review action: allow admin to change previous review decision
    public function updateReview(ReviewSubmissionRequest $request, Submission $submission)
    {
        // only allow update review if submission has been reviewed before
        if ($submission->status === 'pending') {
            return back()->withErrors(['status' => 'Submission ini belum direview. Gunakan form review biasa.']);
        }

        // prevent update if user has already uploaded biodata
        $biodata = $submission->biodata;
        if ($biodata) {
            return back()->withErrors(['status' => 'Tidak dapat mengubah review karena user sudah mengupload biodata. Untuk mencegah inkonsistensi data, silakan hubungi user untuk koordinasi lebih lanjut.']);
        }

        $validatedData = $request->validated();
        $adminId = session('admin_id');
        
        $submission->status = $validatedData['status'];
        $submission->reviewed_at = now();
        $submission->reviewed_by_admin_id = $adminId;
        $submission->rejection_reason = $validatedData['rejection_reason'] ?? null;
        $submission->save();

        // Save history
        SubmissionHistory::create([
            'submission_id' => $submission->id,
            'admin_id' => $adminId,
            'action' => $validatedData['status'] === 'approved' ? 'approved' : 'rejected',
            'notes' => $validatedData['rejection_reason'] ?? null,
        ]);

        return redirect()->route('admin.submissions.show', $submission)->with('success', 'Review berhasil diupdate.');
    }

    // download file - force download instead of opening in browser
    public function download(Submission $submission)
    {
        $filePath = storage_path('app/public/' . $submission->file_path);
        
        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->download($filePath, $submission->file_name);
    }

    // destroy submission (only pending or rejected)
    public function destroy(Submission $submission)
    {
        // Only allow deletion if status is pending or rejected
        if (!in_array($submission->status, ['pending', 'rejected'])) {
            return back()->with('error', 'Hanya pengajuan dengan status Pending atau Ditolak yang dapat dihapus.');
        }

        // Check if biodata exists - prevent deletion if biodata uploaded
        if ($submission->biodata) {
            return back()->with('error', 'Tidak dapat menghapus pengajuan karena biodata sudah diupload.');
        }

        try {
            // Delete file from storage
            if ($submission->file_path && \Storage::disk('public')->exists($submission->file_path)) {
                \Storage::disk('public')->delete($submission->file_path);
            }

            // Delete submission from database
            $submission->delete();

            return redirect()->route('admin.submissions.index')->with('success', 'Pengajuan berhasil dihapus.');
        } catch (\Exception $e) {
            \Log::error('Error deleting submission: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus pengajuan.');
        }
    }
}