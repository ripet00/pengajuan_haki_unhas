<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengajuan - HKI Unhas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
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
                        <h1 class="text-sm sm:text-lg font-bold text-white leading-tight">Direktorat Inovasi dan Kekayaan Intelektual</h1>
                        <p class="text-red-100 text-xs sm:text-sm">Universitas Hasanuddin</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <div class="flex items-center text-white min-w-0 flex-1 sm:flex-initial">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-2 flex-shrink-0">
                            <span class="text-white font-medium text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <div class="min-w-0 flex-1 sm:flex-initial">
                            <span class="font-medium text-sm sm:text-base hidden sm:block">{{ Auth::user()->name }}</span>
                            <span class="font-medium text-sm block sm:hidden truncate">{{ explode(' ', Auth::user()->name)[0] }}</span>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('user.logout') }}">
                        @csrf
                        <button type="submit" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-3 sm:px-4 py-2 rounded-lg font-medium transition duration-200 text-sm sm:text-base">
                            <i class="fas fa-sign-out-alt mr-1 sm:mr-2"></i><span class="hidden sm:inline">Logout</span>
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
                <i class="fas fa-route mr-2 text-blue-600"></i>Progress Pengajuan HKI
            </h3>
            
            <div class="flex items-center justify-between relative">
                <!-- Progress Line -->
                <div class="absolute top-5 left-0 right-0 h-1 bg-gray-200 rounded-full z-0">
                    <div class="h-full bg-gradient-to-r from-blue-500 to-green-500 rounded-full transition-all duration-500" 
                         style="width: {{ $submission->biodata_status == 'approved' ? '100%' : ($submission->status == 'approved' ? '66%' : ($submission->status == 'rejected' ? '33%' : '33%')) }}"></div>
                </div>

                <!-- Step 1: Upload Dokumen -->
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 {{ $submission->created_at ? 'bg-green-500 border-green-500' : 'bg-gray-200 border-gray-300' }}">
                        <i class="fas fa-upload {{ $submission->created_at ? 'text-white' : 'text-gray-400' }}"></i>
                    </div>
                    <span class="mt-2 text-xs text-center font-medium {{ $submission->created_at ? 'text-green-600' : 'text-gray-400' }}">
                        Upload<br>Dokumen
                    </span>
                </div>

                <!-- Step 2: Review Dokumen -->
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 {{ $submission->status == 'approved' ? 'bg-green-500 border-green-500' : ($submission->status == 'rejected' ? 'bg-red-500 border-red-500' : ($submission->status == 'pending' ? 'bg-yellow-500 border-yellow-500' : 'bg-gray-200 border-gray-300')) }}">
                        @if($submission->status == 'approved')
                            <i class="fas fa-check text-white"></i>
                        @elseif($submission->status == 'rejected')
                            <i class="fas fa-times text-white"></i>
                        @elseif($submission->status == 'pending')
                            <i class="fas fa-clock text-white"></i>
                        @else
                            <i class="fas fa-gavel text-gray-400"></i>
                        @endif
                    </div>
                    <span class="mt-2 text-xs text-center font-medium {{ $submission->status == 'approved' ? 'text-green-600' : ($submission->status == 'rejected' ? 'text-red-600' : ($submission->status == 'pending' ? 'text-yellow-600' : 'text-gray-400')) }}">
                        Review<br>Dokumen
                    </span>
                </div>

                <!-- Step 3: Upload Biodata -->
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 {{ $submission->biodata_status == 'approved' || $submission->biodata_status == 'rejected' || $submission->biodata_status == 'pending' ? ($submission->biodata_status == 'approved' ? 'bg-green-500 border-green-500' : ($submission->biodata_status == 'rejected' ? 'bg-red-500 border-red-500' : 'bg-yellow-500 border-yellow-500')) : ($submission->status == 'approved' ? 'bg-blue-500 border-blue-500' : 'bg-gray-200 border-gray-300') }}">
                        @if($submission->biodata_status == 'approved')
                            <i class="fas fa-check text-white"></i>
                        @elseif($submission->biodata_status == 'rejected')
                            <i class="fas fa-times text-white"></i>
                        @elseif($submission->biodata_status == 'pending')
                            <i class="fas fa-clock text-white"></i>
                        @elseif($submission->status == 'approved')
                            <i class="fas fa-user-plus text-white"></i>
                        @else
                            <i class="fas fa-user-plus text-gray-400"></i>
                        @endif
                    </div>
                    <span class="mt-2 text-xs text-center font-medium {{ $submission->biodata_status == 'approved' ? 'text-green-600' : ($submission->biodata_status == 'rejected' ? 'text-red-600' : ($submission->biodata_status == 'pending' ? 'text-yellow-600' : ($submission->status == 'approved' ? 'text-blue-600' : 'text-gray-400'))) }}">
                        Upload<br>Biodata
                    </span>
                </div>

                <!-- Step 4: Selesai -->
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 {{ $submission->biodata_status == 'approved' ? 'bg-green-500 border-green-500' : 'bg-gray-200 border-gray-300' }}">
                        <i class="fas fa-certificate {{ $submission->biodata_status == 'approved' ? 'text-white' : 'text-gray-400' }}"></i>
                    </div>
                    <span class="mt-2 text-xs text-center font-medium {{ $submission->biodata_status == 'approved' ? 'text-green-600' : 'text-gray-400' }}">
                        Selesai
                    </span>
                </div>
            </div>

            <!-- Progress Description -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                @if($submission->biodata_status == 'approved')
                    <p class="text-sm text-green-700 font-medium">
                        <i class="fas fa-check-circle mr-2"></i>Pengajuan HKI Anda telah selesai diproses!
                    </p>
                @elseif($submission->biodata_status == 'rejected')
                    <p class="text-sm text-red-700 font-medium">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Biodata ditolak. Silakan upload ulang biodata yang sesuai.
                    </p>
                @elseif($submission->biodata_status == 'pending')
                    <p class="text-sm text-yellow-700 font-medium">
                        <i class="fas fa-clock mr-2"></i>Biodata sedang direview oleh admin.
                    </p>
                @elseif($submission->status == 'approved')
                    <p class="text-sm text-blue-700 font-medium">
                        <i class="fas fa-arrow-right mr-2"></i>Dokumen disetujui! Silakan upload biodata untuk melanjutkan.
                    </p>
                @elseif($submission->status == 'rejected')
                    <p class="text-sm text-red-700 font-medium">
                        <i class="fas fa-times-circle mr-2"></i>Dokumen ditolak. Perbaiki dan upload ulang dokumen.
                    </p>
                @else
                    <p class="text-sm text-yellow-700 font-medium">
                        <i class="fas fa-hourglass-half mr-2"></i>Dokumen sedang direview oleh admin.
                    </p>
                @endif
            </div>
        </div>

        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('user.dashboard') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Dashboard
            </a>
        </div>

        <!-- Submission Details -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Detail Pengajuan HKI</h2>
                <p class="text-sm text-gray-600 mt-1">ID Pengajuan: #{{ $submission->id }}</p>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Submission Info -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Judul Karya</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $submission->title }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Kategori</label>
                            <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full 
                                {{ $submission->categories == 'Universitas' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                <i class="fas fa-{{ $submission->categories == 'Universitas' ? 'university' : 'globe' }} mr-1"></i>
                                {{ $submission->categories }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                            @if($submission->status == 'pending')
                                <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>Menunggu Review
                                </span>
                            @elseif($submission->status == 'approved')
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
                            <p class="text-gray-900">{{ $submission->created_at->format('d F Y, H:i') }} WITA</p>
                        </div>

                        @if($submission->reviewed_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Review</label>
                            <p class="text-gray-900">{{ $submission->reviewed_at->format('d F Y, H:i') }} WITA</p>
                        </div>
                        @endif

                        @if($submission->reviewedByAdmin)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Direview oleh</label>
                            <p class="text-gray-900">{{ $submission->reviewedByAdmin->name }}</p>
                        </div>
                        @endif

                        <!-- Creator Information -->
                        @if($submission->creator_name || $submission->creator_whatsapp)
                        <div class="pt-4 border-t border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-user-edit mr-1"></i>Informasi Pencipta Pertama
                            </h4>
                            
                            @if($submission->creator_name)
                            <div class="mb-2">
                                <label class="block text-sm font-medium text-gray-500 mb-1">Nama Pencipta</label>
                                <p class="text-gray-900">{{ $submission->creator_name }}</p>
                            </div>
                            @endif

                            @if($submission->creator_whatsapp)
                            <div class="mb-2">
                                <label class="block text-sm font-medium text-gray-500 mb-1">WhatsApp Pencipta</label>
                                <div class="flex items-center space-x-2">
                                    <p class="text-gray-900">{{ $submission->creator_whatsapp }}</p>
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $submission->creator_whatsapp) }}" 
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
                            <p class="text-gray-900">{{ $submission->file_name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Ukuran File</label>
                            <p class="text-gray-900">{{ number_format($submission->file_size / 1024 / 1024, 2) }} MB</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Jenis File</label>
                            @if($submission->file_type === 'video')
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">
                                    <i class="fas fa-video mr-1"></i>Video MP4
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-file-pdf mr-1"></i>PDF
                                </span>
                            @endif
                        </div>

                        @if($submission->file_type === 'video' && $submission->youtube_link)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Link YouTube</label>
                            <a href="{{ $submission->youtube_link }}" 
                               target="_blank" 
                               class="text-blue-600 hover:text-blue-800 underline text-sm break-all">
                                {{ $submission->youtube_link }}
                            </a>
                        </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-3">Aksi File</label>
                                <div class="space-y-2">
                                <a href="{{ asset('storage/' . $submission->file_path) }}" 
                                   target="_blank"
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                    @if($submission->file_type === 'video')
                                        <i class="fas fa-play mr-2"></i>Lihat Video
                                    @else
                                        <i class="fas fa-eye mr-2"></i>Lihat File PDF
                                    @endif
                                </a>
                                <a href="{{ asset('storage/' . $submission->file_path) }}" 
                                   download="{{ $submission->file_name }}"
                                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition duration-200 ml-2">
                                    <i class="fas fa-download mr-2"></i>Download
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rejection Reason -->
                @if($submission->status == 'rejected' && $submission->rejection_reason)
                <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <h4 class="text-sm font-semibold text-red-800 mb-2">
                        <i class="fas fa-exclamation-triangle mr-1"></i>Alasan Penolakan
                    </h4>
                    <p class="text-sm text-red-700">{{ $submission->rejection_reason }}</p>
                </div>
                @endif

                <!-- Revision Section -->
                @if($submission->status == 'rejected')
                <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <h4 class="text-sm font-semibold text-yellow-800 mb-3">
                        <i class="fas fa-redo mr-1"></i>Revisi Pengajuan
                    </h4>
                    <p class="text-sm text-yellow-700 mb-4">Anda dapat mengunggah ulang dokumen yang telah diperbaiki.</p>
                    
                    <form method="POST" action="{{ route('user.submissions.resubmit', $submission) }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Karya (dapat diubah)</label>
                            <input 
                                type="text" 
                                id="title" 
                                name="title" 
                                value="{{ old('title', $submission->title) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                required
                            >
                        </div>

                        <div>
                            <label for="categories" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <select 
                                id="categories" 
                                name="categories"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                required
                            >
                                <option value="Universitas" {{ old('categories', $submission->categories) == 'Universitas' ? 'selected' : '' }}>Universitas</option>
                                <option value="Umum" {{ old('categories', $submission->categories) == 'Umum' ? 'selected' : '' }}>Umum</option>
                            </select>
                        </div>

                        <div>
                            <label for="file_type" class="block text-sm font-medium text-gray-700 mb-1">Jenis File</label>
                            <select 
                                id="file_type" 
                                name="file_type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                required
                                onchange="toggleFileFields()"
                            >
                                <option value="pdf" {{ old('file_type', $submission->file_type) == 'pdf' ? 'selected' : '' }}>PDF</option>
                                <option value="video" {{ old('file_type', $submission->file_type) == 'video' ? 'selected' : '' }}>Video (MP4)</option>
                            </select>
                        </div>

                        <div>
                            <label for="creator_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Pencipta Pertama</label>
                            <input 
                                type="text" 
                                id="creator_name" 
                                name="creator_name" 
                                value="{{ old('creator_name', $submission->creator_name) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                required
                            >
                        </div>

                        <div>
                            <label for="creator_whatsapp" class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp Pencipta Pertama</label>
                            <input 
                                type="text" 
                                id="creator_whatsapp" 
                                name="creator_whatsapp" 
                                value="{{ old('creator_whatsapp', $submission->creator_whatsapp) }}"
                                placeholder="Contoh: 081234567890 atau +6281234567890"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                required
                            >
                        </div>

                        <div id="youtube-field" style="display: {{ old('file_type', $submission->file_type) == 'video' ? 'block' : 'none' }};">
                            <label for="youtube_link" class="block text-sm font-medium text-gray-700 mb-1">Link YouTube (opsional)</label>
                            <input 
                                type="url" 
                                id="youtube_link" 
                                name="youtube_link" 
                                value="{{ old('youtube_link', $submission->youtube_link) }}"
                                placeholder="https://www.youtube.com/watch?v=..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                            >
                        </div>

                        <div>
                            <label for="document" class="block text-sm font-medium text-gray-700 mb-1">
                                <span id="file-label">
                                    {{ old('file_type', $submission->file_type) == 'video' ? 'File Video MP4 Baru' : 'Dokumen PDF Baru' }}
                                </span>
                            </label>
                            <input 
                                type="file" 
                                id="document" 
                                name="document" 
                                accept="{{ old('file_type', $submission->file_type) == 'video' ? '.mp4' : '.pdf' }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                required
                            >
                        </div>

                        <button 
                            type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200"
                        >
                            <i class="fas fa-upload mr-2"></i>Upload Ulang
                        </button>
                    </form>
                </div>

                <script>
                function toggleFileFields() {
                    const fileType = document.getElementById('file_type').value;
                    const youtubeField = document.getElementById('youtube-field');
                    const documentInput = document.getElementById('document');
                    const fileLabel = document.getElementById('file-label');
                    
                    if (fileType === 'video') {
                        youtubeField.style.display = 'block';
                        documentInput.accept = '.mp4';
                        fileLabel.textContent = 'File Video MP4 Baru';
                    } else {
                        youtubeField.style.display = 'none';
                        documentInput.accept = '.pdf';
                        fileLabel.textContent = 'Dokumen PDF Baru';
                    }
                }
                </script>
                @endif
            </div>
        </div>
    </main>
</body>
</html>