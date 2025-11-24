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
        // Share pending certificates count and pending users count with admin sidebar
        View::composer('admin.partials.sidebar', function ($view) {
            // Count pending certificates (berkas disetor, belum terbit sertifikat)
            $pendingCertificates = Biodata::where('status', 'approved')
                ->where('document_submitted', true)
                ->where('certificate_issued', false)
                ->count();
            
            // Count pending users (user dengan status pending)
            $pendingUsers = \App\Models\User::where('status', 'pending')->count();
            
            $view->with([
                'pendingCertificates' => $pendingCertificates,
                'pendingUsers' => $pendingUsers,
            ]);
        });
    }
}
