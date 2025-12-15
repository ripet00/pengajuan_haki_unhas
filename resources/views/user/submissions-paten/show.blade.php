<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengajuan Paten - HKI Unhas</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        }
        
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
        
        .header-icon {
            color: #ffffff !important;
            filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.3));
        }
    </style>
</head>
<body class="bg-gray-100">
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

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <ul class="text-sm text-red-700 list-disc list-inside">
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
                <i class="fas fa-route mr-2 text-blue-600"></i>Progress Pengajuan HKI
            </h3>
            
            <div class="flex items-center justify-between relative">
                <!-- Progress Line -->
                <div class="absolute top-5 left-0 right-0 h-1 bg-gray-200 rounded-full z-0">
                    <div class="h-full bg-gradient-to-r from-blue-500 to-green-500 rounded-full transition-all duration-500" 
                         style="width: {{ $submissionPaten->biodata_status == 'approved' ? '100%' : ($submissionPaten->status == 'approved' ? '66%' : ($submissionPaten->status == 'rejected' ? '33%' : '33%')) }}"></div>
                </div>

                <!-- Step 1: Upload Dokumen -->
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 {{ $submissionPaten->created_at ? 'bg-green-500 border-green-500' : 'bg-gray-200 border-gray-300' }}">
                        <i class="fas fa-upload {{ $submissionPaten->created_at ? 'text-white' : 'text-gray-400' }}"></i>
                    </div>
                    <span class="mt-2 text-xs text-center font-medium {{ $submissionPaten->created_at ? 'text-green-600' : 'text-gray-400' }}">
                        Upload<br>Paten
                    </span>
                </div>

                <!-- Step 2: Review Dokumen -->
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 {{ $submissionPaten->status == 'approved' ? 'bg-green-500 border-green-500' : ($submissionPaten->status == 'rejected' ? 'bg-red-500 border-red-500' : ($submissionPaten->status == 'pending' ? 'bg-yellow-500 border-yellow-500' : 'bg-gray-200 border-gray-300')) }}">
                        @if($submissionPaten->status == 'approved')
                            <i class="fas fa-check text-white"></i>
                        @elseif($submissionPaten->status == 'rejected')
                            <i class="fas fa-times text-white"></i>
                        @elseif($submissionPaten->status == 'pending')
                            <i class="fas fa-clock text-white"></i>
                        @else
                            <i class="fas fa-gavel text-gray-400"></i>
                        @endif
                    </div>
                    <span class="mt-2 text-xs text-center font-medium {{ $submissionPaten->status == 'approved' ? 'text-green-600' : ($submissionPaten->status == 'rejected' ? 'text-red-600' : ($submissionPaten->status == 'pending' ? 'text-yellow-600' : 'text-gray-400')) }}">
                        Review<br>Paten
                    </span>
                </div>

                <!-- Step 3: Upload Biodata -->
                <div class="relative z-10 flex flex-col items-center">
                    {{-- normalize checks to accept both 'rejected' and 'denied' values for biodata_status --}}
                    @php
                        $bStatus = $submissionPaten->biodata_status;
                        $isBRejected = $bStatus === 'rejected' || $bStatus === 'denied';
                    @endphp
                    <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 {{ $bStatus == 'approved' || $isBRejected || $bStatus == 'pending' ? ($bStatus == 'approved' ? 'bg-green-500 border-green-500' : ($isBRejected ? 'bg-red-500 border-red-500' : 'bg-yellow-500 border-yellow-500')) : ($submissionPaten->status == 'approved' ? 'bg-blue-500 border-blue-500' : 'bg-gray-200 border-gray-300') }}">
                        @if($bStatus == 'approved')
                            <i class="fas fa-check text-white"></i>
                        @elseif($isBRejected)
                            <i class="fas fa-times text-white"></i>
                        @elseif($bStatus == 'pending')
                            <i class="fas fa-clock text-white"></i>
                        @elseif($submissionPaten->status == 'approved')
                            <i class="fas fa-user-plus text-white"></i>
                        @else
                            <i class="fas fa-user-plus text-gray-400"></i>
                        @endif
                    </div>
                    <span class="mt-2 text-xs text-center font-medium {{ $bStatus == 'approved' ? 'text-green-600' : ($isBRejected ? 'text-red-600' : ($bStatus == 'pending' ? 'text-yellow-600' : ($submissionPaten->status == 'approved' ? 'text-blue-600' : 'text-gray-400'))) }}">
                        Upload<br>Biodata
                    </span>
                </div>

                <!-- Step 4: Selesai -->
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 {{ $submissionPaten->biodata_status == 'approved' ? 'bg-green-500 border-green-500' : 'bg-gray-200 border-gray-300' }}">
                        <i class="fas fa-certificate {{ $submissionPaten->biodata_status == 'approved' ? 'text-white' : 'text-gray-400' }}"></i>
                    </div>
                    <span class="mt-2 text-xs text-center font-medium {{ $submissionPaten->biodata_status == 'approved' ? 'text-green-600' : 'text-gray-400' }}">
                        Selesai
                    </span>
                </div>
            </div>

            <!-- Progress Description -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                @php
                    $bStatus = $submissionPaten->biodata_status;
                    $isBRejected = $bStatus === 'rejected' || $bStatus === 'denied';
                @endphp
                @if($bStatus == 'approved')
                    <p class="text-sm text-green-700 font-medium">
                        <i class="fas fa-check-circle mr-2"></i>Pengajuan Paten Anda telah selesai diproses!
                    </p>
                @elseif($isBRejected)
                    <p class="text-sm text-red-700 font-medium">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Biodata ditolak. Silakan upload ulang biodata yang sesuai.
                    </p>
                @elseif($bStatus == 'pending')
                    <p class="text-sm text-yellow-700 font-medium">
                        <i class="fas fa-clock mr-2"></i>Biodata sedang direview oleh admin.
                    </p>
                @elseif($submissionPaten->status == 'approved')
                    <a href="{{ route('user.biodata-paten.create', $submissionPaten) }}" class="text-sm text-blue-700 font-medium hover:text-blue-800 transition duration-200">
                        <i class="fas fa-arrow-right mr-2"></i>Dokumen disetujui! Silakan upload biodata untuk melanjutkan.
                    </a>
                @elseif($submissionPaten->status == 'rejected')
                    <p class="text-sm text-red-700 font-medium">
                        <i class="fas fa-times-circle mr-2"></i>Paten ditolak. Perbaiki dan upload ulang Paten.
                    </p>
                @else
                    <p class="text-sm text-yellow-700 font-medium">
                        <i class="fas fa-hourglass-half mr-2"></i>Paten sedang direview oleh admin.
                    </p>
                @endif
            </div>
        </div>

        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('user.submissions-paten.index') }}" class="inline-flex items-center px-5 py-3 bg-white hover:bg-gray-50 text-gray-800 hover:text-gray-900 border-2 border-gray-500 hover:border-gray-700 rounded-lg font-bold transition duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Daftar Pengajuan Paten
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
                            <label class="block text-sm font-medium text-gray-500 mb-1">Judul Paten</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $submissionPaten->judul_paten }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Kategori Paten</label>
                            <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full 
                                {{ $submissionPaten->kategori_paten == 'Paten' ? 'bg-green-100 text-green-800' : 'bg-emerald-100 text-emerald-800' }}">
                                <i class="fas fa-{{ $submissionPaten->kategori_paten == 'Paten' ? 'certificate' : 'award' }} mr-1"></i>
                                {{ $submissionPaten->kategori_paten }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                            @if($submissionPaten->status == 'pending')
                                <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>Menunggu Review
                                </span>
                            @elseif($submissionPaten->status == 'approved')
                                <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Disetujui
                                </span>
                            @else
                                <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>Ditolak
                                </span>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Pengajuan</label>
                            <p class="text-gray-900">{{ $submissionPaten->created_at->format('d F Y, H:i') }} WITA</p>
                        </div>

                        @if($submissionPaten->reviewed_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Review</label>
                            <p class="text-gray-900">{{ $submissionPaten->reviewed_at->format('d F Y, H:i') }} WITA</p>
                        </div>
                        @endif

                        @if($submissionPaten->reviewedByAdmin)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Direview oleh</label>
                            <p class="text-gray-900">{{ $submissionPaten->reviewedByAdmin->name }}</p>
                        </div>
                        @endif

                        <!-- Inventor Information -->
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
                            <p class="text-gray-900">{{ $submissionPaten->file_name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Ukuran File</label>
                            <p class="text-gray-900">{{ number_format($submissionPaten->file_size / 1024 / 1024, 2) }} MB</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Jenis File</label>
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-file-pdf mr-1"></i>PDF
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-3">Aksi File</label>
                            <div class="space-y-2">
                                <a href="{{ asset('storage/' . $submissionPaten->file_path) }}" 
                                   target="_blank"
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                    <i class="fas fa-eye mr-2"></i>Download File Docx
                                </a>
                                <a href="{{ asset('storage/' . $submissionPaten->file_path) }}" 
                                   download="{{ $submissionPaten->file_name }}"
                                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition duration-200 ml-2">
                                    <i class="fas fa-download mr-2"></i>Download
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submission Rejection Section -->
                @if($submissionPaten->status == 'rejected' && $submissionPaten->rejection_reason)
                    <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                            <h4 class="font-semibold text-red-800">Dokumen Ditolak</h4>
                        </div>
                        <p class="text-sm text-red-700">{{ $submissionPaten->rejection_reason }}</p>
                    </div>
                @endif

                <!-- Revision Section -->
                @if($submissionPaten->status == 'rejected')
                <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <h4 class="text-sm font-semibold text-yellow-800 mb-3">
                        <i class="fas fa-redo mr-1"></i>Revisi Pengajuan
                    </h4>
                    <p class="text-sm text-yellow-700 mb-4">Anda dapat mengunggah ulang dokumen yang telah diperbaiki.</p>
                    
                    <form method="POST" action="{{ route('user.submissions-paten.resubmit', $submissionPaten) }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label for="judul_paten" class="block text-sm font-medium text-gray-700 mb-1">Judul Paten (dapat diubah)</label>
                            <input 
                                type="text" 
                                id="judul_paten" 
                                name="judul_paten" 
                                value="{{ old('judul_paten', $submissionPaten->judul_paten) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                required
                            >
                        </div>

                        <div>
                            <label for="kategori_paten" class="block text-sm font-medium text-gray-700 mb-1">Kategori Paten</label>
                            <select 
                                id="kategori_paten" 
                                name="kategori_paten"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
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
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
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
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm"
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
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                        required
                                    >
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Masukkan nomor dengan format 0xxxxxxxx</p>
                        </div>

                        <div>
                            <label for="document" class="block text-sm font-medium text-gray-700 mb-1">Dokumen PDF Baru</label>
                            <input 
                                type="file" 
                                id="document" 
                                name="document" 
                                accept=".pdf"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                required
                            >
                            <p class="text-xs text-gray-500 mt-1">Format: PDF, Maksimal 20MB</p>
                        </div>

                        <button 
                            type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200"
                        >
                            <i class="fas fa-upload mr-2"></i>Upload Ulang
                        </button>
                    </form>
                </div>
                @endif

                <!-- Biodata Section -->
                @if($submissionPaten->status == 'approved')
                    @if($submissionPaten->biodataPaten)
                        <!-- Biodata exists - show status -->
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-user-tie mr-2 text-yellow-600"></i>Biodata Inventor
                            </h3>
                            
                            <div class="bg-white rounded-lg border border-gray-200 p-4">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        @if($submissionPaten->biodataPaten->status == 'pending')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 mb-3">
                                                <i class="fas fa-clock mr-1"></i>Sedang Direview
                                            </span>
                                            <p class="text-sm text-gray-600">
                                                Biodata inventor sedang dalam proses review oleh admin. Mohon tunggu konfirmasi lebih lanjut.
                                            </p>
                                        @elseif($submissionPaten->biodataPaten->status == 'approved')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 mb-3">
                                                <i class="fas fa-check mr-1"></i>Disetujui
                                            </span>
                                            <p class="text-sm text-gray-600">
                                                Biodata inventor telah disetujui. Silakan ikuti langkah selanjutnya.
                                            </p>
                                        @elseif($submissionPaten->biodataPaten->status == 'denied')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 mb-3">
                                                <i class="fas fa-times mr-1"></i>Ditolak
                                            </span>
                                            <p class="text-sm text-red-600 mb-3">
                                                Biodata ditolak. Silakan perbaiki data sesuai petunjuk admin.
                                            </p>
                                            
                                            @if($submissionPaten->biodataPaten->rejection_reason)
                                                <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                                                    <h5 class="font-semibold text-red-800 text-sm mb-1">Alasan Penolakan:</h5>
                                                    <p class="text-sm text-red-700">{{ $submissionPaten->biodataPaten->rejection_reason }}</p>
                                                </div>
                                            @endif

                                            {{-- Biodata denied - show error flags --}}
                                            @php 
                                                $biodataErrors = [];
                                                if($submissionPaten->biodataPaten->error_tempat_invensi) $biodataErrors[] = 'Tempat Invensi';
                                                if($submissionPaten->biodataPaten->error_tanggal_invensi) $biodataErrors[] = 'Tanggal Invensi';
                                                if($submissionPaten->biodataPaten->error_uraian_singkat) $biodataErrors[] = 'Uraian Singkat';

                                                $inventorErrors = [];
                                                foreach($submissionPaten->biodataPaten->inventors as $index => $inventor) {
                                                    $inventorFieldErrors = [];
                                                    if($inventor->error_name) $inventorFieldErrors[] = 'Nama';
                                                    if($inventor->error_nik) $inventorFieldErrors[] = 'NIK';
                                                    if($inventor->error_paspor) $inventorFieldErrors[] = 'Nomor Paspor';
                                                    if($inventor->error_negara_asal) $inventorFieldErrors[] = 'Negara Asal';
                                                    if($inventor->error_tempat_lahir) $inventorFieldErrors[] = 'Tempat Lahir';
                                                    if($inventor->error_tanggal_lahir) $inventorFieldErrors[] = 'Tanggal Lahir';
                                                    if($inventor->error_jenis_kelamin) $inventorFieldErrors[] = 'Jenis Kelamin';
                                                    if($inventor->error_pekerjaan) $inventorFieldErrors[] = 'Pekerjaan';
                                                    if($inventor->error_instansi) $inventorFieldErrors[] = 'Instansi';
                                                    if($inventor->error_alamat_instansi) $inventorFieldErrors[] = 'Alamat Instansi';
                                                    if($inventor->error_alamat) $inventorFieldErrors[] = 'Alamat';
                                                    if($inventor->error_kelurahan) $inventorFieldErrors[] = 'Kelurahan';
                                                    if($inventor->error_kecamatan) $inventorFieldErrors[] = 'Kecamatan';
                                                    if($inventor->error_kota_kabupaten) $inventorFieldErrors[] = 'Kota/Kabupaten';
                                                    if($inventor->error_provinsi) $inventorFieldErrors[] = 'Provinsi';
                                                    if($inventor->error_kode_pos) $inventorFieldErrors[] = 'Kode Pos';
                                                    if($inventor->error_email) $inventorFieldErrors[] = 'Email';
                                                    if($inventor->error_nomor_hp) $inventorFieldErrors[] = 'Nomor HP';
                                                    if($inventor->error_kewarganegaraan) $inventorFieldErrors[] = 'Kewarganegaraan';
                                                    if($inventor->error_negara_alamat) $inventorFieldErrors[] = 'Negara (Alamat)';

                                                    if(count($inventorFieldErrors) > 0) {
                                                        $inventorErrors[] = [
                                                            'index' => $index + 1,
                                                            'name' => $inventor->name,
                                                            'fields' => $inventorFieldErrors
                                                        ];
                                                    }
                                                }
                                            @endphp

                                            @if(count($biodataErrors) > 0 || count($inventorErrors) > 0)
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

                                                    @if(count($inventorErrors) > 0)
                                                        <div>
                                                            <h6 class="font-medium text-red-800 mb-2">Kesalahan Data Inventor:</h6>
                                                            <div class="space-y-2">
                                                                @foreach($inventorErrors as $inventorError)
                                                                    <div class="bg-red-100 rounded-lg p-3">
                                                                        <p class="font-medium text-red-800 text-sm mb-1">
                                                                            Inventor {{ $inventorError['index'] }}: {{ $inventorError['name'] }}
                                                                        </p>
                                                                        <div class="flex flex-wrap gap-1">
                                                                            @foreach($inventorError['fields'] as $field)
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
                                    
                                    <div class="ml-4">
                                        <a href="{{ route('user.biodata-paten.show', [$submissionPaten, $submissionPaten->biodataPaten]) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                            <i class="fas fa-eye mr-2"></i>Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Biodata not created yet - show create biodata section -->
                        <div class="mt-6 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl shadow-lg overflow-hidden border border-green-200">
                            <div class="p-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-3">
                                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                                <i class="fas fa-arrow-right text-green-600 text-lg"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-xl font-semibold text-green-900">Langkah Selanjutnya</h4>
                                                <p class="text-sm text-green-700">Dokumen telah disetujui</p>
                                            </div>
                                        </div>
                                        <p class="text-green-800 mb-4">
                                            Selamat! Dokumen paten Anda telah disetujui oleh admin. Silakan lengkapi biodata inventor untuk melanjutkan proses pengajuan paten.
                                        </p>
                                        <div class="flex items-center text-sm text-green-700">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            <span>Biodata diperlukan untuk penyelesaian proses paten</span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex-shrink-0 ml-6">
                                        <a href="{{ route('user.biodata-paten.create', $submissionPaten) }}" 
                                           class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
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
                    </div>
                @endif
            </div>
        </div>
    </main>
</body>
</html>
