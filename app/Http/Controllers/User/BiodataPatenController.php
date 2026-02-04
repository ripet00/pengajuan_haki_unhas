<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BiodataPaten;
use App\Models\BiodataPatenInventor;
use App\Models\SubmissionPaten;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;

class BiodataPatenController extends Controller
{
    /**
     * Show the form for creating a new biodata paten or editing existing one
     */
    public function create(SubmissionPaten $submissionPaten)
    {
        // Check if user owns the submission
        if ($submissionPaten->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this submission.');
        }

        // Check if submission is approved (substance review passed)
        if ($submissionPaten->status !== SubmissionPaten::STATUS_APPROVED_SUBSTANCE) {
            return redirect()->route('user.submissions-paten.show', $submissionPaten)
                ->with('error', 'Biodata hanya dapat dibuat untuk pengajuan paten yang substansinya telah disetujui.');
        }

        // Get existing biodata or create new one
        $biodataPaten = $submissionPaten->biodataPaten;
        $isEdit = $biodataPaten !== null;

        // If biodata is approved, redirect to view mode
        if ($biodataPaten && $biodataPaten->isApproved()) {
            return redirect()->route('user.biodata-paten.show', [$submissionPaten, $biodataPaten])
                ->with('info', 'Biodata telah disetujui dan tidak dapat diubah.');
        }

        // Allow resubmission if status is denied
        $canEdit = !$biodataPaten || $biodataPaten->status === 'pending' || $biodataPaten->status === 'denied';
        
        if ($biodataPaten && !$canEdit) {
            return redirect()->route('user.biodata-paten.show', [$submissionPaten, $biodataPaten])
                ->with('info', 'Biodata tidak dapat diubah.');
        }

        // Get existing inventors or create empty array
        $inventors = $biodataPaten ? $biodataPaten->inventors : collect();
        
        // Check if this is first time submitting (no inventors exist yet)
        $isFirstTimeSubmit = $inventors->isEmpty();
        
        // Get creator data for auto-fill (inventor pertama from submission)
        $creatorData = [
            'name' => $submissionPaten->creator_name,
            'phone' => $submissionPaten->creator_whatsapp,
            'country_code' => $submissionPaten->creator_country_code ?? '+62'
        ];
        
        // If there's old input (validation failed), merge it with inventors
        if (old('inventors')) {
            $oldInventors = old('inventors');
            $inventors = $inventors->map(function ($inventor, $index) use ($oldInventors) {
                if (isset($oldInventors[$index])) {
                    // Merge old input with existing inventor
                    foreach ($oldInventors[$index] as $key => $value) {
                        $inventor->$key = $value;
                    }
                }
                return $inventor;
            });
        }

        return view('user.biodata-paten.create', compact('submissionPaten', 'biodataPaten', 'inventors', 'isEdit', 'canEdit', 'isFirstTimeSubmit', 'creatorData'));
    }

    /**
     * Store a newly created biodata paten in storage
     */
    public function store(Request $request, SubmissionPaten $submissionPaten)
    {
        // Check if user owns the submission
        if ($submissionPaten->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this submission.');
        }

        // Check if submission is approved (substance review passed)
        if ($submissionPaten->status !== SubmissionPaten::STATUS_APPROVED_SUBSTANCE) {
            return redirect()->route('user.submissions-paten.show', $submissionPaten)
                ->with('error', 'Biodata hanya dapat dibuat untuk pengajuan paten yang substansinya telah disetujui.');
        }

        // Validate the request
        $validatedData = $request->validate([
            'inventors' => 'required|array|min:1|max:10',
            'inventors.*.name' => 'required|string|max:255',
            'inventors.*.pekerjaan' => 'required|string|max:255',
            'inventors.*.universitas' => 'required|string|max:255',
            'inventors.*.fakultas' => 'required|string|max:255',
            'inventors.*.alamat' => 'required|string',
            'inventors.*.kelurahan' => 'required|string|max:255',
            'inventors.*.kecamatan' => 'required|string|max:255',
            'inventors.*.kota_kabupaten' => 'required|string|max:255',
            'inventors.*.provinsi' => 'required|string|max:255',
            'inventors.*.kode_pos' => 'required|string|max:10',
            'inventors.*.email' => 'required|email|max:255',
            'inventors.*.nomor_hp' => 'required|string|max:20',
            'inventors.*.kewarganegaraan' => 'required|string|max:100',
            // Optional helper fields that won't be stored
            'inventors.*.kewarganegaraan_type' => 'nullable|string',
            'inventors.*.kewarganegaraan_asing' => 'nullable|string|max:100',
        ]);

        try {
            DB::beginTransaction();
            
            // Debug log
            Log::info('Biodata Paten submission data:', [
                'inventors' => $request->inventors,
                'has_npwp' => isset($request->inventors[0]['npwp']),
                'npwp_value' => $request->inventors[0]['npwp'] ?? 'not set'
            ]);

            // Check if this is an edit/resubmit
            $isEdit = $submissionPaten->biodataPaten !== null;

            // Create or update biodata paten
            $biodataPaten = $submissionPaten->biodataPaten;
            if (!$biodataPaten) {
                $biodataPaten = BiodataPaten::create([
                    'submission_paten_id' => $submissionPaten->id,
                    'user_id' => Auth::id(),
                    'status' => 'pending',
                ]);
            } else {
                // Only allow editing if not approved
                if ($biodataPaten->isApproved()) {
                    return redirect()->route('user.biodata-paten.show', [$submissionPaten, $biodataPaten])
                        ->with('error', 'Biodata yang telah disetujui tidak dapat diubah.');
                }

                $biodataPaten->update([
                    'status' => 'pending', // Reset to pending when edited
                    'rejection_reason' => null, // Clear previous rejection reason
                    'reviewed_at' => null, // Clear review timestamp
                    'reviewed_by' => null, // Clear reviewer
                ]);

                // Delete existing inventors
                $biodataPaten->inventors()->delete();
            }

            // Create inventors
            foreach ($request->inventors as $index => $inventorData) {
                BiodataPatenInventor::create([
                    'biodata_paten_id' => $biodataPaten->id,
                    'name' => $inventorData['name'],
                    'pekerjaan' => $inventorData['pekerjaan'],
                    'universitas' => $inventorData['universitas'],
                    'fakultas' => $inventorData['fakultas'],
                    'alamat' => $inventorData['alamat'],
                    'kelurahan' => $inventorData['kelurahan'],
                    'kecamatan' => $inventorData['kecamatan'],
                    'kota_kabupaten' => $inventorData['kota_kabupaten'],
                    'provinsi' => $inventorData['provinsi'],
                    'kode_pos' => $inventorData['kode_pos'],
                    'email' => $inventorData['email'] ?? null,
                    'nomor_hp' => $inventorData['nomor_hp'],
                    'kewarganegaraan' => $inventorData['kewarganegaraan'],
                    'is_leader' => $index === 0, // First inventor is the leader
                    // Reset all error flags for new submission
                    'error_name' => false,
                    'error_pekerjaan' => false,
                    'error_universitas' => false,
                    'error_fakultas' => false,
                    'error_program_studi' => false,
                    'error_alamat' => false,
                    'error_kelurahan' => false,
                    'error_kecamatan' => false,
                    'error_kota_kabupaten' => false,
                    'error_provinsi' => false,
                    'error_kode_pos' => false,
                    'error_email' => false,
                    'error_nomor_hp' => false,
                    'error_kewarganegaraan' => false,
                ]);
            }

            // Update submission biodata_status when biodata is submitted
            $submissionPaten->update([
                'biodata_status' => 'pending',
                'biodata_submitted_at' => now(),
            ]);

            DB::commit();

            // Different message for resubmission vs new submission
            $message = $isEdit 
                ? 'Biodata berhasil direvisi dan telah dikirim ulang untuk review admin.' 
                : 'Biodata berhasil disimpan dan sedang menunggu review admin.';

            return redirect()->route('user.biodata-paten.show', [$submissionPaten, $biodataPaten])
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan biodata: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified biodata paten
     */
    public function show(SubmissionPaten $submissionPaten, BiodataPaten $biodataPaten)
    {
        // Check if user owns the submission
        if ($submissionPaten->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this submission.');
        }

        // Check if biodata belongs to submission
        if ($biodataPaten->submission_paten_id !== $submissionPaten->id) {
            abort(404, 'Biodata not found for this submission.');
        }

        $biodataPaten->load('inventors', 'reviewedBy');

        return view('user.biodata-paten.show', compact('submissionPaten', 'biodataPaten'));
    }

    /**
     * Generate and download Word document from template
     */
    public function downloadFormulir(BiodataPaten $biodataPaten)
    {
        // 1. CEK AUTHORIZATION - hanya user pemilik
        if (Auth::id() !== $biodataPaten->submissionPaten->user_id) {
            abort(403, 'Unauthorized access');
        }
        
        // 2. CEK STATUS - hanya biodata yang sudah ACC
        if ($biodataPaten->status !== 'approved') {
            return back()->with('error', 'Biodata harus di-ACC oleh admin terlebih dahulu');
        }
        
        // 3. LOAD TEMPLATE berdasarkan kategori submission paten
        $category = strtolower($biodataPaten->submissionPaten->kategori_paten ?? 'paten');
        $category = str_replace(' ', '_', $category); // ganti spasi dengan underscore
        
        $templateFileName = "{$category}_formulir_paten.docx";
        $templatePath = public_path("templates/{$templateFileName}");
        
        // Fallback ke template default jika template kategori tidak ditemukan
        if (!file_exists($templatePath)) {
            $templatePath = public_path('templates/formulir_paten_template.docx');
            
            if (!file_exists($templatePath)) {
                return back()->with('error', "Template dokumen untuk kategori '{$biodataPaten->submissionPaten->kategori_paten}' tidak ditemukan. Silakan hubungi administrator.");
            }
        }
        
        try {
            $templateProcessor = new TemplateProcessor($templatePath);
            
            // 4. LOAD RELATIONSHIPS
            $biodataPaten->load(['inventors', 'submissionPaten']);
            
            // 5. SET SINGLE VALUES (data umum yang tidak perlu di-clone)
            $templateProcessor->setValue('tanggal_download', 
                \Carbon\Carbon::now('Asia/Makassar')->locale('id')->isoFormat('D MMMM Y'));
            
            $templateProcessor->setValue('tanggal_pengajuan', 
                \Carbon\Carbon::parse($biodataPaten->created_at)->locale('id')->isoFormat('D MMMM Y'));
            
            // Data Submission Paten
            $templateProcessor->setValue('judul_paten', 
                $biodataPaten->submissionPaten->judul_paten ?? '-');
            
            $templateProcessor->setValue('kategori_paten', 
                $biodataPaten->submissionPaten->kategori_paten ?? '-');
            
            // Data Biodata (tempat, tanggal, uraian invensi)
            $templateProcessor->setValue('tempat_invensi', 
                $biodataPaten->tempat_invensi ?? '-');
            $templateProcessor->setValue('tanggal_invensi', 
                $biodataPaten->tanggal_invensi ? \Carbon\Carbon::parse($biodataPaten->tanggal_invensi)->locale('id')->isoFormat('D MMMM Y') : '-');
            $templateProcessor->setValue('uraian_singkat', 
                $biodataPaten->uraian_singkat ?? '-');
            
            // 6. CLONE BLOCK untuk SEMUA INVENTORS
            $allInventors = $biodataPaten->inventors;
            $inventorCount = $allInventors->count();
            
            // Gabungkan semua nama inventor untuk ditampilkan dalam 1 baris
            $allInventorNames = $allInventors->pluck('name')->implode(' ; ');
            $templateProcessor->setValue('all_inventor_names', $allInventorNames ?: '-');
            
            if ($inventorCount > 0) {
                // Try clone row
                try {
                    $templateProcessor->cloneRow('inventor_no', $inventorCount);
                    Log::info("Cloned row 'inventor_no' for {$inventorCount} inventors");
                } catch (\Exception $e) {
                    Log::info("cloneRow 'inventor_no' failed, trying block cloning: " . $e->getMessage());
                }
                
                // Set values untuk setiap inventor
                foreach ($allInventors as $index => $inventor) {
                    $num = $index + 1;
                    
                    // Susun alamat lengkap dengan format singkat
                    $alamatParts = [];
                    
                    if ($inventor->alamat) {
                        $alamatParts[] = $inventor->alamat;
                    }
                    
                    if ($inventor->kelurahan) {
                        // Cek apakah "Desa" atau "Kelurahan"
                        $kelurahan = $inventor->kelurahan;
                        if (stripos($kelurahan, 'DESA') === 0) {
                            // Jika Desa, tetap gunakan "Desa"
                            $alamatParts[] = $kelurahan;
                        } else {
                            // Jika Kelurahan, singkat jadi "Kel."
                            $kelurahan = preg_replace('/^KELURAHAN\s+/i', '', $kelurahan);
                            $alamatParts[] = 'Kel. ' . $kelurahan;
                        }
                    }
                    
                    if ($inventor->kecamatan) {
                        $alamatParts[] = 'Kec. ' . $inventor->kecamatan;
                    }
                    
                    if ($inventor->kota_kabupaten) {
                        // Singkat Kabupaten jadi Kab.
                        $kotaKab = $inventor->kota_kabupaten;
                        $kotaKab = preg_replace('/^KABUPATEN\s+/i', 'Kab. ', $kotaKab);
                        $alamatParts[] = $kotaKab;
                    }
                    
                    // Tidak sertakan kata "Provinsi", langsung nama provinsinya
                    if ($inventor->provinsi) {
                        $provinsi = preg_replace('/^PROVINSI\s+/i', '', $inventor->provinsi);
                        $alamatParts[] = $provinsi;
                    }
                    
                    if ($inventor->kode_pos) {
                        $alamatParts[] = $inventor->kode_pos;
                    }
                    
                    $alamatLengkap = implode(', ', $alamatParts);
                    
                    // Set with numbering
                    $templateProcessor->setValue("inventor_no#$num", $num . ')');
                    $templateProcessor->setValue("inventor_name#$num", $inventor->name);
                    $templateProcessor->setValue("inventor_nik#$num", $inventor->nik ?? '-');
                    $templateProcessor->setValue("inventor_npwp#$num", $inventor->npwp ?? '-');
                    $templateProcessor->setValue("inventor_jenis_kelamin#$num", $inventor->jenis_kelamin ?? '-');
                    $templateProcessor->setValue("inventor_kewarganegaraan#$num", $inventor->kewarganegaraan ?? '-');
                    $templateProcessor->setValue("inventor_pekerjaan#$num", $inventor->pekerjaan ?? '-');
                    $templateProcessor->setValue("inventor_universitas#$num", $inventor->universitas ?? '-');
                    $templateProcessor->setValue("inventor_fakultas#$num", $inventor->fakultas ?? '-');
                    $templateProcessor->setValue("inventor_program_studi#$num", $inventor->program_studi ?? '-');
                    $templateProcessor->setValue("inventor_alamat#$num", $alamatLengkap ?: '-');
                    $templateProcessor->setValue("inventor_email#$num", $inventor->email ?? '-');
                    $templateProcessor->setValue("inventor_nomor_hp#$num", $inventor->nomor_hp ?? '-');
                    
                    // Set without numbering for first inventor
                    if ($num === 1) {
                        $templateProcessor->setValue("inventor_no", $num . ')');
                        $templateProcessor->setValue("inventor_name", $inventor->name);
                        $templateProcessor->setValue("inventor_nik", $inventor->nik ?? '-');
                        $templateProcessor->setValue("inventor_npwp", $inventor->npwp ?? '-');
                        $templateProcessor->setValue("inventor_alamat", $alamatLengkap ?: '-');
                        $templateProcessor->setValue("inventor_email", $inventor->email ?? '-');
                        $templateProcessor->setValue("inventor_nomor_hp", $inventor->nomor_hp ?? '-');
                    }
                }
            }
            
            // 7. SAVE FILE
            $fileName = 'Formulir_Paten_' . $category . '_' . $biodataPaten->id . '_' . time() . '.docx';
            $outputPath = storage_path('app/public/generated_documents/' . $fileName);
            
            // Create directory if not exists
            if (!file_exists(dirname($outputPath))) {
                mkdir(dirname($outputPath), 0755, true);
            }
            
            $templateProcessor->saveAs($outputPath);
            
            // 8. DOWNLOAD FILE dan hapus setelah download
            return response()->download($outputPath, $fileName)->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            Log::error('Error generating Word document for Paten: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat generate dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Mark document as submitted
     */
    public function markDocumentSubmitted(BiodataPaten $biodataPaten)
    {
        // Check authorization
        if (Auth::id() !== $biodataPaten->submissionPaten->user_id) {
            abort(403, 'Unauthorized access');
        }
        
        // Check if biodata is approved
        if ($biodataPaten->status !== 'approved') {
            return back()->with('error', 'Biodata harus di-ACC terlebih dahulu sebelum menyetor berkas.');
        }
        
        // Update status
        $biodataPaten->update([
            'document_submitted' => true,
            'document_submitted_at' => now(),
        ]);
        
        return back()->with('success', 'Berkas telah ditandai sebagai disetor. Terima kasih!');
    }

    /**
     * Generate and download Surat Pengalihan Invensi (Transfer of Invention Letter)
     */
    public function downloadSuratPengalihan(BiodataPaten $biodataPaten)
    {
        // 1. CEK AUTHORIZATION - hanya user pemilik
        if (Auth::id() !== $biodataPaten->submissionPaten->user_id) {
            abort(403, 'Unauthorized access');
        }
        
        // 2. CEK STATUS - hanya biodata yang sudah ACC
        if ($biodataPaten->status !== 'approved') {
            return back()->with('error', 'Biodata harus di-ACC oleh admin terlebih dahulu');
        }
        
        // 3. LOAD TEMPLATE
        $templatePath = public_path('templates/surat_pengalihan_invensi.docx');
        
        if (!file_exists($templatePath)) {
            return back()->with('error', 'Template Surat Pengalihan Invensi tidak ditemukan. Silakan hubungi administrator.');
        }
        
        try {
            $templateProcessor = new TemplateProcessor($templatePath);
            
            // 4. LOAD RELATIONSHIPS
            $biodataPaten->load(['inventors', 'submissionPaten']);
            
            // 5. SET SINGLE VALUES (data umum)
            $templateProcessor->setValue('tanggal_download', 
                \Carbon\Carbon::now('Asia/Makassar')->locale('id')->isoFormat('D MMMM Y'));
            
            $templateProcessor->setValue('tanggal_pengajuan', 
                \Carbon\Carbon::parse($biodataPaten->created_at)->locale('id')->isoFormat('D MMMM Y'));
            
            // Data Submission Paten
            $templateProcessor->setValue('judul_paten', 
                $biodataPaten->submissionPaten->judul_paten ?? '-');
            
            $templateProcessor->setValue('paten_title', 
                strtoupper($biodataPaten->submissionPaten->judul_paten ?? '-'));
            
            $templateProcessor->setValue('kategori_paten', 
                $biodataPaten->submissionPaten->kategori_paten ?? '-');
            
            // Data Biodata
            $templateProcessor->setValue('tempat_invensi', 
                $biodataPaten->tempat_invensi ?? '-');
            $templateProcessor->setValue('tanggal_invensi', 
                $biodataPaten->tanggal_invensi ? \Carbon\Carbon::parse($biodataPaten->tanggal_invensi)->locale('id')->isoFormat('D MMMM Y') : '-');
            $templateProcessor->setValue('uraian_singkat', 
                $biodataPaten->uraian_singkat ?? '-');
            
            // 6. CLONE ROW untuk INVENTORS
            $allInventors = $biodataPaten->inventors;
            $inventorCount = $allInventors->count();
            
            Log::info('Processing Surat Pengalihan Invensi', [
                'biodata_paten_id' => $biodataPaten->id,
                'inventor_count' => $inventorCount,
                'inventors' => $allInventors->pluck('name')->toArray()
            ]);
            
            if ($inventorCount > 0) {
                // Clone row untuk inventor
                try {
                    $templateProcessor->cloneRow('inventor_no', $inventorCount);
                    Log::info("Cloned row 'inventor_no' for {$inventorCount} inventors");
                } catch (\Exception $e) {
                    Log::warning("cloneRow 'inventor_no' failed: " . $e->getMessage());
                }
                
                // Clone row untuk signature_inventor
                try {
                    $templateProcessor->cloneRow('signature_inventor', $inventorCount);
                    Log::info("Cloned row 'signature_inventor' for {$inventorCount} inventors");
                } catch (\Exception $e) {
                    Log::warning("cloneRow 'signature_inventor' failed: " . $e->getMessage());
                }
                
                // Set values untuk setiap inventor
                foreach ($allInventors as $index => $inventor) {
                    $num = $index + 1;
                    
                    // Susun alamat lengkap - Urutan: alamat jalan, kode pos, kelurahan, kecamatan, kota/kab, provinsi
                    $alamatParts = [];
                    
                    if ($inventor->alamat) {
                        $alamatParts[] = $inventor->alamat;
                    }
                    
                    if ($inventor->kode_pos) {
                        $alamatParts[] = $inventor->kode_pos;
                    }
                    
                    if ($inventor->kelurahan) {
                        // Format kelurahan/desa dengan singkatan
                        $kelurahan = $inventor->kelurahan;
                        
                        // Cek apakah "Desa" atau "Kelurahan"
                        if (stripos($kelurahan, 'DESA') === 0) {
                            // Jika Desa, tetap gunakan "Desa"
                            $alamatParts[] = $kelurahan;
                        } else {
                            // Jika Kelurahan, singkat jadi "Kel."
                            $kelurahan = preg_replace('/^KELURAHAN\\s+/i', '', $kelurahan);
                            $alamatParts[] = 'Kel. ' . $kelurahan;
                        }
                    }
                    
                    if ($inventor->kecamatan) {
                        $alamatParts[] = 'Kec. ' . $inventor->kecamatan;
                    }
                    
                    if ($inventor->kota_kabupaten) {
                        // Singkat Kabupaten jadi Kab.
                        $kotaKab = $inventor->kota_kabupaten;
                        $kotaKab = preg_replace('/^KABUPATEN\\s+/i', 'Kab. ', $kotaKab);
                        $alamatParts[] = $kotaKab;
                    }
                    
                    // Tidak sertakan kata "Provinsi", langsung nama provinsinya
                    if ($inventor->provinsi) {
                        $provinsi = preg_replace('/^PROVINSI\\s+/i', '', $inventor->provinsi);
                        $alamatParts[] = $provinsi;
                    }
                    
                    $alamatLengkap = implode(', ', $alamatParts);
                    
                    // HALAMAN 1: Data inventor lengkap
                    $templateProcessor->setValue("inventor_no#$num", $num . '.');
                    $templateProcessor->setValue("inventor_name#$num", $inventor->name ?? '-');
                    $templateProcessor->setValue("inventor_pekerjaan#$num", $inventor->pekerjaan ?? '-');
                    $templateProcessor->setValue("inventor_alamat#$num", $alamatLengkap ?: '-');
                    
                    // HALAMAN 2: Signature section - Format: "1. Nama", "2. Nama", dst
                    $templateProcessor->setValue("signature_inventor#$num", $num . '. ' . ($inventor->name ?? '-'));
                    
                    // Materai hanya untuk inventor pertama
                    if ($num === 1) {
                        $templateProcessor->setValue("materai#$num", config('hki.materai.text', 'MATERAI'));
                    } else {
                        $templateProcessor->setValue("materai#$num", '');
                    }
                    
                    // Pejabat data - hanya row pertama yang dapat nilai, sisanya kosong
                    if ($num === 1) {
                        $templateProcessor->setValue("pejabat_nama#$num", config('hki.pejabat_pengalihan.nama', '-'));
                        $templateProcessor->setValue("pejabat_nip#$num", config('hki.pejabat_pengalihan.nip', '-'));
                    } else {
                        $templateProcessor->setValue("pejabat_nama#$num", '');
                        $templateProcessor->setValue("pejabat_nip#$num", '');
                    }
                    
                    // Set without numbering untuk inventor pertama (fallback jika template punya variable tanpa #)
                    if ($num === 1) {
                        $templateProcessor->setValue("inventor_no", $num . '.');
                        $templateProcessor->setValue("inventor_name", $inventor->name ?? '-');
                        $templateProcessor->setValue("inventor_pekerjaan", $inventor->pekerjaan ?? '-');
                        $templateProcessor->setValue("inventor_alamat", $alamatLengkap ?: '-');
                        $templateProcessor->setValue("signature_inventor", $num . '. ' . ($inventor->name ?? '-'));
                        $templateProcessor->setValue("materai", config('hki.materai.text', 'MATERAI'));
                        $templateProcessor->setValue('pejabat_nama', config('hki.pejabat_pengalihan.nama', '-'));
                        $templateProcessor->setValue('pejabat_nip', config('hki.pejabat_pengalihan.nip', '-'));
                    }
                }
                    
            } else {
                // Jika tidak ada inventor, set nilai default
                $templateProcessor->setValue("inventor_no", '-');
                $templateProcessor->setValue("inventor_name", '-');
                $templateProcessor->setValue("inventor_pekerjaan", '-');
                $templateProcessor->setValue("inventor_alamat", '-');
                $templateProcessor->setValue("signature_inventor", '-');
                $templateProcessor->setValue("materai", '-');
                $templateProcessor->setValue('pejabat_nama', config('hki.pejabat_pengalihan.nama', '-'));
                $templateProcessor->setValue('pejabat_nip', config('hki.pejabat_pengalihan.nip', '-'));
            }
            
            // 7. SAVE FILE
            $fileName = 'Surat_Pengalihan_Invensi_' . $biodataPaten->id . '_' . time() . '.docx';
            $outputPath = storage_path('app/public/generated_documents/' . $fileName);
            
            // Create directory if not exists
            if (!file_exists(dirname($outputPath))) {
                mkdir(dirname($outputPath), 0755, true);
            }
            
            $templateProcessor->saveAs($outputPath);
            
            Log::info('Surat Pengalihan Invensi generated successfully', [
                'biodata_paten_id' => $biodataPaten->id,
                'file_name' => $fileName,
                'output_path' => $outputPath
            ]);
            
            // 8. DOWNLOAD FILE dan hapus setelah download
            return response()->download($outputPath, $fileName)->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            Log::error('Error generating Surat Pengalihan Invensi: ' . $e->getMessage(), [
                'biodata_paten_id' => $biodataPaten->id,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Terjadi kesalahan saat generate dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Generate and download Surat Pernyataan Kepemilikan Invensi oleh Inventor
     */
    public function downloadSuratPernyataan(BiodataPaten $biodataPaten)
    {
        // 1. CEK AUTHORIZATION - hanya user pemilik
        if (Auth::id() !== $biodataPaten->submissionPaten->user_id) {
            abort(403, 'Unauthorized access');
        }
        
        // 2. CEK STATUS - hanya biodata yang sudah ACC
        if ($biodataPaten->status !== 'approved') {
            return back()->with('error', 'Biodata harus di-ACC oleh admin terlebih dahulu');
        }
        
        // 3. LOAD TEMPLATE
        $templatePath = public_path('templates/surat_pernyataan_kepemilikan_invensi_oleh_inventor.docx');
        
        if (!file_exists($templatePath)) {
            return back()->with('error', 'Template Surat Pernyataan Kepemilikan Invensi tidak ditemukan. Silakan hubungi administrator.');
        }
        
        try {
            // Validate file is actually a valid DOCX (ZIP) file
            if (!class_exists('ZipArchive')) {
                return back()->with('error', 'ZipArchive extension tidak tersedia. Silakan hubungi administrator.');
            }
            
            $zip = new \ZipArchive();
            if ($zip->open($templatePath) !== true) {
                Log::error('Template file is not a valid DOCX (ZIP) file', [
                    'template_path' => $templatePath,
                    'file_exists' => file_exists($templatePath),
                    'file_size' => filesize($templatePath)
                ]);
                return back()->with('error', 'Template file bukan format DOCX yang valid. File mungkin corrupt atau hanya di-rename dari .doc ke .docx tanpa di-convert. Silakan convert ulang menggunakan Microsoft Word (File → Save As → Word Document .docx)');
            }
            $zip->close();
            
            $templateProcessor = new TemplateProcessor($templatePath);
            
            // 4. LOAD RELATIONSHIPS
            $biodataPaten->load(['inventors', 'submissionPaten']);
            
            // 5. SET SINGLE VALUES (data umum)
            $templateProcessor->setValue('tanggal_download', 
                \Carbon\Carbon::now('Asia/Makassar')->locale('id')->isoFormat('D MMMM Y'));
            
            $templateProcessor->setValue('tanggal_pengajuan', 
                \Carbon\Carbon::parse($biodataPaten->created_at)->locale('id')->isoFormat('D MMMM Y'));
            
            // Data Submission Paten
            $templateProcessor->setValue('judul_paten', 
                $biodataPaten->submissionPaten->judul_paten ?? '-');
            
            $templateProcessor->setValue('paten_title', 
                strtoupper($biodataPaten->submissionPaten->judul_paten ?? '-'));
            
            $templateProcessor->setValue('kategori_paten', 
                $biodataPaten->submissionPaten->kategori_paten ?? '-');
            
            // Data Biodata
            $templateProcessor->setValue('tempat_invensi', 
                $biodataPaten->tempat_invensi ?? '-');
            $templateProcessor->setValue('tanggal_invensi', 
                $biodataPaten->tanggal_invensi ? \Carbon\Carbon::parse($biodataPaten->tanggal_invensi)->locale('id')->isoFormat('D MMMM Y') : '-');
            $templateProcessor->setValue('uraian_singkat', 
                $biodataPaten->uraian_singkat ?? '-');
            
            // 6. CLONE ROW untuk INVENTORS
            $allInventors = $biodataPaten->inventors;
            $inventorCount = $allInventors->count();
            
            Log::info('Processing Surat Pernyataan Kepemilikan Invensi', [
                'biodata_paten_id' => $biodataPaten->id,
                'inventor_count' => $inventorCount,
                'inventors' => $allInventors->pluck('name')->toArray()
            ]);
            
            if ($inventorCount > 0) {
                // Clone row untuk inventor
                try {
                    $templateProcessor->cloneRow('inventor_no', $inventorCount);
                    Log::info("Cloned row 'inventor_no' for {$inventorCount} inventors");
                } catch (\Exception $e) {
                    Log::warning("cloneRow 'inventor_no' failed: " . $e->getMessage());
                }
                
                // Set values untuk setiap inventor
                foreach ($allInventors as $index => $inventor) {
                    $num = $index + 1;
                    
                    // Susun alamat lengkap - Urutan: alamat jalan, kode pos, kelurahan, kecamatan, kota/kab, provinsi
                    $alamatParts = [];
                    
                    if ($inventor->alamat) {
                        $alamatParts[] = $inventor->alamat;
                    }
                    
                    if ($inventor->kode_pos) {
                        $alamatParts[] = $inventor->kode_pos;
                    }
                    
                    if ($inventor->kelurahan) {
                        // Format kelurahan/desa dengan prefix yang jelas
                        $kelurahan = $inventor->kelurahan;
                        
                        // Cek apakah sudah ada prefix KELURAHAN atau DESA di awal nama
                        if (stripos($kelurahan, 'KELURAHAN') === 0) {
                            // Jika sudah ada prefix KELURAHAN, gunakan langsung
                            $alamatParts[] = $kelurahan;
                        } elseif (stripos($kelurahan, 'DESA') === 0) {
                            // Jika sudah ada prefix DESA, gunakan langsung
                            $alamatParts[] = $kelurahan;
                        } else {
                            // Jika tidak ada prefix, tambahkan 'Kelurahan' sebagai default
                            $alamatParts[] = 'Kelurahan ' . $kelurahan;
                        }
                    }
                    
                    if ($inventor->kecamatan) {
                        $alamatParts[] = 'Kec. ' . $inventor->kecamatan;
                    }
                    
                    if ($inventor->kota_kabupaten) {
                        $alamatParts[] = $inventor->kota_kabupaten;
                    }
                    
                    if ($inventor->provinsi) {
                        $alamatParts[] = 'Provinsi ' . $inventor->provinsi;
                    }
                    
                    $alamatLengkap = implode(', ', $alamatParts);
                    
                    // Set values dengan numbering
                    $templateProcessor->setValue("inventor_no#$num", $num . '.');
                    $templateProcessor->setValue("inventor_name#$num", $inventor->name ?? '-');
                    $templateProcessor->setValue("inventor_alamat#$num", $alamatLengkap ?: '-');
                    $templateProcessor->setValue("inventor_email#$num", $inventor->email ?? '-');
                    $templateProcessor->setValue("inventor_kewarganegaraan#$num", $inventor->kewarganegaraan ?? '-');
                    $templateProcessor->setValue("inventor_nomor_hp#$num", $inventor->nomor_hp ?? '-');
                    
                    // Set without numbering untuk inventor pertama (fallback jika template punya variable tanpa #)
                    if ($num === 1) {
                        $templateProcessor->setValue("inventor_no", $num . '.');
                        $templateProcessor->setValue("inventor_name", $inventor->name ?? '-');
                        $templateProcessor->setValue("inventor_alamat", $alamatLengkap ?: '-');
                        $templateProcessor->setValue("inventor_email", $inventor->email ?? '-');
                        $templateProcessor->setValue("inventor_kewarganegaraan", $inventor->kewarganegaraan ?? '-');
                        $templateProcessor->setValue("inventor_nomor_hp", $inventor->nomor_hp ?? '-');
                    }
                }
            } else {
                // Jika tidak ada inventor, set nilai default
                $templateProcessor->setValue("inventor_no", '-');
                $templateProcessor->setValue("inventor_name", '-');
                $templateProcessor->setValue("inventor_alamat", '-');
                $templateProcessor->setValue("inventor_email", '-');
                $templateProcessor->setValue("inventor_kewarganegaraan", '-');
                $templateProcessor->setValue("inventor_nomor_hp", '-');
            }
            
            // 7. CLONE ROW TABEL untuk TANDA TANGAN dengan 2 kolom TERPISAH (Kiri-Kanan)
            // STRATEGI: Gunakan placeholder berbeda untuk kiri dan kanan
            // Format: ${inventor_kiri} | ${inventor_kanan}
            // Ganjil (1,3,5,...) → Kiri | Genap (2,4,6,...) → Kanan
            // Materai hanya untuk inventor pertama (kiri baris 1)
            if ($inventorCount > 0) {
                // Hitung jumlah row yang dibutuhkan (2 inventor per row)
                $rowsNeeded = ceil($inventorCount / 2);
                
                Log::info("Attempting to clone signature table with separate left/right placeholders", [
                    'inventor_count' => $inventorCount,
                    'rows_needed' => $rowsNeeded,
                ]);
                
                try {
                    // Clone row berdasarkan placeholder di kolom kiri
                    $templateProcessor->cloneRow('inventor_kiri', $rowsNeeded);
                    Log::info("CloneRow 'inventor_kiri' success - cloned {$rowsNeeded} rows");
                    
                    // Set values untuk setiap row
                    $inventorIndex = 0;
                    for ($row = 1; $row <= $rowsNeeded; $row++) {
                        // Kolom KIRI (inventor index: 0, 2, 4, ... = urutan ganjil dalam 1-based)
                        if ($inventorIndex < $inventorCount) {
                            $inventor = $allInventors[$inventorIndex];
                            $nomorUrut = $inventorIndex + 1;
                            // Format: "1. Nama", "2. Nama", dst
                            $templateProcessor->setValue("inventor_kiri#$row", $nomorUrut . '. ' . $inventor->name);
                            
                            // Materai hanya untuk inventor pertama (index 0, row 1, kolom kiri)
                            if ($inventorIndex === 0) {
                                try {
                                    $templateProcessor->setValue("materai_kiri#$row", config('hki.materai.text', 'MATERAI'));
                                    Log::info("Set inventor_kiri#{$row} = {$nomorUrut}. {$inventor->name} WITH MATERAI");
                                } catch (\Exception $e) {
                                    Log::info("Set inventor_kiri#{$row} = {$nomorUrut}. {$inventor->name}");
                                }
                            } else {
                                try {
                                    $templateProcessor->setValue("materai_kiri#$row", '');
                                } catch (\Exception $e) {}
                                Log::info("Set inventor_kiri#{$row} = {$nomorUrut}. {$inventor->name}");
                            }
                            $inventorIndex++;
                        } else {
                            // Cell kosong - tanpa kurung
                            $templateProcessor->setValue("inventor_kiri#$row", '');
                            try {
                                $templateProcessor->setValue("materai_kiri#$row", '');
                            } catch (\Exception $e) {}
                        }
                        
                        // Kolom KANAN (inventor index: 1, 3, 5, ... = urutan genap dalam 1-based)
                        if ($inventorIndex < $inventorCount) {
                            $inventor = $allInventors[$inventorIndex];
                            $nomorUrut = $inventorIndex + 1;
                            // Format: "1. Nama", "2. Nama", dst
                            $templateProcessor->setValue("inventor_kanan#$row", $nomorUrut . '. ' . $inventor->name);
                            Log::info("Set inventor_kanan#{$row} = {$nomorUrut}. {$inventor->name}");
                            $inventorIndex++;
                        } else {
                            // Cell kosong - tanpa kurung
                            $templateProcessor->setValue("inventor_kanan#$row", '');
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning("CloneRow 'inventor_kiri' failed: " . $e->getMessage());
                    // Fallback jika clone gagal - set single values
                    $templateProcessor->setValue("inventor_kiri", '1. ' . ($allInventors[0]->name ?? '-'));
                    $templateProcessor->setValue("inventor_kanan", isset($allInventors[1]) ? '2. ' . $allInventors[1]->name : '');
                    $templateProcessor->setValue("materai_kiri", config('hki.materai.text', 'MATERAI'));
                }
            } else {
                // Jika tidak ada inventor, set nilai default kosong
                try {
                    $templateProcessor->setValue("inventor_kiri", '-');
                    $templateProcessor->setValue("inventor_kanan", '');
                    $templateProcessor->setValue("materai_kiri", '');
                } catch (\Exception $e) {
                    Log::warning("Failed to set default signature values: " . $e->getMessage());
                }
            }
            
            // 8. SAVE FILE
            $fileName = 'Surat_Pernyataan_Kepemilikan_Invensi_Oleh_Inventor_' . $biodataPaten->id . '_' . time() . '.docx';
            $outputPath = storage_path('app/public/generated_documents/' . $fileName);
            
            // Create directory if not exists
            if (!file_exists(dirname($outputPath))) {
                mkdir(dirname($outputPath), 0755, true);
            }
            
            $templateProcessor->saveAs($outputPath);
            
            Log::info('Surat Pernyataan Kepemilikan Invensi Oleh Inventor generated successfully', [
                'biodata_paten_id' => $biodataPaten->id,
                'file_name' => $fileName,
                'output_path' => $outputPath
            ]);
            
            // 8. DOWNLOAD FILE dan hapus setelah download
            return response()->download($outputPath, $fileName)->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            Log::error('Error generating Surat Pernyataan Kepemilikan Invensi Oleh Inventor: ' . $e->getMessage(), [
                'biodata_paten_id' => $biodataPaten->id,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Terjadi kesalahan saat generate dokumen: ' . $e->getMessage());
        }
    }
    
    /**
     * Upload 4 Patent Documents (Deskripsi, Klaim, Abstrak, Gambar)
     */
    public function uploadPatentDocuments(Request $request, BiodataPaten $biodataPaten)
    {
        // Check authorization
        if ($biodataPaten->submissionPaten->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }
        
        // Check if biodata is approved
        if ($biodataPaten->status !== 'approved') {
            return back()->with('error', 'Biodata harus di-ACC oleh admin terlebih dahulu');
        }
        
        // Validate upload - Maks 10MB per file PDF
        $validated = $request->validate([
            'deskripsi_pdf' => 'nullable|file|mimes:pdf|max:10240',
            'klaim_pdf' => 'nullable|file|mimes:pdf|max:10240',
            'abstrak_pdf' => 'nullable|file|mimes:pdf|max:10240',
            'gambar_pdf' => 'nullable|file|mimes:pdf|max:10240',
        ], [
            'deskripsi_pdf.mimes' => 'File Deskripsi harus berformat PDF',
            'deskripsi_pdf.max' => 'File Deskripsi maksimal 10MB',
            'klaim_pdf.mimes' => 'File Klaim harus berformat PDF',
            'klaim_pdf.max' => 'File Klaim maksimal 10MB',
            'abstrak_pdf.mimes' => 'File Abstrak harus berformat PDF',
            'abstrak_pdf.max' => 'File Abstrak maksimal 10MB',
            'gambar_pdf.mimes' => 'File Gambar harus berformat PDF',
            'gambar_pdf.max' => 'File Gambar maksimal 10MB',
        ]);
        
        try {
            $uploadedFiles = [];
            
            // Upload Deskripsi PDF
            if ($request->hasFile('deskripsi_pdf')) {
                // Delete old file if exists
                if ($biodataPaten->deskripsi_pdf && Storage::disk('public')->exists($biodataPaten->deskripsi_pdf)) {
                    Storage::disk('public')->delete($biodataPaten->deskripsi_pdf);
                }
                
                $file = $request->file('deskripsi_pdf');
                $fileName = 'deskripsi_' . $biodataPaten->id . '_' . time() . '.pdf';
                $path = $file->storeAs('patent_documents/deskripsi', $fileName, 'public');
                $biodataPaten->deskripsi_pdf = $path;
                $uploadedFiles[] = 'Deskripsi';
            }
            
            // Upload Klaim PDF
            if ($request->hasFile('klaim_pdf')) {
                // Delete old file if exists
                if ($biodataPaten->klaim_pdf && Storage::disk('public')->exists($biodataPaten->klaim_pdf)) {
                    Storage::disk('public')->delete($biodataPaten->klaim_pdf);
                }
                
                $file = $request->file('klaim_pdf');
                $fileName = 'klaim_' . $biodataPaten->id . '_' . time() . '.pdf';
                $path = $file->storeAs('patent_documents/klaim', $fileName, 'public');
                $biodataPaten->klaim_pdf = $path;
                $uploadedFiles[] = 'Klaim';
            }
            
            // Upload Abstrak PDF
            if ($request->hasFile('abstrak_pdf')) {
                // Delete old file if exists
                if ($biodataPaten->abstrak_pdf && Storage::disk('public')->exists($biodataPaten->abstrak_pdf)) {
                    Storage::disk('public')->delete($biodataPaten->abstrak_pdf);
                }
                
                $file = $request->file('abstrak_pdf');
                $fileName = 'abstrak_' . $biodataPaten->id . '_' . time() . '.pdf';
                $path = $file->storeAs('patent_documents/abstrak', $fileName, 'public');
                $biodataPaten->abstrak_pdf = $path;
                $uploadedFiles[] = 'Abstrak';
            }
            
            // Upload Gambar PDF (Optional)
            if ($request->hasFile('gambar_pdf')) {
                // Delete old file if exists
                if ($biodataPaten->gambar_pdf && Storage::disk('public')->exists($biodataPaten->gambar_pdf)) {
                    Storage::disk('public')->delete($biodataPaten->gambar_pdf);
                }
                
                $file = $request->file('gambar_pdf');
                $fileName = 'gambar_' . $biodataPaten->id . '_' . time() . '.pdf';
                $path = $file->storeAs('patent_documents/gambar', $fileName, 'public');
                $biodataPaten->gambar_pdf = $path;
                $uploadedFiles[] = 'Gambar';
            }
            
            // Update timestamp if at least one file uploaded
            if (count($uploadedFiles) > 0) {
                $biodataPaten->patent_documents_uploaded_at = now();
                $biodataPaten->save();
                
                $message = 'Berhasil mengupload: ' . implode(', ', $uploadedFiles);
                return back()->with('success', $message);
            }
            
            return back()->with('info', 'Tidak ada file yang diupload');
            
        } catch (\Exception $e) {
            Log::error('Error uploading patent documents: ' . $e->getMessage(), [
                'biodata_paten_id' => $biodataPaten->id,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Terjadi kesalahan saat upload dokumen: ' . $e->getMessage());
        }
    }
    
    /**
     * Download specific patent document
     */
    public function downloadPatentDocument(BiodataPaten $biodataPaten, $type)
    {
        // Check authorization
        if ($biodataPaten->submissionPaten->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }
        
        // Validate type
        $allowedTypes = ['deskripsi', 'klaim', 'abstrak', 'gambar'];
        if (!in_array($type, $allowedTypes)) {
            abort(404, 'Invalid document type');
        }
        
        $fieldName = $type . '_pdf';
        $filePath = $biodataPaten->$fieldName;
        
        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            return back()->with('error', 'File tidak ditemukan');
        }
        
        $fullPath = storage_path('app/public/' . $filePath);
        $fileName = ucfirst($type) . '_Paten_' . $biodataPaten->id . '.pdf';
        
        return response()->download($fullPath, $fileName);
    }
}
