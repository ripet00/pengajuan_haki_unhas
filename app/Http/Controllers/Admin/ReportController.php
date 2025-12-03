<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Biodata;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    protected function getCurrentAdmin()
    {
        return \App\Models\Admin::find(session('admin_id'));
    }

    /**
     * Display list of approved biodatas for tracking
     */
    public function index(Request $request)
    {
        $query = Biodata::with(['user', 'submission', 'submission.jenisKarya', 'reviewedBy'])
                        ->where('status', 'approved')
                        ->latest('reviewed_at');

        // Filter by tracking status
        if ($request->filled('tracking_status')) {
            switch ($request->tracking_status) {
                case 'document_pending':
                    $query->where('document_submitted', false);
                    break;
                case 'document_submitted':
                    $query->where('document_submitted', true)
                          ->where('certificate_issued', false);
                    break;
                case 'certificate_issued':
                    $query->where('certificate_issued', true);
                    break;
                case 'document_overdue':
                    // Will filter in collection
                    break;
                case 'certificate_overdue':
                    // Will filter in collection
                    break;
            }
        }

        // Search functionality
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

        $biodatas = $query->get();

        // Apply overdue filters if needed
        if ($request->filled('tracking_status')) {
            if ($request->tracking_status === 'document_overdue') {
                $biodatas = $biodatas->filter(function($biodata) {
                    return !$biodata->document_submitted && $biodata->isDocumentOverdue();
                });
            } elseif ($request->tracking_status === 'certificate_overdue') {
                $biodatas = $biodatas->filter(function($biodata) {
                    return $biodata->document_submitted && !$biodata->certificate_issued && $biodata->isCertificateOverdue();
                });
            }
        }

        // Paginate the collection
        $perPage = 15;
        $currentPage = $request->get('page', 1);
        $biodatas = new \Illuminate\Pagination\LengthAwarePaginator(
            $biodatas->forPage($currentPage, $perPage),
            $biodatas->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Get statistics
        $totalApproved = Biodata::where('status', 'approved')->count();
        
        $documentPending = Biodata::where('status', 'approved')
            ->where('document_submitted', false)
            ->count();
        
        $documentSubmitted = Biodata::where('status', 'approved')
            ->where('document_submitted', true)
            ->where('certificate_issued', false)
            ->count();
        
        $certificateIssued = Biodata::where('status', 'approved')
            ->where('certificate_issued', true)
            ->count();

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

        return view('admin.reports.index', compact(
            'biodatas',
            'totalApproved',
            'documentPending',
            'documentSubmitted',
            'certificateIssued',
            'documentOverdue',
            'certificateOverdue'
        ));
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

        if ($biodata->status !== 'approved') {
            return back()->with('error', 'Hanya biodata yang disetujui yang dapat ditandai berkas disetor.');
        }

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

        if ($biodata->status !== 'approved') {
            return back()->with('error', 'Hanya biodata yang disetujui yang dapat ditandai sertifikat terbit.');
        }

        if (!$biodata->document_submitted) {
            return back()->with('error', 'Berkas harus disetor terlebih dahulu sebelum sertifikat dapat ditandai terbit.');
        }

        if ($biodata->certificate_issued) {
            return back()->with('error', 'Sertifikat sudah ditandai sebagai terbit sebelumnya.');
        }

        $biodata->update([
            'certificate_issued' => true,
            'certificate_issued_at' => now(),
        ]);

        return back()->with('success', 'Sertifikat HKI berhasil ditandai sebagai sudah terbit pada ' . now()->format('d F Y, H:i') . ' WITA');
    }

    /**
     * Download kelengkapan pendaftaran HKI for admin
     */
    public function downloadKelengkapan(Biodata $biodata)
    {
        $admin = $this->getCurrentAdmin();
        
        if (!$admin) {
            return back()->with('error', 'Admin session tidak valid.');
        }

        // CEK STATUS - hanya biodata yang sudah ACC
        if ($biodata->status !== 'approved') {
            return back()->with('error', 'Biodata harus di-ACC terlebih dahulu');
        }

        // LOAD TEMPLATE ADMIN
        $templatePath = public_path('templates/kelengkapan_pendaftaran_HKI_Pengusul.docx');
        
        if (!file_exists($templatePath)) {
            return back()->with('error', 'Template kelengkapan pendaftaran tidak ditemukan. Silakan hubungi administrator.');
        }

        try {
            $templateProcessor = new TemplateProcessor($templatePath);
            
            // LOAD RELATIONSHIPS
            $biodata->load(['members', 'submission.jenisKarya']);
            
            // SET SINGLE VALUES
            $templateProcessor->setValue('tanggal_download', 
                \Carbon\Carbon::now('Asia/Makassar')->locale('id')->isoFormat('D MMMM Y'));
            
            // Data Submission
            $templateProcessor->setValue('title', $biodata->submission->title ?? '-');
            $templateProcessor->setValue('judul_karya', $biodata->submission->title ?? '-');
            $templateProcessor->setValue('jenis_karya', $biodata->submission->jenisKarya->nama ?? '-');
            
            // Data Biodata
            $templateProcessor->setValue('tempat_ciptaan', $biodata->tempat_ciptaan ?? '-');
            $templateProcessor->setValue('tanggal_ciptaan', 
                $biodata->tanggal_ciptaan ? \Carbon\Carbon::parse($biodata->tanggal_ciptaan)->locale('id')->isoFormat('D MMMM Y') : '-');
            $templateProcessor->setValue('uraian_singkat', $biodata->uraian_singkat ?? '-');
            
            // CLONE MEMBERS DATA
            $allMembers = $biodata->members;
            $memberCount = $allMembers->count();
            
            if ($memberCount > 0) {
                // Gunakan cloneRow untuk tabel member
                try {
                    Log::info("Cloning row 'member_no' for admin template", ['count' => $memberCount]);
                    $templateProcessor->cloneRow('member_no', $memberCount);
                    
                    // Set values untuk setiap member
                    foreach ($allMembers as $index => $member) {
                        $num = $index + 1;
                        
                        // Alamat lengkap (tanpa prefix "Kec.")
                        $alamatLengkap = collect([
                            $member->alamat,
                            $member->kelurahan,
                            $member->kecamatan,
                            $member->kota_kabupaten,
                            $member->provinsi,
                            $member->kode_pos
                        ])->filter()->implode(', ');
                        
                        // Set all member fields
                        $templateProcessor->setValue("member_no#$num", $num);
                        $templateProcessor->setValue("member_name#$num", $member->name ?? '-');
                        $templateProcessor->setValue("member_nik#$num", $member->nik ?? '-');
                        $templateProcessor->setValue("member_pekerjaan#$num", $member->pekerjaan ?? '-');
                        $templateProcessor->setValue("member_universitas#$num", $member->universitas ?? '-');
                        $templateProcessor->setValue("member_fakultas#$num", $member->fakultas ?? '-');
                        $templateProcessor->setValue("member_program_studi#$num", $member->program_studi ?? '-');
                        $templateProcessor->setValue("member_alamat#$num", $member->alamat ?? '-');
                        $templateProcessor->setValue("member_kelurahan#$num", $member->kelurahan ?? '-');
                        $templateProcessor->setValue("member_kecamatan#$num", $member->kecamatan ?? '-');
                        $templateProcessor->setValue("member_kota_kabupaten#$num", $member->kota_kabupaten ?? '-');
                        $templateProcessor->setValue("member_provinsi#$num", $member->provinsi ?? '-');
                        $templateProcessor->setValue("member_kode_pos#$num", $member->kode_pos ?? '-');
                        $templateProcessor->setValue("member_email#$num", $member->email ?? '-');
                        $templateProcessor->setValue("member_nomor_hp#$num", $member->nomor_hp ?? '-');
                        $templateProcessor->setValue("member_kewarganegaraan#$num", $member->kewarganegaraan ?? '-');
                        $templateProcessor->setValue("member_npwp#$num", $member->npwp ?? '-');
                        
                        Log::info("Set member #{$num} data", ['name' => $member->name]);
                    }
                } catch (\Exception $e) {
                    Log::error("Failed to clone row: " . $e->getMessage());
                    return back()->with('error', 'Gagal memproses data pencipta. Error: ' . $e->getMessage());
                }
            }
            
            // SAVE AND DOWNLOAD
            $fileName = 'kelengkapan_pendaftaran_HKI_Pengusul' . $biodata->id . '_' . now()->format('Ymd_His') . '.docx';
            $outputPath = storage_path('app/public/generated_documents/' . $fileName);
            
            // Create directory if not exists
            if (!file_exists(dirname($outputPath))) {
                mkdir(dirname($outputPath), 0755, true);
            }
            
            $templateProcessor->saveAs($outputPath);
            
            Log::info("Admin downloaded kelengkapan HKI", [
                'biodata_id' => $biodata->id,
                'admin_id' => $admin->id,
                'file' => $fileName
            ]);
            
            return response()->download($outputPath, $fileName)->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            Log::error('Error generating kelengkapan HKI document: ' . $e->getMessage());
            return back()->with('error', 'Gagal generate dokumen: ' . $e->getMessage());
        }
    }
}
