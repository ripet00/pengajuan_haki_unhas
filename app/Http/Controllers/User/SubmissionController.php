<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubmissionRequest;
use App\Http\Requests\ResubmitSubmissionRequest;
use App\Models\Submission;
use App\Models\JenisKarya;
use App\Helpers\FileUploadHelper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SubmissionController extends Controller
{
    // List user's submissions
    public function index() {
        $user = Auth::user();
        $submissions = Submission::where('user_id', $user->id)
                                ->with(['reviewedByAdmin', 'jenisKarya'])
                                ->orderBy('created_at', 'desc')
                                ->paginate(10);
        
        return view('user.submissions.index', compact('submissions'));
    }

    // Show create form
    public function create() {
        $jenisKaryas = JenisKarya::active()->orderBy('nama')->get();
        return view('user.submissions.create', compact('jenisKaryas'));
    }

    // Store initial submission
        /**
     * Store initial submission
     *
     * @param \App\Http\Requests\StoreSubmissionRequest|\Illuminate\Http\Request $request
     */

    public function store(StoreSubmissionRequest $request) {
        try {
            $user = Auth::user();
            $path = null;
            $fileName = null;
            $fileSize = null;
            
            // For PDF: handle file upload with secure FileUploadHelper
            if ($request->input('file_type') === 'pdf' && $request->hasFile('document')) {
                $file = $request->file('document');
                $fileSize = $file->getSize();
                $maxSize = 20 * 1024 * 1024; // 20MB in bytes
                
                if ($fileSize > $maxSize) {
                    $fileSizeMB = round($fileSize / (1024 * 1024), 2);
                    return back()->withErrors([
                        'document' => "Ukuran file terlalu besar ({$fileSizeMB} MB). Maksimal ukuran file adalah 20MB. Silakan kompres file PDF Anda terlebih dahulu."
                    ])->withInput();
                }
                
                // Check available disk space
                $availableSpace = disk_free_space(storage_path('app/private'));
                
                if ($availableSpace < ($fileSize * 2)) { // Need double space for safety
                    return back()->withErrors([
                        'document' => 'Server tidak memiliki ruang penyimpanan yang cukup. Silakan coba lagi nanti atau hubungi administrator.'
                    ])->withInput();
                }

                // Use secure upload helper
                $uploadResult = FileUploadHelper::uploadSecure($file, 'submissions', ['pdf']);
                
                if (!$uploadResult['success']) {
                    return back()->withErrors([
                        'document' => $uploadResult['error']
                    ])->withInput();
                }
                
                $path = $uploadResult['path'];
                $fileName = $uploadResult['hashed_name'];
                $fileSize = $file->getSize();
            }
            // For Video: no file upload, only store link
            
            $submission = Submission::create([
                'user_id' => $user->id,
                'title' => $request->input('title'),
                'categories' => $request->input('categories'),
                'jenis_karya_id' => $request->input('jenis_karya_id'),
                'file_type' => $request->input('file_type'),
                'video_link' => $request->input('video_link'), // Store video link (null for PDF)
                'creator_name' => $request->input('creator_name'),
                'creator_whatsapp' => $request->input('creator_whatsapp'),
                'creator_country_code' => $request->input('creator_country_code'),
                'file_path' => $path, // null for video
                'file_name' => $fileName, // null for video
                'original_filename' => $request->input('file_type') === 'pdf' && isset($uploadResult) ? $uploadResult['original_name'] : null,
                'file_size' => $fileSize, // null for video
                'status' => 'pending',
                'revisi' => false,
            ]);
            
            $successMessage = $request->input('file_type') === 'pdf' 
                ? 'File berhasil diunggah. Menunggu review admin'
                : 'Link video berhasil disimpan. Menunggu review admin';
            
            return redirect()->route('user.submissions.show', $submission)->with('success', $successMessage);
            
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('File upload error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request_data' => $request->all(),
                'file_size' => $request->hasFile('document') ? $request->file('document')->getSize() : 'unknown',
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'error_trace' => $e->getTraceAsString()
            ]);
            
            // Return user-friendly error message with actual error for debugging
            return back()->withErrors([
                'document' => 'Terjadi kesalahan saat mengupload file: ' . $e->getMessage() . '. Pastikan semua field terisi dan file tidak melebihi 20MB.'
            ])->withInput();
        }
    }

    // Show a user's submission
    public function show(Submission $submission) {
        $this->authorizeOwnership($submission);
        $submission->load(['jenisKarya', 'histories.admin']);
        $jenisKaryas = JenisKarya::active()->orderBy('nama')->get();
        return view('user.submissions.show', compact('submission', 'jenisKaryas'));
    }


    // Store initial submission
        /**
     * Store initial submission
     *
     * @param \App\Http\Requests\StoreSubmissionRequest|\Illuminate\Http\Request $request
     * @param \App\Models\Submission $submission
     */
    public function resubmit(ResubmitSubmissionRequest $request, Submission $submission) {
        $this->authorizeOwnership($submission);

        if ($submission->status !== 'rejected') {
            return back()->withErrors(['document' => 'Hanya submission yang berstatus rejected yang boleh direvisi.']);
        }

        try {
            $path = $submission->file_path;
            $fileName = $submission->file_name;
            $fileSize = $submission->file_size;
            
            // For PDF: handle file upload with secure FileUploadHelper
            if ($request->input('file_type') === 'pdf' && $request->hasFile('document')) {
                $file = $request->file('document');
                $uploadFileSize = $file->getSize();
                $maxSize = 20 * 1024 * 1024; // 20MB in bytes
                
                if ($uploadFileSize > $maxSize) {
                    $fileSizeMB = round($uploadFileSize / (1024 * 1024), 2);
                    return back()->withErrors([
                        'document' => "Ukuran file terlalu besar ({$fileSizeMB} MB). Maksimal ukuran file adalah 20MB. Silakan kompres file PDF Anda terlebih dahulu."
                    ])->withInput();
                }
                
                // Check available disk space
                $availableSpace = disk_free_space(storage_path('app/private'));
                
                if ($availableSpace < ($uploadFileSize * 2)) { // Need double space for safety
                    return back()->withErrors([
                        'document' => 'Server tidak memiliki ruang penyimpanan yang cukup. Silakan coba lagi nanti atau hubungi administrator.'
                    ])->withInput();
                }

                // Delete old file if exists
                if ($submission->file_path) {
                    FileUploadHelper::deleteSecure($submission->file_path);
                }

                // Use secure upload helper
                $uploadResult = FileUploadHelper::uploadSecure($file, 'submissions', ['pdf']);
                
                if (!$uploadResult['success']) {
                    return back()->withErrors([
                        'document' => $uploadResult['error']
                    ])->withInput();
                }
                
                $path = $uploadResult['path'];
                $fileName = $uploadResult['hashed_name'];
                $fileSize = $file->getSize();
            }
            // For Video: if changing to video type, delete old file
            elseif ($request->input('file_type') === 'video') {
                // Delete old file if exists
                if ($submission->file_path) {
                    FileUploadHelper::deleteSecure($submission->file_path);
                }
                $path = null;
                $fileName = null;
                $fileSize = null;
            }

            $submission->update([
                'title' => $request->input('title'),
                'categories' => $request->input('categories'),
                'jenis_karya_id' => $request->input('jenis_karya_id'),
                'file_type' => $request->input('file_type'),
                'video_link' => $request->input('video_link'),
                'creator_name' => $request->input('creator_name'),
                'creator_whatsapp' => $request->input('creator_whatsapp'),
                'creator_country_code' => $request->input('creator_country_code'),
                'file_path' => $path,
                'file_name' => $fileName,
                'original_filename' => isset($uploadResult) ? $uploadResult['original_name'] : $submission->original_filename,
                'file_size' => $fileSize,
                'status' => 'pending',
                'revisi' => true,
                'reviewed_at' => null,
                'rejection_reason' => null,
                'reviewed_by_admin_id' => null,
            ]);

            return redirect()->route('user.submissions.show', $submission)->with('success', 'File berhasil diunggah ulang. Menunggu review admin.');
            
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('File resubmit error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'submission_id' => $submission->id,
                'file_size' => $request->hasFile('document') ? $request->file('document')->getSize() : 'unknown',
                'error_trace' => $e->getTraceAsString()
            ]);
            
            // Return user-friendly error message instead of internal server error
            return back()->withErrors([
                'document' => 'Terjadi kesalahan saat mengupload ulang file. Pastikan file PDF Anda tidak melebihi 20MB dan coba lagi.'
            ])->withInput();
        }
    }

    protected function authorizeOwnership(Submission $submission) {
        if ($submission->user_id !== Auth::id()) {
            abort(403);
        }
    }

    // download file - force download instead of opening in browser
    public function download(Submission $submission)
    {
        // Ensure user can only download their own submissions
        $this->authorizeOwnership($submission);
        
        // Check if file exists using FileUploadHelper
        if (!$submission->file_path || !FileUploadHelper::exists($submission->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        $filePath = storage_path('app/private/' . $submission->file_path);
        
        // Use original filename if available, fallback to file_name
        $downloadName = $submission->original_filename ?? $submission->file_name ?? 'document.pdf';

        return response()->download($filePath, $downloadName);
    }
}
