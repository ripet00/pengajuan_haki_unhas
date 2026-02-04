<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubmissionPaten;
use App\Models\SubmissionPatenHistory;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PendampingPatenController extends Controller
{
    protected function getCurrentPendampingPaten()
    {
        return Admin::find(session('admin_id'));
    }

    /**
     * Display dashboard for Pendamping Paten
     */
    public function dashboard()
    {
        $pendampingPaten = $this->getCurrentPendampingPaten();

        // Get statistics
        $totalAssigned = $pendampingPaten->assignedPatenSubmissions()->count();
        $pendingReview = $pendampingPaten->assignedPatenSubmissions()
            ->where('status', SubmissionPaten::STATUS_PENDING_SUBSTANCE_REVIEW)
            ->count();
        $rejectedWaitingRevision = $pendampingPaten->assignedPatenSubmissions()
            ->where('status', SubmissionPaten::STATUS_REJECTED_SUBSTANCE_REVIEW)
            ->count();
        $completed = $pendampingPaten->assignedPatenSubmissions()
            ->where('status', SubmissionPaten::STATUS_APPROVED_SUBSTANCE)
            ->count();

        // Prepare stats array for view
        $stats = [
            'pending' => $pendingReview,
            'approved' => $completed,
            'rejected' => $rejectedWaitingRevision,
            'total' => $totalAssigned
        ];

        // Get recent submissions
        $recentSubmissions = $pendampingPaten->assignedPatenSubmissions()
            ->with('user')
            ->whereIn('status', [
                SubmissionPaten::STATUS_PENDING_SUBSTANCE_REVIEW,
                SubmissionPaten::STATUS_REJECTED_SUBSTANCE_REVIEW
            ])
            ->latest('assigned_at')
            ->take(10)
            ->get();

        return view('admin.pendamping-paten.dashboard', compact('stats', 'recentSubmissions'));
    }

    /**
     * Display list of assigned paten submissions
     */
    public function index(Request $request)
    {
        $pendampingPaten = $this->getCurrentPendampingPaten();

        $query = $pendampingPaten->assignedPatenSubmissions()->with('user');

        // Filter by status (default: active = pending + rejected)
        $filter = $request->get('filter', 'active');
        
        if ($filter === 'active') {
            $query->whereIn('status', [
                SubmissionPaten::STATUS_PENDING_SUBSTANCE_REVIEW,
                SubmissionPaten::STATUS_REJECTED_SUBSTANCE_REVIEW
            ]);
        } elseif ($filter === 'completed') {
            $query->where('status', SubmissionPaten::STATUS_APPROVED_SUBSTANCE);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul_paten', 'LIKE', "%{$search}%")
                  ->orWhere('kategori_paten', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $submissions = $query->latest('assigned_at')->paginate(15);

        return view('admin.pendamping-paten.index', compact('submissions', 'filter'));
    }

    /**
     * Display detail of assigned submission
     */
    public function show(SubmissionPaten $submissionPaten)
    {
        $pendampingPaten = $this->getCurrentPendampingPaten();

        // Verify this submission is assigned to current Pendamping Paten
        if ($submissionPaten->pendamping_paten_id !== $pendampingPaten->id) {
            abort(403, 'Anda tidak memiliki akses ke pengajuan ini.');
        }

        $submissionPaten->load(['user', 'reviewedByAdmin', 'pendampingPaten', 'histories.admin']);

        return view('admin.pendamping-paten.show', compact('submissionPaten'));
    }

    /**
     * Download paten file
     */
    public function download(SubmissionPaten $submissionPaten)
    {
        $pendampingPaten = $this->getCurrentPendampingPaten();

        // Verify access
        if ($submissionPaten->pendamping_paten_id !== $pendampingPaten->id) {
            abort(403, 'Anda tidak memiliki akses ke file ini.');
        }

        $filePath = storage_path('app/private/' . $submissionPaten->file_path);
        
        if (!file_exists($filePath)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        $downloadName = $submissionPaten->original_filename ?? $submissionPaten->file_name ?? 'document.docx';

        return response()->file($filePath, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'Content-Disposition' => 'inline; filename="' . $downloadName . '"',
        ]);
    }

    /**
     * Review substance of paten submission
     */
    public function reviewSubstance(Request $request, SubmissionPaten $submissionPaten)
    {
        $pendampingPaten = $this->getCurrentPendampingPaten();

        // Verify access
        if ($submissionPaten->pendamping_paten_id !== $pendampingPaten->id) {
            abort(403, 'Anda tidak memiliki akses untuk review pengajuan ini.');
        }

        $request->validate([
            'status' => 'required|in:approved_substance,rejected_substance_review',
            'substance_review_notes' => 'required_if:status,rejected_substance_review',
            'substance_review_file' => 'nullable|file|mimes:docx,doc,pdf|max:10240',
        ], [
            'status.required' => 'Status harus dipilih.',
            'substance_review_notes.required_if' => 'Catatan review harus diisi jika substansi ditolak.',
            'substance_review_file.mimes' => 'File harus berformat DOCX, DOC, atau PDF.',
            'substance_review_file.max' => 'Ukuran file maksimal 10MB.',
        ]);

        // Prepare update data
        $updateData = [
            'status' => $request->status,
            'substance_review_notes' => $request->status === 'rejected_substance_review' ? $request->substance_review_notes : null,
            'substance_reviewed_at' => now(),
        ];

        // Handle file upload
        if ($request->hasFile('substance_review_file')) {
            // Delete old file if exists
            if ($submissionPaten->substance_review_file && Storage::disk('public')->exists($submissionPaten->substance_review_file)) {
                Storage::disk('public')->delete($submissionPaten->substance_review_file);
            }

            $file = $request->file('substance_review_file');
            $fileName = 'substance_review_' . $submissionPaten->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('substance_review_files/paten', $fileName, 'public');

            $updateData['substance_review_file'] = $filePath;
        }

        $submissionPaten->update($updateData);

        // Save history for substance review
        SubmissionPatenHistory::create([
            'submission_paten_id' => $submissionPaten->id,
            'admin_id' => $pendampingPaten->id,
            'review_type' => 'substance_review',
            'action' => $request->status === 'approved_substance' ? 'approved' : 'rejected',
            'notes' => $request->substance_review_notes ?? null,
        ]);

        $statusText = $request->status === 'approved_substance' ? 'disetujui' : 'ditolak';
        
        return redirect()->route('admin.pendamping-paten.show', $submissionPaten)
                       ->with('success', "Review substansi paten berhasil {$statusText}.");
    }

    /**
     * Download substance review file
     */
    public function downloadSubstanceReview(SubmissionPaten $submissionPaten)
    {
        $pendampingPaten = $this->getCurrentPendampingPaten();

        // Verify access
        if ($submissionPaten->pendamping_paten_id !== $pendampingPaten->id) {
            abort(403, 'Anda tidak memiliki akses ke file ini.');
        }

        if (!$submissionPaten->substance_review_file) {
            return back()->with('error', 'File review substansi tidak ditemukan.');
        }

        $filePath = storage_path('app/public/' . $submissionPaten->substance_review_file);
        
        if (!file_exists($filePath)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        return response()->download($filePath);
    }

    /**
     * Show detail of specific Pendamping Paten (for Super Admin)
     * Shows all assigned submissions with filter
     */
    public function detail(Admin $admin, Request $request)
    {
        // Verify the admin is actually a Pendamping Paten
        if ($admin->role !== Admin::ROLE_PENDAMPING_PATEN) {
            abort(404);
        }

        $filter = $request->get('filter', 'active');
        
        $query = $admin->assignedPatenSubmissions()->with('user');

        if ($filter === 'active') {
            $query->whereIn('status', [
                SubmissionPaten::STATUS_PENDING_SUBSTANCE_REVIEW,
                SubmissionPaten::STATUS_REJECTED_SUBSTANCE_REVIEW
            ]);
        } elseif ($filter === 'completed') {
            $query->where('status', SubmissionPaten::STATUS_APPROVED_SUBSTANCE);
        }

        $submissions = $query->latest('assigned_at')->paginate(15);

        // Get statistics
        $totalAssigned = $admin->assignedPatenSubmissions()->count();
        $pendingCount = $admin->assignedPatenSubmissions()
            ->where('status', SubmissionPaten::STATUS_PENDING_SUBSTANCE_REVIEW)
            ->count();
        $rejectedCount = $admin->assignedPatenSubmissions()
            ->where('status', SubmissionPaten::STATUS_REJECTED_SUBSTANCE_REVIEW)
            ->count();
        $completedCount = $admin->assignedPatenSubmissions()
            ->where('status', SubmissionPaten::STATUS_APPROVED_SUBSTANCE)
            ->count();

        // Prepare stats array for view
        $stats = [
            'total' => $totalAssigned,
            'pending' => $pendingCount,
            'approved' => $completedCount,
            'rejected' => $rejectedCount
        ];

        return view('admin.admins.detail-pendamping-paten', [
            'pendamping' => $admin,
            'submissions' => $submissions,
            'filter' => $filter,
            'stats' => $stats
        ]);
    }

    /**
     * Get fakultas list from biodata_members (API endpoint)
     */
    public function getFakultasList()
    {
        $fakultasList = DB::table('biodata_members')
            ->select('fakultas')
            ->whereNotNull('fakultas')
            ->where('fakultas', '!=', '')
            ->distinct()
            ->orderBy('fakultas')
            ->pluck('fakultas');

        // Fallback data jika database kosong
        if ($fakultasList->isEmpty()) {
            $fakultasList = collect([
                'Fakultas Ekonomi dan Bisnis',
                'Fakultas Hukum',
                'Fakultas Ilmu Budaya',
                'Fakultas Ilmu Kelautan dan Perikanan',
                'Fakultas Ilmu Sosial dan Ilmu Politik',
                'Fakultas Kedokteran',
                'Fakultas Kedokteran Gigi',
                'Fakultas Kedokteran Hewan',
                'Fakultas Kehutanan',
                'Fakultas Kesehatan Masyarakat',
                'Fakultas Farmasi',
                'Fakultas Keperawatan',
                'Fakultas Matematika dan Ilmu Pengetahuan Alam',
                'Fakultas Pertanian',
                'Fakultas Peternakan',
                'Fakultas Teknik',
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $fakultasList->values()->toArray()
        ]);
    }

    /**
     * Get program_studi list from biodata_members based on fakultas (API endpoint)
     */
    public function getProgramStudiList(Request $request)
    {
        $fakultas = $request->get('fakultas');
        
        $query = DB::table('biodata_members')
            ->select('program_studi')
            ->whereNotNull('program_studi')
            ->where('program_studi', '!=', '')
            ->distinct()
            ->orderBy('program_studi');

        if ($fakultas) {
            $query->where('fakultas', $fakultas);
        }

        $programStudiList = $query->pluck('program_studi');

        // Fallback data based on fakultas
        if ($programStudiList->isEmpty() && $fakultas) {
            $programStudiMap = [
                'Fakultas Teknik' => [
                    'Teknik Elektro',
                    'Teknik Mesin',
                    'Teknik Sipil',
                    'Teknik Industri',
                    'Teknik Informatika',
                    'Arsitektur',
                    'Teknik Geologi',
                    'Teknik Perkapalan',
                ],
                'Fakultas Matematika dan Ilmu Pengetahuan Alam' => [
                    'Matematika',
                    'Fisika',
                    'Kimia',
                    'Biologi',
                    'Statistika',
                    'Geofisika',
                ],
                'Fakultas Kedokteran' => [
                    'Pendidikan Dokter',
                    'Ilmu Keperawatan',
                ],
                'Fakultas Farmasi' => [
                    'Farmasi',
                ],
                'Fakultas Pertanian' => [
                    'Agroteknologi',
                    'Agribisnis',
                    'Ilmu Tanah',
                    'Ilmu Hama dan Penyakit Tumbuhan',
                ],
                'Fakultas Peternakan' => [
                    'Peternakan',
                    'Nutrisi dan Makanan Ternak',
                ],
                'Fakultas Ekonomi dan Bisnis' => [
                    'Ekonomi Pembangunan',
                    'Manajemen',
                    'Akuntansi',
                ],
            ];

            $programStudiList = collect($programStudiMap[$fakultas] ?? []);
        }

        return response()->json([
            'success' => true,
            'data' => $programStudiList->values()->toArray()
        ]);
    }
}
