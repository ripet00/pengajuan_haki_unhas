<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Biodata Paten - HKI Unhas</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
        }
        .status-pending { @apply bg-yellow-100 text-yellow-800 border-yellow-200; }
        .status-approved { @apply bg-green-100 text-green-800 border-green-200; }
        .status-denied { @apply bg-red-100 text-red-800 border-red-200; }
        
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

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('user.submissions-paten.show', $submissionPaten) }}" class="inline-flex items-center px-5 py-3 bg-white hover:bg-gray-50 text-gray-800 hover:text-gray-900 border-2 border-gray-500 hover:border-gray-700 rounded-lg font-bold transition duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Detail Submission
            </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-600 text-xl mr-3"></i>
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Header Section -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900">
                            <i class="fas fa-lightbulb mr-3 text-yellow-600"></i>
                            Detail Biodata Paten
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">
                            Submission: <strong>{{ $submissionPaten->title }}</strong> (ID: #{{ $submissionPaten->id }})
                        </p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <!-- Status Badge -->
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border
                            @if($biodataPaten->status == 'pending') 
                                bg-yellow-100 text-yellow-800 border-yellow-200
                            @elseif($biodataPaten->status == 'approved') 
                                bg-green-100 text-green-800 border-green-200
                            @elseif($biodataPaten->status == 'denied') 
                                bg-red-100 text-red-800 border-red-200
                            @endif
                        ">
                            @if($biodataPaten->status == 'pending')
                                <i class="fas fa-clock mr-1"></i>Menunggu Review
                            @elseif($biodataPaten->status == 'approved')
                                <i class="fas fa-check mr-1"></i>Disetujui
                            @elseif($biodataPaten->status == 'denied')
                                <i class="fas fa-times mr-1"></i>Ditolak
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Status Info -->
            <div class="px-6 py-4">
                @if($biodataPaten->status == 'denied' && $biodataPaten->rejection_reason)
                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                        <h4 class="text-sm font-semibold text-red-800 mb-2">
                            <i class="fas fa-times-circle mr-1"></i>Biodata Ditolak
                        </h4>
                        <p class="text-sm text-red-700">{{ $biodataPaten->rejection_reason }}</p>
                        <p class="text-sm text-red-600 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Anda dapat mengedit biodata untuk memperbaiki masalah ini.
                        </p>
                    </div>
                @elseif($biodataPaten->status == 'approved')
                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                        <h4 class="text-sm font-semibold text-green-800 mb-2">
                            <i class="fas fa-check-circle mr-1"></i>Biodata Disetujui
                        </h4>
                        <p class="text-sm text-green-700">
                            Biodata paten telah disetujui oleh admin. Silakan ikuti langkah berikutnya untuk menyelesaikan proses pengajuan paten.
                        </p>
                        @if($biodataPaten->reviewed_at && $biodataPaten->reviewedBy)
                            <p class="text-sm text-green-600 mt-2">
                                Disetujui oleh: {{ $biodataPaten->reviewedBy->name }} pada {{ $biodataPaten->reviewed_at->format('d M Y H:i') }}
                            </p>
                        @endif
                    </div>
                @else
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <h4 class="text-sm font-semibold text-yellow-800 mb-2">
                            <i class="fas fa-clock mr-1"></i>Menunggu Review Admin
                        </h4>
                        <p class="text-sm text-yellow-700">
                            Biodata sedang dalam proses review oleh admin. Mohon tunggu konfirmasi lebih lanjut.
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Biodata Information -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Informasi Invensi</h3>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Judul Invensi</h4>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $submissionPaten->title }}</p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Tempat Invensi</h4>
                        <p class="mt-1 text-lg text-gray-900">{{ $biodataPaten->tempat_invensi }}</p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Tanggal Invensi</h4>
                        <p class="mt-1 text-lg text-gray-900">{{ $biodataPaten->tanggal_invensi->format('d F Y') }}</p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Dibuat Pada</h4>
                        <p class="mt-1 text-lg text-gray-900">{{ $biodataPaten->created_at->format('d F Y H:i') }}</p>
                    </div>
                </div>
                
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Uraian Singkat Invensi</h4>
                    <p class="mt-2 text-gray-900 leading-relaxed">{{ $biodataPaten->uraian_singkat }}</p>
                </div>
            </div>
        </div>

        <!-- Inventors Information -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    Data Inventor ({{ $biodataPaten->inventors->count() }} Orang)
                </h3>
            </div>
            
            <div class="divide-y divide-gray-200">
                @foreach($biodataPaten->inventors as $index => $inventor)
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <h4 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-user-tie mr-2 text-yellow-600"></i>
                                Inventor ke-{{ $index + 1 }}
                            </h4>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Personal Information -->
                            <div class="space-y-3">
                                <h5 class="font-medium text-gray-800 border-b border-gray-200 pb-1">Informasi Personal</h5>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Nama Lengkap:</span>
                                    <p class="text-gray-900">{{ $inventor->name }}</p>
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Kewarganegaraan:</span>
                                    <p class="text-gray-900">{{ $inventor->kewarganegaraan == 'WNI' ? 'WNI - Warga Negara Indonesia' : 'WNA - Warga Negara Asing' }}</p>
                                </div>
                                
                                @if($inventor->kewarganegaraan == 'WNI')
                                    @if($inventor->nik)
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">NIK:</span>
                                            <p class="text-gray-900">{{ $inventor->nik }}</p>
                                        </div>
                                    @endif
                                @else
                                    @if($inventor->paspor)
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Nomor Paspor:</span>
                                            <p class="text-gray-900">{{ $inventor->paspor }}</p>
                                        </div>
                                    @endif
                                    
                                    @if($inventor->negara_asal)
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Negara Asal:</span>
                                            <p class="text-gray-900">{{ $inventor->negara_asal }}</p>
                                        </div>
                                    @endif
                                @endif
                                
                                @if($inventor->tempat_lahir)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Tempat Lahir:</span>
                                        <p class="text-gray-900">{{ $inventor->tempat_lahir }}</p>
                                    </div>
                                @endif
                                
                                @if($inventor->tanggal_lahir)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Tanggal Lahir:</span>
                                        <p class="text-gray-900">{{ $inventor->tanggal_lahir->format('d F Y') }}</p>
                                    </div>
                                @endif
                                
                                @if($inventor->jenis_kelamin)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Jenis Kelamin:</span>
                                        <p class="text-gray-900">{{ $inventor->jenis_kelamin }}</p>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Professional Information -->
                            <div class="space-y-3">
                                <h5 class="font-medium text-gray-800 border-b border-gray-200 pb-1">Informasi Profesi</h5>
                                
                                @if($inventor->pekerjaan)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Pekerjaan:</span>
                                        <p class="text-gray-900">{{ $inventor->pekerjaan }}</p>
                                    </div>
                                @endif
                                
                                @if($inventor->instansi)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Instansi:</span>
                                        <p class="text-gray-900">{{ $inventor->instansi }}</p>
                                    </div>
                                @endif
                                
                                @if($inventor->alamat_instansi)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Alamat Instansi:</span>
                                        <p class="text-gray-900">{{ $inventor->alamat_instansi }}</p>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Contact & Address Information -->
                            <div class="space-y-3">
                                <h5 class="font-medium text-gray-800 border-b border-gray-200 pb-1">Kontak & Alamat</h5>
                                
                                @if($inventor->email)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Email:</span>
                                        <p class="text-gray-900">{{ $inventor->email }}</p>
                                    </div>
                                @endif
                                
                                @if($inventor->nomor_hp)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Nomor HP:</span>
                                        <p class="text-gray-900">{{ $inventor->nomor_hp }}</p>
                                    </div>
                                @endif
                                
                                @if($inventor->alamat)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Alamat:</span>
                                        <p class="text-gray-900">{{ $inventor->alamat }}</p>
                                    </div>
                                @endif
                                
                                @if($inventor->kewarganegaraan == 'WNI')
                                    @if($inventor->kelurahan || $inventor->kecamatan || $inventor->kota_kabupaten || $inventor->provinsi || $inventor->kode_pos)
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Wilayah:</span>
                                            <p class="text-gray-900">
                                                @php
                                                    $alamatParts = [];
                                                    if($inventor->kelurahan) $alamatParts[] = "Kel. " . $inventor->kelurahan;
                                                    if($inventor->kecamatan) $alamatParts[] = "Kec. " . $inventor->kecamatan;
                                                    if($inventor->kota_kabupaten) $alamatParts[] = $inventor->kota_kabupaten;
                                                    if($inventor->provinsi) $alamatParts[] = $inventor->provinsi;
                                                    if($inventor->kode_pos) $alamatParts[] = $inventor->kode_pos;
                                                @endphp
                                                {{ implode(', ', $alamatParts) }}
                                            </p>
                                        </div>
                                    @endif
                                @else
                                    @if($inventor->negara_alamat)
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Negara:</span>
                                            <p class="text-gray-900">{{ $inventor->negara_alamat }}</p>
                                        </div>
                                    @endif
                                    
                                    @if($inventor->kode_pos)
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Kode Pos:</span>
                                            <p class="text-gray-900">{{ $inventor->kode_pos }}</p>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex justify-center space-x-4">
            <!-- Edit Biodata Button - Only show when status is denied -->
            @if($biodataPaten->status === 'denied')
                <a href="{{ route('user.biodata-paten.create', $submissionPaten) }}" 
                   class="inline-flex items-center px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition duration-200">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Biodata
                </a>
            @endif
            
            <a href="{{ route('user.submissions-paten.show', $submissionPaten) }}" 
               class="inline-flex items-center px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Submission
            </a>
        </div>
    </main>
</body>
</html>
