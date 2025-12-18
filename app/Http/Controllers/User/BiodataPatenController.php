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

        // Check if submission is approved
        if ($submissionPaten->status !== 'approved') {
            return redirect()->route('user.submissions-paten.show', $submissionPaten)
                ->with('error', 'Biodata hanya dapat dibuat untuk pengajuan paten yang telah disetujui.');
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

        // Check if submission is approved
        if ($submissionPaten->status !== 'approved') {
            return redirect()->route('user.submissions-paten.show', $submissionPaten)
                ->with('error', 'Biodata hanya dapat dibuat untuk pengajuan paten yang telah disetujui.');
        }

        // Validate the request
        $validatedData = $request->validate([
            'tempat_invensi' => 'required|string|max:255',
            'tanggal_invensi' => 'required|date',
            'uraian_singkat' => 'required|string',
            'inventors' => 'required|array|min:1|max:10',
            'inventors.*.name' => 'required|string|max:255',
            'inventors.*.nik' => 'required|digits:16',
            'inventors.*.npwp' => 'nullable|string|max:255',
            'inventors.*.jenis_kelamin' => 'required|in:Pria,Wanita',
            'inventors.*.pekerjaan' => 'required|string|max:255',
            'inventors.*.universitas' => 'required|string|max:255',
            'inventors.*.fakultas' => 'required|string|max:255',
            'inventors.*.program_studi' => 'required|string|max:255',
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
                    'tempat_invensi' => $request->tempat_invensi,
                    'tanggal_invensi' => $request->tanggal_invensi,
                    'uraian_singkat' => $request->uraian_singkat,
                    'status' => 'pending',
                ]);
            } else {
                // Only allow editing if not approved
                if ($biodataPaten->isApproved()) {
                    return redirect()->route('user.biodata-paten.show', [$submissionPaten, $biodataPaten])
                        ->with('error', 'Biodata yang telah disetujui tidak dapat diubah.');
                }

                $biodataPaten->update([
                    'tempat_invensi' => $request->tempat_invensi,
                    'tanggal_invensi' => $request->tanggal_invensi,
                    'uraian_singkat' => $request->uraian_singkat,
                    'status' => 'pending', // Reset to pending when edited
                    'rejection_reason' => null, // Clear previous rejection reason
                    'reviewed_at' => null, // Clear review timestamp
                    'reviewed_by' => null, // Clear reviewer
                    // Reset all error flags
                    'error_tempat_invensi' => false,
                    'error_tanggal_invensi' => false,
                    'error_uraian_singkat' => false,
                ]);

                // Delete existing inventors
                $biodataPaten->inventors()->delete();
            }

            // Create inventors
            foreach ($request->inventors as $index => $inventorData) {
                BiodataPatenInventor::create([
                    'biodata_paten_id' => $biodataPaten->id,
                    'name' => $inventorData['name'],
                    'nik' => $inventorData['nik'],
                    'npwp' => $inventorData['npwp'] ?? null,
                    'jenis_kelamin' => $inventorData['jenis_kelamin'],
                    'pekerjaan' => $inventorData['pekerjaan'],
                    'universitas' => $inventorData['universitas'],
                    'fakultas' => $inventorData['fakultas'],
                    'program_studi' => $inventorData['program_studi'],
                    'alamat' => $inventorData['alamat'],
                    'kelurahan' => $inventorData['kelurahan'],
                    'kecamatan' => $inventorData['kecamatan'],
                    'kota_kabupaten' => $inventorData['kota_kabupaten'],
                    'provinsi' => $inventorData['provinsi'],
                    'kode_pos' => $inventorData['kode_pos'],
                    'email' => $inventorData['email'],
                    'nomor_hp' => $inventorData['nomor_hp'],
                    'kewarganegaraan' => $inventorData['kewarganegaraan'],
                    'is_leader' => $index === 0, // First inventor is the leader
                    // Reset all error flags for new submission
                    'error_name' => false,
                    'error_nik' => false,
                    'error_npwp' => false,
                    'error_jenis_kelamin' => false,
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
                    
                    $alamatLengkap = collect([
                        $inventor->alamat,
                        $inventor->kelurahan,
                        'Kec. ' . $inventor->kecamatan,
                        $inventor->kota_kabupaten,
                        $inventor->provinsi,
                        $inventor->kode_pos
                    ])->filter()->implode(', ');
                    
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
}
