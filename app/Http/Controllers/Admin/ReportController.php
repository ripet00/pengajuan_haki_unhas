<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Biodata;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected function getCurrentAdmin()
    {
        return \App\Models\Admin::find(session('admin_id'));
    }

    /**
     * Display list of approved biodatas for tracking
     */
    public function index(Request $request)
    {
        $query = Biodata::with(['user', 'submission', 'submission.jenisKarya', 'reviewedBy'])
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
                          ->where('certificate_issued', false);
                    break;
                case 'certificate_issued':
                    $query->where('certificate_issued', true);
                    break;
                case 'document_overdue':
                    // Will filter in collection
                    break;
                case 'certificate_overdue':
                    // Will filter in collection
                    break;
            }
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'LIKE', "%{$search}%")
                             ->orWhere('phone_number', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('submission', function($submissionQuery) use ($search) {
                    $submissionQuery->where('title', 'LIKE', "%{$search}%");
                });
            });
        }

        $biodatas = $query->get();

        // Apply overdue filters if needed
        if ($request->filled('tracking_status')) {
            if ($request->tracking_status === 'document_overdue') {
                $biodatas = $biodatas->filter(function($biodata) {
                    return !$biodata->document_submitted && $biodata->isDocumentOverdue();
                });
            } elseif ($request->tracking_status === 'certificate_overdue') {
                $biodatas = $biodatas->filter(function($biodata) {
                    return $biodata->document_submitted && !$biodata->certificate_issued && $biodata->isCertificateOverdue();
                });
            }
        }

        // Paginate the collection
        $perPage = 15;
        $currentPage = $request->get('page', 1);
        $biodatas = new \Illuminate\Pagination\LengthAwarePaginator(
            $biodatas->forPage($currentPage, $perPage),
            $biodatas->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Get statistics
        $totalApproved = Biodata::where('status', 'approved')->count();
        
        $documentPending = Biodata::where('status', 'approved')
            ->where('document_submitted', false)
            ->count();
        
        $documentSubmitted = Biodata::where('status', 'approved')
            ->where('document_submitted', true)
            ->where('certificate_issued', false)
            ->count();
        
        $certificateIssued = Biodata::where('status', 'approved')
            ->where('certificate_issued', true)
            ->count();

        $documentOverdue = Biodata::where('status', 'approved')
            ->where('document_submitted', false)
            ->get()
            ->filter(function($biodata) {
                return $biodata->isDocumentOverdue();
            })
            ->count();

        $certificateOverdue = Biodata::where('status', 'approved')
            ->where('document_submitted', true)
            ->where('certificate_issued', false)
            ->get()
            ->filter(function($biodata) {
                return $biodata->isCertificateOverdue();
            })
            ->count();

        return view('admin.reports.index', compact(
            'biodatas',
            'totalApproved',
            'documentPending',
            'documentSubmitted',
            'certificateIssued',
            'documentOverdue',
            'certificateOverdue'
        ));
    }

    /**
     * Mark biodata as document submitted
     */
    public function markDocumentSubmitted(Biodata $biodata)
    {
        $admin = $this->getCurrentAdmin();
        
        if (!$admin) {
            return back()->with('error', 'Admin session tidak valid.');
        }

        if ($biodata->status !== 'approved') {
            return back()->with('error', 'Hanya biodata yang disetujui yang dapat ditandai berkas disetor.');
        }

        if ($biodata->document_submitted) {
            return back()->with('error', 'Berkas sudah ditandai sebagai disetor sebelumnya.');
        }

        $biodata->update([
            'document_submitted' => true,
            'document_submitted_at' => now(),
        ]);

        return back()->with('success', 'Berkas berhasil ditandai sebagai sudah disetor pada ' . now()->format('d F Y, H:i') . ' WITA');
    }

    /**
     * Mark biodata certificate as issued
     */
    public function markCertificateIssued(Biodata $biodata)
    {
        $admin = $this->getCurrentAdmin();
        
        if (!$admin) {
            return back()->with('error', 'Admin session tidak valid.');
        }

        if ($biodata->status !== 'approved') {
            return back()->with('error', 'Hanya biodata yang disetujui yang dapat ditandai sertifikat terbit.');
        }

        if (!$biodata->document_submitted) {
            return back()->with('error', 'Berkas harus disetor terlebih dahulu sebelum sertifikat dapat ditandai terbit.');
        }

        if ($biodata->certificate_issued) {
            return back()->with('error', 'Sertifikat sudah ditandai sebagai terbit sebelumnya.');
        }

        $biodata->update([
            'certificate_issued' => true,
            'certificate_issued_at' => now(),
        ]);

        return back()->with('success', 'Sertifikat HKI berhasil ditandai sebagai sudah terbit pada ' . now()->format('d F Y, H:i') . ' WITA');
    }
}
