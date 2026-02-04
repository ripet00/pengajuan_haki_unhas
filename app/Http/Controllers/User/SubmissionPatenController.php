<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SubmissionPaten;
use App\Helpers\FileUploadHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SubmissionPatenController extends Controller
{
    /**
     * Display a listing of user's paten submissions
     */
    public function index()
    {
        $user = Auth::user();
        $submissionsPaten = SubmissionPaten::where('user_id', $user->id)
                                ->with(['reviewedByAdmin'])
                                ->orderBy('created_at', 'desc')
                                ->paginate(10);
        
        return view('user.submissions-paten.index', compact('submissionsPaten'));
    }

    /**
     * Show the form for creating a new paten submission
     */
    public function create()
    {
        return view('user.submissions-paten.create');
    }

    /**
     * Store a newly created paten submission in storage
     */
    public function store(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'judul_paten' => 'required|string|max:255',
                'kategori_paten' => 'required|in:Paten,Paten Sederhana',
                'creator_name' => 'required|string|max:255',
                'creator_whatsapp' => 'required|string|max:255',
                'creator_country_code' => 'required|string|max:5',
                'document' => 'required|file|mimes:docx|max:5120', // Max 5MB for DOCX
            ], [
                'judul_paten.required' => 'Judul paten harus diisi.',
                'kategori_paten.required' => 'Kategori paten harus dipilih.',
                'kategori_paten.in' => 'Kategori paten tidak valid.',
                'creator_name.required' => 'Nama inventor harus diisi.',
                'creator_whatsapp.required' => 'Nomor WhatsApp harus diisi.',
                'document.required' => 'Draft paten harus diunggah.',
                'document.mimes' => 'Draft paten harus berformat .docx (Microsoft Word).',
                'document.max' => 'Ukuran draft paten maksimal 5MB.',
            ]);

            // Pre-upload validation for file size
            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $fileSize = $file->getSize();
                $maxSize = 5 * 1024 * 1024; // 5MB in bytes
                
                if ($fileSize > $maxSize) {
                    $fileSizeMB = round($fileSize / (1024 * 1024), 2);
                    return back()->withErrors([
                        'document' => "Ukuran file terlalu besar ({$fileSizeMB} MB). Maksimal ukuran file adalah 5MB. Silakan kompres file DOCX Anda terlebih dahulu."
                    ])->withInput();
                }
            }
            
            // Check available disk space
            $availableSpace = disk_free_space(storage_path('app/private'));
            $fileSize = $request->file('document')->getSize();
            
            if ($availableSpace < ($fileSize * 2)) { // Need double space for safety
                return back()->withErrors([
                    'document' => 'Server tidak memiliki ruang penyimpanan yang cukup. Silakan coba lagi nanti atau hubungi administrator.'
                ])->withInput();
            }

            $user = Auth::user();
            $file = $request->file('document');

            // Use secure upload helper
            $uploadResult = FileUploadHelper::uploadSecure($file, 'submissions_paten', ['docx']);
            
            if (!$uploadResult['success']) {
                Log::info('File upload rejected', [
                    'user_id' => Auth::id(),
                    'filename' => $file->getClientOriginalName(),
                    'error' => $uploadResult['error']
                ]);
                
                return back()->withErrors([
                    'document' => $uploadResult['error']
                ])->withInput();
            }
            
            $submission = SubmissionPaten::create([
                'user_id' => $user->id,
                'judul_paten' => $validated['judul_paten'],
                'kategori_paten' => $validated['kategori_paten'],
                'creator_name' => $validated['creator_name'],
                'creator_whatsapp' => $validated['creator_whatsapp'],
                'creator_country_code' => $validated['creator_country_code'],
                'file_path' => $uploadResult['path'],
                'file_name' => $uploadResult['hashed_name'],
                'original_filename' => $uploadResult['original_name'],
                'file_size' => $file->getSize(),
                'status' => SubmissionPaten::STATUS_PENDING_FORMAT_REVIEW,
                'revisi' => false,
                'biodata_status' => 'not_started',
            ]);
            
            return redirect()->route('user.submissions-paten.show', $submission)
                           ->with('success', 'Pengajuan paten berhasil diunggah. Menunggu review admin.');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Paten submission upload error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors([
                'document' => 'Terjadi kesalahan saat mengunggah file. Silakan coba lagi atau hubungi administrator jika masalah berlanjut.'
            ])->withInput();
        }
    }

    /**
     * Display the specified paten submission
     */
    public function show(SubmissionPaten $submissionPaten)
    {
        // Check if user owns this submission
        if ($submissionPaten->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this submission.');
        }

        $submissionPaten->load(['reviewedByAdmin', 'biodataReviewedByAdmin', 'biodataPaten.inventors', 'histories.admin']);
        
        return view('user.submissions-paten.show', compact('submissionPaten'));
    }

    /**
     * Download the paten submission file
     */
    public function download(SubmissionPaten $submissionPaten)
    {
        // Check if user owns this submission
        if ($submissionPaten->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this submission.');
        }

        // Check if file exists using FileUploadHelper
        if (!$submissionPaten->file_path || !FileUploadHelper::exists($submissionPaten->file_path)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        $filePath = storage_path('app/private/' . $submissionPaten->file_path);
        
        // Use original filename if available, fallback to file_name
        $downloadName = $submissionPaten->original_filename ?? $submissionPaten->file_name ?? 'document.docx';

        return response()->download($filePath, $downloadName);
    }

    /**
     * Resubmit a rejected paten submission
     */
    public function resubmit(Request $request, SubmissionPaten $submissionPaten)
    {
        // Check if user owns this submission
        if ($submissionPaten->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this submission.');
        }

        // Check if submission is rejected (format review)
        if ($submissionPaten->status !== SubmissionPaten::STATUS_REJECTED_FORMAT_REVIEW) {
            return back()->with('error', 'Hanya submission dengan format yang ditolak yang dapat diajukan ulang.');
        }

        try {
            // Validate request
            $validated = $request->validate([
                'judul_paten' => 'required|string|max:255',
                'kategori_paten' => 'required|in:Paten,Paten Sederhana',
                'creator_name' => 'required|string|max:255',
                'creator_whatsapp' => 'required|string|max:255',
                'creator_country_code' => 'required|string|max:5',
                'document' => 'required|file|mimes:docx|max:5120', // Max 5MB for DOCX
            ], [
                'judul_paten.required' => 'Judul paten harus diisi.',
                'kategori_paten.required' => 'Kategori paten harus dipilih.',
                'creator_name.required' => 'Nama inventor harus diisi.',
                'creator_whatsapp.required' => 'Nomor WhatsApp harus diisi.',
                'document.required' => 'Draft paten harus diunggah.',
                'document.mimes' => 'Draft paten harus berformat .docx (Microsoft Word).',
                'document.max' => 'Ukuran draft paten maksimal 5MB.',
            ]);

            $file = $request->file('document');

            // Delete old file
            if ($submissionPaten->file_path) {
                FileUploadHelper::deleteSecure($submissionPaten->file_path);
            }

            // Use secure upload helper
            $uploadResult = FileUploadHelper::uploadSecure($file, 'submissions_paten', ['docx']);
            
            if (!$uploadResult['success']) {
                return back()->withErrors([
                    'document' => $uploadResult['error']
                ])->withInput();
            }

            // Update submission
            $submissionPaten->update([
                'judul_paten' => $validated['judul_paten'],
                'kategori_paten' => $validated['kategori_paten'],
                'creator_name' => $validated['creator_name'],
                'creator_whatsapp' => $validated['creator_whatsapp'],
                'creator_country_code' => $validated['creator_country_code'],
                'file_path' => $uploadResult['path'],
                'file_name' => $uploadResult['hashed_name'],
                'original_filename' => $uploadResult['original_name'],
                'file_size' => $file->getSize(),
                'status' => SubmissionPaten::STATUS_PENDING_FORMAT_REVIEW,
                'rejection_reason' => null,
                'reviewed_at' => null,
                'reviewed_by_admin_id' => null,
            ]);

            return redirect()->route('user.submissions-paten.show', $submissionPaten)
                           ->with('success', 'Pengajuan paten berhasil diajukan ulang. Menunggu review format dari admin.');

        } catch (\Exception $e) {
            Log::error('Paten resubmission error: ' . $e->getMessage());
            
            return back()->withErrors([
                'document' => 'Terjadi kesalahan saat mengunggah ulang file.'
            ])->withInput();
        }
    }

    /**
     * Resubmit a paten submission after substance review rejection
     */
    public function resubmitSubstance(Request $request, SubmissionPaten $submissionPaten)
    {
        // Check if user owns this submission
        if ($submissionPaten->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this submission.');
        }

        // Check if submission is rejected in substance review
        if ($submissionPaten->status !== SubmissionPaten::STATUS_REJECTED_SUBSTANCE_REVIEW) {
            return back()->with('error', 'Hanya pengajuan yang ditolak pada tahap review substansi yang dapat diajukan ulang.');
        }

        try {
            // Validate request
            $validated = $request->validate([
                'judul_paten' => 'required|string|max:255',
                'kategori_paten' => 'required|in:Paten,Paten Sederhana',
                'creator_name' => 'required|string|max:255',
                'creator_whatsapp' => 'required|string|max:255',
                'creator_country_code' => 'required|string|max:5',
                'file_paten' => 'required|file|mimes:docx|max:5120', // Max 5MB for DOCX
            ], [
                'judul_paten.required' => 'Judul paten harus diisi.',
                'kategori_paten.required' => 'Kategori paten harus dipilih.',
                'creator_name.required' => 'Nama inventor harus diisi.',
                'creator_whatsapp.required' => 'Nomor WhatsApp harus diisi.',
                'file_paten.required' => 'Draft paten harus diunggah.',
                'file_paten.mimes' => 'Draft paten harus berformat .docx (Microsoft Word).',
                'file_paten.max' => 'Ukuran draft paten maksimal 5MB.',
            ]);

            $file = $request->file('file_paten');

            // Delete old file
            if ($submissionPaten->file_path) {
                FileUploadHelper::deleteSecure($submissionPaten->file_path);
            }

            // Use secure upload helper
            $uploadResult = FileUploadHelper::uploadSecure($file, 'submissions_paten', ['docx']);
            
            if (!$uploadResult['success']) {
                return back()->withErrors([
                    'file_paten' => $uploadResult['error']
                ])->withInput();
            }

            // Update submission - reset to pending substance review
            $submissionPaten->update([
                'judul_paten' => $validated['judul_paten'],
                'kategori_paten' => $validated['kategori_paten'],
                'creator_name' => $validated['creator_name'],
                'creator_whatsapp' => $validated['creator_whatsapp'],
                'creator_country_code' => $validated['creator_country_code'],
                'file_path' => $uploadResult['path'],
                'file_name' => $uploadResult['hashed_name'],
                'original_filename' => $uploadResult['original_name'],
                'file_size' => $file->getSize(),
                'status' => SubmissionPaten::STATUS_PENDING_SUBSTANCE_REVIEW,
                'substance_review_notes' => null,
                'substance_review_file' => null,
                'substance_reviewed_at' => null,
            ]);

            return redirect()->route('user.submissions-paten.show', $submissionPaten)
                           ->with('success', 'Pengajuan paten berhasil diajukan ulang untuk review substansi. Menunggu review oleh Pendamping Paten.');

        } catch (\Exception $e) {
            Log::error('Paten substance resubmission error: ' . $e->getMessage());
            
            return back()->withErrors([
                'file_paten' => 'Terjadi kesalahan saat mengunggah ulang file.'
            ])->withInput();
        }
    }

    /**
     * Upload 4 Patent Documents (Deskripsi, Klaim, Abstrak, Gambar)
     */
    public function uploadPatentDocuments(Request $request, SubmissionPaten $submissionPaten)
    {
        // Check authorization
        if ($submissionPaten->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }
        
        // Check if biodata exists and is approved
        $biodataPaten = $submissionPaten->biodataPaten;
        if (!$biodataPaten || $biodataPaten->status !== 'approved') {
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
                if ($biodataPaten->deskripsi_pdf) {
                    FileUploadHelper::deleteSecure($biodataPaten->deskripsi_pdf);
                }
                
                $file = $request->file('deskripsi_pdf');
                $uploadResult = FileUploadHelper::uploadSecure($file, 'patent_documents/deskripsi', ['pdf']);
                
                if ($uploadResult['success']) {
                    $biodataPaten->deskripsi_pdf = $uploadResult['path'];
                    $uploadedFiles[] = 'Deskripsi';
                }
            }
            
            // Upload Klaim PDF
            if ($request->hasFile('klaim_pdf')) {
                // Delete old file if exists
                if ($biodataPaten->klaim_pdf) {
                    FileUploadHelper::deleteSecure($biodataPaten->klaim_pdf);
                }
                
                $file = $request->file('klaim_pdf');
                $uploadResult = FileUploadHelper::uploadSecure($file, 'patent_documents/klaim', ['pdf']);
                
                if ($uploadResult['success']) {
                    $biodataPaten->klaim_pdf = $uploadResult['path'];
                    $uploadedFiles[] = 'Klaim';
                }
            }
            
            // Upload Abstrak PDF
            if ($request->hasFile('abstrak_pdf')) {
                // Delete old file if exists
                if ($biodataPaten->abstrak_pdf) {
                    FileUploadHelper::deleteSecure($biodataPaten->abstrak_pdf);
                }
                
                $file = $request->file('abstrak_pdf');
                $uploadResult = FileUploadHelper::uploadSecure($file, 'patent_documents/abstrak', ['pdf']);
                
                if ($uploadResult['success']) {
                    $biodataPaten->abstrak_pdf = $uploadResult['path'];
                    $uploadedFiles[] = 'Abstrak';
                }
            }
            
            // Upload Gambar PDF (Optional)
            if ($request->hasFile('gambar_pdf')) {
                // Delete old file if exists
                if ($biodataPaten->gambar_pdf) {
                    FileUploadHelper::deleteSecure($biodataPaten->gambar_pdf);
                }
                
                $file = $request->file('gambar_pdf');
                $uploadResult = FileUploadHelper::uploadSecure($file, 'patent_documents/gambar', ['pdf']);
                
                if ($uploadResult['success']) {
                    $biodataPaten->gambar_pdf = $uploadResult['path'];
                    $uploadedFiles[] = 'Gambar';
                }
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
                'submission_paten_id' => $submissionPaten->id,
                'biodata_paten_id' => $biodataPaten->id,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Terjadi kesalahan saat upload dokumen: ' . $e->getMessage());
        }
    }
    
    /**
     * Download specific patent document
     */
    public function downloadPatentDocument(SubmissionPaten $submissionPaten, $type)
    {
        // Check authorization
        if ($submissionPaten->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }
        
        // Check if biodata exists
        $biodataPaten = $submissionPaten->biodataPaten;
        if (!$biodataPaten) {
            abort(404, 'Biodata not found');
        }
        
        // Validate type
        $allowedTypes = ['deskripsi', 'klaim', 'abstrak', 'gambar'];
        if (!in_array($type, $allowedTypes)) {
            abort(404, 'Invalid document type');
        }
        
        $fieldName = $type . '_pdf';
        $filePath = $biodataPaten->$fieldName;
        
        if (!$filePath || !FileUploadHelper::exists($filePath)) {
            return back()->with('error', 'File tidak ditemukan');
        }
        
        $fullPath = storage_path('app/private/' . $filePath);
        $fileName = ucfirst($type) . '_Paten_' . $biodataPaten->id . '.pdf';
        
        return response()->download($fullPath, $fileName);
    }
}
