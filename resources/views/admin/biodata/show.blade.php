<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Biodata - Pengajuan HKI</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('admin.partials.header', ['title' => 'Detail Biodata'])

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="px-4 sm:px-6 lg:px-8 py-6">
                    @if(session('success'))
                        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded">
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

                    @if($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
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

                    <div class="space-y-6">
                        <!-- Back Button -->
                        <div>
                            <a href="{{ route('admin.biodata.index') }}" class="inline-flex items-center px-5 py-3 bg-white hover:bg-gray-50 text-gray-800 hover:text-gray-900 border-2 border-gray-500 hover:border-gray-700 rounded-lg font-bold transition duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali ke Daftar Biodata
                            </a>
                        </div>

                        <!-- Admin Instruction Note -->
                        @if($biodata->status === 'pending')
                        <div class="bg-blue-500 text-white rounded-lg p-6 shadow-lg border-l-4 border-yellow-400">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-yellow-300 text-3xl"></i>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h3 class="text-lg font-bold mb-2 flex items-center">
                                        <i class="fas fa-clipboard-list mr-2"></i>Petunjuk Review Biodata
                                    </h3>
                                    <div class="text-sm space-y-2">
                                        <p class="font-medium">Silakan ikuti langkah-langkah berikut untuk melakukan review:</p>
                                        <ol class="list-decimal list-inside space-y-1.5 ml-2">
                                            <li><strong>Periksa Detail Biodata</strong> - Pastikan informasi tempat ciptaan, tanggal, dan uraian singkat sudah benar</li>
                                            <li><strong>Periksa Data Pencipta</strong> - Review data <strong>semua {{ $biodata->members->count() }} pencipta</strong> secara menyeluruh (nama, NIK, NPWP, alamat, dll)</li>
                                            <li><strong>Tandai Field yang Error</strong> - Buka toggle "Tandai Field Error" pada setiap pencipta untuk menandai field yang bermasalah</li>
                                            <li><strong>Submit Review di Bagian Bawah</strong> - Scroll ke bawah halaman untuk memberikan keputusan review (setujui/tolak)</li>
                                        </ol>
                                        <div class="mt-3 pt-3 border-t border-blue-400">
                                            <p class="text-xs italic flex items-center">
                                                <i class="fas fa-lightbulb mr-2 text-yellow-300"></i>
                                                <span>Field yang ditandai error akan ditampilkan dengan <span class="bg-red-200 text-red-900 px-1 rounded font-semibold">highlight merah</span> kepada user untuk mempermudah perbaikan.</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Main Content Grid -->
                        @if($biodata->status === 'pending')
                            <!-- Wrap entire content in review form for pending biodata -->
                            <form id="pendingReviewForm" method="POST" action="{{ route('admin.biodata.review', $biodata) }}">
                                @csrf
                        @endif
                        
                        <!-- Single Column Layout -->
                        <div class="space-y-6">

                        <!-- Header Card -->
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                                    <i class="fas fa-user-friends mr-3 text-red-600"></i>Detail Biodata #{{ $biodata->id }}
                                    @php
                                        $status = $biodata->status;
                                        $statusClasses = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'approved' => 'bg-green-100 text-green-800', 
                                            'rejected' => 'bg-red-100 text-red-800',
                                            'denied' => 'bg-red-100 text-red-800'
                                        ];
                                        $statusIcons = [
                                            'pending' => 'fas fa-clock',
                                            'approved' => 'fas fa-check',
                                            'rejected' => 'fas fa-times',
                                            'denied' => 'fas fa-times'
                                        ];
                                    @endphp
                                    <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClasses[$status] ?? 'bg-gray-100 text-gray-800' }}">
                                        <i class="{{ $statusIcons[$status] ?? 'fas fa-question' }} mr-1"></i>
                                        {{ ucfirst($status) }}
                                    </span>
                                </h2>
                            </div>

                            <div class="p-6">
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <!-- Pengaju Information -->
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                            <i class="fas fa-user mr-2 text-blue-600"></i>Informasi Pengaju
                                        </h3>
                                        <div class="space-y-3">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                                <p class="mt-1 text-sm text-gray-900">{{ $biodata->user->name }}</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                                <p class="mt-1 text-sm text-gray-900">{{ $biodata->user->email }}</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                                                <p class="mt-1 text-sm text-gray-900">{{ $biodata->user->phone_number }}</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Fakultas</label>
                                                <p class="mt-1 text-sm text-gray-900">{{ $biodata->user->faculty }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Submission Information -->
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                            <i class="fas fa-file-alt mr-2 text-green-600"></i>Informasi Submission
                                        </h3>
                                        <div class="space-y-3">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Judul Karya</label>
                                                <p class="mt-1 text-sm text-gray-900">{{ $biodata->submission->title }}</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Jenis Karya</label>
                                                <p class="mt-1 text-sm text-gray-900">
                                                    {{ $biodata->submission->jenisKarya->nama ?? 'Tidak ada' }}
                                                </p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Kategori</label>
                                                <p class="mt-1 text-sm text-gray-900">{{ $biodata->submission->categories }}</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Jenis File</label>
                                                <p class="mt-1 text-sm text-gray-900">
                                                    @if($biodata->submission->file_type === 'video')
                                                        <i class="fas fa-video text-purple-500 mr-1"></i>Video
                                                    @else
                                                        <i class="fas fa-file-pdf text-red-500 mr-1"></i>PDF
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Biodata Details -->
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <i class="fas fa-id-card mr-2 text-indigo-600"></i>Detail Biodata
                                </h3>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-600">Tempat Ciptaan</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $biodata->tempat_ciptaan ?: '-' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-600">Tanggal Ciptaan</label>
                                        <p class="mt-1 text-sm text-gray-900">
                                            {{ $biodata->tanggal_ciptaan ? $biodata->tanggal_ciptaan->translatedFormat('d M Y') : '-' }}
                                        </p>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-bold text-gray-600">Uraian Singkat</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $biodata->uraian_singkat ?: '-' }}</p>
                                    </div>
                                </div>

                                <!-- Biodata-level error flags (positioned under Detail Biodata) -->
                                <div class="mt-4 border-t pt-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Tandai Error pada Biodata</h4>
                                    <div class="flex flex-wrap gap-3">
                                        <label class="inline-flex items-center text-sm cursor-pointer relative">
                                            <input type="hidden" name="error_tempat_ciptaan" value="0">
                                            <input type="checkbox" name="error_tempat_ciptaan" value="1" class="absolute w-6 h-6 opacity-0 cursor-pointer z-10" aria-label="Tandai Tempat Ciptaan salah" {{ $biodata->error_tempat_ciptaan ? 'checked' : '' }}>
                                            <span class="inline-flex items-center justify-center h-6 w-6 border rounded text-red-600 mr-2 relative">
                                                <i class="fas fa-times {{ $biodata->error_tempat_ciptaan ? 'opacity-100' : 'opacity-0' }} transition-opacity duration-150"></i>
                                            </span>
                                            <span>Tempat Ciptaan</span>
                                        </label>
                                        <label class="inline-flex items-center text-sm cursor-pointer relative">
                                            <input type="hidden" name="error_tanggal_ciptaan" value="0">
                                            <input type="checkbox" name="error_tanggal_ciptaan" value="1" class="absolute w-6 h-6 opacity-0 cursor-pointer z-10" aria-label="Tandai Tanggal Ciptaan salah" {{ $biodata->error_tanggal_ciptaan ? 'checked' : '' }}>
                                            <span class="inline-flex items-center justify-center h-6 w-6 border rounded text-red-600 mr-2 relative">
                                                <i class="fas fa-times {{ $biodata->error_tanggal_ciptaan ? 'opacity-100' : 'opacity-0' }} transition-opacity duration-150"></i>
                                            </span>
                                            <span>Tanggal Ciptaan</span>
                                        </label>
                                        <label class="inline-flex items-center text-sm cursor-pointer relative">
                                            <input type="hidden" name="error_uraian_singkat" value="0">
                                            <input type="checkbox" name="error_uraian_singkat" value="1" class="absolute w-6 h-6 opacity-0 cursor-pointer z-10" aria-label="Tandai Uraian Singkat salah" {{ $biodata->error_uraian_singkat ? 'checked' : '' }}>
                                            <span class="inline-flex items-center justify-center h-6 w-6 border rounded text-red-600 mr-2 relative">
                                                <i class="fas fa-times {{ $biodata->error_uraian_singkat ? 'opacity-100' : 'opacity-0' }} transition-opacity duration-150"></i>
                                            </span>
                                            <span>Uraian Singkat</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Members Information (integrated with per-member cross-check) -->
                        @if($biodata->members && $biodata->members->count() > 0)
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center justify-between">
                                    <span><i class="fas fa-users mr-2 text-purple-600"></i>Pencipta ({{ $biodata->members->count() }} Orang)</span>
                                </h3>
                            </div>
                            <div class="p-6 space-y-8">
                                @foreach($biodata->members->sortBy('is_leader', SORT_REGULAR, true) as $index => $member)
                                <div class="bg-gray-50 rounded-lg p-6 {{ !$loop->last ? 'mb-6' : '' }}">
                                    <!-- Member Header -->
                                    <div class="flex items-center justify-between mb-6 pb-4 border-b-2 border-gray-300">
                                        <span class="inline-flex items-center px-4 py-2 rounded-full text-base font-bold {{ $member->is_leader ? 'bg-blue-600 text-white' : 'bg-gray-700 text-white' }}">
                                            <i class="fas fa-user mr-2"></i>
                                            Pencipta {{ $index + 1 }}{{ $member->is_leader ? ' (Ketua)' : '' }}
                                        </span>
                                    </div>
                                    
                                    <!-- Hidden fields for member ID -->
                                    <input type="hidden" name="members[{{ $member->id }}][id]" value="{{ $member->id }}">

                                    <!-- Member Information Grid - Clean Display -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-5 mb-6">
                                        @php $mid = $member->id; @endphp

                                        <!-- Clean display fields -->
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                                            <p class="text-sm text-gray-900 {{ $member->error_name ? 'bg-red-100 border-l-4 border-red-600 pl-3 py-2 font-semibold' : '' }}">{{ $member->name ?: '-' }}</p>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">NIK</label>
                                            <p class="text-sm text-gray-900 {{ $member->error_nik ? 'bg-red-100 border-l-4 border-red-600 pl-3 py-2 font-semibold' : '' }}">{{ $member->nik ?: '-' }}</p>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">NPWP</label>
                                            <p class="text-sm text-gray-900 {{ $member->error_npwp ? 'bg-red-100 border-l-4 border-red-600 pl-3 py-2 font-semibold' : '' }}">{{ $member->npwp ?: '-' }}</p>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Jenis Kelamin</label>
                                            <p class="text-sm text-gray-900 {{ $member->error_jenis_kelamin ? 'bg-red-100 border-l-4 border-red-600 pl-3 py-2 font-semibold' : '' }}">{{ $member->jenis_kelamin ?: '-' }}</p>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Pekerjaan</label>
                                            <p class="text-sm text-gray-900 {{ $member->error_pekerjaan ? 'bg-red-100 border-l-4 border-red-600 pl-3 py-2 font-semibold' : '' }}">{{ $member->pekerjaan ?: '-' }}</p>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Universitas</label>
                                            <p class="text-sm text-gray-900 {{ $member->error_universitas ? 'bg-red-100 border-l-4 border-red-600 pl-3 py-2 font-semibold' : '' }}">{{ $member->universitas ?: '-' }}</p>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Fakultas</label>
                                            <p class="text-sm text-gray-900 {{ $member->error_fakultas ? 'bg-red-100 border-l-4 border-red-600 pl-3 py-2 font-semibold' : '' }}">{{ $member->fakultas ?: '-' }}</p>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Program Studi</label>
                                            <p class="text-sm text-gray-900 {{ $member->error_program_studi ? 'bg-red-100 border-l-4 border-red-600 pl-3 py-2 font-semibold' : '' }}">{{ $member->program_studi ?: '-' }}</p>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Kewarganegaraan</label>
                                            <p class="text-sm text-gray-900 {{ $member->error_kewarganegaraan ? 'bg-red-100 border-l-4 border-red-600 pl-3 py-2 font-semibold' : '' }}">{{ $member->kewarganegaraan ?: '-' }}</p>
                                        </div>

                                        <div class="lg:col-span-3">
                                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Alamat</label>
                                            <p class="text-sm text-gray-900 {{ $member->error_alamat ? 'bg-red-100 border-l-4 border-red-600 pl-3 py-2 font-semibold' : '' }}">{{ $member->alamat ?: '-' }}</p>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Kelurahan</label>
                                            <p class="text-sm text-gray-900 {{ $member->error_kelurahan ? 'bg-red-100 border-l-4 border-red-600 pl-3 py-2 font-semibold' : '' }}">{{ $member->kelurahan ?: '-' }}</p>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Kecamatan</label>
                                            <p class="text-sm text-gray-900 {{ $member->error_kecamatan ? 'bg-red-100 border-l-4 border-red-600 pl-3 py-2 font-semibold' : '' }}">{{ $member->kecamatan ?: '-' }}</p>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Kota/Kabupaten</label>
                                            <p class="text-sm text-gray-900 {{ $member->error_kota_kabupaten ? 'bg-red-100 border-l-4 border-red-600 pl-3 py-2 font-semibold' : '' }}">{{ $member->kota_kabupaten ?: '-' }}</p>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Provinsi</label>
                                            <p class="text-sm text-gray-900 {{ $member->error_provinsi ? 'bg-red-100 border-l-4 border-red-600 pl-3 py-2 font-semibold' : '' }}">{{ $member->provinsi ?: '-' }}</p>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Kode Pos</label>
                                            <p class="text-sm text-gray-900 {{ $member->error_kode_pos ? 'bg-red-100 border-l-4 border-red-600 pl-3 py-2 font-semibold' : '' }}">{{ $member->kode_pos ?: '-' }}</p>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Email</label>
                                            <p class="text-sm text-gray-900 break-all {{ $member->error_email ? 'bg-red-100 border-l-4 border-red-600 pl-3 py-2 font-semibold' : '' }}">{{ $member->email ?: '-' }}</p>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Nomor HP</label>
                                            <p class="text-sm text-gray-900 {{ $member->error_nomor_hp ? 'bg-red-100 border-l-4 border-red-600 pl-3 py-2 font-semibold' : '' }}">{{ $member->nomor_hp ?: '-' }}</p>
                                        </div>
                                    </div>

                                    <!-- Error Checkboxes Section (Collapsible) -->
                                    <div class="mt-6 border-t-2 border-gray-300 pt-4">
                                        <details class="group">
                                            @php
                                                // Color variations for each member (only background colors)
                                                $bgColors = ['bg-blue-100', 'bg-purple-100', 'bg-green-100', 'bg-orange-100', 'bg-pink-100', 'bg-indigo-100', 'bg-teal-100', 'bg-red-100'];
                                                $bgHoverColors = ['hover:bg-blue-200', 'hover:bg-purple-200', 'hover:bg-green-200', 'hover:bg-orange-200', 'hover:bg-pink-200', 'hover:bg-indigo-200', 'hover:bg-teal-200', 'hover:bg-red-200'];
                                                $colorIndex = $index % 8;
                                            @endphp
                                            <summary class="cursor-pointer list-none flex items-center justify-between p-4 rounded-lg border-2 border-gray-300 {{ $bgColors[$colorIndex] }} {{ $bgHoverColors[$colorIndex] }} transition-all shadow-sm">
                                                <span class="font-bold text-gray-800 flex items-center">
                                                    <i class="fas fa-exclamation-triangle mr-2 text-orange-600"></i>
                                                    Tandai Field dengan Error untuk Pencipta {{ $index + 1 }} (Klik untuk expand)
                                                </span>
                                                <i class="fas fa-chevron-down group-open:rotate-180 transition-transform text-gray-600"></i>
                                            </summary>
                                            
                                            <div class="mt-4 p-5 bg-white rounded-lg border-2 border-gray-200 shadow-inner">
                                                <p class="text-sm text-gray-600 mb-4 bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded">
                                                    <i class="fas fa-info-circle mr-1"></i>
                                                    <strong>Petunjuk:</strong> Centang field yang memiliki kesalahan data. Field yang ditandai akan ditampilkan dengan latar belakang merah.
                                                </p>
                                                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2">
                                                    <!-- Hidden inputs for unchecked state -->
                                                    <input type="hidden" name="members[{{ $mid }}][error_name]" value="0">
                                                    <input type="hidden" name="members[{{ $mid }}][error_nik]" value="0">
                                                    <input type="hidden" name="members[{{ $mid }}][error_npwp]" value="0">
                                                    <input type="hidden" name="members[{{ $mid }}][error_jenis_kelamin]" value="0">
                                                    <input type="hidden" name="members[{{ $mid }}][error_pekerjaan]" value="0">
                                                    <input type="hidden" name="members[{{ $mid }}][error_universitas]" value="0">
                                                    <input type="hidden" name="members[{{ $mid }}][error_fakultas]" value="0">
                                                    <input type="hidden" name="members[{{ $mid }}][error_program_studi]" value="0">
                                                    <input type="hidden" name="members[{{ $mid }}][error_alamat]" value="0">
                                                    <input type="hidden" name="members[{{ $mid }}][error_kecamatan]" value="0">
                                                    <input type="hidden" name="members[{{ $mid }}][error_kelurahan]" value="0">
                                                    <input type="hidden" name="members[{{ $mid }}][error_kota_kabupaten]" value="0">
                                                    <input type="hidden" name="members[{{ $mid }}][error_provinsi]" value="0">
                                                    <input type="hidden" name="members[{{ $mid }}][error_kode_pos]" value="0">
                                                    <input type="hidden" name="members[{{ $mid }}][error_email]" value="0">
                                                    <input type="hidden" name="members[{{ $mid }}][error_nomor_hp]" value="0">
                                                    <input type="hidden" name="members[{{ $mid }}][error_kewarganegaraan]" value="0">
                                                    
                                                    <!-- Checkboxes -->
                                                    <label class="flex items-center space-x-2 text-sm cursor-pointer hover:bg-red-50 p-2 rounded border border-transparent hover:border-red-200">
                                                        <input type="checkbox" name="members[{{ $mid }}][error_name]" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500 member-error-checkbox" {{ $member->error_name ? 'checked' : '' }}>
                                                        <span class="text-gray-700">Nama</span>
                                                    </label>
                                                    <label class="flex items-center space-x-2 text-sm cursor-pointer hover:bg-red-50 p-2 rounded border border-transparent hover:border-red-200">
                                                        <input type="checkbox" name="members[{{ $mid }}][error_nik]" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500 member-error-checkbox" {{ $member->error_nik ? 'checked' : '' }}>
                                                        <span class="text-gray-700">NIK</span>
                                                    </label>
                                                    <label class="flex items-center space-x-2 text-sm cursor-pointer hover:bg-red-50 p-2 rounded border border-transparent hover:border-red-200">
                                                        <input type="checkbox" name="members[{{ $mid }}][error_npwp]" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500 member-error-checkbox" {{ $member->error_npwp ? 'checked' : '' }}>
                                                        <span class="text-gray-700">NPWP</span>
                                                    </label>
                                                    <label class="flex items-center space-x-2 text-sm cursor-pointer hover:bg-red-50 p-2 rounded border border-transparent hover:border-red-200">
                                                        <input type="checkbox" name="members[{{ $mid }}][error_jenis_kelamin]" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500 member-error-checkbox" {{ $member->error_jenis_kelamin ? 'checked' : '' }}>
                                                        <span class="text-gray-700">Jenis Kelamin</span>
                                                    </label>
                                                    <label class="flex items-center space-x-2 text-sm cursor-pointer hover:bg-red-50 p-2 rounded border border-transparent hover:border-red-200">
                                                        <input type="checkbox" name="members[{{ $mid }}][error_pekerjaan]" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500 member-error-checkbox" {{ $member->error_pekerjaan ? 'checked' : '' }}>
                                                        <span class="text-gray-700">Pekerjaan</span>
                                                    </label>
                                                    <label class="flex items-center space-x-2 text-sm cursor-pointer hover:bg-red-50 p-2 rounded border border-transparent hover:border-red-200">
                                                        <input type="checkbox" name="members[{{ $mid }}][error_universitas]" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500 member-error-checkbox" {{ $member->error_universitas ? 'checked' : '' }}>
                                                        <span class="text-gray-700">Universitas</span>
                                                    </label>
                                                    <label class="flex items-center space-x-2 text-sm cursor-pointer hover:bg-red-50 p-2 rounded border border-transparent hover:border-red-200">
                                                        <input type="checkbox" name="members[{{ $mid }}][error_fakultas]" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500 member-error-checkbox" {{ $member->error_fakultas ? 'checked' : '' }}>
                                                        <span class="text-gray-700">Fakultas</span>
                                                    </label>
                                                    <label class="flex items-center space-x-2 text-sm cursor-pointer hover:bg-red-50 p-2 rounded border border-transparent hover:border-red-200">
                                                        <input type="checkbox" name="members[{{ $mid }}][error_program_studi]" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500 member-error-checkbox" {{ $member->error_program_studi ? 'checked' : '' }}>
                                                        <span class="text-gray-700">Program Studi</span>
                                                    </label>
                                                    <label class="flex items-center space-x-2 text-sm cursor-pointer hover:bg-red-50 p-2 rounded border border-transparent hover:border-red-200">
                                                        <input type="checkbox" name="members[{{ $mid }}][error_alamat]" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500 member-error-checkbox" {{ $member->error_alamat ? 'checked' : '' }}>
                                                        <span class="text-gray-700">Alamat</span>
                                                    </label>
                                                    <label class="flex items-center space-x-2 text-sm cursor-pointer hover:bg-red-50 p-2 rounded border border-transparent hover:border-red-200">
                                                        <input type="checkbox" name="members[{{ $mid }}][error_kelurahan]" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500 member-error-checkbox" {{ $member->error_kelurahan ? 'checked' : '' }}>
                                                        <span class="text-gray-700">Kelurahan</span>
                                                    </label>
                                                    <label class="flex items-center space-x-2 text-sm cursor-pointer hover:bg-red-50 p-2 rounded border border-transparent hover:border-red-200">
                                                        <input type="checkbox" name="members[{{ $mid }}][error_kecamatan]" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500 member-error-checkbox" {{ $member->error_kecamatan ? 'checked' : '' }}>
                                                        <span class="text-gray-700">Kecamatan</span>
                                                    </label>
                                                    <label class="flex items-center space-x-2 text-sm cursor-pointer hover:bg-red-50 p-2 rounded border border-transparent hover:border-red-200">
                                                        <input type="checkbox" name="members[{{ $mid }}][error_kota_kabupaten]" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500 member-error-checkbox" {{ $member->error_kota_kabupaten ? 'checked' : '' }}>
                                                        <span class="text-gray-700">Kota/Kabupaten</span>
                                                    </label>
                                                    <label class="flex items-center space-x-2 text-sm cursor-pointer hover:bg-red-50 p-2 rounded border border-transparent hover:border-red-200">
                                                        <input type="checkbox" name="members[{{ $mid }}][error_provinsi]" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500 member-error-checkbox" {{ $member->error_provinsi ? 'checked' : '' }}>
                                                        <span class="text-gray-700">Provinsi</span>
                                                    </label>
                                                    <label class="flex items-center space-x-2 text-sm cursor-pointer hover:bg-red-50 p-2 rounded border border-transparent hover:border-red-200">
                                                        <input type="checkbox" name="members[{{ $mid }}][error_kode_pos]" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500 member-error-checkbox" {{ $member->error_kode_pos ? 'checked' : '' }}>
                                                        <span class="text-gray-700">Kode Pos</span>
                                                    </label>
                                                    <label class="flex items-center space-x-2 text-sm cursor-pointer hover:bg-red-50 p-2 rounded border border-transparent hover:border-red-200">
                                                        <input type="checkbox" name="members[{{ $mid }}][error_email]" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500 member-error-checkbox" {{ $member->error_email ? 'checked' : '' }}>
                                                        <span class="text-gray-700">Email</span>
                                                    </label>
                                                    <label class="flex items-center space-x-2 text-sm cursor-pointer hover:bg-red-50 p-2 rounded border border-transparent hover:border-red-200">
                                                        <input type="checkbox" name="members[{{ $mid }}][error_nomor_hp]" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500 member-error-checkbox" {{ $member->error_nomor_hp ? 'checked' : '' }}>
                                                        <span class="text-gray-700">Nomor HP</span>
                                                    </label>
                                                    <label class="flex items-center space-x-2 text-sm cursor-pointer hover:bg-red-50 p-2 rounded border border-transparent hover:border-red-200">
                                                        <input type="checkbox" name="members[{{ $mid }}][error_kewarganegaraan]" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500 member-error-checkbox" {{ $member->error_kewarganegaraan ? 'checked' : '' }}>
                                                        <span class="text-gray-700">Kewarganegaraan</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </details>
                                    </div>

                                    <!-- Separator between members -->
                                    @if(!$loop->last)
                                        <div class="mt-8 mb-2 border-t-4 border-gray-400 relative">
                                        </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Review Panel (Full Width) -->
                        @if($biodata->status === 'pending')
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-lg p-6 shadow-lg">
                                <h3 class="text-xl font-bold text-blue-900 mb-4 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2 text-blue-600 animate-pulse"></i>
                                    REVIEW DIPERLUKAN
                                </h3>
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                    <p class="text-yellow-800 font-medium text-sm">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        Biodata ini membutuhkan review Anda. Silakan periksa data dan berikan keputusan.
                                    </p>
                                </div>
                        @else
                            <div class="bg-gray-50 rounded-lg p-6 shadow">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-gavel mr-2 text-red-600"></i>Panel Review
                                </h3>
                        @endif
                            
                            @if($biodata->status === 'pending')
                                <!-- Review controls -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <!-- Warning before review decision -->
                                        <div class="bg-orange-50 border-l-4 border-orange-400 p-4 rounded mb-6">
                                            <div class="flex">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-exclamation-triangle text-orange-600"></i>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-semibold text-orange-800 mb-2">
                                                        <i class="fas fa-clipboard-check mr-1"></i>Peringatan Penting Sebelum Review:
                                                    </p>
                                                    <ul class="text-xs text-orange-700 space-y-1 list-disc list-inside">
                                                        <li>Pastikan Anda sudah memeriksa <strong>semua {{ $biodata->members->count() }} pencipta</strong> yang terdaftar</li>
                                                        <li>Jika ada data yang bermasalah, <strong>wajib tandai field dengan error</strong> pada tombol toggle di setiap pencipta</li>
                                                        <li>Field yang ditandai error akan ditampilkan dengan latar belakang merah untuk memudahkan user memperbaiki</li>
                                                        <li>Review yang menyeluruh akan mempercepat proses persetujuan HKI</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-3"><strong>Keputusan Review:</strong></label>
                                        <div class="space-y-2">
                                            <label class="flex items-center">
                                                <input type="radio" name="action" value="approve" required class="text-green-600 focus:ring-green-500 border-gray-300">
                                                <span class="ml-2 text-green-700 font-medium">
                                                    <i class="fas fa-check-circle mr-1"></i>Setujui Biodata
                                                </span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" name="action" value="reject" required class="text-red-600 focus:ring-red-500 border-gray-300">
                                                <span class="ml-2 text-red-700 font-medium">
                                                    <i class="fas fa-times-circle mr-1"></i>Tolak Biodata
                                                </span>
                                            </label>
                                        </div>
                                    </div>

                                    <div>
                                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-1">
                                            <strong>Catatan/Alasan Penolakan:</strong>
                                            <small class="text-gray-500">(Opsional untuk approval, wajib untuk rejection)</small>
                                        </label>
                                        <textarea 
                                            id="rejection_reason" 
                                            name="rejection_reason" 
                                            rows="4"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                            placeholder="Tulis catatan atau alasan penolakan di sini..."></textarea>
                                    </div>

                                    <div class="md:col-span-2">
                                        <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-4 px-6 rounded-lg transition duration-200 transform hover:scale-105 shadow-lg">
                                            <i class="fas fa-gavel mr-2"></i>SUBMIT REVIEW
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="mb-6">
                                    <div class="text-center mb-4">
                                        @if($biodata->status == 'approved')
                                            <i class="fas fa-check-circle text-green-500 text-3xl"></i>
                                            <h4 class="text-green-700 font-semibold mt-2">Status: Disetujui</h4>
                                        @else
                                            <i class="fas fa-times-circle text-red-500 text-3xl"></i>
                                            <h4 class="text-red-700 font-semibold mt-2">Status: Ditolak</h4>
                                        @endif
                                        
                                        <div class="text-sm text-gray-600 mt-2">
                                            @if($biodata->reviewed_at)
                                                Direview: {{ $biodata->reviewed_at->translatedFormat('d F Y, H:i') }} WITA
                                                <br>
                                            @endif
                                            @if($biodata->reviewedBy)
                                                oleh: {{ $biodata->reviewedBy->name ?? 'Admin' }}
                                            @endif
                                        </div>
                                    </div>

                                    @if($biodata->rejection_reason && in_array($biodata->status, ['rejected', 'denied']))
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                                        <p class="text-sm text-red-700"><strong>Catatan:</strong> {{ $biodata->rejection_reason }}</p>
                                    </div>
                                    @endif

                                    <div class="border-t pt-4">
                                        <p class="text-sm text-gray-600 mb-4 text-center">
                                            <i class="fas fa-edit mr-1"></i>Perlu mengubah keputusan review?
                                        </p>
                                        
                                        @if($biodata->document_submitted)
                                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                                <div class="flex items-start">
                                                    <i class="fas fa-lock text-yellow-600 mt-1 mr-2"></i>
                                                    <div>
                                                        <p class="text-sm text-yellow-800 font-medium">Update Review Tidak Tersedia</p>
                                                        <p class="text-xs text-yellow-700 mt-1">User sudah menyetor berkas fisik. Untuk mencegah inkonsistensi data, perubahan review tidak diizinkan. Silakan hubungi user untuk koordinasi lebih lanjut jika ada perubahan yang diperlukan.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                        <form id="editReviewForm" method="POST" action="{{ route('admin.biodata.review', $biodata) }}" class="space-y-4">
                                            @csrf
                                            
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-3">Ubah Keputusan:</label>
                                                <div class="space-y-2">
                                                    <label class="flex items-center">
                                                        <input type="radio" name="action" value="approve" {{ $biodata->status == 'approved' ? 'checked' : '' }} required class="text-green-600 focus:ring-green-500 border-gray-300">
                                                        <span class="ml-2 text-green-700 font-medium">
                                                            <i class="fas fa-check-circle mr-1"></i>Setujui Biodata
                                                        </span>
                                                    </label>
                                                    <label class="flex items-center">
                                                        <input type="radio" name="action" value="reject" {{ in_array($biodata->status, ['rejected', 'denied']) ? 'checked' : '' }} required class="text-red-600 focus:ring-red-500 border-gray-300">
                                                        <span class="ml-2 text-red-700 font-medium">
                                                            <i class="fas fa-times-circle mr-1"></i>Tolak Biodata
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div>
                                                <label for="rejection_reason_edit" class="block text-sm font-medium text-gray-700 mb-1">
                                                    Catatan/Alasan Penolakan:
                                                    <small class="text-gray-500">(Opsional untuk approval, wajib untuk rejection)</small>
                                                </label>
                                                <textarea 
                                                    id="rejection_reason_edit" 
                                                    name="rejection_reason" 
                                                    rows="3"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm"
                                                    placeholder="Tulis catatan atau alasan penolakan di sini...">{{ $biodata->rejection_reason }}</textarea>
                                            </div>

                                            <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
                                                <i class="fas fa-edit mr-2"></i>UPDATE REVIEW
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Document Tracking (Only for Approved Biodata) -->
                        @if($biodata->status == 'approved')
                        <div class="bg-white rounded-lg shadow p-6 border-2 border-blue-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-clipboard-check mr-2 text-blue-600"></i>Tracking Dokumen & Sertifikat
                            </h3>
                            
                            <!-- Document Submission Status -->
                            <div class="mb-6 pb-6 border-b border-gray-200">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-semibold text-gray-800">
                                        <i class="fas fa-file-upload mr-2 text-orange-500"></i>Status Penyetoran Berkas
                                    </h4>
                                    @if($biodata->document_submitted)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>Sudah Disetor
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>Belum Disetor
                                        </span>
                                    @endif
                                </div>

                                @if($biodata->document_submitted)
                                    <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-3">
                                        <p class="text-sm text-green-800">
                                            <i class="fas fa-calendar-check mr-1"></i>
                                            Disetor pada: <strong>{{ $biodata->document_submitted_at->translatedFormat('d F Y, H:i') }} WITA</strong>
                                        </p>
                                        <p class="text-xs text-green-700 mt-1">
                                            {{ $biodata->document_submitted_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    
                                    {{-- Cancel Document Submitted Button (only if certificate not yet issued) --}}
                                    @if(!$biodata->certificate_issued)
                                        <form method="POST" action="{{ route('admin.biodata.cancel-document-submitted', $biodata) }}" class="mt-3">
                                            @csrf
                                            <button type="submit" 
                                                    onclick="return confirm('⚠️ PERINGATAN!\n\nApakah Anda yakin ingin MEMBATALKAN status \'Berkas Disetor\'?\n\nBiodata akan kembali ke tahap sebelumnya.\n\nLanjutkan pembatalan?')"
                                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 text-sm">
                                                <i class="fas fa-times-circle mr-2"></i>Batalkan "Berkas Disetor"
                                            </button>
                                        </form>
                                        <p class="text-xs text-gray-600 mt-2 text-center">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Gunakan jika admin salah klik. Hanya bisa dibatalkan jika sertifikat belum terbit.
                                        </p>
                                    @endif
                                @else
                                    @php
                                        $deadline = $biodata->getDocumentDeadline();
                                        $daysRemaining = $biodata->getDaysUntilDocumentDeadline();
                                        $isOverdue = $biodata->isDocumentOverdue();
                                    @endphp
                                    
                                    @if($isOverdue)
                                        <div class="bg-red-50 border border-red-300 rounded-lg p-3 mb-3">
                                            <p class="text-sm font-semibold text-red-900">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                TERLAMBAT! Deadline: {{ $deadline->translatedFormat('d F Y') }}
                                            </p>
                                            <p class="text-xs text-red-700 mt-1">
                                                Terlambat {{ abs($daysRemaining) }} hari
                                            </p>
                                        </div>
                                    @elseif($daysRemaining <= 7)
                                        <div class="bg-orange-50 border border-orange-300 rounded-lg p-3 mb-3">
                                            <p class="text-sm font-semibold text-orange-900">
                                                <i class="fas fa-hourglass-half mr-1"></i>
                                                Deadline mendekat: {{ $deadline->translatedFormat('d F Y') }}
                                            </p>
                                            <p class="text-xs text-orange-700 mt-1">
                                                Sisa {{ $daysRemaining }} hari lagi
                                            </p>
                                        </div>
                                    @else
                                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-3">
                                            <p class="text-sm text-blue-800">
                                                <i class="fas fa-calendar-alt mr-1"></i>
                                                Deadline: {{ $deadline->translatedFormat('d F Y') }}
                                            </p>
                                            <p class="text-xs text-blue-700 mt-1">
                                                Sisa {{ $daysRemaining }} hari lagi
                                            </p>
                                        </div>
                                    @endif
                                @endif

                                <!-- Mark as Submitted Form -->
                                @if(!$biodata->document_submitted)
                                    <form method="POST" action="{{ route('admin.biodata.mark-document-submitted', $biodata) }}" class="mt-3">
                                        @csrf
                                        <button type="submit" 
                                                onclick="return confirm('Apakah Anda yakin berkas telah disetor oleh user?')"
                                                class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 text-sm">
                                            <i class="fas fa-check-circle mr-2"></i>Tandai Berkas Sudah Disetor
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <!-- Certificate Issue Status -->
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-semibold text-gray-800">
                                        <i class="fas fa-certificate mr-2 text-blue-500"></i>Status Sertifikat HKI
                                    </h4>
                                    @if($biodata->certificate_issued)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-check-double mr-1"></i>Sudah Terbit
                                        </span>
                                    @elseif($biodata->document_submitted)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-hourglass-half mr-1"></i>Dalam Proses
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-minus-circle mr-1"></i>Menunggu Berkas
                                        </span>
                                    @endif
                                </div>

                                @if($biodata->certificate_issued)
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-3">
                                        <p class="text-sm text-blue-800">
                                            <i class="fas fa-calendar-check mr-1"></i>
                                            Terbit pada: <strong>{{ $biodata->certificate_issued_at->translatedFormat('d F Y, H:i') }} WITA</strong>
                                        </p>
                                        <p class="text-xs text-blue-700 mt-1">
                                            {{ $biodata->certificate_issued_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    
                                    <!-- Email Pencipta untuk Kirim Sertifikat -->
                                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-3">
                                        <div class="flex items-center justify-between mb-2">
                                            <h5 class="text-sm font-semibold text-purple-900">
                                                <i class="fas fa-envelope mr-2"></i>Email Pencipta untuk Kirim Sertifikat
                                            </h5>
                                            <span class="text-xs text-purple-700 bg-purple-100 px-2 py-1 rounded-full">
                                                {{ $biodata->members->count() }} pencipta
                                            </span>
                                        </div>
                                        
                                        <div class="bg-white border border-purple-200 rounded p-3 mb-2">
                                            @php
                                                $allEmails = $biodata->members->pluck('email')->filter()->unique()->values();
                                                $emailList = $allEmails->implode('; ');
                                            @endphp
                                            <div class="flex items-start gap-2">
                                                <div class="flex-1">
                                                    <p class="text-xs text-gray-600 mb-1 font-medium">
                                                        <i class="fas fa-users mr-1"></i>Semua Email Pencipta:
                                                    </p>
                                                    <div id="emailList" class="text-sm text-gray-800 font-mono break-all bg-gray-50 p-2 rounded border border-gray-200">
                                                        {{ $emailList }}
                                                    </div>
                                                </div>
                                                <button type="button" 
                                                        onclick="copyEmails()"
                                                        class="flex-shrink-0 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200 flex items-center gap-2">
                                                    <i class="fas fa-copy"></i>
                                                    <span id="copyButtonText">Copy</span>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="space-y-1">
                                            <p class="text-xs text-purple-800">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                <strong>{{ $allEmails->count() }}</strong> alamat email unik dari <strong>{{ $biodata->members->count() }}</strong> pencipta
                                            </p>
                                            <p class="text-xs text-purple-700">
                                                <i class="fas fa-lightbulb mr-1"></i>
                                                Klik tombol "Copy" untuk menyalin semua email, lalu paste ke email client Anda (Gmail, Outlook, dll) untuk mengirim sertifikat
                                            </p>
                                        </div>
                                    </div>
                                @elseif($biodata->document_submitted)
                                    @php
                                        $certDeadline = $biodata->getCertificateDeadline();
                                        $certDaysRemaining = $biodata->getDaysUntilCertificateDeadline();
                                        $isCertOverdue = $biodata->isCertificateOverdue();
                                    @endphp
                                    
                                    @if($isCertOverdue)
                                        <div class="bg-red-50 border border-red-300 rounded-lg p-3 mb-3">
                                            <p class="text-sm font-semibold text-red-900">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                TERLAMBAT! Estimasi selesai: {{ $certDeadline->translatedFormat('d F Y') }}
                                            </p>
                                            <p class="text-xs text-red-700 mt-1">
                                                Terlambat {{ abs($certDaysRemaining) }} hari. Segera proses penerbitan sertifikat!
                                            </p>
                                        </div>
                                    @elseif($certDaysRemaining <= 5)
                                        <div class="bg-orange-50 border border-orange-300 rounded-lg p-3 mb-3">
                                            <p class="text-sm font-semibold text-orange-900">
                                                <i class="fas fa-hourglass-half mr-1"></i>
                                                Estimasi selesai: {{ $certDeadline->translatedFormat('d F Y') }}
                                            </p>
                                            <p class="text-xs text-orange-700 mt-1">
                                                Sisa {{ $certDaysRemaining }} hari lagi (1 minggu sejak berkas disetor)
                                            </p>
                                        </div>
                                    @else
                                        <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-3">
                                            <p class="text-sm text-green-800">
                                                <i class="fas fa-calendar-alt mr-1"></i>
                                                Estimasi selesai: {{ $certDeadline->translatedFormat('d F Y') }}
                                            </p>
                                            <p class="text-xs text-green-700 mt-1">
                                                Sisa {{ $certDaysRemaining }} hari lagi (1 minggu sejak berkas disetor)
                                            </p>
                                        </div>
                                    @endif
                                @else
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 mb-3">
                                        <p class="text-sm text-gray-700">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Menunggu user menyetor berkas terlebih dahulu
                                        </p>
                                    </div>
                                @endif

                                <!-- Mark as Issued Form -->
                                @if($biodata->document_submitted && !$biodata->certificate_issued)
                                    <form method="POST" action="{{ route('admin.biodata.mark-certificate-issued', $biodata) }}" class="mt-3">
                                        @csrf
                                        <button type="submit" 
                                                onclick="return confirm('Apakah Anda yakin sertifikat HKI sudah terbit?')"
                                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 text-sm">
                                            <i class="fas fa-certificate mr-2"></i>Tandai Sertifikat Sudah Terbit
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Quick Actions (Full Width) -->
                        <div class="bg-gray-50 rounded-lg p-6 shadow">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-tools mr-2 text-red-600"></i>Aksi Cepat
                            </h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                <a href="{{ route('admin.submissions.show', $biodata->submission) }}" 
                                   target="_blank"
                                   class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                    <i class="fas fa-file-alt mr-2"></i>Lihat Submission
                                </a>
                                
                                @if(function_exists('generateWhatsAppUrl'))
                                <a href="{{ generateWhatsAppUrl($biodata->user->phone_number, $biodata->user->country_code ?? '+62', 'Halo ' . $biodata->user->name . ', terkait biodata pengajuan HKI #' . $biodata->submission->id) }}" 
                                   target="_blank"
                                   class="inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                    <i class="fab fa-whatsapp mr-2"></i>Hubungi Pengaju
                                </a>
                                
                                @php
                                    $leaderMember = $biodata->members->firstWhere('is_leader', true);
                                @endphp
                                @if($leaderMember && $leaderMember->nomor_hp && function_exists('generateWhatsAppUrl'))
                                <a href="{{ generateWhatsAppUrl($leaderMember->nomor_hp, '+62', 'Halo ' . $leaderMember->name . ', terkait biodata pengajuan HKI #' . $biodata->submission->id . ' sebagai Pencipta 1 (Ketua)') }}" 
                                   target="_blank"
                                   class="inline-flex items-center justify-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                    <i class="fab fa-whatsapp mr-2"></i>Hubungi Pencipta 1
                                </a>
                                @endif
                                @endif
                            </div>
                        </div>
                    
                    @if($biodata->status === 'pending')
                        </form>
                    @endif
                    
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
    // Auto-require rejection reason when reject is selected
    document.addEventListener('DOMContentLoaded', function() {
        // Handle both forms - pending review and edit review
        function setupFormValidation(formId) {
            const form = document.getElementById(formId);
            if (!form) return;
            
            const rejectedRadio = form.querySelector('input[value="reject"]');
            const approvedRadio = form.querySelector('input[value="approve"]');
            const rejectionReasonTextarea = form.querySelector('textarea[name="rejection_reason"]');
            
            if (rejectedRadio && rejectionReasonTextarea) {
                rejectedRadio.addEventListener('change', function() {
                    if (this.checked) {
                        rejectionReasonTextarea.required = true;
                        rejectionReasonTextarea.focus();
                    }
                });
                
                if (approvedRadio) {
                    approvedRadio.addEventListener('change', function() {
                        if (this.checked) {
                            rejectionReasonTextarea.required = false;
                        }
                    });
                }
            }

            // Add form submit validation
            form.addEventListener('submit', function(e) {
                const approveRadio = form.querySelector('input[value="approve"]');
                const rejectRadio = form.querySelector('input[value="reject"]');
                const rejectionTextarea = form.querySelector('textarea[name="rejection_reason"]');
                
                // Check if reject is selected
                if (rejectRadio && rejectRadio.checked) {
                    const rejectionText = rejectionTextarea.value.trim();
                    
                    // If rejection reason is empty
                    if (rejectionText === '') {
                        e.preventDefault(); // Stop form submission
                        
                        // Show alert
                        alert('⚠️ PERINGATAN!\n\nAnda memilih untuk MENOLAK biodata ini.\nHarap isi Catatan/Alasan Penolakan terlebih dahulu sebelum submit review.\n\nCatatan penolakan wajib diisi agar user mengetahui alasan penolakan dan dapat memperbaiki data yang bermasalah.');
                        
                        // Focus on textarea and highlight it
                        rejectionTextarea.focus();
                        rejectionTextarea.style.borderColor = '#ef4444';
                        rejectionTextarea.style.borderWidth = '2px';
                        
                        // Remove highlight after 3 seconds
                        setTimeout(function() {
                            rejectionTextarea.style.borderColor = '';
                            rejectionTextarea.style.borderWidth = '';
                        }, 3000);
                        
                        return false;
                    }
                    
                    // Show confirmation for rejection
                    e.preventDefault();
                    const confirmReject = confirm(
                        '🚫 KONFIRMASI PENOLAKAN BIODATA\n\n' +
                        '⚠️ Apakah Anda yakin ingin MENOLAK biodata ini?\n\n' +
                        'Pastikan:\n' +
                        '✓ Anda sudah menandai SEMUA field data pencipta yang bermasalah\n' +
                        '✓ Alasan penolakan sudah jelas dan spesifik\n' +
                        '✓ User dapat memahami kesalahan dan memperbaikinya\n\n' +
                        'Klik OK untuk melanjutkan penolakan, atau Cancel untuk kembali.'
                    );
                    
                    if (confirmReject) {
                        form.submit();
                    }
                    return false;
                }
                
                // Check if approve is selected
                if (approveRadio && approveRadio.checked) {
                    e.preventDefault();
                    const confirmApprove = confirm(
                        '✅ KONFIRMASI PERSETUJUAN BIODATA\n\n' +
                        '⚠️ Apakah Anda yakin ingin MENYETUJUI biodata ini?\n\n' +
                        'Pastikan:\n' +
                        '✓ Semua data pencipta sudah diperiksa dengan teliti\n' +
                        '✓ Tidak ada kesalahan data pada semua field\n' +
                        '✓ Data sudah sesuai dengan dokumen yang diajukan\n' +
                        '✓ Biodata siap diproses ke tahap selanjutnya\n\n' +
                        'Setelah disetujui, user dapat melanjutkan ke proses penyetoran berkas.\n\n' +
                        'Klik OK untuk menyetujui, atau Cancel untuk kembali memeriksa.'
                    );
                    
                    if (confirmApprove) {
                        form.submit();
                    }
                    return false;
                }
            });
        }
        
        // Setup validation for both forms with specific IDs
        setupFormValidation('pendingReviewForm');
        setupFormValidation('editReviewForm');
        
        // Handle error flag checkboxes for biodata level
        function handleErrorCheckboxes() {
            const biodataCheckboxes = document.querySelectorAll('input[type="checkbox"][name^="error_"]');
            biodataCheckboxes.forEach(checkbox => {
                // Set initial state based on database value (checked attribute)
                const icon = checkbox.parentElement.querySelector('i.fas.fa-times');
                if (icon) {
                    if (checkbox.checked) {
                        icon.classList.remove('opacity-0');
                        icon.classList.add('opacity-100');
                    } else {
                        icon.classList.remove('opacity-100');
                        icon.classList.add('opacity-0');
                    }
                }
                
                // Handle changes
                checkbox.addEventListener('change', function() {
                    const icon = this.parentElement.querySelector('i.fas.fa-times');
                    if (icon) {
                        if (this.checked) {
                            icon.classList.remove('opacity-0');
                            icon.classList.add('opacity-100');
                        } else {
                            icon.classList.remove('opacity-100');
                            icon.classList.add('opacity-0');
                        }
                    }
                });
            });
            
            // Handle member error checkboxes
            const memberCheckboxes = document.querySelectorAll('input[type="checkbox"][name*="members["]');
            memberCheckboxes.forEach(checkbox => {
                // Set initial state based on database value (checked attribute)
                const icon = checkbox.parentElement.querySelector('i.fas.fa-times');
                if (icon) {
                    if (checkbox.checked) {
                        icon.classList.remove('opacity-0');
                        icon.classList.add('opacity-100');
                    } else {
                        icon.classList.remove('opacity-100');
                        icon.classList.add('opacity-0');
                    }
                }
                
                // Handle changes
                checkbox.addEventListener('change', function() {
                    const icon = this.parentElement.querySelector('i.fas.fa-times');
                    if (icon) {
                        if (this.checked) {
                            icon.classList.remove('opacity-0');
                            icon.classList.add('opacity-100');
                        } else {
                            icon.classList.remove('opacity-100');
                            icon.classList.add('opacity-0');
                        }
                    }
                });
            });
        }
        
        // Function to refresh checkbox states after form submission
        function refreshCheckboxStates() {
            handleErrorCheckboxes();
        }
        
        // Initialize error checkboxes
        handleErrorCheckboxes();
    });
    
    // Function to copy all emails to clipboard
    function copyEmails() {
        const emailText = document.getElementById('emailList').textContent.trim();
        const copyBtn = document.getElementById('copyButtonText');
        
        // Copy to clipboard
        navigator.clipboard.writeText(emailText).then(function() {
            // Success feedback
            copyBtn.innerHTML = '<i class="fas fa-check mr-1"></i>Copied!';
            copyBtn.parentElement.classList.remove('bg-purple-600', 'hover:bg-purple-700');
            copyBtn.parentElement.classList.add('bg-green-600', 'hover:bg-green-700');
            
            // Reset after 2 seconds
            setTimeout(function() {
                copyBtn.textContent = 'Copy';
                copyBtn.parentElement.classList.remove('bg-green-600', 'hover:bg-green-700');
                copyBtn.parentElement.classList.add('bg-purple-600', 'hover:bg-purple-700');
            }, 2000);
        }).catch(function(err) {
            // Error feedback
            alert('Gagal copy email: ' + err);
        });
    }
    </script>

    @include('admin.partials.sidebar-script')
</body>
</html>