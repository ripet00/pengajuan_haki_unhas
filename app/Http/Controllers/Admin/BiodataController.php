<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Biodata;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BiodataController extends Controller
{
    protected function getCurrentAdmin()
    {
        return \App\Models\Admin::find(session('admin_id'));
    }

    public function index(Request $request)
    {
        $query = Biodata::with(['user', 'submission', 'submission.jenisKarya', 'reviewedBy'])
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
                ->orWhereHas('submission', function($submissionQuery) use ($search) {
                    $submissionQuery->where('title', 'LIKE', "%{$search}%");
                });
            });
        }

        $biodatas = $query->paginate(15);

        // Get statistics for cards
        $totalBiodatas = Biodata::count();
        $approvedBiodatas = Biodata::where('status', 'approved')->count();
        $pendingBiodatas = Biodata::where('status', 'pending')->count();
        $rejectedBiodatas = Biodata::whereIn('status', ['rejected', 'denied'])->count();

        // Get overdue tracking statistics
        $documentOverdue = Biodata::where('status', 'approved')
            ->where('document_submitted', false)
            ->get()
            ->filter(function($biodata) {
                return $biodata->isDocumentOverdue();
            })
            ->count();

        $certificateOverdue = Biodata::where('status', 'approved')
            ->where('document_submitted', true)
            ->where('certificate_issued', false)
            ->get()
            ->filter(function($biodata) {
                return $biodata->isCertificateOverdue();
            })
            ->count();

        return view('admin.biodata.index', compact(
            'biodatas',
            'documentOverdue',
            'certificateOverdue',
            'totalBiodatas',
            'approvedBiodatas',
            'pendingBiodatas',
            'rejectedBiodatas'
        ));
    }

    public function show(Biodata $biodata)
    {
        // Load all relationships needed for the detailed view
        $biodata->load([
            'user',
            'submission',
            'submission.jenisKarya',
            'members',
            'reviewedBy'
        ]);

        return view('admin.biodata.show', compact('biodata'));
    }

    public function review(Request $request, Biodata $biodata)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'rejection_reason' => 'required_if:action,reject|nullable|string|max:1000'
        ]);

        $admin = $this->getCurrentAdmin();
        
        if (!$admin) {
            return back()->with('error', 'Admin session tidak valid.');
        }

        // Update biodata error flags
        $biodata->update([
            'error_tempat_ciptaan' => $request->boolean('error_tempat_ciptaan'),
            'error_tanggal_ciptaan' => $request->boolean('error_tanggal_ciptaan'),
            'error_uraian_singkat' => $request->boolean('error_uraian_singkat'),
        ]);

        // Update member error flags
        if ($request->has('members')) {
            foreach ($request->members as $memberId => $memberErrors) {
                $member = $biodata->members()->find($memberId);
                if ($member) {
                    $member->update([
                        'error_name' => isset($memberErrors['error_name']) ? (bool)$memberErrors['error_name'] : false,
                        'error_nik' => isset($memberErrors['error_nik']) ? (bool)$memberErrors['error_nik'] : false,
                        'error_npwp' => isset($memberErrors['error_npwp']) ? (bool)$memberErrors['error_npwp'] : false,
                        'error_jenis_kelamin' => isset($memberErrors['error_jenis_kelamin']) ? (bool)$memberErrors['error_jenis_kelamin'] : false,
                        'error_pekerjaan' => isset($memberErrors['error_pekerjaan']) ? (bool)$memberErrors['error_pekerjaan'] : false,
                        'error_universitas' => isset($memberErrors['error_universitas']) ? (bool)$memberErrors['error_universitas'] : false,
                        'error_fakultas' => isset($memberErrors['error_fakultas']) ? (bool)$memberErrors['error_fakultas'] : false,
                        'error_program_studi' => isset($memberErrors['error_program_studi']) ? (bool)$memberErrors['error_program_studi'] : false,
                        'error_alamat' => isset($memberErrors['error_alamat']) ? (bool)$memberErrors['error_alamat'] : false,
                        'error_kelurahan' => isset($memberErrors['error_kelurahan']) ? (bool)$memberErrors['error_kelurahan'] : false,
                        'error_kecamatan' => isset($memberErrors['error_kecamatan']) ? (bool)$memberErrors['error_kecamatan'] : false,
                        'error_kota_kabupaten' => isset($memberErrors['error_kota_kabupaten']) ? (bool)$memberErrors['error_kota_kabupaten'] : false,
                        'error_provinsi' => isset($memberErrors['error_provinsi']) ? (bool)$memberErrors['error_provinsi'] : false,
                        'error_kode_pos' => isset($memberErrors['error_kode_pos']) ? (bool)$memberErrors['error_kode_pos'] : false,
                        'error_email' => isset($memberErrors['error_email']) ? (bool)$memberErrors['error_email'] : false,
                        'error_nomor_hp' => isset($memberErrors['error_nomor_hp']) ? (bool)$memberErrors['error_nomor_hp'] : false,
                        'error_kewarganegaraan' => isset($memberErrors['error_kewarganegaraan']) ? (bool)$memberErrors['error_kewarganegaraan'] : false,
                    ]);
                }
            }
        }

        if ($request->action === 'approve') {
            $biodata->update([
                'status' => 'approved',
                'reviewed_by' => $admin->id,
                'reviewed_at' => now(),
                'rejection_reason' => null
            ]);

            // Update submission biodata_status
            $biodata->submission->update([
                'biodata_status' => 'approved',
                'biodata_reviewed_at' => now(),
                'biodata_reviewed_by' => $admin->id,
                'biodata_rejection_reason' => null
            ]);

            return back()->with('success', 'Biodata berhasil disetujui dan error flags berhasil disimpan.');
        } else {
            $biodata->update([
                'status' => 'denied',
                'reviewed_by' => $admin->id,
                'reviewed_at' => now(),
                'rejection_reason' => $request->rejection_reason
            ]);

            // Update submission biodata_status - use 'rejected' not 'denied'
            $biodata->submission->update([
                'biodata_status' => 'rejected',
                'biodata_reviewed_at' => now(),
                'biodata_reviewed_by' => $admin->id,
                'biodata_rejection_reason' => $request->rejection_reason
            ]);

            return back()->with('success', 'Biodata berhasil ditolak dan error flags berhasil disimpan.');
        }
    }

    public function updateErrorFlags(Request $request, Biodata $biodata)
    {
        $admin = $this->getCurrentAdmin();
        
        if (!$admin) {
            return back()->with('error', 'Admin session tidak valid.');
        }

        // Update biodata error flags
        $biodata->update([
            'error_tempat_ciptaan' => $request->boolean('error_tempat_ciptaan'),
            'error_tanggal_ciptaan' => $request->boolean('error_tanggal_ciptaan'),
            'error_uraian_singkat' => $request->boolean('error_uraian_singkat'),
        ]);

        // Update member error flags
        if ($request->has('members')) {
            foreach ($request->members as $memberId => $memberErrors) {
                $member = $biodata->members()->find($memberId);
                if ($member) {
                    $member->update([
                        'error_name' => isset($memberErrors['error_name']) ? (bool)$memberErrors['error_name'] : false,
                        'error_nik' => isset($memberErrors['error_nik']) ? (bool)$memberErrors['error_nik'] : false,
                        'error_npwp' => isset($memberErrors['error_npwp']) ? (bool)$memberErrors['error_npwp'] : false,
                        'error_jenis_kelamin' => isset($memberErrors['error_jenis_kelamin']) ? (bool)$memberErrors['error_jenis_kelamin'] : false,
                        'error_pekerjaan' => isset($memberErrors['error_pekerjaan']) ? (bool)$memberErrors['error_pekerjaan'] : false,
                        'error_universitas' => isset($memberErrors['error_universitas']) ? (bool)$memberErrors['error_universitas'] : false,
                        'error_fakultas' => isset($memberErrors['error_fakultas']) ? (bool)$memberErrors['error_fakultas'] : false,
                        'error_program_studi' => isset($memberErrors['error_program_studi']) ? (bool)$memberErrors['error_program_studi'] : false,
                        'error_alamat' => isset($memberErrors['error_alamat']) ? (bool)$memberErrors['error_alamat'] : false,
                        'error_kelurahan' => isset($memberErrors['error_kelurahan']) ? (bool)$memberErrors['error_kelurahan'] : false,
                        'error_kecamatan' => isset($memberErrors['error_kecamatan']) ? (bool)$memberErrors['error_kecamatan'] : false,
                        'error_kota_kabupaten' => isset($memberErrors['error_kota_kabupaten']) ? (bool)$memberErrors['error_kota_kabupaten'] : false,
                        'error_provinsi' => isset($memberErrors['error_provinsi']) ? (bool)$memberErrors['error_provinsi'] : false,
                        'error_kode_pos' => isset($memberErrors['error_kode_pos']) ? (bool)$memberErrors['error_kode_pos'] : false,
                        'error_email' => isset($memberErrors['error_email']) ? (bool)$memberErrors['error_email'] : false,
                        'error_nomor_hp' => isset($memberErrors['error_nomor_hp']) ? (bool)$memberErrors['error_nomor_hp'] : false,
                        'error_kewarganegaraan' => isset($memberErrors['error_kewarganegaraan']) ? (bool)$memberErrors['error_kewarganegaraan'] : false,
                    ]);
                }
            }
        }

        return back()->with('success', 'Error flags berhasil diupdate.');
    }

    /**
     * Mark biodata as document submitted
     */
    public function markDocumentSubmitted(Biodata $biodata)
    {
        $admin = $this->getCurrentAdmin();
        
        if (!$admin) {
            return back()->with('error', 'Admin session tidak valid.');
        }

        // Only approved biodata can have documents submitted
        if ($biodata->status !== 'approved') {
            return back()->with('error', 'Hanya biodata yang disetujui yang dapat ditandai berkas disetor.');
        }

        // Check if already submitted
        if ($biodata->document_submitted) {
            return back()->with('error', 'Berkas sudah ditandai sebagai disetor sebelumnya.');
        }

        $biodata->update([
            'document_submitted' => true,
            'document_submitted_at' => now(),
        ]);

        return back()->with('success', 'Berkas berhasil ditandai sebagai sudah disetor pada ' . now()->format('d F Y, H:i') . ' WITA');
    }

    /**
     * Mark biodata certificate as issued
     */
    public function markCertificateIssued(Biodata $biodata)
    {
        $admin = $this->getCurrentAdmin();
        
        if (!$admin) {
            return back()->with('error', 'Admin session tidak valid.');
        }

        // Only approved biodata with submitted documents can have certificates issued
        if ($biodata->status !== 'approved') {
            return back()->with('error', 'Hanya biodata yang disetujui yang dapat ditandai sertifikat terbit.');
        }

        if (!$biodata->document_submitted) {
            return back()->with('error', 'Berkas harus disetor terlebih dahulu sebelum sertifikat dapat ditandai terbit.');
        }

        // Check if already issued
        if ($biodata->certificate_issued) {
            return back()->with('error', 'Sertifikat sudah ditandai sebagai terbit sebelumnya.');
        }

        $biodata->update([
            'certificate_issued' => true,
            'certificate_issued_at' => now(),
        ]);

        return back()->with('success', 'Sertifikat HKI berhasil ditandai sebagai sudah terbit pada ' . now()->format('d F Y, H:i') . ' WITA');
    }
}