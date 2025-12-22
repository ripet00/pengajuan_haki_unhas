<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BiodataPaten;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReportPatenController extends Controller
{
    protected function getCurrentAdmin()
    {
        return \App\Models\Admin::find(session('admin_id'));
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
                          ->where('ready_for_signing', false);
                    break;
                case 'ready_for_signing':
                    $query->where('ready_for_signing', true);
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
                    return $biodata->document_submitted && !$biodata->ready_for_signing && $biodata->isSigningOverdue();
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
            ->where('ready_for_signing', false)
            ->count();
        
        $readyForSigning = BiodataPaten::where('status', 'approved')
            ->where('ready_for_signing', true)
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
            ->where('ready_for_signing', false)
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
            'readyForSigning',
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
     * Mark biodata paten document as ready for signing by leadership
     */
    public function markReadyForSigning(BiodataPaten $biodataPaten)
    {
        $admin = $this->getCurrentAdmin();
        
        if (!$admin) {
            return back()->with('error', 'Admin session tidak valid.');
        }

        if ($biodataPaten->status !== 'approved') {
            return back()->with('error', 'Hanya biodata yang disetujui yang dapat ditandai siap ditandatangani.');
        }

        if (!$biodataPaten->document_submitted) {
            return back()->with('error', 'Berkas harus disetor terlebih dahulu sebelum dapat ditandai siap ditandatangani pimpinan.');
        }

        if ($biodataPaten->ready_for_signing) {
            return back()->with('error', 'Dokumen sudah ditandai sebagai siap ditandatangani sebelumnya.');
        }

        $biodataPaten->update([
            'ready_for_signing' => true,
            'ready_for_signing_at' => now(),
        ]);

        return back()->with('success', 'Dokumen paten berhasil ditandai sebagai siap ditandatangani pimpinan pada ' . now()->format('d F Y, H:i') . ' WITA');
    }
}
