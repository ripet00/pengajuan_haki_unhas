<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\SubmissionController as AdminSubmissionController;
use App\Http\Controllers\Admin\SubmissionPatenController as AdminSubmissionPatenController;
use App\Http\Controllers\Admin\PasswordResetManagementController;
use App\Http\Controllers\User\SubmissionController as UserSubmissionController;
use App\Http\Controllers\User\SubmissionPatenController as UserSubmissionPatenController;

// Default route redirects based on authentication status
Route::get('/', function () {
    // Use the Auth facade for static analysis compatibility (intelephense)
    if (Auth::check()) {
        return redirect('/users/dashboard');
    }
    if (session('admin_id')) {
        $admin = \App\Models\Admin::find(session('admin_id'));
        if ($admin && $admin->role === \App\Models\Admin::ROLE_PENDAMPING_PATEN) {
            return redirect()->route('admin.pendamping-paten.dashboard');
        }
        return redirect('/admin');
    }
    return redirect('/login');
});

// User Authentication Routes
Route::prefix('/')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [UserAuthController::class, 'showLoginForm'])->name('login'); // Laravel default name
        Route::post('/login', [UserAuthController::class, 'login']);
        
        Route::get('/register', [UserAuthController::class, 'showRegisterForm'])->name('user.register');
        Route::post('/register', [UserAuthController::class, 'register']);
        
        // Password Reset Routes (accessible without authentication)
        Route::get('/password/forgot', [PasswordResetController::class, 'showForgotForm'])->name('password.forgot');
        Route::post('/password/request', [PasswordResetController::class, 'submitRequest'])->name('password.request.submit');
        Route::get('/password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
        Route::post('/password/reset', [PasswordResetController::class, 'resetPassword'])->name('password.reset.submit');
    });
});

// User protected routes with /users prefix
Route::prefix('users')->middleware(['auth', 'check.user.status'])->group(function () {
    Route::get('/dashboard', function () {
        return view('user.dashboard_modern');
    })->name('user.dashboard');
    
    Route::post('/logout', [UserAuthController::class, 'logout'])->name('user.logout');
    
    // User submission routes
    Route::get('submissions', [UserSubmissionController::class, 'index'])->name('user.submissions.index');
    Route::get('submissions/create', [UserSubmissionController::class, 'create'])->name('user.submissions.create');
    Route::post('submissions', [UserSubmissionController::class, 'store'])->middleware('file.upload')->name('user.submissions.store');
    Route::get('submissions/{submission}', [UserSubmissionController::class, 'show'])->name('user.submissions.show');
    Route::get('submissions/{submission}/download', [UserSubmissionController::class, 'download'])->name('user.submissions.download');
    Route::post('submissions/{submission}/resubmit', [UserSubmissionController::class, 'resubmit'])->middleware('file.upload')->name('user.submissions.resubmit');
    
    // Biodata routes
    Route::get('submissions/{submission}/biodata/create', [App\Http\Controllers\User\BiodataController::class, 'create'])->name('user.biodata.create');
    Route::post('submissions/{submission}/biodata', [App\Http\Controllers\User\BiodataController::class, 'store'])->name('user.biodata.store');
    Route::get('submissions/{submission}/biodata/{biodata}', [App\Http\Controllers\User\BiodataController::class, 'show'])->name('user.biodata.show');
    Route::get('biodata/{biodata}/download-formulir', [App\Http\Controllers\User\BiodataController::class, 'downloadFormulir'])->name('user.biodata.download-formulir');
    
    // Paten submission routes
    Route::get('submissions-paten', [UserSubmissionPatenController::class, 'index'])->name('user.submissions-paten.index');
    Route::get('submissions-paten/create', [UserSubmissionPatenController::class, 'create'])->name('user.submissions-paten.create');
    Route::post('submissions-paten', [UserSubmissionPatenController::class, 'store'])->middleware('file.upload')->name('user.submissions-paten.store');
    Route::get('submissions-paten/{submissionPaten}', [UserSubmissionPatenController::class, 'show'])->name('user.submissions-paten.show');
    Route::get('submissions-paten/{submissionPaten}/download', [UserSubmissionPatenController::class, 'download'])->name('user.submissions-paten.download');
    Route::post('submissions-paten/{submissionPaten}/resubmit', [UserSubmissionPatenController::class, 'resubmit'])->middleware('file.upload')->name('user.submissions-paten.resubmit');
    Route::post('submissions-paten/{submissionPaten}/resubmit-substance', [UserSubmissionPatenController::class, 'resubmitSubstance'])->middleware('file.upload')->name('user.submissions-paten.resubmit-substance');
    
    // Patent Documents Upload routes (moved from biodata-paten)
    Route::post('submissions-paten/{submissionPaten}/upload-patent-documents', [UserSubmissionPatenController::class, 'uploadPatentDocuments'])->name('user.submissions-paten.upload-patent-documents');
    Route::get('submissions-paten/{submissionPaten}/download-patent-document/{type}', [UserSubmissionPatenController::class, 'downloadPatentDocument'])->name('user.submissions-paten.download-patent-document');
    
    // Biodata Paten routes
    Route::get('submissions-paten/{submissionPaten}/biodata-paten/create', [App\Http\Controllers\User\BiodataPatenController::class, 'create'])->name('user.biodata-paten.create');
    Route::post('submissions-paten/{submissionPaten}/biodata-paten', [App\Http\Controllers\User\BiodataPatenController::class, 'store'])->name('user.biodata-paten.store');
    Route::get('submissions-paten/{submissionPaten}/biodata-paten/{biodataPaten}', [App\Http\Controllers\User\BiodataPatenController::class, 'show'])->name('user.biodata-paten.show');
    Route::get('biodata-paten/{biodataPaten}/download-formulir', [App\Http\Controllers\User\BiodataPatenController::class, 'downloadFormulir'])->name('user.biodata-paten.download-formulir');
    Route::get('biodata-paten/{biodataPaten}/download-surat-pengalihan', [App\Http\Controllers\User\BiodataPatenController::class, 'downloadSuratPengalihan'])->name('user.biodata-paten.download-surat-pengalihan');
    Route::get('biodata-paten/{biodataPaten}/download-surat-pernyataan', [App\Http\Controllers\User\BiodataPatenController::class, 'downloadSuratPernyataan'])->name('user.biodata-paten.download-surat-pernyataan');
    Route::post('biodata-paten/{biodataPaten}/mark-document-submitted', [App\Http\Controllers\User\BiodataPatenController::class, 'markDocumentSubmitted'])->name('user.biodata-paten.mark-document-submitted');
    
    // Wilayah API routes
    Route::get('api/wilayah/provinces', [App\Http\Controllers\Api\WilayahController::class, 'getProvinces'])->name('api.wilayah.provinces');
    Route::get('api/wilayah/cities/{provinceCode}', [App\Http\Controllers\Api\WilayahController::class, 'getCities'])->name('api.wilayah.cities');
    Route::get('api/wilayah/districts/{cityCode}', [App\Http\Controllers\Api\WilayahController::class, 'getDistricts'])->name('api.wilayah.districts');
    Route::get('api/wilayah/villages/{districtCode}', [App\Http\Controllers\Api\WilayahController::class, 'getVillages'])->name('api.wilayah.villages');
});

// Admin Authentication Routes
Route::prefix('admin')->group(function () {
    Route::middleware('admin.guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [AdminAuthController::class, 'login']);
    });
    
    // Admin protected routes
    Route::middleware('admin.auth')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        // Password Reset Management Routes
        Route::prefix('password-reset')->group(function () {
            Route::get('/', [PasswordResetManagementController::class, 'index'])->name('admin.password-reset.index');
            Route::get('/{id}', [PasswordResetManagementController::class, 'show'])->name('admin.password-reset.show');
            Route::post('/{id}/approve', [PasswordResetManagementController::class, 'approve'])->name('admin.password-reset.approve');
            Route::post('/{id}/reject', [PasswordResetManagementController::class, 'reject'])->name('admin.password-reset.reject');
            Route::delete('/{id}', [PasswordResetManagementController::class, 'destroy'])->name('admin.password-reset.destroy')->middleware('admin.role:super_admin');
        });
        
        // User management routes - semua role bisa akses
        Route::patch('/users/{user}/status', [AdminController::class, 'updateUserStatus'])->name('admin.users.update-status');
        Route::get('/users', [AdminController::class, 'userIndex'])->name('admin.users.index');
        
        // Admin management routes - hanya super_admin
        Route::middleware('admin.role:super_admin')->group(function () {
            Route::get('/admins', [AdminController::class, 'adminIndex'])->name('admin.admins.index');
            Route::get('/create-admin', [AdminController::class, 'createAdmin'])->name('admin.create');
            Route::post('/create-admin', [AdminController::class, 'storeAdmin'])->name('admin.store');
            Route::get('/admins/{admin}/edit', [AdminController::class, 'editAdmin'])->name('admin.admins.edit');
            Route::put('/admins/{admin}', [AdminController::class, 'updateAdmin'])->name('admin.admins.update');
            Route::patch('/admins/{admin}/status', [AdminController::class, 'updateAdminStatus'])->name('admin.admins.update-status');
            
            // Pendamping Paten detail view - hanya super_admin
            Route::get('/pendamping-paten/{admin}/detail', [\App\Http\Controllers\Admin\PendampingPatenController::class, 'detail'])->name('admin.pendamping-paten.detail');
        });
        
        // Hak Cipta routes - super_admin, admin_hakcipta
        Route::middleware('admin.role:admin_hakcipta')->group(function () {
            // Admin submission routes
            Route::get('submissions', [AdminSubmissionController::class, 'index'])->name('admin.submissions.index');
            Route::get('submissions/{submission}', [AdminSubmissionController::class, 'show'])->name('admin.submissions.show');
            Route::get('submissions/{submission}/download', [AdminSubmissionController::class, 'download'])->name('admin.submissions.download');
            Route::post('submissions/{submission}/review', [AdminSubmissionController::class, 'review'])->name('admin.submissions.review');
            Route::post('submissions/{submission}/update-review', [AdminSubmissionController::class, 'updateReview'])->name('admin.submissions.update-review');
            Route::delete('submissions/{submission}', [AdminSubmissionController::class, 'destroy'])->name('admin.submissions.destroy');

            // Admin biodata routes  
            Route::get('biodata', [\App\Http\Controllers\Admin\BiodataController::class, 'index'])->name('admin.biodata.index');
            Route::get('biodata/{biodata}', [\App\Http\Controllers\Admin\BiodataController::class, 'show'])->name('admin.biodata.show');
            Route::post('biodata/{biodata}/review', [\App\Http\Controllers\Admin\BiodataController::class, 'review'])->name('admin.biodata.review');
            Route::post('biodata/{biodata}/update-errors', [\App\Http\Controllers\Admin\BiodataController::class, 'updateErrorFlags'])->name('admin.biodata.update-errors');
            Route::post('biodata/{biodata}/mark-document-submitted', [\App\Http\Controllers\Admin\BiodataController::class, 'markDocumentSubmitted'])->name('admin.biodata.mark-document-submitted');
            Route::post('biodata/{biodata}/mark-certificate-issued', [\App\Http\Controllers\Admin\BiodataController::class, 'markCertificateIssued'])->name('admin.biodata.mark-certificate-issued');
            
            // Admin reports routes
            Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('admin.reports.index');
            Route::post('reports/{biodata}/mark-document-submitted', [\App\Http\Controllers\Admin\ReportController::class, 'markDocumentSubmitted'])->name('admin.reports.mark-document-submitted');
            Route::post('reports/{biodata}/mark-certificate-issued', [\App\Http\Controllers\Admin\ReportController::class, 'markCertificateIssued'])->name('admin.reports.mark-certificate-issued');
            Route::get('reports/{biodata}/download-kelengkapan', [\App\Http\Controllers\Admin\ReportController::class, 'downloadKelengkapan'])->name('admin.reports.download-kelengkapan');
        });

        // Jenis Karya management routes - super_admin, admin_hakcipta
        Route::middleware('admin.role:admin_hakcipta')->group(function () {
            Route::resource('jenis-karyas', \App\Http\Controllers\Admin\JenisKaryaController::class)->names([
                'index' => 'admin.jenis-karyas.index',
                'create' => 'admin.jenis-karyas.create',
                'store' => 'admin.jenis-karyas.store',
                'show' => 'admin.jenis-karyas.show',
                'edit' => 'admin.jenis-karyas.edit',
                'update' => 'admin.jenis-karyas.update',
                'destroy' => 'admin.jenis-karyas.destroy',
            ]);
        });

        // Paten routes - super_admin, admin_paten
        Route::middleware('admin.role:admin_paten')->group(function () {
            // Admin paten submission routes
            Route::get('submissions-paten', [AdminSubmissionPatenController::class, 'index'])->name('admin.submissions-paten.index');
            Route::get('submissions-paten/{submissionPaten}', [AdminSubmissionPatenController::class, 'show'])->name('admin.submissions-paten.show');
            Route::get('submissions-paten/{submissionPaten}/download', [AdminSubmissionPatenController::class, 'download'])->name('admin.submissions-paten.download');
            Route::post('submissions-paten/{submissionPaten}/review', [AdminSubmissionPatenController::class, 'review'])->name('admin.submissions-paten.review');
            Route::post('submissions-paten/{submissionPaten}/update-review', [AdminSubmissionPatenController::class, 'updateReview'])->name('admin.submissions-paten.update-review');
            Route::post('submissions-paten/{submissionPaten}/assign', [AdminSubmissionPatenController::class, 'assign'])->name('admin.submissions-paten.assign');
            Route::get('api/pendamping-paten-list', [AdminSubmissionPatenController::class, 'getPendampingPatenList'])->name('admin.api.pendamping-paten-list');
            Route::delete('submissions-paten/{submissionPaten}', [AdminSubmissionPatenController::class, 'destroy'])->name('admin.submissions-paten.destroy');

            // Admin biodata paten routes  
            Route::get('biodata-paten', [\App\Http\Controllers\Admin\BiodataPatenController::class, 'index'])->name('admin.biodata-paten.index');
            Route::get('biodata-paten/{biodataPaten}', [\App\Http\Controllers\Admin\BiodataPatenController::class, 'show'])->name('admin.biodata-paten.show');
            Route::post('biodata-paten/{biodataPaten}/review', [\App\Http\Controllers\Admin\BiodataPatenController::class, 'review'])->name('admin.biodata-paten.review');
            Route::post('biodata-paten/{biodataPaten}/update-errors', [\App\Http\Controllers\Admin\BiodataPatenController::class, 'updateErrorFlags'])->name('admin.biodata-paten.update-errors');
            Route::post('biodata-paten/{biodataPaten}/mark-document-submitted', [\App\Http\Controllers\Admin\BiodataPatenController::class, 'markDocumentSubmitted'])->name('admin.biodata-paten.mark-document-submitted');
            Route::post('biodata-paten/{biodataPaten}/mark-certificate-issued', [\App\Http\Controllers\Admin\BiodataPatenController::class, 'markCertificateIssued'])->name('admin.biodata-paten.mark-certificate-issued');
            
            // Admin reports paten routes
            Route::get('reports-paten', [\App\Http\Controllers\Admin\ReportPatenController::class, 'index'])->name('admin.reports-paten.index');
            Route::post('reports-paten/{biodataPaten}/mark-document-submitted', [\App\Http\Controllers\Admin\ReportPatenController::class, 'markDocumentSubmitted'])->name('admin.reports-paten.mark-document-submitted');
            Route::post('reports-paten/{biodataPaten}/upload-application-document', [\App\Http\Controllers\Admin\ReportPatenController::class, 'uploadApplicationDocument'])->name('admin.reports-paten.upload-application-document');
            
            // Patent Documents PDF routes (NEW)
            Route::get('reports-paten/{biodataPaten}/patent-documents', [\App\Http\Controllers\Admin\ReportPatenController::class, 'showPatentDocuments'])->name('admin.reports-paten.show-patent-documents');
            Route::get('reports-paten/{biodataPaten}/patent-documents/{type}/view', [\App\Http\Controllers\Admin\ReportPatenController::class, 'viewPatentDocument'])->name('admin.reports-paten.view-patent-document');
            Route::get('reports-paten/{biodataPaten}/patent-documents/{type}/download', [\App\Http\Controllers\Admin\ReportPatenController::class, 'downloadPatentDocument'])->name('admin.reports-paten.download-patent-document');
        });
        
        // Pendamping Paten routes - super_admin, pendamping_paten
        Route::middleware('admin.role:pendamping_paten')->group(function () {
            // Dashboard Pendamping Paten
            Route::get('pendamping-paten/dashboard', [\App\Http\Controllers\Admin\PendampingPatenController::class, 'dashboard'])->name('admin.pendamping-paten.dashboard');
            
            // Pendamping Paten submission routes
            Route::get('pendamping-paten/submissions', [\App\Http\Controllers\Admin\PendampingPatenController::class, 'index'])->name('admin.pendamping-paten.index');
            Route::get('pendamping-paten/submissions/{submissionPaten}', [\App\Http\Controllers\Admin\PendampingPatenController::class, 'show'])->name('admin.pendamping-paten.show');
            Route::get('pendamping-paten/submissions/{submissionPaten}/download', [\App\Http\Controllers\Admin\PendampingPatenController::class, 'download'])->name('admin.pendamping-paten.download');
            Route::post('pendamping-paten/submissions/{submissionPaten}/review', [\App\Http\Controllers\Admin\PendampingPatenController::class, 'reviewSubstance'])->name('admin.pendamping-paten.review');
            Route::get('pendamping-paten/submissions/{submissionPaten}/download-review', [\App\Http\Controllers\Admin\PendampingPatenController::class, 'downloadSubstanceReview'])->name('admin.pendamping-paten.download-review');
            
            // API untuk dropdown fakultas dan program studi
            Route::get('api/fakultas-list', [\App\Http\Controllers\Admin\PendampingPatenController::class, 'getFakultasList'])->name('admin.api.fakultas-list');
            Route::get('api/program-studi-list', [\App\Http\Controllers\Admin\PendampingPatenController::class, 'getProgramStudiList'])->name('admin.api.program-studi-list');
        });
        
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    });
});
