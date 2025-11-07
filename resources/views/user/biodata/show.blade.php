<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Biodata - HKI Unhas</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
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
                        <p class="text-red-100 text-xs sm:text-sm">Universitas Hasanuddin</p>
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
            <a href="{{ route('user.submissions.show', $biodata->submission) }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Detail Submission
            </a>
        </div>

        <!-- Header Section -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900">
                            <i class="fas fa-user-friends mr-3 text-blue-600"></i>
                            Detail Biodata Karya Cipta
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">
                            Submission: <strong>{{ $biodata->submission->title }}</strong> (ID: #{{ $biodata->submission->id }})
                        </p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <!-- Status Badge -->
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border status-{{ $biodata->status }}">
                            @if($biodata->status == 'pending')
                                <i class="fas fa-clock mr-1"></i>Menunggu Review
                            @elseif($biodata->status == 'approved')
                                <i class="fas fa-check-circle mr-1"></i>Disetujui
                            @elseif($biodata->status == 'denied')
                                <i class="fas fa-times-circle mr-1"></i>Ditolak
                            @endif
                        </span>
                        
                        <!-- Edit Button -->
                        @if($biodata->canBeEdited())
                            <a href="{{ route('user.biodata.create', $biodata->submission) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                <i class="fas fa-edit mr-2"></i>Edit Biodata
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Status Info -->
            <div class="px-6 py-4">
                @if($biodata->status == 'denied' && $biodata->rejection_reason)
                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                        <h4 class="text-sm font-semibold text-red-800 mb-2">
                            <i class="fas fa-exclamation-triangle mr-1"></i>Alasan Penolakan
                        </h4>
                        <p class="text-sm text-red-700">{{ $biodata->rejection_reason }}</p>
                        <p class="text-sm text-red-600 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Anda dapat mengedit biodata untuk memperbaiki masalah ini.
                        </p>
                    </div>
                @elseif($biodata->status == 'approved')
                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                        <h4 class="text-sm font-semibold text-green-800 mb-2">
                            <i class="fas fa-check-circle mr-1"></i>Biodata Disetujui
                        </h4>
                        <p class="text-sm text-green-700">
                            Biodata telah disetujui oleh admin. Proses pengajuan HKI dapat dilanjutkan.
                        </p>
                        @if($biodata->reviewed_at && $biodata->reviewedBy)
                            <p class="text-sm text-green-600 mt-2">
                                Disetujui oleh: {{ $biodata->reviewedBy->name }} pada {{ $biodata->reviewed_at->format('d M Y H:i') }}
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
                <h3 class="text-lg font-semibold text-gray-900">Informasi Karya Cipta</h3>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Judul Karya Cipta</h4>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $biodata->submission->title }}</p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Tempat Ciptaan</h4>
                        <p class="mt-1 text-lg text-gray-900">{{ $biodata->tempat_ciptaan }}</p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Tanggal Ciptaan</h4>
                        <p class="mt-1 text-lg text-gray-900">{{ $biodata->tanggal_ciptaan->format('d F Y') }}</p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Dibuat Pada</h4>
                        <p class="mt-1 text-lg text-gray-900">{{ $biodata->created_at->format('d F Y H:i') }}</p>
                    </div>
                </div>
                
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Uraian Singkat Karya Cipta</h4>
                    <p class="mt-2 text-gray-900 leading-relaxed">{{ $biodata->uraian_singkat }}</p>
                </div>
            </div>
        </div>

        <!-- Members Information -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    Data Pencipta ({{ $biodata->members->count() }} Orang)
                </h3>
            </div>
            
            <div class="divide-y divide-gray-200">
                @foreach($biodata->members as $index => $member)
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <h4 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-user mr-2 text-blue-600"></i>
                                Pencipta ke-{{ $index + 1 }} {{ $member->is_leader ? '(Ketua)' : '' }}
                            </h4>
                            @if($member->is_leader)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-crown mr-1"></i>Ketua
                                </span>
                            @endif
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Personal Information -->
                            <div class="space-y-3">
                                <h5 class="font-medium text-gray-800 border-b border-gray-200 pb-1">Informasi Personal</h5>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Nama Lengkap:</span>
                                    <p class="text-gray-900">{{ $member->name }}</p>
                                </div>
                                
                                @if($member->nik)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">NIK:</span>
                                        <p class="text-gray-900">{{ $member->nik }}</p>
                                    </div>
                                @endif
                                
                                @if($member->pekerjaan)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Pekerjaan:</span>
                                        <p class="text-gray-900">{{ $member->pekerjaan }}</p>
                                    </div>
                                @endif
                                
                                @if($member->kewarganegaraan)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Kewarganegaraan:</span>
                                        <p class="text-gray-900">{{ $member->kewarganegaraan }}</p>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Academic Information -->
                            <div class="space-y-3">
                                <h5 class="font-medium text-gray-800 border-b border-gray-200 pb-1">Informasi Akademik</h5>
                                
                                @if($member->universitas)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Universitas:</span>
                                        <p class="text-gray-900">{{ $member->universitas }}</p>
                                    </div>
                                @endif
                                
                                @if($member->fakultas)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Fakultas:</span>
                                        <p class="text-gray-900">{{ $member->fakultas }}</p>
                                    </div>
                                @endif
                                
                                @if($member->program_studi)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Program Studi:</span>
                                        <p class="text-gray-900">{{ $member->program_studi }}</p>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Contact & Address Information -->
                            <div class="space-y-3">
                                <h5 class="font-medium text-gray-800 border-b border-gray-200 pb-1">Kontak & Alamat</h5>
                                
                                @if($member->email)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Email:</span>
                                        <p class="text-gray-900">{{ $member->email }}</p>
                                    </div>
                                @endif
                                
                                @if($member->nomor_hp)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Nomor HP:</span>
                                        <p class="text-gray-900">{{ $member->nomor_hp }}</p>
                                    </div>
                                @endif
                                
                                @if($member->alamat)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Alamat:</span>
                                        <p class="text-gray-900">{{ $member->alamat }}</p>
                                    </div>
                                @endif
                                
                                @if($member->kelurahan || $member->kecamatan || $member->kota_kabupaten || $member->provinsi || $member->kode_pos)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Wilayah:</span>
                                        <p class="text-gray-900">
                                            @php
                                                $alamatParts = [];
                                                if($member->kelurahan) $alamatParts[] = "Kel. " . $member->kelurahan;
                                                if($member->kecamatan) $alamatParts[] = "Kec. " . $member->kecamatan;
                                                if($member->kota_kabupaten) $alamatParts[] = $member->kota_kabupaten;
                                                if($member->provinsi) $alamatParts[] = $member->provinsi;
                                                if($member->kode_pos) $alamatParts[] = $member->kode_pos;
                                            @endphp
                                            {{ implode(', ', $alamatParts) }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex justify-center space-x-4">
            <a href="{{ route('user.submissions.show', $biodata->submission) }}" 
               class="inline-flex items-center px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Submission
            </a>
            
            @if($biodata->canBeEdited())
                <a href="{{ route('user.biodata.create', $biodata->submission) }}" 
                   class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-200">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Biodata
                </a>
            @endif
        </div>
    </main>
</body>
</html>