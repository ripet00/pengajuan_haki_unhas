<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BiodataPaten;
use App\Models\SubmissionPaten;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BiodataPatenController extends Controller
{
    protected function getCurrentAdmin()
    {
        return Auth::guard('admin')->user();
    }

    public function index(Request $request)
    {
        $query = BiodataPaten::with(['user', 'submissionPaten', 'reviewedBy'])
                        ->latest();

        // Filter by biodata status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search functionality - search by user name, phone, or submission title
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'LIKE', "%{$search}%")
                             ->orWhere('phone_number', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('submissionPaten', function($submissionQuery) use ($search) {
                    $submissionQuery->where('judul_paten', 'LIKE', "%{$search}%");
                });
            });
        }

        $biodataPatens = $query->paginate(15);

        // Get statistics for cards
        $totalBiodatas = BiodataPaten::count();
        $approvedBiodatas = BiodataPaten::where('status', 'approved')->count();
        $pendingBiodatas = BiodataPaten::where('status', 'pending')->count();
        $rejectedBiodatas = BiodataPaten::whereIn('status', ['rejected', 'denied'])->count();

        // Get overdue tracking statistics
        $documentOverdue = BiodataPaten::where('status', 'approved')
            ->where('document_submitted', false)
            ->get()
            ->filter(function($biodataPaten) {
                return $biodataPaten->isDocumentOverdue();
            })
            ->count();

        $signingOverdue = BiodataPaten::where('status', 'approved')
            ->where('document_submitted', true)
            ->whereNull('application_document')
            ->get()
            ->filter(function($biodataPaten) {
                return $biodataPaten->isSigningOverdue();
            })
            ->count();

        return view('admin.biodata-paten.index', compact(
            'biodataPatens',
            'documentOverdue',
            'signingOverdue',
            'totalBiodatas',
            'approvedBiodatas',
            'pendingBiodatas',
            'rejectedBiodatas'
        ));
    }

    public function show(BiodataPaten $biodataPaten)
    {
        // Load all relationships needed for the detailed view
        $biodataPaten->load([
            'user',
            'submissionPaten',
            'inventors',
            'reviewedBy'
        ]);

        $submissionPaten = $biodataPaten->submissionPaten;

        return view('admin.biodata-paten.show', compact('biodataPaten', 'submissionPaten'));
    }

    public function review(Request $request, BiodataPaten $biodataPaten)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'rejection_reason' => 'required_if:action,reject|nullable|string|max:1000'
        ]);

        $admin = $this->getCurrentAdmin();
        
        if (!$admin) {
            return back()->with('error', 'Admin session tidak valid.');
        }

        // DEBUG: Log request data
        Log::info('Review Request Data:', [
            'all' => $request->all(),
            'has_members' => $request->has('members'),
            'members' => $request->input('members'),
        ]);

        // Update inventor error flags
        if ($request->has('members')) {
            foreach ($request->members as $inventorId => $inventorErrors) {
                $inventor = $biodataPaten->inventors()->find($inventorId);
                if ($inventor) {
                    $inventor->update([
                        'error_name' => isset($inventorErrors['error_name']) ? (bool)$inventorErrors['error_name'] : false,
                        'error_pekerjaan' => isset($inventorErrors['error_pekerjaan']) ? (bool)$inventorErrors['error_pekerjaan'] : false,
                        'error_universitas' => isset($inventorErrors['error_universitas']) ? (bool)$inventorErrors['error_universitas'] : false,
                        'error_fakultas' => isset($inventorErrors['error_fakultas']) ? (bool)$inventorErrors['error_fakultas'] : false,
                        'error_alamat' => isset($inventorErrors['error_alamat']) ? (bool)$inventorErrors['error_alamat'] : false,
                        'error_kelurahan' => isset($inventorErrors['error_kelurahan']) ? (bool)$inventorErrors['error_kelurahan'] : false,
                        'error_kecamatan' => isset($inventorErrors['error_kecamatan']) ? (bool)$inventorErrors['error_kecamatan'] : false,
                        'error_kota_kabupaten' => isset($inventorErrors['error_kota_kabupaten']) ? (bool)$inventorErrors['error_kota_kabupaten'] : false,
                        'error_provinsi' => isset($inventorErrors['error_provinsi']) ? (bool)$inventorErrors['error_provinsi'] : false,
                        'error_kode_pos' => isset($inventorErrors['error_kode_pos']) ? (bool)$inventorErrors['error_kode_pos'] : false,
                        'error_email' => isset($inventorErrors['error_email']) ? (bool)$inventorErrors['error_email'] : false,
                        'error_nomor_hp' => isset($inventorErrors['error_nomor_hp']) ? (bool)$inventorErrors['error_nomor_hp'] : false,
                        'error_kewarganegaraan' => isset($inventorErrors['error_kewarganegaraan']) ? (bool)$inventorErrors['error_kewarganegaraan'] : false,
                    ]);
                }
            }
        }

        if ($request->action === 'approve') {
            $biodataPaten->update([
                'status' => 'approved',
                'reviewed_by' => $admin->id,
                'reviewed_at' => now(),
                'rejection_reason' => null
            ]);

            // Update submission biodata_status
            $biodataPaten->submissionPaten->update([
                'biodata_status' => 'approved',
                'biodata_reviewed_at' => now(),
                'biodata_reviewed_by' => $admin->id,
                'biodata_rejection_reason' => null
            ]);

            return back()->with('success', 'Biodata Paten berhasil disetujui dan error flags berhasil disimpan.');
        } else {
            $biodataPaten->update([
                'status' => 'denied',
                'reviewed_by' => $admin->id,
                'reviewed_at' => now(),
                'rejection_reason' => $request->rejection_reason
            ]);

            // Update submission biodata_status - use 'rejected' not 'denied'
            $biodataPaten->submissionPaten->update([
                'biodata_status' => 'rejected',
                'biodata_reviewed_at' => now(),
                'biodata_reviewed_by' => $admin->id,
                'biodata_rejection_reason' => $request->rejection_reason
            ]);

            return back()->with('success', 'Biodata Paten berhasil ditolak dan error flags berhasil disimpan.');
        }
    }

    public function updateErrorFlags(Request $request, BiodataPaten $biodataPaten)
    {
        $admin = $this->getCurrentAdmin();
        
        if (!$admin) {
            return back()->with('error', 'Admin session tidak valid.');
        }

        // Update biodata error flags - no longer needed for removed fields

        // Update inventor error flags
        if ($request->has('members')) {
            foreach ($request->members as $inventorId => $inventorErrors) {
                $inventor = $biodataPaten->inventors()->find($inventorId);
                if ($inventor) {
                    $inventor->update([
                        'error_name' => isset($inventorErrors['error_name']) ? (bool)$inventorErrors['error_name'] : false,
                        'error_pekerjaan' => isset($inventorErrors['error_pekerjaan']) ? (bool)$inventorErrors['error_pekerjaan'] : false,
                        'error_universitas' => isset($inventorErrors['error_universitas']) ? (bool)$inventorErrors['error_universitas'] : false,
                        'error_fakultas' => isset($inventorErrors['error_fakultas']) ? (bool)$inventorErrors['error_fakultas'] : false,
                        'error_alamat' => isset($inventorErrors['error_alamat']) ? (bool)$inventorErrors['error_alamat'] : false,
                        'error_kelurahan' => isset($inventorErrors['error_kelurahan']) ? (bool)$inventorErrors['error_kelurahan'] : false,
                        'error_kecamatan' => isset($inventorErrors['error_kecamatan']) ? (bool)$inventorErrors['error_kecamatan'] : false,
                        'error_kota_kabupaten' => isset($inventorErrors['error_kota_kabupaten']) ? (bool)$inventorErrors['error_kota_kabupaten'] : false,
                        'error_provinsi' => isset($inventorErrors['error_provinsi']) ? (bool)$inventorErrors['error_provinsi'] : false,
                        'error_kode_pos' => isset($inventorErrors['error_kode_pos']) ? (bool)$inventorErrors['error_kode_pos'] : false,
                        'error_email' => isset($inventorErrors['error_email']) ? (bool)$inventorErrors['error_email'] : false,
                        'error_nomor_hp' => isset($inventorErrors['error_nomor_hp']) ? (bool)$inventorErrors['error_nomor_hp'] : false,
                        'error_kewarganegaraan' => isset($inventorErrors['error_kewarganegaraan']) ? (bool)$inventorErrors['error_kewarganegaraan'] : false,
                    ]);
                }
            }
        }

        return back()->with('success', 'Error flags berhasil diupdate.');
    }

    /**
     * Mark biodata paten as document submitted
     */
    public function markDocumentSubmitted(BiodataPaten $biodataPaten)
    {
        $admin = $this->getCurrentAdmin();
        
        if (!$admin) {
            return back()->with('error', 'Admin session tidak valid.');
        }

        // Only approved biodata can have documents submitted
        if ($biodataPaten->status !== 'approved') {
            return back()->with('error', 'Hanya biodata yang disetujui yang dapat ditandai berkas disetor.');
        }

        // Check if already submitted
        if ($biodataPaten->document_submitted) {
            return back()->with('error', 'Berkas sudah ditandai sebagai disetor sebelumnya.');
        }

        $biodataPaten->update([
            'document_submitted' => true,
            'document_submitted_at' => now(),
        ]);

        return back()->with('success', 'Berkas berhasil ditandai sebagai sudah disetor pada ' . now()->format('d F Y, H:i') . ' WITA');
    }

    /**
     * Cancel document submitted (only if patent documents not uploaded AND application document not issued)
     */
    public function cancelDocumentSubmitted(BiodataPaten $biodataPaten)
    {
        $admin = $this->getCurrentAdmin();
        
        if (!$admin) {
            return back()->with('error', 'Admin session tidak valid.');
        }

        // Check if document was submitted
        if (!$biodataPaten->document_submitted) {
            return back()->with('error', 'Berkas belum pernah ditandai sebagai disetor.');
        }

        // CRITICAL: Can only cancel if:
        // 1. Patent documents (3 required) NOT YET UPLOADED, OR
        // 2. Application document NOT YET ISSUED
        // (tahap selanjutnya belum selesai)
        
        $hasPatentDocs = $biodataPaten->deskripsi_pdf || $biodataPaten->klaim_pdf || $biodataPaten->abstrak_pdf || $biodataPaten->gambar_pdf;
        
        if ($hasPatentDocs) {
            return back()->with('error', 'Tidak dapat membatalkan karena user sudah mengupload dokumen paten. Tahap selanjutnya sudah dimulai.');
        }
        
        if ($biodataPaten->application_document) {
            return back()->with('error', 'Tidak dapat membatalkan karena dokumen permohonan paten sudah terbit. Tahap selanjutnya sudah selesai.');
        }

        // Reset document submission
        $biodataPaten->update([
            'document_submitted' => false,
            'document_submitted_at' => null,
        ]);

        return back()->with('success', 'Status "Berkas Disetor" berhasil dibatalkan. Biodata kembali ke tahap sebelumnya.');
    }

    /**
     * Mark biodata paten document as ready for signing by leadership
     */
    public function markReadyForSigning(BiodataPaten $biodataPaten)
    {
        $admin = $this->getCurrentAdmin();
        
        if (!$admin) {
            return back()->with('error', 'Admin session tidak valid.');
        }

        // This method is deprecated - use ReportPatenController::uploadApplicationDocument instead
        return back()->with('error', 'Fitur ini sudah diganti. Silakan gunakan menu Laporan Paten untuk upload dokumen permohonan.');
    }

    /**
     * Mark certificate as issued for biodata paten
     */
    public function markCertificateIssued(BiodataPaten $biodataPaten)
    {
        $admin = $this->getCurrentAdmin();
        
        if (!$admin) {
            return back()->with('error', 'Admin session tidak valid.');
        }

        // Only approved biodata with submitted documents can have certificates issued
        if ($biodataPaten->status !== 'approved') {
            return back()->with('error', 'Hanya biodata yang disetujui yang dapat ditandai sertifikat terbit.');
        }

        if (!$biodataPaten->document_submitted) {
            return back()->with('error', 'Berkas harus disetor terlebih dahulu sebelum sertifikat dapat ditandai terbit.');
        }

        // Check if already issued
        if ($biodataPaten->certificate_issued) {
            return back()->with('error', 'Sertifikat sudah ditandai sebagai terbit sebelumnya.');
        }

        $biodataPaten->update([
            'certificate_issued' => true,
            'certificate_issued_at' => now(),
        ]);

        return back()->with('success', 'Sertifikat Paten berhasil ditandai sebagai sudah terbit pada ' . now()->format('d F Y, H:i') . ' WITA');
    }
}
