<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubmissionPaten;
use App\Models\SubmissionPatenHistory;
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
        $submissionPaten->load(['user', 'reviewedByAdmin', 'biodataReviewedByAdmin', 'biodataPaten.inventors', 'histories.admin', 'pendampingPaten']);
        
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
            'status' => 'required|in:approved_format,rejected_format_review',
            'rejection_reason' => 'required_if:status,rejected_format_review',
            'file_review' => 'nullable|file|mimes:docx,doc,pdf',
            'pendamping_paten_id' => 'required_if:status,approved_format|nullable|exists:admins,id',
        ], [
            'status.required' => 'Status harus dipilih.',
            'rejection_reason.required_if' => 'Alasan penolakan harus diisi jika status ditolak.',
            'file_review.mimes' => 'File harus berformat DOCX, DOC, atau PDF.',
            'pendamping_paten_id.required_if' => 'Pendamping Paten harus dipilih saat menyetujui format.',
            'pendamping_paten_id.exists' => 'Pendamping Paten tidak ditemukan.',
        ]);

        // Verify the selected admin is actually a Pendamping Paten (if provided)
        if ($request->status === 'approved_format' && $request->pendamping_paten_id) {
            $pendampingPaten = \App\Models\Admin::findOrFail($request->pendamping_paten_id);
            if ($pendampingPaten->role !== \App\Models\Admin::ROLE_PENDAMPING_PATEN) {
                return back()->with('error', 'Admin yang dipilih bukan Pendamping Paten.');
            }
        }

        $admin = $this->getCurrentAdmin();

        // Prepare update data
        $updateData = [
            'status' => $request->status,
            'rejection_reason' => $request->status === 'rejected_format_review' ? $request->rejection_reason : null,
            'reviewed_at' => now(),
            'reviewed_by_admin_id' => $admin->id,
        ];

        // If approved, also assign to pendamping and change status to pending_substance_review
        if ($request->status === 'approved_format' && $request->pendamping_paten_id) {
            $updateData['pendamping_paten_id'] = $request->pendamping_paten_id;
            $updateData['assigned_at'] = now();
            $updateData['status'] = SubmissionPaten::STATUS_PENDING_SUBSTANCE_REVIEW;
        }

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

        // Save history for format review
        SubmissionPatenHistory::create([
            'submission_paten_id' => $submissionPaten->id,
            'admin_id' => $admin->id,
            'review_type' => 'format_review',
            'action' => $request->status === 'approved_format' ? 'approved' : 'rejected',
            'notes' => $request->rejection_reason ?? null,
        ]);

        if ($request->status === 'approved_format') {
            $pendampingName = \App\Models\Admin::find($request->pendamping_paten_id)->name ?? 'Pendamping Paten';
            return redirect()->route('admin.submissions-paten.show', $submissionPaten)
                           ->with('success', "Format pengajuan paten berhasil disetujui dan ditugaskan kepada {$pendampingName} untuk review substansi.");
        } else {
            return redirect()->route('admin.submissions-paten.show', $submissionPaten)
                           ->with('success', "Format pengajuan paten berhasil ditolak. User dapat melakukan revisi dan submit ulang.");
        }
    }

    /**
     * Update review status (for revision)
     */
    public function updateReview(Request $request, SubmissionPaten $submissionPaten)
    {
        // Prevent update if format already approved and assigned to Pendamping Paten
        if ($submissionPaten->pendamping_paten_id && in_array($submissionPaten->status, [
            SubmissionPaten::STATUS_PENDING_SUBSTANCE_REVIEW,
            SubmissionPaten::STATUS_APPROVED_SUBSTANCE,
            SubmissionPaten::STATUS_REJECTED_SUBSTANCE_REVIEW
        ])) {
            return back()->withErrors(['status' => 'Tidak dapat mengubah review format karena sudah disetujui dan ditugaskan kepada Pendamping Paten untuk review substansi. Perubahan review tidak diizinkan untuk menjaga integritas proses review.']);
        }

        // Prevent update if user has already uploaded biodata
        if ($submissionPaten->biodataPaten) {
            return back()->withErrors(['status' => 'Tidak dapat mengubah review karena user sudah mengupload biodata. Untuk mencegah inkonsistensi data, silakan hubungi user untuk koordinasi lebih lanjut.']);
        }

        $request->validate([
            'status' => 'required|in:approved_format,rejected_format_review',
            'rejection_reason' => 'required_if:status,rejected_format_review',
            'file_review' => 'nullable|file|mimes:docx,doc,pdf',
            'pendamping_paten_id' => 'required_if:status,approved_format|nullable|exists:admins,id',
        ], [
            'rejection_reason.required_if' => 'Alasan penolakan harus diisi jika status ditolak.',
            'file_review.mimes' => 'File harus berformat DOCX, DOC, atau PDF.',
            'pendamping_paten_id.required_if' => 'Pendamping Paten harus dipilih jika format disetujui.',
            'pendamping_paten_id.exists' => 'Pendamping Paten tidak ditemukan.',
        ]);

        $admin = $this->getCurrentAdmin();

        // Prepare update data
        $updateData = [
            'status' => $request->status,
            'rejection_reason' => $request->status === 'rejected_format_review' ? $request->rejection_reason : null,
            'reviewed_at' => now(),
            'reviewed_by_admin_id' => $admin->id,
        ];
        
        // Handle pendamping_paten_id for approved format
        if ($request->status === 'approved_format' && $request->filled('pendamping_paten_id')) {
            $updateData['pendamping_paten_id'] = $request->pendamping_paten_id;
            $updateData['assigned_at'] = now();
            $updateData['status'] = SubmissionPaten::STATUS_PENDING_SUBSTANCE_REVIEW;
        }

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
        
        // Save history for format review update
        SubmissionPatenHistory::create([
            'submission_paten_id' => $submissionPaten->id,
            'admin_id' => $admin->id,
            'review_type' => 'format_review',
            'action' => $request->status === 'approved_format' ? 'approved' : 'rejected',
            'notes' => $request->rejection_reason ?? null,
        ]);

        $statusText = $request->status === 'approved_format' ? 'disetujui' : 'ditolak';
        
        return redirect()->route('admin.submissions-paten.show', $submissionPaten)
                       ->with('success', "Pengajuan paten berhasil {$statusText}.");
    }

    /**
     * Delete paten submission (only pending or rejected)
     */
    public function destroy(SubmissionPaten $submissionPaten)
    {
        // Only allow deletion if status is pending or rejected
        if (!in_array($submissionPaten->status, [
            SubmissionPaten::STATUS_PENDING_FORMAT_REVIEW, 
            SubmissionPaten::STATUS_REJECTED_FORMAT_REVIEW,
            SubmissionPaten::STATUS_REJECTED_SUBSTANCE_REVIEW
        ])) {
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

            // Delete substance review file from storage if exists
            if ($submissionPaten->substance_review_file && Storage::disk('public')->exists($submissionPaten->substance_review_file)) {
                Storage::disk('public')->delete($submissionPaten->substance_review_file);
            }

            // Delete submission from database
            $submissionPaten->delete();

            return redirect()->route('admin.submissions-paten.index')->with('success', 'Pengajuan paten berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting submission paten: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus pengajuan.');
        }
    }

    /**
     * Assign submission to Pendamping Paten
     */
    public function assign(Request $request, SubmissionPaten $submissionPaten)
    {
        $request->validate([
            'pendamping_paten_id' => 'required|exists:admins,id',
        ], [
            'pendamping_paten_id.required' => 'Pendamping Paten harus dipilih.',
            'pendamping_paten_id.exists' => 'Pendamping Paten tidak ditemukan.',
        ]);

        // Verify the selected admin is actually a Pendamping Paten
        $pendampingPaten = \App\Models\Admin::findOrFail($request->pendamping_paten_id);
        if ($pendampingPaten->role !== \App\Models\Admin::ROLE_PENDAMPING_PATEN) {
            return back()->with('error', 'Admin yang dipilih bukan Pendamping Paten.');
        }

        // Verify submission status is approved_format
        if ($submissionPaten->status !== SubmissionPaten::STATUS_APPROVED_FORMAT) {
            return back()->with('error', 'Hanya pengajuan dengan format yang sudah disetujui yang dapat ditugaskan.');
        }

        $submissionPaten->update([
            'pendamping_paten_id' => $request->pendamping_paten_id,
            'assigned_at' => now(),
            'status' => SubmissionPaten::STATUS_PENDING_SUBSTANCE_REVIEW,
        ]);

        return redirect()->route('admin.submissions-paten.show', $submissionPaten)
                       ->with('success', 'Pendamping Paten berhasil ditugaskan.');
    }

    /**
     * Get list of Pendamping Paten for assignment (API endpoint)
     */
    public function getPendampingPatenList()
    {
        $pendampingPatenList = \App\Models\Admin::where('role', \App\Models\Admin::ROLE_PENDAMPING_PATEN)
            ->where('is_active', true)
            ->withCount(['assignedPatenSubmissions as active_paten_count' => function ($query) {
                $query->whereIn('status', [
                    SubmissionPaten::STATUS_PENDING_SUBSTANCE_REVIEW,
                    SubmissionPaten::STATUS_REJECTED_SUBSTANCE_REVIEW
                ]);
            }])
            ->orderBy('name')
            ->get(['id', 'name', 'nip_nidn_nidk_nim', 'fakultas', 'program_studi']);

        return response()->json([
            'success' => true,
            'data' => $pendampingPatenList
        ]);
    }
}
