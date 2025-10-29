<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubmissionRequest;
use App\Models\Submission;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SubmissionController extends Controller
{
    // List user's submissions
    public function index() {
        $user = Auth::user();
        $submissions = Submission::where('user_id', $user->id)
                                ->with('reviewedByAdmin')
                                ->orderBy('created_at', 'desc')
                                ->paginate(10);
        
        return view('user.submissions.index', compact('submissions'));
    }

    // Show create form
    public function create() {
        return view('user.submissions.create');
    }

    // Store initial submission
        /**
     * Store initial submission
     *
     * @param \App\Http\Requests\StoreSubmissionRequest|\Illuminate\Http\Request $request
     */

    public function store(StoreSubmissionRequest $request) {
        try {
            // Pre-upload validation for file size
            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $fileSize = $file->getSize();
                $maxSize = 20 * 1024 * 1024; // 20MB in bytes
                
                if ($fileSize > $maxSize) {
                    $fileSizeMB = round($fileSize / (1024 * 1024), 2);
                    return back()->withErrors([
                        'document' => "Ukuran file terlalu besar ({$fileSizeMB} MB). Maksimal ukuran file adalah 20MB. Silakan kompres file PDF Anda terlebih dahulu."
                    ])->withInput();
                }
            }
            
            // Check available disk space
            $availableSpace = disk_free_space(storage_path('app/public'));
            $fileSize = $request->file('document')->getSize();
            
            if ($availableSpace < ($fileSize * 2)) { // Need double space for safety
                return back()->withErrors([
                    'document' => 'Server tidak memiliki ruang penyimpanan yang cukup. Silakan coba lagi nanti atau hubungi administrator.'
                ])->withInput();
            }

            $user = Auth::user();
            /** @var \Illuminate\Http\UploadedFile $file */
            $file = $request->file('document');

            $path = $file->store('submissions', 'public');
            $submission = Submission::create([
                'user_id' => $user->id,
                'title' => $request->input('title'),
                'categories' => $request->input('categories'),
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'status' => 'pending',
                'revisi' => false,
            ]);
            
            return redirect()->route('user.submissions.show', $submission)->with('success', 'File berhasil diunggah. Menunggu review admin');
            
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('File upload error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'file_size' => $request->hasFile('document') ? $request->file('document')->getSize() : 'unknown',
                'error_trace' => $e->getTraceAsString()
            ]);
            
            // Return user-friendly error message instead of internal server error
            return back()->withErrors([
                'document' => 'Terjadi kesalahan saat mengupload file. Pastikan file PDF Anda tidak melebihi 20MB dan coba lagi.'
            ])->withInput();
        }
    }

    // Show a user's submission
    public function show(Submission $submission) {
        $this->authorizeOwnership($submission);
        return view('user.submissions.show', compact('submission'));
    }


    // Store initial submission
        /**
     * Store initial submission
     *
     * @param \App\Http\Requests\StoreSubmissionRequest|\Illuminate\Http\Request $request
     * @param \App\Models\Submission $submission
     */
    public function resubmit(StoreSubmissionRequest $request, Submission $submission) {
        $this->authorizeOwnership($submission);

        if ($submission->status !== 'rejected') {
            return back()->withErrors(['document' => 'Hanya submission yang berstatus rejected yang boleh direvisi.']);
        }

        try {
            // Pre-upload validation for file size
            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $fileSize = $file->getSize();
                $maxSize = 20 * 1024 * 1024; // 20MB in bytes
                
                if ($fileSize > $maxSize) {
                    $fileSizeMB = round($fileSize / (1024 * 1024), 2);
                    return back()->withErrors([
                        'document' => "Ukuran file terlalu besar ({$fileSizeMB} MB). Maksimal ukuran file adalah 20MB. Silakan kompres file PDF Anda terlebih dahulu."
                    ])->withInput();
                }
            }
            
            // Check available disk space
            $availableSpace = disk_free_space(storage_path('app/public'));
            $fileSize = $request->file('document')->getSize();
            
            if ($availableSpace < ($fileSize * 2)) { // Need double space for safety
                return back()->withErrors([
                    'document' => 'Server tidak memiliki ruang penyimpanan yang cukup. Silakan coba lagi nanti atau hubungi administrator.'
                ])->withInput();
            }

            // Delete old file
            if ($submission->file_path && Storage::disk('public')->exists($submission->file_path)) {
                Storage::disk('public')->delete($submission->file_path);
            }

            /** @var \Illuminate\Http\UploadedFile $file */
            $file = $request->file('document');
            $path = $file->store('submissions', 'public');

            $submission->update([
                'title' => $request->input('title'),
                'categories' => $request->input('categories'),
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
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
}
