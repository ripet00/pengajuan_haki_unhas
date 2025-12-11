<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SubmissionPaten;
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
                'document' => 'required|file|mimes:pdf|max:20480', // Max 20MB
            ], [
                'judul_paten.required' => 'Judul paten harus diisi.',
                'kategori_paten.required' => 'Kategori paten harus dipilih.',
                'kategori_paten.in' => 'Kategori paten tidak valid.',
                'creator_name.required' => 'Nama pencipta harus diisi.',
                'creator_whatsapp.required' => 'Nomor WhatsApp harus diisi.',
                'document.required' => 'Dokumen paten harus diunggah.',
                'document.mimes' => 'Dokumen harus berformat PDF.',
                'document.max' => 'Ukuran dokumen maksimal 20MB.',
            ]);

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
            $file = $request->file('document');

            // Generate unique filename with original extension
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileName = pathinfo($originalName, PATHINFO_FILENAME);
            $uniqueFileName = $fileName . '_' . time() . '.' . $extension;
            
            $path = $file->storeAs('submissions_paten', $uniqueFileName, 'public');
            
            $submission = SubmissionPaten::create([
                'user_id' => $user->id,
                'judul_paten' => $validated['judul_paten'],
                'kategori_paten' => $validated['kategori_paten'],
                'creator_name' => $validated['creator_name'],
                'creator_whatsapp' => $validated['creator_whatsapp'],
                'creator_country_code' => $validated['creator_country_code'],
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'status' => 'pending',
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

        $submissionPaten->load(['reviewedByAdmin', 'biodataReviewedByAdmin', 'biodataPaten.inventors']);
        
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

        $filePath = storage_path('app/public/' . $submissionPaten->file_path);
        
        if (!file_exists($filePath)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        return response()->download($filePath, $submissionPaten->file_name);
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

        // Check if submission is rejected
        if ($submissionPaten->status !== 'rejected') {
            return back()->with('error', 'Hanya submission yang ditolak yang dapat diajukan ulang.');
        }

        try {
            // Validate request
            $validated = $request->validate([
                'judul_paten' => 'required|string|max:255',
                'kategori_paten' => 'required|in:Paten,Paten Sederhana',
                'creator_name' => 'required|string|max:255',
                'creator_whatsapp' => 'required|string|max:255',
                'creator_country_code' => 'required|string|max:5',
                'document' => 'required|file|mimes:pdf|max:20480', // Max 20MB
            ]);

            $file = $request->file('document');

            // Delete old file
            if (Storage::disk('public')->exists($submissionPaten->file_path)) {
                Storage::disk('public')->delete($submissionPaten->file_path);
            }

            // Generate unique filename
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileName = pathinfo($originalName, PATHINFO_FILENAME);
            $uniqueFileName = $fileName . '_' . time() . '.' . $extension;
            
            $path = $file->storeAs('submissions_paten', $uniqueFileName, 'public');

            // Update submission
            $submissionPaten->update([
                'judul_paten' => $validated['judul_paten'],
                'kategori_paten' => $validated['kategori_paten'],
                'creator_name' => $validated['creator_name'],
                'creator_whatsapp' => $validated['creator_whatsapp'],
                'creator_country_code' => $validated['creator_country_code'],
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'status' => 'pending',
                'rejection_reason' => null,
                'revisi' => true,
                'reviewed_at' => null,
                'reviewed_by_admin_id' => null,
            ]);

            return redirect()->route('user.submissions-paten.show', $submissionPaten)
                           ->with('success', 'Pengajuan paten berhasil diajukan ulang. Menunggu review admin.');

        } catch (\Exception $e) {
            Log::error('Paten resubmission error: ' . $e->getMessage());
            
            return back()->withErrors([
                'document' => 'Terjadi kesalahan saat mengunggah ulang file.'
            ])->withInput();
        }
    }
}
