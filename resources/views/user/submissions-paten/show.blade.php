<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengajuan - HKI Unhas</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        }
        
        /* Ensure text is always visible with high contrast */
        .header-text {
            color: #ffffff !important;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }
        
        .user-avatar {
            background: rgba(255, 255, 255, 0.25) !important;
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }
        
        .logout-btn {
            background: rgba(255, 255, 255, 0.2) !important;
            border: 1px solid rgba(255, 255, 255, 0.4) !important;
            backdrop-filter: blur(10px);
            color: #ffffff !important;
        }
        
        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.35) !important;
            border-color: rgba(255, 255, 255, 0.6) !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .logout-btn i, .logout-btn span {
            color: #ffffff !important;
            filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.3));
        }
        
        /* Additional visibility fixes */
        .header-icon {
            color: #ffffff !important;
            filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.3));
        }
    </style>
</head>
<body class="bg-gray-100">
@php
use Illuminate\Support\Facades\Storage;
@endphp
    <!-- Header -->
    <header class="gradient-bg shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-center py-4 sm:py-6 space-y-3 sm:space-y-0 w-full">
                <div class="flex items-center">
                    <img src="{{ asset('images/logo-unhas-kecil.png') }}" alt="Logo Unhas" class="w-10 h-10 sm:w-12 sm:h-12 mr-3">
                    <div>
                        <h1 class="text-sm sm:text-lg font-bold header-text leading-tight">Direktorat Inovasi dan Kekayaan Intelektual</h1>
                        <p class="text-green-100 text-xs sm:text-sm">Universitas Hasanuddin</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <div class="flex items-center header-text min-w-0 flex-1 sm:flex-initial">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 user-avatar rounded-full flex items-center justify-center mr-2 flex-shrink-0">
                            <span class="header-text font-medium text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <div class="min-w-0 flex-1 sm:flex-initial">
                            <span class="font-medium text-sm sm:text-base header-text hidden sm:block">{{ Auth::user()->name }}</span>
                            <span class="font-medium text-sm header-text block sm:hidden truncate">{{ explode(' ', Auth::user()->name)[0] }}</span>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('user.logout') }}">
                        @csrf
                        <button type="submit" class="logout-btn px-3 sm:px-4 py-2 rounded-lg font-medium transition duration-200 text-sm sm:text-base">
                            <i class="fas fa-sign-out-alt mr-1 sm:mr-2 header-icon"></i><span class="hidden sm:inline header-text">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto py-6 sm:py-8 px-4 sm:px-6 lg:px-8">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <ul class="text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Progress Tracker -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-route mr-2 text-blue-600"></i>Progress Pengajuan Paten
            </h3>
            
            <div class="flex items-center justify-between relative">
                <!-- Progress Line -->
                @php
                    use App\Models\SubmissionPaten;
                    
                    $biodata = $submissionPaten->biodataPaten;
                    $docSubmitted = $biodata && $biodata->document_submitted;
                    $documentIssued = $biodata && $biodata->application_document;
                    $patentDocsUploaded = $biodata && $biodata->deskripsi_pdf && $biodata->klaim_pdf && $biodata->abstrak_pdf;
                    $bStatus = $submissionPaten->biodata_status;
                    $isBRejected = $bStatus === 'rejected' || $bStatus === 'denied';
                    
                    // Calculate progress percentage for 7 steps (0%, 16.67%, 33.33%, 50%, 66.67%, 83.33%, 100%)
                    $progressWidth = '16.67%'; // Default: Upload done
                    if ($documentIssued) {
                        $progressWidth = '100%'; // Step 7: Dokumen permohonan terbit (TERAKHIR)
                    } elseif ($patentDocsUploaded) {
                        $progressWidth = '83.33%'; // Step 6: Dokumen paten PDF uploaded (3 wajib)
                    } elseif ($docSubmitted) {
                        $progressWidth = '66.67%'; // Step 5: Berkas disetor
                    } elseif ($bStatus == 'approved') {
                        $progressWidth = '50%'; // Step 4: Biodata approved
                    } elseif ($submissionPaten->status == SubmissionPaten::STATUS_APPROVED_SUBSTANCE) {
                        $progressWidth = '50%'; // Substansi approved, lanjut biodata
                    } elseif ($submissionPaten->status == SubmissionPaten::STATUS_REJECTED_SUBSTANCE_REVIEW) {
                        $progressWidth = '33.33%'; // Substansi ditolak
                    } elseif ($submissionPaten->status == SubmissionPaten::STATUS_PENDING_SUBSTANCE_REVIEW) {
                        $progressWidth = '33.33%'; // Step 3: Pending review substansi
                    } elseif ($submissionPaten->status == SubmissionPaten::STATUS_APPROVED_FORMAT) {
                        $progressWidth = '33.33%'; // Format approved, siap review substansi
                    } elseif ($submissionPaten->status == SubmissionPaten::STATUS_REJECTED_FORMAT_REVIEW) {
                        $progressWidth = '16.67%'; // Format ditolak
                    }
                @endphp
                <div class="absolute top-5 left-0 right-0 h-1 bg-gray-200 rounded-full z-0">
                    <div class="h-full bg-gradient-to-r from-blue-500 via-purple-500 to-green-500 rounded-full transition-all duration-500" 
                         style="width: {{ $progressWidth }}"></div>
                </div>

                <!-- Step 1: Upload Dokumen -->
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 {{ $submissionPaten->created_at ? 'bg-green-500 border-green-500' : 'bg-gray-200 border-gray-300' }}">
                        <i class="fas fa-upload {{ $submissionPaten->created_at ? 'text-white' : 'text-gray-400' }}"></i>
                    </div>
                    <span class="mt-2 text-xs text-center font-medium {{ $submissionPaten->created_at ? 'text-green-600' : 'text-gray-400' }}">
                        Upload<br>Dokumen
                    </span>
                </div>

                <!-- Step 2: Review Format (Admin Paten) -->
                @php
                    $formatApproved = in_array($submissionPaten->status, [
                        SubmissionPaten::STATUS_APPROVED_FORMAT,
                        SubmissionPaten::STATUS_PENDING_SUBSTANCE_REVIEW,
                        SubmissionPaten::STATUS_REJECTED_SUBSTANCE_REVIEW,
                        SubmissionPaten::STATUS_APPROVED_SUBSTANCE
                    ]);
                    $formatRejected = $submissionPaten->status == SubmissionPaten::STATUS_REJECTED_FORMAT_REVIEW;
                    $formatPending = $submissionPaten->status == SubmissionPaten::STATUS_PENDING_FORMAT_REVIEW;
                @endphp
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 {{ $formatApproved ? 'bg-green-500 border-green-500' : ($formatRejected ? 'bg-red-500 border-red-500' : ($formatPending ? 'bg-yellow-500 border-yellow-500' : 'bg-gray-200 border-gray-300')) }}">
                        @if($formatApproved)
                            <i class="fas fa-check text-white"></i>
                        @elseif($formatRejected)
                            <i class="fas fa-times text-white"></i>
                        @elseif($formatPending)
                            <i class="fas fa-clock text-white"></i>
                        @else
                            <i class="fas fa-file-alt text-gray-400"></i>
                        @endif
                    </div>
                    <span class="mt-2 text-xs text-center font-medium {{ $formatApproved ? 'text-green-600' : ($formatRejected ? 'text-red-600' : ($formatPending ? 'text-yellow-600' : 'text-gray-400')) }}">
                        Review<br>Format
                    </span>
                </div>

                <!-- Step 3: Review Substansi (Pendamping Paten) -->
                @php
                    $substanceApproved = $submissionPaten->status == SubmissionPaten::STATUS_APPROVED_SUBSTANCE;
                    $substanceRejected = $submissionPaten->status == SubmissionPaten::STATUS_REJECTED_SUBSTANCE_REVIEW;
                    $substancePending = $submissionPaten->status == SubmissionPaten::STATUS_PENDING_SUBSTANCE_REVIEW;
                    $substanceReady = $submissionPaten->status == SubmissionPaten::STATUS_APPROVED_FORMAT;
                @endphp
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 {{ $substanceApproved ? 'bg-green-500 border-green-500' : ($substanceRejected ? 'bg-orange-500 border-orange-500' : ($substancePending ? 'bg-blue-500 border-blue-500' : ($substanceReady ? 'bg-purple-500 border-purple-500' : 'bg-gray-200 border-gray-300'))) }}">
                        @if($substanceApproved)
                            <i class="fas fa-check text-white"></i>
                        @elseif($substanceRejected)
                            <i class="fas fa-times text-white"></i>
                        @elseif($substancePending)
                            <i class="fas fa-clock text-white"></i>
                        @elseif($substanceReady)
                            <i class="fas fa-user-tie text-white"></i>
                        @else
                            <i class="fas fa-microscope text-gray-400"></i>
                        @endif
                    </div>
                    <span class="mt-2 text-xs text-center font-medium {{ $substanceApproved ? 'text-green-600' : ($substanceRejected ? 'text-orange-600' : ($substancePending ? 'text-blue-600' : ($substanceReady ? 'text-purple-600' : 'text-gray-400'))) }}">
                        Review<br>Substansi
                    </span>
                </div>

                <!-- Step 4: Upload Biodata -->
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 {{ $bStatus == 'approved' || $isBRejected || $bStatus == 'pending' ? ($bStatus == 'approved' ? 'bg-green-500 border-green-500' : ($isBRejected ? 'bg-red-500 border-red-500' : 'bg-yellow-500 border-yellow-500')) : ($substanceApproved ? 'bg-blue-500 border-blue-500' : 'bg-gray-200 border-gray-300') }}">
                        @if($bStatus == 'approved')
                            <i class="fas fa-check text-white"></i>
                        @elseif($isBRejected)
                            <i class="fas fa-times text-white"></i>
                        @elseif($bStatus == 'pending')
                            <i class="fas fa-clock text-white"></i>
                        @elseif($substanceApproved)
                            <i class="fas fa-user-plus text-white"></i>
                        @else
                            <i class="fas fa-user-plus text-gray-400"></i>
                        @endif
                    </div>
                    <span class="mt-2 text-xs text-center font-medium {{ $bStatus == 'approved' ? 'text-green-600' : ($isBRejected ? 'text-red-600' : ($bStatus == 'pending' ? 'text-yellow-600' : ($substanceApproved ? 'text-blue-600' : 'text-gray-400'))) }}">
                        Upload<br>Biodata
                    </span>
                </div>

                <!-- Step 5: Setor Berkas -->
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 {{ $docSubmitted ? 'bg-green-500 border-green-500' : ($bStatus == 'approved' ? 'bg-blue-500 border-blue-500' : 'bg-gray-200 border-gray-300') }}">
                        @if($docSubmitted)
                            <i class="fas fa-check text-white"></i>
                        @elseif($bStatus == 'approved')
                            <i class="fas fa-folder-open text-white"></i>
                        @else
                            <i class="fas fa-folder-open text-gray-400"></i>
                        @endif
                    </div>
                    <span class="mt-2 text-xs text-center font-medium {{ $docSubmitted ? 'text-green-600' : ($bStatus == 'approved' ? 'text-blue-600' : 'text-gray-400') }}">
                        Setor<br>Berkas
                    </span>
                </div>

                <!-- Step 6: Upload Dokumen Paten (PDF) - SEKARANG SEBELUM DOKUMEN TERBIT -->
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 {{ $patentDocsUploaded ? 'bg-green-500 border-green-500' : ($docSubmitted ? 'bg-blue-500 border-blue-500' : 'bg-gray-200 border-gray-300') }}">
                        @if($patentDocsUploaded)
                            <i class="fas fa-check text-white"></i>
                        @elseif($docSubmitted)
                            <i class="fas fa-cloud-upload-alt text-white"></i>
                        @else
                            <i class="fas fa-cloud-upload-alt text-gray-400"></i>
                        @endif
                    </div>
                    <span class="mt-2 text-xs text-center font-medium {{ $patentDocsUploaded ? 'text-green-600' : ($docSubmitted ? 'text-blue-600' : 'text-gray-400') }}">
                        Upload<br>Dok. Paten
                    </span>
                </div>

                <!-- Step 7: Dokumen Permohonan Terbit - SEKARANG TERAKHIR -->
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 {{ $documentIssued ? 'bg-green-500 border-green-500' : ($patentDocsUploaded ? 'bg-purple-500 border-purple-500' : 'bg-gray-200 border-gray-300') }}">
                        @if($documentIssued)
                            <i class="fas fa-check text-white"></i>
                        @elseif($patentDocsUploaded)
                            <i class="fas fa-file-pdf text-white"></i>
                        @else
                            <i class="fas fa-file-pdf text-gray-400"></i>
                        @endif
                    </div>
                    <span class="mt-2 text-xs text-center font-medium {{ $documentIssued ? 'text-green-600' : ($patentDocsUploaded ? 'text-purple-600' : 'text-gray-400') }}">
                        Dokumen<br>Terbit
                    </span>
                </div>
            </div>

            <!-- Progress Description -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                @if($documentIssued)
                    <p class="text-sm text-green-700 font-medium">
                        <i class="fas fa-check-double mr-2"></i>Semua tahapan selesai! Dokumen permohonan paten sudah terbit.
                    </p>
                @elseif($patentDocsUploaded)
                    <p class="text-sm text-purple-700 font-medium">
                        <i class="fas fa-clock mr-2"></i>Dokumen paten (Deskripsi, Klaim, Abstrak) sudah diupload. Menunggu admin menerbitkan dokumen permohonan paten.
                    </p>
                @elseif($docSubmitted)
                    <p class="text-sm text-blue-700 font-medium">
                        <i class="fas fa-arrow-right mr-2"></i>Berkas sudah disetor. Silakan upload 3 dokumen paten wajib (Deskripsi, Klaim, Abstrak) untuk melanjutkan proses.
                    </p>
                @elseif($bStatus == 'approved')
                    <p class="text-sm text-blue-700 font-medium">
                        <i class="fas fa-arrow-right mr-2"></i>Biodata disetujui! Silakan setor berkas ke kantor untuk melanjutkan proses.
                    </p>
                @elseif($isBRejected)
                    <p class="text-sm text-red-700 font-medium">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Biodata ditolak. Silakan upload ulang biodata yang sesuai.
                    </p>
                @elseif($bStatus == 'pending')
                    <p class="text-sm text-yellow-700 font-medium">
                        <i class="fas fa-clock mr-2"></i>Biodata sedang direview oleh admin.
                    </p>
                @elseif($submissionPaten->status == SubmissionPaten::STATUS_APPROVED_SUBSTANCE)
                    <p class="text-sm text-blue-700 font-medium">
                        <i class="fas fa-arrow-right mr-2"></i>Substansi paten disetujui oleh Pendamping Paten! Silakan upload biodata untuk melanjutkan.
                    </p>
                @elseif($submissionPaten->status == SubmissionPaten::STATUS_REJECTED_SUBSTANCE_REVIEW)
                    <p class="text-sm text-orange-700 font-medium">
                        <i class="fas fa-times-circle mr-2"></i>Substansi paten ditolak oleh Pendamping Paten. Perbaiki dan upload ulang dokumen.
                    </p>
                @elseif($submissionPaten->status == SubmissionPaten::STATUS_PENDING_SUBSTANCE_REVIEW)
                    <p class="text-sm text-blue-700 font-medium">
                        <i class="fas fa-user-tie mr-2"></i>Dokumen sedang direview substansi oleh Pendamping Paten.
                    </p>
                @elseif($submissionPaten->status == SubmissionPaten::STATUS_APPROVED_FORMAT)
                    <p class="text-sm text-purple-700 font-medium">
                        <i class="fas fa-hourglass-half mr-2"></i>Format dokumen disetujui. Menunggu penugasan Pendamping Paten untuk review substansi.
                    </p>
                @elseif($submissionPaten->status == SubmissionPaten::STATUS_REJECTED_FORMAT_REVIEW)
                    <p class="text-sm text-red-700 font-medium">
                        <i class="fas fa-times-circle mr-2"></i>Format dokumen ditolak. Perbaiki dan upload ulang dokumen.
                    </p>
                @else
                    <p class="text-sm text-yellow-700 font-medium">
                        <i class="fas fa-hourglass-half mr-2"></i>Dokumen sedang direview format oleh admin.
                    </p>
                @endif
            </div>
        </div>

        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('user.submissions-paten.index') }}" class="inline-flex items-center px-5 py-3 bg-white hover:bg-gray-50 text-gray-800 hover:text-gray-900 border-2 border-gray-500 hover:border-gray-700 rounded-lg font-bold transition duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Daftar Pengajuan
            </a>
        </div>

        <!-- Submission Details -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Detail Pengajuan Paten</h2>
                <p class="text-sm text-gray-600 mt-1">ID Pengajuan: #{{ $submissionPaten->id }}</p>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Submission Info -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Judul Karya</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $submissionPaten->judul_paten }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Kategori</label>
                            <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full 
                                {{ $submissionPaten->kategori_paten == 'Paten' ? 'bg-green-100 text-green-800' : 'bg-emerald-100 text-emerald-800' }}">
                                <i class="fas fa-{{ $submissionPaten->kategori_paten == 'Paten' ? 'certificate' : 'award' }} mr-1"></i>
                                {{ $submissionPaten->kategori_paten }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                            @if($submissionPaten->status == SubmissionPaten::STATUS_PENDING_FORMAT_REVIEW)
                                <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>Menunggu Review Format
                                </span>
                            @elseif($submissionPaten->status == SubmissionPaten::STATUS_REJECTED_FORMAT_REVIEW)
                                <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>Format Ditolak
                                </span>
                            @elseif($submissionPaten->status == SubmissionPaten::STATUS_APPROVED_FORMAT)
                                <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-purple-100 text-purple-800">
                                    <i class="fas fa-check-circle mr-1"></i>Format Disetujui - Menunggu Penugasan
                                </span>
                            @elseif($submissionPaten->status == SubmissionPaten::STATUS_PENDING_SUBSTANCE_REVIEW)
                                <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800">
                                    <i class="fas fa-microscope mr-1"></i>Review Substansi Berlangsung
                                </span>
                            @elseif($submissionPaten->status == SubmissionPaten::STATUS_REJECTED_SUBSTANCE_REVIEW)
                                <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-orange-100 text-orange-800">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Substansi Ditolak
                                </span>
                            @elseif($submissionPaten->status == SubmissionPaten::STATUS_APPROVED_SUBSTANCE)
                                <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-double mr-1"></i>Substansi Disetujui
                                </span>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Pengajuan</label>
                            <p class="text-gray-900">{{ $submissionPaten->created_at->translatedFormat('d F Y, H:i') }} WITA</p>
                        </div>

                        @if($submissionPaten->reviewed_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Review</label>
                            <p class="text-gray-900">{{ $submissionPaten->reviewed_at->translatedFormat('d F Y, H:i') }} WITA</p>
                        </div>
                        @endif

                        @if($submissionPaten->reviewedByAdmin)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Direview Format oleh</label>
                            <p class="text-gray-900">{{ $submissionPaten->reviewedByAdmin->name }}</p>
                        </div>
                        @endif

                        <!-- Creator Information -->
                        @if($submissionPaten->creator_name || $submissionPaten->creator_whatsapp)
                        <div class="pt-4 border-t border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-user-edit mr-1"></i>Informasi Inventor Pertama
                            </h4>
                            
                            @if($submissionPaten->creator_name)
                            <div class="mb-2">
                                <label class="block text-sm font-medium text-gray-500 mb-1">Nama Inventor</label>
                                <p class="text-gray-900">{{ $submissionPaten->creator_name }}</p>
                            </div>
                            @endif

                            @if($submissionPaten->creator_whatsapp)
                            <div class="mb-2">
                                <label class="block text-sm font-medium text-gray-500 mb-1">WhatsApp Inventor</label>
                                <div class="flex items-center space-x-2">
                                    <p class="text-gray-900">({{ $submissionPaten->creator_country_code ?? '+62' }}) {{ $submissionPaten->creator_whatsapp }}</p>
                                    <a href="{{ generateWhatsAppUrl($submissionPaten->creator_whatsapp, $submissionPaten->creator_country_code ?? '+62') }}" 
                                       target="_blank"
                                       class="inline-flex items-center px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition duration-200">
                                        <i class="fab fa-whatsapp mr-1"></i>Hubungi
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>

                    <!-- File Info -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Nama File</label>
                            <p class="text-gray-900">{{ $submissionPaten->original_filename ?? $submissionPaten->file_name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Ukuran File</label>
                            <p class="text-gray-900">{{ number_format($submissionPaten->file_size / 1024 / 1024, 2) }} MB</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Jenis File</label>
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                <i class="fas fa-file-word mr-1"></i>DOCX
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-3">Aksi File</label>
                            <div class="space-y-2">
                                <a href="{{ route('files.submissions-paten.download', $submissionPaten) }}" 
                                   target="_blank"
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                    <i class="fas fa-eye mr-2"></i>Lihat File DOCX
                                </a>
                                <a href="{{ route('files.submissions-paten.download', $submissionPaten) }}" 
                                   download="{{ $submissionPaten->original_filename ?? $submissionPaten->file_name }}"
                                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition duration-200 ml-2">
                                    <i class="fas fa-download mr-2"></i>Download
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Format Review Rejection Notes Section -->
                @if($submissionPaten->status == SubmissionPaten::STATUS_REJECTED_FORMAT_REVIEW && $submissionPaten->rejection_reason)
                    <div class="mt-6 bg-red-50 border-2 border-red-200 rounded-lg p-5">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-file-alt text-red-600"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h4 class="text-base font-semibold text-red-900 mb-2">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Catatan Review Format dari Admin Paten
                                </h4>
                                <div class="bg-white border border-red-200 rounded-lg p-4 mb-4">
                                    <p class="text-sm text-gray-800 whitespace-pre-line">{{ $submissionPaten->rejection_reason }}</p>
                                </div>
                                
                                @if($submissionPaten->file_review_path)
                                    <div class="bg-white border border-red-200 rounded-lg p-3">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <i class="fas fa-file-word text-blue-600 text-xl mr-3"></i>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">File Review dari Admin Paten</p>
                                                    <p class="text-xs text-gray-600">Catatan koreksi format - {{ $submissionPaten->file_review_name ?? 'File Koreksi.docx' }}</p>
                                                </div>
                                            </div>
                                            <a href="{{ Storage::disk('public')->url($submissionPaten->file_review_path) }}" 
                                               download
                                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200 shadow-sm hover:shadow-md">
                                                <i class="fas fa-download mr-2"></i>Download
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                <div class="mt-4 p-3 bg-red-100 border border-red-300 rounded-lg">
                                    <p class="text-sm text-red-800">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Silakan perbaiki format paten sesuai catatan di atas, kemudian upload ulang dokumen di bagian bawah.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Substance Review Notes Section -->
                @if($submissionPaten->status == SubmissionPaten::STATUS_REJECTED_SUBSTANCE_REVIEW && $submissionPaten->substance_review_notes)
                    <div class="mt-6 bg-orange-50 border-2 border-orange-200 rounded-lg p-5">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-microscope text-orange-600"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h4 class="text-base font-semibold text-orange-900 mb-2">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Catatan Review Substansi dari Pendamping Paten
                                </h4>
                                <div class="bg-white border border-orange-200 rounded-lg p-4 mb-4">
                                    <p class="text-sm text-gray-800 whitespace-pre-line">{{ $submissionPaten->substance_review_notes }}</p>
                                </div>
                                
                                @if($submissionPaten->substance_review_file)
                                    <div class="bg-white border border-orange-200 rounded-lg p-3">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <i class="fas fa-file-word text-blue-600 text-xl mr-3"></i>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">File Review dari Pendamping Paten</p>
                                                    <p class="text-xs text-gray-600">Catatan koreksi substansi</p>
                                                </div>
                                            </div>
                                            <a href="{{ Storage::disk('public')->url($submissionPaten->substance_review_file) }}" 
                                               download
                                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200 shadow-sm hover:shadow-md">
                                                <i class="fas fa-download mr-2"></i>Download
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                <div class="mt-4 p-3 bg-orange-100 border border-orange-300 rounded-lg">
                                    <p class="text-sm text-orange-800">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Silakan perbaiki substansi paten sesuai catatan di atas, kemudian upload ulang dokumen di bagian bawah.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Revision Form for Substance Review -->
                        <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <h4 class="text-sm font-semibold text-yellow-800 mb-3">
                                <i class="fas fa-redo mr-1"></i>Revisi Substansi Paten
                            </h4>
                            <p class="text-sm text-yellow-700 mb-4">Anda dapat mengunggah ulang dokumen substansi yang telah diperbaiki sesuai catatan review.</p>
                            
                            <form method="POST" action="{{ route('user.submissions-paten.resubmit-substance', $submissionPaten) }}" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                
                                <div>
                                    <label for="judul_paten_substance" class="block text-sm font-medium text-gray-700 mb-1">Judul Karya (dapat diubah)</label>
                                    <input 
                                        type="text" 
                                        id="judul_paten_substance" 
                                        name="judul_paten" 
                                        value="{{ old('judul_paten', $submissionPaten->judul_paten) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                        required
                                    >
                                </div>

                                <div>
                                    <label for="kategori_paten_substance" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                                    <select 
                                        id="kategori_paten_substance" 
                                        name="kategori_paten"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                        required
                                    >
                                        <option value="Paten" {{ old('kategori_paten', $submissionPaten->kategori_paten) == 'Paten' ? 'selected' : '' }}>Paten</option>
                                        <option value="Paten Sederhana" {{ old('kategori_paten', $submissionPaten->kategori_paten) == 'Paten Sederhana' ? 'selected' : '' }}>Paten Sederhana</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="creator_name_substance" class="block text-sm font-medium text-gray-700 mb-1">Nama Inventor Pertama</label>
                                    <input 
                                        type="text" 
                                        id="creator_name_substance" 
                                        name="creator_name" 
                                        value="{{ old('creator_name', $submissionPaten->creator_name) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                        required
                                    >
                                </div>

                                <div>
                                    <label for="creator_whatsapp_substance" class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp Inventor Pertama</label>
                                    <div class="grid grid-cols-3 gap-2">
                                        <div class="col-span-1">
                                            <select 
                                                id="creator_country_code_substance" 
                                                name="creator_country_code"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm"
                                                required
                                            >
                                                @foreach(getCountryCodes() as $code => $label)
                                                    <option value="{{ $code }}" {{ old('creator_country_code', $submissionPaten->creator_country_code ?? '+62') == $code ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-span-2">
                                            <input 
                                                type="text" 
                                                id="creator_whatsapp_substance" 
                                                name="creator_whatsapp" 
                                                value="{{ old('creator_whatsapp', $submissionPaten->creator_whatsapp) }}"
                                                placeholder="081234567890"
                                                pattern="^0[0-9]{8,13}$"
                                                title="Nomor harus dimulai dengan 0 dan berisi 9-14 digit"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                                required
                                            >
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label for="file_paten_substance" class="block text-sm font-medium text-gray-700 mb-1">Upload Dokumen Paten Baru</label>
                                    <div class="mt-1 flex items-center">
                                        <input 
                                            type="file" 
                                            id="file_paten_substance" 
                                            name="file_paten" 
                                            accept=".docx"
                                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-500"
                                            required
                                        >
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Format: .docx (max 5MB)</p>
                                </div>

                                <button 
                                    type="submit"
                                    class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200"
                                >
                                    <i class="fas fa-upload mr-2"></i>Upload Ulang Substansi
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- Biodata Management Section -->
                @if($submissionPaten->status == SubmissionPaten::STATUS_APPROVED_SUBSTANCE)
                    @if($submissionPaten->biodataPaten)
                        <!-- Biodata exists - show biodata status and actions -->
                        <div class="mt-6 bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
                            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                                <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                                    <i class="fas fa-user-friends mr-3 text-blue-600"></i>
                                    Status Biodata
                                </h3>
                            </div>
                            
                            <div class="p-6">
                                <!-- Biodata Status Card -->
                                <div class="flex items-start justify-between mb-6">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-3">
                                            <h4 class="text-lg font-semibold text-gray-900 mr-3">
                                                Biodata Inventor
                                            </h4>
                                            @if($submissionPaten->biodataPaten->status == 'approved')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                                                    <i class="fas fa-check mr-1"></i>Disetujui
                                                </span>
                                            @elseif($submissionPaten->biodataPaten->status == 'denied')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-200">
                                                    <i class="fas fa-times mr-1"></i>Ditolak
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                    <i class="fas fa-clock mr-1"></i>Menunggu Review
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <p class="text-sm text-gray-600 mb-2">
                                            Total Inventor: <span class="font-semibold">{{ $submissionPaten->biodataPaten->inventors->count() }} orang</span>
                                        </p>
                                        
                                        @if($submissionPaten->biodataPaten->created_at)
                                            <p class="text-sm text-gray-600">
                                                Dibuat: {{ $submissionPaten->biodataPaten->created_at->translatedFormat('d F Y, H:i') }} WITA
                                            </p>
                                        @endif
                                        
                                        @if($submissionPaten->biodataPaten->status == 'denied' && $submissionPaten->biodataPaten->rejection_reason)
                                            <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                                <p class="text-sm font-medium text-red-800 mb-1">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>Alasan Penolakan:
                                                </p>
                                                <p class="text-sm text-red-700">{{ $submissionPaten->biodataPaten->rejection_reason }}</p>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex-shrink-0 ml-6">
                                        <a href="{{ route('user.biodata-paten.show', [$submissionPaten, $submissionPaten->biodataPaten]) }}" 
                                           class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-200 shadow-sm hover:shadow-md">
                                            <i class="fas fa-eye mr-2"></i>Lihat Detail
                                        </a>
                                    </div>
                                </div>

                                {{-- Status Information and Error Flags Section --}}
                                @php 
                                    $biodataPaten = $submissionPaten->biodataPaten;
                                @endphp

                                {{-- Show different messages based on biodata status --}}
                                @if($biodataPaten->status == 'approved')
                                    {{-- Biodata approved - check document submission status --}}
                                    @if($biodataPaten->document_submitted)
                                        {{-- Document submitted - show upload patent documents section FIRST --}}
                                        
                                        {{-- TAHAP PERTAMA: Upload Dokumen Paten (Deskripsi, Klaim, Abstrak, Gambar) --}}
                                        <div class="bg-purple-50 border-2 border-purple-300 rounded-xl p-6">
                                            <div class="flex items-center mb-4">
                                                <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center mr-4">
                                                    <i class="fas fa-file-pdf text-white text-xl"></i>
                                                </div>
                                                <div>
                                                    <h4 class="text-lg font-bold text-purple-900">
                                                        Tahap Pertama: Upload Dokumen Paten
                                                    </h4>
                                                    <p class="text-sm text-purple-700">
                                                        Upload 4 file PDF: Deskripsi, Klaim, Abstrak (wajib), dan Gambar (opsional)
                                                    </p>
                                                </div>
                                            </div>

                                            {{-- Progress Bar --}}
                                            @php
                                                $progress = $biodataPaten->getPatentDocumentsProgress();
                                            @endphp
                                            <div class="mb-4">
                                                <div class="flex justify-between items-center mb-2">
                                                    <span class="text-sm font-semibold text-purple-900">Progress Upload</span>
                                                    <span class="text-sm font-bold text-purple-900">{{ $progress }}%</span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                                    <div class="h-full bg-gradient-to-r from-purple-500 to-purple-700 transition-all duration-500" 
                                                         style="width: {{ $progress }}%"></div>
                                                </div>
                                                <p class="text-xs text-purple-600 mt-1">
                                                    <i class="fas fa-info-circle mr-1"></i>
                                                    3 file wajib (Deskripsi, Klaim, Abstrak) + 1 file opsional (Gambar)
                                                </p>
                                            </div>

                                            {{-- Upload Form --}}
                                            <form action="{{ route('user.submissions-paten.upload-patent-documents', $submissionPaten) }}" 
                                                  method="POST" 
                                                  enctype="multipart/form-data"
                                                  class="space-y-4">
                                                @csrf
                                                
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    {{-- Deskripsi PDF --}}
                                                    <div class="bg-white border-2 border-purple-200 rounded-lg p-4">
                                                        <label class="block text-sm font-semibold text-purple-900 mb-2">
                                                            <i class="fas fa-file-alt mr-1"></i>
                                                            1. Deskripsi <span class="text-red-600">*</span>
                                                        </label>
                                                        @if($biodataPaten->deskripsi_pdf)
                                                            <div class="mb-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                                                                <div class="flex items-center justify-between">
                                                                    <div class="flex items-center">
                                                                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                                                        <span class="text-sm text-green-800 font-medium">File sudah diupload</span>
                                                                    </div>
                                                                    <a href="{{ route('user.submissions-paten.download-patent-document', [$submissionPaten, 'deskripsi']) }}" 
                                                                       class="text-sm text-green-700 hover:text-green-900 underline">
                                                                        <i class="fas fa-download mr-1"></i>Download
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <input type="file" 
                                                               name="deskripsi_pdf" 
                                                               accept=".pdf"
                                                               class="block w-full text-sm text-gray-900 border border-purple-300 rounded-lg cursor-pointer bg-purple-50 focus:outline-none focus:ring-2 focus:ring-purple-500">
                                                        <p class="text-xs text-gray-600 mt-1">
                                                            <i class="fas fa-info-circle mr-1"></i>PDF, Max 10MB
                                                        </p>
                                                        @error('deskripsi_pdf')
                                                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    {{-- Klaim PDF --}}
                                                    <div class="bg-white border-2 border-purple-200 rounded-lg p-4">
                                                        <label class="block text-sm font-semibold text-purple-900 mb-2">
                                                            <i class="fas fa-gavel mr-1"></i>
                                                            2. Klaim <span class="text-red-600">*</span>
                                                        </label>
                                                        @if($biodataPaten->klaim_pdf)
                                                            <div class="mb-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                                                                <div class="flex items-center justify-between">
                                                                    <div class="flex items-center">
                                                                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                                                        <span class="text-sm text-green-800 font-medium">File sudah diupload</span>
                                                                    </div>
                                                                    <a href="{{ route('user.submissions-paten.download-patent-document', [$submissionPaten, 'klaim']) }}" 
                                                                       class="text-sm text-green-700 hover:text-green-900 underline">
                                                                        <i class="fas fa-download mr-1"></i>Download
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <input type="file" 
                                                               name="klaim_pdf" 
                                                               accept=".pdf"
                                                               class="block w-full text-sm text-gray-900 border border-purple-300 rounded-lg cursor-pointer bg-purple-50 focus:outline-none focus:ring-2 focus:ring-purple-500">
                                                        <p class="text-xs text-gray-600 mt-1">
                                                            <i class="fas fa-info-circle mr-1"></i>PDF, Max 10MB
                                                        </p>
                                                        @error('klaim_pdf')
                                                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    {{-- Abstrak PDF --}}
                                                    <div class="bg-white border-2 border-purple-200 rounded-lg p-4">
                                                        <label class="block text-sm font-semibold text-purple-900 mb-2">
                                                            <i class="fas fa-align-left mr-1"></i>
                                                            3. Abstrak <span class="text-red-600">*</span>
                                                        </label>
                                                        @if($biodataPaten->abstrak_pdf)
                                                            <div class="mb-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                                                                <div class="flex items-center justify-between">
                                                                    <div class="flex items-center">
                                                                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                                                        <span class="text-sm text-green-800 font-medium">File sudah diupload</span>
                                                                    </div>
                                                                    <a href="{{ route('user.submissions-paten.download-patent-document', [$submissionPaten, 'abstrak']) }}" 
                                                                       class="text-sm text-green-700 hover:text-green-900 underline">
                                                                        <i class="fas fa-download mr-1"></i>Download
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <input type="file" 
                                                               name="abstrak_pdf" 
                                                               accept=".pdf"
                                                               class="block w-full text-sm text-gray-900 border border-purple-300 rounded-lg cursor-pointer bg-purple-50 focus:outline-none focus:ring-2 focus:ring-purple-500">
                                                        <p class="text-xs text-gray-600 mt-1">
                                                            <i class="fas fa-info-circle mr-1"></i>PDF, Max 10MB
                                                        </p>
                                                        @error('abstrak_pdf')
                                                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    {{-- Gambar PDF (Optional) --}}
                                                    <div class="bg-white border-2 border-purple-200 rounded-lg p-4">
                                                        <label class="block text-sm font-semibold text-purple-900 mb-2">
                                                            <i class="fas fa-image mr-1"></i>
                                                            4. Gambar <span class="text-gray-500">(Opsional)</span>
                                                        </label>
                                                        @if($biodataPaten->gambar_pdf)
                                                            <div class="mb-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                                                                <div class="flex items-center justify-between">
                                                                    <div class="flex items-center">
                                                                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                                                        <span class="text-sm text-green-800 font-medium">File sudah diupload</span>
                                                                    </div>
                                                                    <a href="{{ route('user.submissions-paten.download-patent-document', [$submissionPaten, 'gambar']) }}" 
                                                                       class="text-sm text-green-700 hover:text-green-900 underline">
                                                                        <i class="fas fa-download mr-1"></i>Download
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <input type="file" 
                                                               name="gambar_pdf" 
                                                               accept=".pdf"
                                                               class="block w-full text-sm text-gray-900 border border-purple-300 rounded-lg cursor-pointer bg-purple-50 focus:outline-none focus:ring-2 focus:ring-purple-500">
                                                        <p class="text-xs text-gray-600 mt-1">
                                                            <i class="fas fa-info-circle mr-1"></i>PDF, Max 10MB (Tidak wajib)
                                                        </p>
                                                        @error('gambar_pdf')
                                                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="mt-4 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                                                    <p class="text-sm text-yellow-800">
                                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                                        <strong>Catatan:</strong> Anda dapat mengupload file satu per satu atau sekaligus. File yang sudah diupload dapat diganti dengan file baru.
                                                    </p>
                                                </div>

                                                <button type="submit" 
                                                        class="w-full px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg transition duration-200 shadow-md hover:shadow-lg">
                                                    <i class="fas fa-cloud-upload-alt mr-2"></i>
                                                    Upload Dokumen Paten
                                                </button>
                                            </form>

                                            {{-- Info about uploaded documents --}}
                                            @if($biodataPaten->patent_documents_uploaded_at)
                                            <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                                <p class="text-sm text-blue-800">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    Terakhir diupdate: <strong>{{ $biodataPaten->patent_documents_uploaded_at->translatedFormat('d F Y H:i') }}</strong>
                                                </p>
                                            </div>
                                            @endif
                                        </div>

                                        {{-- TAHAP TERAKHIR: Dokumen Permohonan Paten Terbit (after 3 required docs uploaded) --}}
                                        @if($biodataPaten->application_document)
                                            <div class="bg-green-50 border-2 border-green-300 rounded-lg p-4 mt-6">
                                                <div class="flex items-start">
                                                    <div class="flex-shrink-0">
                                                        <i class="fas fa-file-pdf text-3xl text-green-600"></i>
                                                    </div>
                                                    <div class="ml-4 flex-1">
                                                        <h5 class="font-semibold text-green-800 mb-2">
                                                            <i class="fas fa-check-double mr-1"></i>Dokumen Permohonan Paten Sudah Terbit
                                                        </h5>
                                                        <p class="text-sm text-green-700 mb-3">
                                                            Selamat! Dokumen permohonan paten Anda sudah terbit pada <strong>{{ $biodataPaten->document_issued_at->translatedFormat('d F Y, H:i') }} WITA</strong>.
                                                        </p>
                                                        <a href="{{ Storage::url($biodataPaten->application_document) }}" 
                                                           target="_blank"
                                                           class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition duration-200 shadow-sm hover:shadow-md">
                                                            <i class="fas fa-download mr-2"></i>Download Dokumen Permohonan (PDF)
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($biodataPaten->deskripsi_pdf && $biodataPaten->klaim_pdf && $biodataPaten->abstrak_pdf)
                                            {{-- 3 required docs uploaded, waiting for admin to issue document --}}
                                            <div class="bg-blue-50 border-2 border-blue-300 rounded-lg p-4 mt-6">
                                                <div class="flex items-start">
                                                    <div class="flex-shrink-0">
                                                        <i class="fas fa-hourglass-half text-2xl text-blue-600"></i>
                                                    </div>
                                                    <div class="ml-4">
                                                        <h5 class="font-semibold text-blue-800 mb-3">
                                                            <i class="fas fa-check-circle mr-1"></i>Menunggu Dokumen Permohonan Terbit
                                                        </h5>
                                                        <p class="text-sm text-blue-700 mb-3">
                                                            Anda telah mengupload 3 dokumen paten wajib (Deskripsi, Klaim, Abstrak).
                                                        </p>
                                                        <div class="bg-blue-100 border border-blue-200 rounded-lg p-3">
                                                            <p class="text-sm text-blue-800 font-medium mb-2">
                                                                <i class="fas fa-info-circle mr-1"></i>Langkah Selanjutnya:
                                                            </p>
                                                            <ul class="text-sm text-blue-700 space-y-1 ml-5 list-disc">
                                                                <li>Admin akan mereview dokumen paten Anda</li>
                                                                <li>Admin akan menerbitkan <strong>dokumen permohonan paten</strong></li>
                                                                <li>Anda akan dihubungi melalui WhatsApp jika sudah terbit</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        {{-- Biodata approved but document not submitted yet --}}
                                        <div class="bg-orange-50 border-2 border-orange-300 rounded-lg p-4">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-file-download text-2xl text-orange-600"></i>
                                                </div>
                                                <div class="ml-4">
                                                    <h5 class="font-semibold text-orange-800 mb-2">
                                                        <i class="fas fa-arrow-right mr-1"></i>Langkah Selanjutnya: Download & Setor Formulir
                                                    </h5>
                                                    <p class="text-sm text-orange-700 mb-2">
                                                        Biodata telah disetujui! Silakan klik <strong>"Lihat Detail"</strong> untuk mendownload formulir pendaftaran HKI dan melihat panduan penyetoran berkas ke kantor HKI Unhas.
                                                    </p>
                                                    <p class="text-sm text-orange-700">
                                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                                        <strong>Penting:</strong> Anda memiliki waktu 1 minggu (7 hari) untuk menyetor berkas.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @elseif($biodataPaten->status == 'pending')
                                    {{-- Biodata pending review --}}
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                        <div class="flex items-center mb-2">
                                            <i class="fas fa-clock text-yellow-500 mr-2"></i>
                                            <h5 class="font-semibold text-yellow-800">Biodata Sedang Direview</h5>
                                        </div>
                                        <p class="text-sm text-yellow-700">
                                            Biodata Anda sedang dalam proses review oleh admin. Silakan tunggu konfirmasi lebih lanjut.
                                        </p>
                                    </div>
                                @else
                                    {{-- Biodata denied - show error flags --}}
                                    @php 
                                        $biodataErrors = [];
                                        if($biodataPaten->error_tempat_invensi) $biodataErrors[] = 'Tempat Invensi';
                                        if($biodataPaten->error_tanggal_invensi) $biodataErrors[] = 'Tanggal Invensi';
                                        if($biodataPaten->error_uraian_singkat) $biodataErrors[] = 'Uraian Singkat';

                                        $memberErrors = [];
                                        foreach($biodataPaten->inventors as $index => $member) {
                                            $memberFieldErrors = [];
                                            if($member->error_name) $memberFieldErrors[] = 'Nama';
                                            if($member->error_nik) $memberFieldErrors[] = 'NIK';
                                            if($member->error_paspor) $memberFieldErrors[] = 'Nomor Paspor';
                                            if($member->error_negara_asal) $memberFieldErrors[] = 'Negara Asal';
                                            if($member->error_tempat_lahir) $memberFieldErrors[] = 'Tempat Lahir';
                                            if($member->error_tanggal_lahir) $memberFieldErrors[] = 'Tanggal Lahir';
                                            if($member->error_jenis_kelamin) $memberFieldErrors[] = 'Jenis Kelamin';
                                            if($member->error_pekerjaan) $memberFieldErrors[] = 'Pekerjaan';
                                            if($member->error_instansi) $memberFieldErrors[] = 'Instansi';
                                            if($member->error_alamat_instansi) $memberFieldErrors[] = 'Alamat Instansi';
                                            if($member->error_alamat) $memberFieldErrors[] = 'Alamat';
                                            if($member->error_kelurahan) $memberFieldErrors[] = 'Kelurahan';
                                            if($member->error_kecamatan) $memberFieldErrors[] = 'Kecamatan';
                                            if($member->error_kota_kabupaten) $memberFieldErrors[] = 'Kota/Kabupaten';
                                            if($member->error_provinsi) $memberFieldErrors[] = 'Provinsi';
                                            if($member->error_kode_pos) $memberFieldErrors[] = 'Kode Pos';
                                            if($member->error_email) $memberFieldErrors[] = 'Email';
                                            if($member->error_nomor_hp) $memberFieldErrors[] = 'Nomor HP';
                                            if($member->error_kewarganegaraan) $memberFieldErrors[] = 'Kewarganegaraan';
                                            if($member->error_negara_alamat) $memberFieldErrors[] = 'Negara (Alamat)';

                                            if(count($memberFieldErrors) > 0) {
                                                $memberErrors[] = [
                                                    'index' => $index + 1,
                                                    'name' => $member->name,
                                                    'fields' => $memberFieldErrors
                                                ];
                                            }
                                        }
                                    @endphp

                                    @if(count($biodataErrors) > 0 || count($memberErrors) > 0)
                                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                            <div class="flex items-center mb-3">
                                                <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                                                <h5 class="font-semibold text-red-800">Perbaikan Diperlukan</h5>
                                            </div>
                                            
                                            <p class="text-sm text-red-700 mb-4">
                                                Admin telah menandai beberapa field yang perlu diperbaiki. Silakan klik "Lihat Detail" untuk memperbaiki data.
                                            </p>

                                            @if(count($biodataErrors) > 0)
                                                <div class="mb-4">
                                                    <h6 class="font-medium text-red-800 mb-2">Kesalahan Biodata:</h6>
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach($biodataErrors as $error)
                                                            <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded">
                                                                <i class="fas fa-times-circle mr-1"></i>{{ $error }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            @if(count($memberErrors) > 0)
                                                <div>
                                                    <h6 class="font-medium text-red-800 mb-2">Kesalahan Data Inventor:</h6>
                                                    <div class="space-y-2">
                                                        @foreach($memberErrors as $memberError)
                                                            <div class="bg-red-100 rounded-lg p-3">
                                                                <p class="font-medium text-red-800 text-sm mb-1">
                                                                    Inventor {{ $memberError['index'] }}: {{ $memberError['name'] }}
                                                                </p>
                                                                <div class="flex flex-wrap gap-1">
                                                                    @foreach($memberError['fields'] as $field)
                                                                        <span class="inline-flex items-center px-2 py-0.5 bg-red-200 text-red-800 text-xs rounded">
                                                                            {{ $field }}
                                                                        </span>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @else
                        <!-- Biodata not created yet - show create biodata section -->
                        <div class="mt-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-lg overflow-hidden border border-blue-200">
                            <div class="p-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-3">
                                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                                <i class="fas fa-arrow-right text-blue-600 text-lg"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-xl font-semibold text-blue-900">Langkah Selanjutnya</h4>
                                                <p class="text-sm text-blue-700">Dokumen telah disetujui</p>
                                            </div>
                                        </div>
                                        <p class="text-blue-800 mb-4">
                                            Selamat! Dokumen Anda telah disetujui oleh admin. Silakan lengkapi biodata inventor untuk melanjutkan proses pengajuan HKI.
                                        </p>
                                        <div class="flex items-center text-sm text-blue-700">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            <span>Biodata diperlukan untuk penyelesaian proses HKI</span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex-shrink-0 ml-6">
                                        <a href="{{ route('user.biodata-paten.create', $submissionPaten) }}" 
                                           class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                                            <i class="fas fa-plus mr-2"></i>Buat Biodata
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                <!-- Submission Rejection Section -->
                @if($submissionPaten->status == 'rejected' && $submissionPaten->rejection_reason)
                    <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                            <h4 class="font-semibold text-red-800">Dokumen Ditolak</h4>
                        </div>
                        <p class="text-sm text-red-700">{{ $submissionPaten->rejection_reason }}</p>
                        
                        @if($submissionPaten->file_review_path)
                            <div class="mt-4 pt-4 border-t border-red-200">
                                <div class="flex items-center justify-between bg-white rounded-lg p-3 border border-red-300">
                                    <div class="flex items-center">
                                        <i class="fas fa-file-word text-blue-600 text-xl mr-3"></i>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">File Koreksi dari Admin</p>
                                            <p class="text-xs text-gray-600">{{ $submissionPaten->file_review_name ?? 'File Koreksi.docx' }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ Storage::disk('public')->url($submissionPaten->file_review_path) }}" 
                                       download
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200 shadow-sm hover:shadow-md">
                                        <i class="fas fa-download mr-2"></i>Download
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Revision Section -->
                @if($submissionPaten->status == SubmissionPaten::STATUS_REJECTED_FORMAT_REVIEW)
                <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <h4 class="text-sm font-semibold text-red-800 mb-3">
                        <i class="fas fa-redo mr-1"></i>Revisi Format Paten
                    </h4>
                    <p class="text-sm text-red-700 mb-4">Format dokumen ditolak oleh admin. Silakan perbaiki dan upload ulang dokumen yang telah diperbaiki.</p>
                    
                    <form method="POST" action="{{ route('user.submissions-paten.resubmit', $submissionPaten) }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label for="judul_paten" class="block text-sm font-medium text-gray-700 mb-1">Judul Karya (dapat diubah)</label>
                            <input 
                                type="text" 
                                id="judul_paten" 
                                name="judul_paten" 
                                value="{{ old('judul_paten', $submissionPaten->judul_paten) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                required
                            >
                        </div>

                        <div>
                            <label for="kategori_paten" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <select 
                                id="kategori_paten" 
                                name="kategori_paten"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                required
                            >
                                <option value="Paten" {{ old('kategori_paten', $submissionPaten->kategori_paten) == 'Paten' ? 'selected' : '' }}>Paten</option>
                                <option value="Paten Sederhana" {{ old('kategori_paten', $submissionPaten->kategori_paten) == 'Paten Sederhana' ? 'selected' : '' }}>Paten Sederhana</option>
                            </select>
                        </div>

                        <div>
                            <label for="creator_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Inventor Pertama</label>
                            <input 
                                type="text" 
                                id="creator_name" 
                                name="creator_name" 
                                value="{{ old('creator_name', $submissionPaten->creator_name) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                required
                            >
                        </div>

                        <div>
                            <label for="creator_whatsapp" class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp Inventor Pertama</label>
                            <div class="grid grid-cols-3 gap-2">
                                <div class="col-span-1">
                                    <select 
                                        id="creator_country_code" 
                                        name="creator_country_code"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm"
                                        required
                                    >
                                        @foreach(getCountryCodes() as $code => $label)
                                            <option value="{{ $code }}" {{ old('creator_country_code', $submissionPaten->creator_country_code ?? '+62') == $code ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-span-2">
                                    <input 
                                        type="text" 
                                        id="creator_whatsapp" 
                                        name="creator_whatsapp" 
                                        value="{{ old('creator_whatsapp', $submissionPaten->creator_whatsapp) }}"
                                        placeholder="081234567890"
                                        pattern="^0[0-9]{8,13}$"
                                        title="Nomor harus dimulai dengan 0 dan berisi 9-14 digit"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        required
                                    >
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Masukkan nomor dengan format 0xxxxxxxx</p>
                        </div>

                        <div>
                            <label for="document" class="block text-sm font-medium text-gray-700 mb-1">Dokumen DOCX Baru</label>
                            <input 
                                type="file" 
                                id="document" 
                                name="document" 
                                accept=".docx,.doc"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                required
                            >
                            <p class="text-xs text-gray-500 mt-1">Upload file DOCX yang sudah diperbaiki (Maks. 5MB)</p>
                        </div>

                        <button 
                            type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200"
                        >
                            <i class="fas fa-upload mr-2"></i>Upload Ulang
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>

        <!-- Riwayat Review -->
        @include('user.submissions-paten.partials.history-section')

        <!-- Floating Next Button -->
        @if($submissionPaten->status == SubmissionPaten::STATUS_APPROVED_SUBSTANCE && !$submissionPaten->biodataPaten)
        <div class="fixed bottom-6 right-6 z-50">
            <a href="{{ route('user.biodata-paten.create', $submissionPaten) }}" 
               class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-full shadow-lg hover:shadow-xl transition duration-200 transform hover:scale-105">
                <span class="mr-2">Next</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        @endif
    </main>
</body>
</html>
