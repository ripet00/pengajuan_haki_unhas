<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Biodata;
use App\Models\BiodataMember;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\TemplateProcessor;

class BiodataController extends Controller
{
    /**
     * Show the form for creating a new biodata or editing existing one
     */
    public function create(Submission $submission)
    {
        // Check if user owns the submission
        if ($submission->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this submission.');
        }

        // Check if submission is approved
        if ($submission->status !== 'approved') {
            return redirect()->route('user.submissions.show', $submission)
                ->with('error', 'Biodata hanya dapat dibuat untuk submission yang telah disetujui.');
        }

        // Get existing biodata or create new one
        $biodata = $submission->biodata;
        $isEdit = $biodata !== null;

        // If biodata is approved, redirect to view mode
        if ($biodata && $biodata->isApproved()) {
            return redirect()->route('user.biodata.show', [$submission, $biodata])
                ->with('info', 'Biodata telah disetujui dan tidak dapat diubah.');
        }

        // Allow resubmission if status is denied
        $canEdit = !$biodata || $biodata->status === 'pending' || $biodata->status === 'denied';
        
        if ($biodata && !$canEdit) {
            return redirect()->route('user.biodata.show', [$submission, $biodata])
                ->with('info', 'Biodata tidak dapat diubah.');
        }

        // Get existing members or create empty array
        $members = $biodata ? $biodata->members : collect();
        
        // If there's old input (validation failed), merge it with members
        if (old('members')) {
            $oldMembers = old('members');
            $members = $members->map(function ($member, $index) use ($oldMembers) {
                if (isset($oldMembers[$index])) {
                    // Merge old input with existing member
                    foreach ($oldMembers[$index] as $key => $value) {
                        $member->$key = $value;
                    }
                }
                return $member;
            });
        }

        return view('user.biodata.create', compact('submission', 'biodata', 'members', 'isEdit', 'canEdit'));
    }

    /**
     * Store a newly created biodata in storage
     */
    public function store(Request $request, Submission $submission)
    {
        // Check if user owns the submission
        if ($submission->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this submission.');
        }

        // Check if submission is approved
        if ($submission->status !== 'approved') {
            return redirect()->route('user.submissions.show', $submission)
                ->with('error', 'Biodata hanya dapat dibuat untuk submission yang telah disetujui.');
        }

                // Validate the request
        $validatedData = $request->validate([
            'tempat_ciptaan' => 'required|string|max:255',
            'tanggal_ciptaan' => 'required|date',
            'uraian_singkat' => 'required|string',
            'members' => 'required|array|min:1|max:10',
            'members.*.name' => 'required|string|max:255',
            'members.*.nik' => 'required|digits:16',
            'members.*.npwp' => 'nullable|string|max:255',
            'members.*.jenis_kelamin' => 'required|in:Pria,Wanita',
            'members.*.pekerjaan' => 'required|string|max:255',
            'members.*.universitas' => 'required|string|max:255',
            'members.*.fakultas' => 'required|string|max:255',
            'members.*.program_studi' => 'required|string|max:255',
            'members.*.alamat' => 'required|string',
            'members.*.kelurahan' => 'required|string|max:255',
            'members.*.kecamatan' => 'required|string|max:255',
            'members.*.kota_kabupaten' => 'required|string|max:255',
            'members.*.provinsi' => 'required|string|max:255',
            'members.*.kode_pos' => 'required|string|max:10',
            'members.*.email' => 'required|email|max:255',
            'members.*.nomor_hp' => 'required|string|max:20',
            'members.*.kewarganegaraan' => 'required|string|max:100',
            // Optional helper fields that won't be stored
            'members.*.kewarganegaraan_type' => 'nullable|string',
            'members.*.kewarganegaraan_asing' => 'nullable|string|max:100',
        ]);

        try {
            DB::beginTransaction();
            
            // Debug log
            Log::info('Biodata submission data:', [
                'members' => $request->members,
                'has_npwp' => isset($request->members[0]['npwp']),
                'npwp_value' => $request->members[0]['npwp'] ?? 'not set'
            ]);

            // Check if this is an edit/resubmit
            $isEdit = $submission->biodata !== null;

            // Create or update biodata
            $biodata = $submission->biodata;
            if (!$biodata) {
                $biodata = Biodata::create([
                    'submission_id' => $submission->id,
                    'user_id' => Auth::id(),
                    'tempat_ciptaan' => $request->tempat_ciptaan,
                    'tanggal_ciptaan' => $request->tanggal_ciptaan,
                    'uraian_singkat' => $request->uraian_singkat,
                    'status' => 'pending',
                ]);
            } else {
                // Only allow editing if not approved
                if ($biodata->isApproved()) {
                    return redirect()->route('user.biodata.show', [$submission, $biodata])
                        ->with('error', 'Biodata yang telah disetujui tidak dapat diubah.');
                }

                $biodata->update([
                    'tempat_ciptaan' => $request->tempat_ciptaan,
                    'tanggal_ciptaan' => $request->tanggal_ciptaan,
                    'uraian_singkat' => $request->uraian_singkat,
                    'status' => 'pending', // Reset to pending when edited
                    'rejection_reason' => null, // Clear previous rejection reason
                    'reviewed_at' => null, // Clear review timestamp
                    'reviewed_by' => null, // Clear reviewer
                    // Reset all error flags
                    'error_tempat_ciptaan' => false,
                    'error_tanggal_ciptaan' => false,
                    'error_uraian_singkat' => false,
                ]);

                // Delete existing members
                $biodata->members()->delete();
            }

            // Create members
            foreach ($request->members as $index => $memberData) {
                BiodataMember::create([
                    'biodata_id' => $biodata->id,
                    'name' => $memberData['name'],
                    'nik' => $memberData['nik'],
                    'npwp' => $memberData['npwp'] ?? null,
                    'jenis_kelamin' => $memberData['jenis_kelamin'],
                    'pekerjaan' => $memberData['pekerjaan'],
                    'universitas' => $memberData['universitas'],
                    'fakultas' => $memberData['fakultas'],
                    'program_studi' => $memberData['program_studi'],
                    'alamat' => $memberData['alamat'],
                    'kelurahan' => $memberData['kelurahan'],
                    'kecamatan' => $memberData['kecamatan'],
                    'kota_kabupaten' => $memberData['kota_kabupaten'],
                    'provinsi' => $memberData['provinsi'],
                    'kode_pos' => $memberData['kode_pos'],
                    'email' => $memberData['email'],
                    'nomor_hp' => $memberData['nomor_hp'],
                    'kewarganegaraan' => $memberData['kewarganegaraan'],
                    'is_leader' => $index === 0, // First member is the leader
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
            $submission->update([
                'biodata_status' => 'pending',
                'biodata_submitted_at' => now(),
            ]);

            DB::commit();

            // Different message for resubmission vs new submission
            $message = $isEdit 
                ? 'Biodata berhasil direvisi dan telah dikirim ulang untuk review admin.' 
                : 'Biodata berhasil disimpan dan sedang menunggu review admin.';

            return redirect()->route('user.biodata.show', [$submission, $biodata])
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan biodata: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified biodata
     */
    public function show(Submission $submission, Biodata $biodata)
    {
        // Check if user owns the submission
        if ($submission->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this submission.');
        }

        // Check if biodata belongs to submission
        if ($biodata->submission_id !== $submission->id) {
            abort(404, 'Biodata not found for this submission.');
        }

        $biodata->load('members', 'reviewedBy');

        return view('user.biodata.show', compact('submission', 'biodata'));
    }

    /**
     * Generate and download Word document from template
     */
    public function downloadFormulir(Biodata $biodata)
    {
        // 1. CEK AUTHORIZATION - hanya user pemilik
        if (Auth::id() !== $biodata->submission->user_id) {
            abort(403, 'Unauthorized access');
        }
        
        // 2. CEK STATUS - hanya biodata yang sudah ACC
        if ($biodata->status !== 'approved') {
            return back()->with('error', 'Biodata harus di-ACC oleh admin terlebih dahulu');
        }
        
        // 3. LOAD TEMPLATE berdasarkan kategori submission
        $category = strtolower($biodata->submission->categories ?? 'umum');
        $category = str_replace(' ', '_', $category); // ganti spasi dengan underscore
        
        $templateFileName = "{$category}_formulir_hki.docx";
        $templatePath = public_path("templates/{$templateFileName}");
        
        // Fallback ke template default jika template kategori tidak ditemukan
        if (!file_exists($templatePath)) {
            $templatePath = public_path('templates/formulir_hki_template.docx');
            
            if (!file_exists($templatePath)) {
                return back()->with('error', "Template dokumen untuk kategori '{$biodata->submission->categories}' tidak ditemukan. Silakan hubungi administrator.");
            }
        }
        
        try {
            $templateProcessor = new TemplateProcessor($templatePath);
            
            // 4. LOAD RELATIONSHIPS
            $biodata->load(['members', 'submission.jenisKarya']);
            
            // 5. SET SINGLE VALUES (data umum yang tidak perlu di-clone)
            // Tanggal download (kapan user klik tombol download) - format: "26 November 2025"
            // Set timezone ke Asia/Makassar (WITA - Waktu Indonesia Tengah)
            $templateProcessor->setValue('tanggal_download', 
                \Carbon\Carbon::now('Asia/Makassar')->locale('id')->isoFormat('D MMMM Y'));
            
            $templateProcessor->setValue('tanggal_pengajuan', 
                \Carbon\Carbon::parse($biodata->created_at)->locale('id')->isoFormat('D MMMM Y'));
            
            // Data Submission
            $templateProcessor->setValue('title', 
                $biodata->submission->title ?? '-');
            $templateProcessor->setValue('judul_karya', 
                $biodata->submission->title ?? '-');
            
            // Jenis karya - ambil nama dari relasi, bukan ID
            $templateProcessor->setValue('jenis_karya_id', 
                $biodata->submission->jenisKarya->nama ?? '-');
            $templateProcessor->setValue('jenis_karya', 
                $biodata->submission->jenisKarya->nama ?? '-');
            
            // Data Biodata (tempat, tanggal, uraian ciptaan)
            $templateProcessor->setValue('tempat_ciptaan', 
                $biodata->tempat_ciptaan ?? '-');
            $templateProcessor->setValue('tanggal_ciptaan', 
                $biodata->tanggal_ciptaan ? \Carbon\Carbon::parse($biodata->tanggal_ciptaan)->locale('id')->isoFormat('D MMMM Y') : '-');
            $templateProcessor->setValue('uraian_singkat', 
                $biodata->uraian_singkat ?? '-');
            
            // 6. CLONE BLOCK untuk SEMUA MEMBERS (termasuk leader)
            $allMembers = $biodata->members; // Ambil semua members (leader + anggota)
            $memberCount = $allMembers->count();
            
            // Gabungkan semua nama member untuk ditampilkan dalam 1 baris (dipisah dengan '; ')
            $allMemberNames = $allMembers->pluck('name')->implode(' ; ');
            $templateProcessor->setValue('all_member_names', $allMemberNames ?: '-');
            
            if ($memberCount > 0) {
                // UNTUK TABEL: Gunakan cloneRow() jika data member ada di dalam tabel
                // Support 2 tabel berbeda di template:
                // Tabel 1: ${member_no} dengan kurung tutup - 4 baris per member
                // Tabel 2: ${member_no_dot} dengan titik - 2 baris per member (Nama + Alamat)
                
                $clonedSuccessfully = false;
                
                // Try cloning Tabel 1 (member_no dengan kurung tutup)
                try {
                    Log::info("Attempting to clone row 'member_no' for table 1", [
                        'member_count' => $memberCount,
                        'members' => $allMembers->pluck('name')->toArray()
                    ]);
                    
                    $templateProcessor->cloneRow('member_no', $memberCount);
                    Log::info("Row 'member_no' cloned successfully");
                    $clonedSuccessfully = true;
                } catch (\Exception $e) {
                    Log::info("cloneRow 'member_no' not found in template", ['error' => $e->getMessage()]);
                }
                
                // Try cloning Tabel 2 (member_no_dot dengan titik - untuk tanda tangan)
                try {
                    Log::info("Attempting to clone row 'member_no_dot' for table 2");
                    $templateProcessor->cloneRow('member_no_dot', $memberCount);
                    Log::info("Row 'member_no_dot' cloned successfully");
                    $clonedSuccessfully = true;
                } catch (\Exception $e) {
                    Log::info("cloneRow 'member_no_dot' not found in template", ['error' => $e->getMessage()]);
                }
                
                // Fallback: Try cloning by member_name
                if (!$clonedSuccessfully) {
                    try {
                        Log::info("Fallback: trying to clone row 'member_name'");
                        $templateProcessor->cloneRow('member_name', $memberCount);
                        Log::info("Row 'member_name' cloned successfully");
                    } catch (\Exception $e2) {
                        Log::error("All cloneRow attempts failed", ['error' => $e2->getMessage()]);
                    }
                }
                
                // Loop dan set value per member (leader akan jadi member pertama)
                foreach ($allMembers as $index => $member) {
                    $num = $index + 1; // untuk numbering (#1, #2, #3, dst)
                    
                    Log::info("Setting values for member #{$num}", ['name' => $member->name]);
                    
                    // Basic info
                    // Tabel 1: member_no dengan kurung tutup
                    $templateProcessor->setValue("member_no#$num", $num . ')');
                    // Tabel 2: member_no_dot dengan titik
                    $templateProcessor->setValue("member_no_dot#$num", $num . '.');
                    
                    $templateProcessor->setValue("member_name#$num", $member->name);
                    $templateProcessor->setValue("member_nik#$num", $member->nik ?? '-');
                    $templateProcessor->setValue("member_npwp#$num", $member->npwp ?? '-');
                    $templateProcessor->setValue("member_jenis_kelamin#$num", $member->jenis_kelamin ?? '-');
                    $templateProcessor->setValue("member_kewarganegaraan#$num", $member->kewarganegaraan ?? '-');
                    $templateProcessor->setValue("member_pekerjaan#$num", $member->pekerjaan ?? '-');
                    $templateProcessor->setValue("member_universitas#$num", $member->universitas ?? '-');
                    $templateProcessor->setValue("member_fakultas#$num", $member->fakultas ?? '-');
                    $templateProcessor->setValue("member_program_studi#$num", $member->program_studi ?? '-');
                    
                    // Alamat lengkap - Format simple
                    $alamatLengkap = collect([
                        $member->alamat,
                        $member->kelurahan,
                        'Kec. ' . $member->kecamatan,
                        $member->kota_kabupaten,
                        $member->provinsi,
                        $member->kode_pos
                    ])->filter()->implode(', ');
                    
                    // Set alamat dengan berbagai alias placeholder
                    $templateProcessor->setValue("alamat#$num", $alamatLengkap ?: '-');
                    $templateProcessor->setValue("member_alamat#$num", $alamatLengkap ?: '-');
                    
                    // Individual address components
                    $templateProcessor->setValue("member_kelurahan#$num", $member->kelurahan ?? '-');
                    $templateProcessor->setValue("member_kecamatan#$num", $member->kecamatan ?? '-');
                    $templateProcessor->setValue("member_kota_kabupaten#$num", $member->kota_kabupaten ?? '-');
                    $templateProcessor->setValue("member_provinsi#$num", $member->provinsi ?? '-');
                    $templateProcessor->setValue("member_kode_pos#$num", $member->kode_pos ?? '-');
                    
                    // Contact info
                    $templateProcessor->setValue("member_email#$num", $member->email ?? '-');
                    $templateProcessor->setValue("member_nomor_hp#$num", $member->nomor_hp ?? '-');
                    
                    // Jika ini adalah leader (member pertama), set juga placeholder khusus untuk backward compatibility
                    if ($member->is_leader) {
                        // JANGAN set 'name' karena akan konflik dengan cloneRow tabel tanda tangan
                        // $templateProcessor->setValue('name', $member->name);
                        $templateProcessor->setValue('alamat', $alamatLengkap ?: '-');
                        $templateProcessor->setValue('leader_name', $member->name);
                        $templateProcessor->setValue('leader_alamat', $alamatLengkap ?: '-');
                        $templateProcessor->setValue('leader_nik', $member->nik ?? '-');
                        $templateProcessor->setValue('leader_npwp', $member->npwp ?? '-');
                        $templateProcessor->setValue('leader_kewarganegaraan', $member->kewarganegaraan ?? '-');
                        $templateProcessor->setValue('leader_email', $member->email ?? '-');
                        $templateProcessor->setValue('leader_nomor_hp', $member->nomor_hp ?? '-');
                    }
                }
            } else {
                // Jika tidak ada member sama sekali, hapus block
                $templateProcessor->deleteBlock('member');
            }
            
            // 7. CLONE ROW TABEL untuk TANDA TANGAN dengan 2 kolom (kiri-kanan-turun)
            // Hanya jalankan jika template punya placeholder ${name} di tabel
            if ($memberCount > 0) {
                try {
                    // Cek apakah template punya placeholder 'name' untuk cloneRow
                    // Untuk tabel 2 kolom: 1 row = 2 nama (kiri-kanan, turun, kiri-kanan)
                    $rowsNeeded = ceil($memberCount / 2);
                    
                    Log::info("Cloning signature table", [
                        'member_count' => $memberCount,
                        'rows_needed' => $rowsNeeded,
                        'total_placeholders' => $rowsNeeded * 2
                    ]);
                    
                    // Clone row tabel tanda tangan
                    $templateProcessor->cloneRow('name', $rowsNeeded);
                    
                    Log::info("CloneRow 'name' success, now setting values...");
                    
                    // PENTING: PHPWord numbering untuk tabel 2 kolom adalah per row (kiri, kanan, turun)
                    // Row 1: name#1 (kiri), name#2 (kanan)
                    // Row 2: name#3 (kiri), name#4 (kanan)
                    // Maka untuk 3 members: Ahmad(#1), Siti(#2), Budi(#3), kosong(#4)
                    
                    $totalPlaceholders = $rowsNeeded * 2;
                    $memberIndex = 0;
                    
                    for ($row = 1; $row <= $rowsNeeded; $row++) {
                        // Kolom kiri (odd number: 1, 3, 5, ...)
                        $leftNum = ($row - 1) * 2 + 1;
                        // Kolom kanan (even number: 2, 4, 6, ...)
                        $rightNum = ($row - 1) * 2 + 2;
                        
                        // Set kolom kiri
                        if ($memberIndex < $memberCount) {
                            $member = $allMembers[$memberIndex];
                            $templateProcessor->setValue("name#$leftNum", $member->name);
                            
                            // Materai hanya untuk member pertama (kolom kiri row pertama)
                            if ($memberIndex === 0) {
                                $templateProcessor->setValue("materai#$leftNum", 'MATERAI');
                            } else {
                                $templateProcessor->setValue("materai#$leftNum", '');
                            }
                            
                            Log::info("Set name#{$leftNum} (left) = {$member->name}");
                            $memberIndex++;
                        } else {
                            $templateProcessor->setValue("name#$leftNum", '');
                            $templateProcessor->setValue("materai#$leftNum", '');
                        }
                        
                        // Set kolom kanan
                        if ($memberIndex < $memberCount) {
                            $member = $allMembers[$memberIndex];
                            $templateProcessor->setValue("name#$rightNum", $member->name);
                            $templateProcessor->setValue("materai#$rightNum", '');
                            Log::info("Set name#{$rightNum} (right) = {$member->name}");
                            $memberIndex++;
                        } else {
                            $templateProcessor->setValue("name#$rightNum", '');
                            $templateProcessor->setValue("materai#$rightNum", '');
                        }
                    }
                } catch (\Exception $e) {
                    // Template tidak punya tabel dengan placeholder ${name}
                    // Skip cloneRow, user bisa isi manual atau pakai block cloning ${member}
                    Log::info("Template tidak punya tabel tanda tangan dengan placeholder 'name': " . $e->getMessage());
                }
            }
            
            // 8. CLONE ROW untuk TABEL 2 KOLOM (Kolom 1 statis di row pertama saja, Kolom 2 dinamis)
            // Tabel: Pemegang Hak Cipta (kolom 1 - hanya row 1) | Pencipta (kolom 2 - semua rows)
            // Placeholder: ${pemegang_hak} | ${signature_name} (bukan member_name untuk menghindari konflik)
            if ($memberCount > 0) {
                try {
                    Log::info("Attempting to clone row 'signature_name' for signature table (column 2 only)");
                    
                    // Clone row untuk kolom Pencipta (signature_name di kolom 2)
                    $templateProcessor->cloneRow('signature_name', $memberCount);
                    
                    Log::info("Row 'signature_name' cloned successfully for {$memberCount} members");
                    
                    // Set value untuk setiap member
                    foreach ($allMembers as $index => $member) {
                        $num = $index + 1;
                        
                        // Kolom 2: Nama member untuk tanda tangan (semua rows)
                        $templateProcessor->setValue("signature_name#$num", $member->name);
                        
                        // Kolom 1: Nama Asmi hanya di row pertama, row lainnya kosong
                        if ($num === 1) {
                            // Tambahkan tanda kurung karena template tidak punya
                            $templateProcessor->setValue("pemegang_hak#$num", '(Asmi Citra Malina, S.Pi., M.Agr., Ph.D.)');
                        } else {
                            // Benar-benar kosong (tanpa tanda kurung)
                            $templateProcessor->setValue("pemegang_hak#$num", '');
                        }
                        
                        Log::info("Set signature_name#{$num} = {$member->name}");
                    }
                } catch (\Exception $e) {
                    Log::info("cloneRow 'signature_name' for signature table not found: " . $e->getMessage());
                }
            }
            
            // 9. SAVE FILE
            $fileName = 'Formulir_HAKI_' . $biodata->id . '_' . time() . '.docx';
            $outputPath = storage_path('app/public/generated_documents/' . $fileName);
            
            $templateProcessor->saveAs($outputPath);
            
            // 10. DOWNLOAD FILE dan hapus setelah download
            return response()->download($outputPath, $fileName)->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            Log::error('Error generating Word document: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat generate dokumen: ' . $e->getMessage());
        }
    }
}
