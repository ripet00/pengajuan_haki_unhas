<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Biodata;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share pending counts with admin sidebar
        View::composer('admin.partials.sidebar', function ($view) {
            // Count pending certificates (berkas disetor, belum terbit sertifikat)
            $pendingCertificates = Biodata::where('status', 'approved')
                ->where('document_submitted', true)
                ->where('certificate_issued', false)
                ->count();
            
            // Count pending signing for paten (berkas disetor, belum terbit dokumen permohonan)
            $pendingSigning = \App\Models\BiodataPaten::where('status', 'approved')
                ->where('document_submitted', true)
                ->whereNull('application_document')
                ->count();
            
            // Count pending users (user dengan status pending)
            $pendingUsers = \App\Models\User::where('status', 'pending')->count();
            
            // Count pending HKI submissions (pengajuan dengan status pending)
            $pendingSubmissions = \App\Models\Submission::where('status', 'pending')->count();
            
            // Count pending biodatas (biodata dengan status pending)
            $pendingBiodatas = Biodata::where('status', 'pending')->count();
            
            // Count pending Paten submissions (pengajuan paten menunggu review format)
            $pendingPatenSubmissions = \App\Models\SubmissionPaten::where('status', \App\Models\SubmissionPaten::STATUS_PENDING_FORMAT_REVIEW)->count();
            
            // Count pending Biodata Paten (biodata paten dengan status pending)
            $pendingBiodataPatens = \App\Models\BiodataPaten::where('status', 'pending')->count();
            
            $view->with([
                'pendingCertificates' => $pendingCertificates,
                'pendingSigning' => $pendingSigning,
                'pendingUsers' => $pendingUsers,
                'pendingSubmissions' => $pendingSubmissions,
                'pendingBiodatas' => $pendingBiodatas,
                'pendingPatenSubmissions' => $pendingPatenSubmissions,
                'pendingBiodataPatens' => $pendingBiodataPatens,
            ]);
        });
    }
}
