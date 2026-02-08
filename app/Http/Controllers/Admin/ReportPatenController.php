<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BiodataPaten;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ReportPatenController extends Controller
{
    protected function getCurrentAdmin()
    {
        return Auth::guard('admin')->user();
    }

    /**
     * Display list of approved biodata patens for tracking
     */
    public function index(Request $request)
    {
        $query = BiodataPaten::with(['submissionPaten', 'submissionPaten.user', 'reviewedBy'])
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
                          ->whereNull('application_document');
                    break;
                case 'document_issued':
                    $query->whereNotNull('application_document');
                    break;
                case 'document_overdue':
                    // Will filter in collection
                    break;
                case 'signing_overdue':
                    // Will filter in collection
                    break;
            }
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('submissionPaten.user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'LIKE', "%{$search}%")
                             ->orWhere('phone_number', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('submissionPaten', function($submissionQuery) use ($search) {
                    $submissionQuery->where('judul_paten', 'LIKE', "%{$search}%");
                });
            });
        }

        $biodataPatens = $query->get();

        // Apply overdue filters if needed
        if ($request->filled('tracking_status')) {
            if ($request->tracking_status === 'document_overdue') {
                $biodataPatens = $biodataPatens->filter(function($biodata) {
                    return !$biodata->document_submitted && $biodata->isDocumentOverdue();
                });
            } elseif ($request->tracking_status === 'signing_overdue') {
                $biodataPatens = $biodataPatens->filter(function($biodata) {
                    return $biodata->document_submitted && !$biodata->application_document && $biodata->isSigningOverdue();
                });
            }
        }

        // Paginate the collection
        $perPage = 15;
        $currentPage = $request->get('page', 1);
        $biodataPatens = new \Illuminate\Pagination\LengthAwarePaginator(
            $biodataPatens->forPage($currentPage, $perPage),
            $biodataPatens->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Get statistics
        $totalApproved = BiodataPaten::where('status', 'approved')->count();
        
        $documentPending = BiodataPaten::where('status', 'approved')
            ->where('document_submitted', false)
            ->count();
        
        $documentSubmitted = BiodataPaten::where('status', 'approved')
            ->where('document_submitted', true)
            ->whereNull('application_document')
            ->count();
        
        $documentIssued = BiodataPaten::where('status', 'approved')
            ->whereNotNull('application_document')
            ->count();

        $documentOverdue = BiodataPaten::where('status', 'approved')
            ->where('document_submitted', false)
            ->get()
            ->filter(function($biodata) {
                return $biodata->isDocumentOverdue();
            })
            ->count();

        $signingOverdue = BiodataPaten::where('status', 'approved')
            ->where('document_submitted', true)
            ->whereNull('application_document')
            ->get()
            ->filter(function($biodata) {
                return $biodata->isSigningOverdue();
            })
            ->count();

        return view('admin.reports-paten.index', compact(
            'biodataPatens',
            'totalApproved',
            'documentPending',
            'documentSubmitted',
            'documentIssued',
            'documentOverdue',
            'signingOverdue'
        ));
    }

    /**
     * Mark biodata paten as document submitted
     */
    public function markDocumentSubmitted(BiodataPaten $biodataPaten)
    {
        $admin = $this->getCurrentAdmin();
        
        if (!$admin) {
            return back()->with('error', 'Admin session tidak valid.');
        }

        if ($biodataPaten->status !== 'approved') {
            return back()->with('error', 'Hanya biodata yang disetujui yang dapat ditandai berkas disetor.');
        }

        if ($biodataPaten->document_submitted) {
            return back()->with('error', 'Berkas sudah ditandai sebagai disetor sebelumnya.');
        }

        $biodataPaten->update([
            'document_submitted' => true,
            'document_submitted_at' => now(),
        ]);

        return back()->with('success', 'Berkas berhasil ditandai sebagai sudah disetor pada ' . now()->format('d F Y, H:i') . ' WITA');
    }

    /**
     * Upload application document (Dokumen Permohonan)
     */
    public function uploadApplicationDocument(Request $request, BiodataPaten $biodataPaten)
    {
        $admin = $this->getCurrentAdmin();
        
        if (!$admin) {
            return back()->with('error', 'Admin session tidak valid.');
        }

        if ($biodataPaten->status !== 'approved') {
            return back()->with('error', 'Hanya biodata yang disetujui yang dapat diunggah dokumen permohonan.');
        }

        if (!$biodataPaten->document_submitted) {
            return back()->with('error', 'Berkas harus disetor terlebih dahulu sebelum dapat mengunggah dokumen permohonan.');
        }

        // NEW: Check if 3 required patent documents are uploaded first
        if (!$biodataPaten->deskripsi_pdf || !$biodataPaten->klaim_pdf || !$biodataPaten->abstrak_pdf) {
            return back()->with('error', 'User harus mengupload 3 dokumen paten wajib terlebih dahulu (Deskripsi, Klaim, Abstrak) sebelum dokumen permohonan dapat diterbitkan.');
        }

        if ($biodataPaten->application_document) {
            return back()->with('error', 'Dokumen permohonan sudah diunggah sebelumnya.');
        }

        $request->validate([
            'application_document' => [
                'required',
                'file',
                'mimetypes:application/pdf,application/x-pdf,application/acrobat,applications/vnd.pdf,text/pdf,text/x-pdf',
                'max:20480' // max 20MB
            ],
        ], [
            'application_document.required' => 'File dokumen permohonan wajib diunggah.',
            'application_document.file' => 'File yang diunggah tidak valid.',
            'application_document.mimetypes' => 'Dokumen permohonan harus berformat PDF.',
            'application_document.max' => 'Ukuran file maksimal 20MB.',
        ]);

        try {
            $file = $request->file('application_document');
            
            // Delete old file if exists
            if ($biodataPaten->application_document) {
                \App\Helpers\FileUploadHelper::deleteSecure($biodataPaten->application_document);
            }
            
            // Upload to private storage with security validation
            $result = \App\Helpers\FileUploadHelper::uploadSecure(
                $file,
                'application_documents',
                ['pdf']
            );

            $biodataPaten->update([
                'application_document' => $result['path'],
                'original_filename' => $result['original_filename'],
                'document_issued_at' => now(),
            ]);

            return back()->with('success', 'Dokumen permohonan paten berhasil diunggah dan diterbitkan pada ' . now()->format('d F Y, H:i') . ' WITA');
        } catch (\Exception $e) {
            Log::error('Error uploading application document: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengunggah dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Show patent documents page with 4 PDFs
     */
    public function showPatentDocuments(BiodataPaten $biodataPaten)
    {
        $admin = $this->getCurrentAdmin();
        
        if (!$admin) {
            abort(403, 'Admin session tidak valid');
        }

        // Load relationships
        $biodataPaten->load(['submissionPaten', 'user', 'inventors']);

        return view('admin.reports-paten.show-patent-documents', compact('biodataPaten'));
    }

    /**
     * Download specific patent document PDF
     */
    public function downloadPatentDocument(BiodataPaten $biodataPaten, $type)
    {
        $admin = $this->getCurrentAdmin();
        
        if (!$admin) {
            abort(403, 'Admin session tidak valid');
        }

        // Validate type
        $allowedTypes = ['deskripsi', 'klaim', 'abstrak', 'gambar'];
        if (!in_array($type, $allowedTypes)) {
            abort(404, 'Tipe dokumen tidak valid');
        }

        $fieldName = $type . '_pdf';
        $filePath = $biodataPaten->$fieldName;

        // Use private storage (local disk) - sesuai security update
        if (!$filePath || !Storage::disk('local')->exists($filePath)) {
            return back()->with('error', 'File tidak ditemukan');
        }

        $fullPath = storage_path('app/private/' . $filePath);
        $fileName = ucfirst($type) . '_Paten_' . $biodataPaten->id . '.pdf';

        return response()->download($fullPath, $fileName);
    }

    /**
     * View patent document PDF in browser (inline)
     */
    public function viewPatentDocument(BiodataPaten $biodataPaten, $type)
    {
        $admin = $this->getCurrentAdmin();
        
        if (!$admin) {
            abort(403, 'Admin session tidak valid');
        }

        // Validate type
        $allowedTypes = ['deskripsi', 'klaim', 'abstrak', 'gambar'];
        if (!in_array($type, $allowedTypes)) {
            abort(404, 'Tipe dokumen tidak valid');
        }

        $fieldName = $type . '_pdf';
        $filePath = $biodataPaten->$fieldName;

        // Use private storage (local disk) - sesuai security update
        if (!$filePath || !Storage::disk('local')->exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        $fullPath = storage_path('app/private/' . $filePath);
        $fileName = ucfirst($type) . '_Paten_' . $biodataPaten->id . '.pdf';

        return response()->file($fullPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $fileName . '"'
        ]);
    }
}
