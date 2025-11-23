<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Biodata;
use App\Models\BiodataMember;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
}
