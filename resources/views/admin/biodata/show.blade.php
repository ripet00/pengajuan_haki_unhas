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
                            <a href="{{ route('admin.biodata-pengaju.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 transition duration-200">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali ke Daftar Biodata
                            </a>
                        </div>

                        <!-- Main Content Grid -->
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <!-- Left Column - Main Content -->
                            <div class="lg:col-span-2 space-y-6">

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
                                        <label class="block text-sm font-medium text-gray-700">Tempat Ciptaan</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $biodata->tempat_ciptaan ?: '-' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Tanggal Ciptaan</label>
                                        <p class="mt-1 text-sm text-gray-900">
                                            {{ $biodata->tanggal_ciptaan ? $biodata->tanggal_ciptaan->format('d M Y') : '-' }}
                                        </p>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Uraian Singkat</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $biodata->uraian_singkat ?: '-' }}</p>
                                    </div>
                                </div>

                                <!-- Biodata-level error flags (positioned under Detail Biodata) -->
                                <div class="mt-4 border-t pt-4">
                                    <form method="POST" action="{{ route('admin.biodata-pengaju.update-errors', $biodata) }}" class="space-y-3">
                                        @csrf
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
                                        <div>
                                            <button type="submit" class="mt-2 inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded">
                                                <i class="fas fa-save mr-2"></i>Simpan Biodata Error
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Members Information (integrated with per-member cross-check) -->
                        @if($biodata->members && $biodata->members->count() > 0)
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center justify-between">
                                    <span><i class="fas fa-users mr-2 text-purple-600"></i>Pencipta ({{ $biodata->members->count() }})</span>
                                </h3>
                            </div>
                            <div class="p-6">
                                @foreach($biodata->members->sortBy('is_leader', SORT_REGULAR, true) as $index => $member)
                                <div class="mb-6 {{ !$loop->last ? 'border-b border-gray-200 pb-6' : '' }}">
                                    <div class="flex items-center justify-between mb-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $member->is_leader ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                            <i class="fas fa-user mr-1"></i>
                                            Pencipta {{ $index + 1 }}
                                        </span>
                                    </div>
                                    
                                    <form method="POST" action="{{ route('admin.biodata-pengaju.update-errors', $biodata) }}">
                                        @csrf
                                        <input type="hidden" name="members[{{ $member->id }}][id]" value="{{ $member->id }}">

                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                            @php $mid = $member->id; @endphp

                                            <div class="flex items-start justify-between">
                                                <div class="pr-4 w-full">
                                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Lengkap</label>
                                                    <p class="mt-1 text-sm text-gray-900">{{ $member->name ?: '-' }}</p>
                                                </div>
                                                <div class="flex-shrink-0 ml-3">
                                                    <label class="inline-flex items-center cursor-pointer relative">
                                                        <input type="hidden" name="members[{{ $mid }}][error_name]" value="0">
                                                        <input type="checkbox" name="members[{{ $mid }}][error_name]" value="1" class="absolute w-6 h-6 opacity-0 cursor-pointer z-10" aria-label="Tandai Nama salah" {{ $member->error_name ? 'checked' : '' }}>
                                                        <span class="inline-flex items-center justify-center h-6 w-6 border rounded text-red-600 relative">
                                                            <i class="fas fa-times {{ $member->error_name ? 'opacity-100' : 'opacity-0' }} transition-opacity duration-150"></i>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="flex items-start justify-between">
                                                <div class="pr-4 w-full">
                                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">NIK</label>
                                                    <p class="mt-1 text-sm text-gray-900">{{ $member->nik ?: '-' }}</p>
                                                </div>
                                                <div class="flex-shrink-0 ml-3">
                                                    <label class="inline-flex items-center cursor-pointer relative">
                                                        <input type="hidden" name="members[{{ $mid }}][error_nik]" value="0">
                                                        <input type="checkbox" name="members[{{ $mid }}][error_nik]" value="1" class="absolute w-6 h-6 opacity-0 cursor-pointer z-10" aria-label="Tandai NIK salah" {{ $member->error_nik ? 'checked' : '' }}>
                                                        <span class="inline-flex items-center justify-center h-6 w-6 border rounded text-red-600 relative">
                                                            <i class="fas fa-times {{ $member->error_nik ? 'opacity-100' : 'opacity-0' }} transition-opacity duration-150"></i>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="flex items-start justify-between">
                                                <div class="pr-4 w-full">
                                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Pekerjaan</label>
                                                    <p class="mt-1 text-sm text-gray-900">{{ $member->pekerjaan ?: '-' }}</p>
                                                </div>
                                                <div class="flex-shrink-0 ml-3">
                                                    <label class="inline-flex items-center cursor-pointer relative">
                                                        <input type="hidden" name="members[{{ $mid }}][error_pekerjaan]" value="0">
                                                        <input type="checkbox" name="members[{{ $mid }}][error_pekerjaan]" value="1" class="absolute w-6 h-6 opacity-0 cursor-pointer z-10" aria-label="Tandai Pekerjaan salah" {{ $member->error_pekerjaan ? 'checked' : '' }}>
                                                        <span class="inline-flex items-center justify-center h-6 w-6 border rounded text-red-600 relative">
                                                            <i class="fas fa-times {{ $member->error_pekerjaan ? 'opacity-100' : 'opacity-0' }} transition-opacity duration-150"></i>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="flex items-start justify-between">
                                                <div class="pr-4 w-full">
                                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Universitas</label>
                                                    <p class="mt-1 text-sm text-gray-900">{{ $member->universitas ?: '-' }}</p>
                                                </div>
                                                <div class="flex-shrink-0 ml-3">
                                                    <label class="inline-flex items-center cursor-pointer relative">
                                                        <input type="hidden" name="members[{{ $mid }}][error_universitas]" value="0">
                                                        <input type="checkbox" name="members[{{ $mid }}][error_universitas]" value="1" class="absolute w-6 h-6 opacity-0 cursor-pointer z-10" aria-label="Tandai Universitas salah" {{ $member->error_universitas ? 'checked' : '' }}>
                                                        <span class="inline-flex items-center justify-center h-6 w-6 border rounded text-red-600 relative">
                                                            <i class="fas fa-times {{ $member->error_universitas ? 'opacity-100' : 'opacity-0' }} transition-opacity duration-150"></i>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="flex items-start justify-between">
                                                <div class="pr-4 w-full">
                                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Fakultas</label>
                                                    <p class="mt-1 text-sm text-gray-900">{{ $member->fakultas ?: '-' }}</p>
                                                </div>
                                                <div class="flex-shrink-0 ml-3">
                                                    <label class="inline-flex items-center cursor-pointer relative">
                                                        <input type="hidden" name="members[{{ $mid }}][error_fakultas]" value="0">
                                                        <input type="checkbox" name="members[{{ $mid }}][error_fakultas]" value="1" class="absolute w-6 h-6 opacity-0 cursor-pointer z-10" aria-label="Tandai Fakultas salah" {{ $member->error_fakultas ? 'checked' : '' }}>
                                                        <span class="inline-flex items-center justify-center h-6 w-6 border rounded text-red-600 relative">
                                                            <i class="fas fa-times {{ $member->error_fakultas ? 'opacity-100' : 'opacity-0' }} transition-opacity duration-150"></i>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="flex items-start justify-between">
                                                <div class="pr-4 w-full">
                                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Program Studi</label>
                                                    <p class="mt-1 text-sm text-gray-900">{{ $member->program_studi ?: '-' }}</p>
                                                </div>
                                                <div class="flex-shrink-0 ml-3">
                                                    <label class="inline-flex items-center cursor-pointer">
                                                        <input type="hidden" name="members[{{ $mid }}][error_program_studi]" value="0">
                                                        <input type="checkbox" name="members[{{ $mid }}][error_program_studi]" value="1" class="peer hidden" aria-label="Tandai Program Studi salah" {{ $member->error_program_studi ? 'checked' : '' }}>
                                                        <span class="inline-flex items-center justify-center h-6 w-6 border rounded text-red-600">
                                                            <i class="fas fa-times opacity-0 peer-checked:opacity-100 transition-opacity duration-150"></i>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="md:col-span-2 flex items-start justify-between">
                                                <div class="pr-4 w-full">
                                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat</label>
                                                    <p class="mt-1 text-sm text-gray-900">{{ $member->alamat ?: '-' }}</p>
                                                </div>
                                                <div class="flex-shrink-0 ml-3">
                                                    <label class="inline-flex items-center cursor-pointer">
                                                        <input type="hidden" name="members[{{ $mid }}][error_alamat]" value="0">
                                                        <input type="checkbox" name="members[{{ $mid }}][error_alamat]" value="1" class="peer hidden" aria-label="Tandai Alamat salah" {{ $member->error_alamat ? 'checked' : '' }}>
                                                        <span class="inline-flex items-center justify-center h-6 w-6 border rounded text-red-600">
                                                            <i class="fas fa-times opacity-0 peer-checked:opacity-100 transition-opacity duration-150"></i>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="flex items-start justify-between">
                                                <div class="pr-4 w-full">
                                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Kecamatan</label>
                                                    <p class="mt-1 text-sm text-gray-900">{{ $member->kecamatan ?: '-' }}</p>
                                                </div>
                                                <div class="flex-shrink-0 ml-3">
                                                    <label class="inline-flex items-center cursor-pointer">
                                                        <input type="hidden" name="members[{{ $mid }}][error_kecamatan]" value="0">
                                                        <input type="checkbox" name="members[{{ $mid }}][error_kecamatan]" value="1" class="peer hidden" aria-label="Tandai Kecamatan salah" {{ $member->error_kecamatan ? 'checked' : '' }}>
                                                        <span class="inline-flex items-center justify-center h-6 w-6 border rounded text-red-600">
                                                            <i class="fas fa-times opacity-0 peer-checked:opacity-100 transition-opacity duration-150"></i>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="flex items-start justify-between">
                                                <div class="pr-4 w-full">
                                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Kelurahan</label>
                                                    <p class="mt-1 text-sm text-gray-900">{{ $member->kelurahan ?: '-' }}</p>
                                                </div>
                                                <div class="flex-shrink-0 ml-3">
                                                    <label class="inline-flex items-center cursor-pointer">
                                                        <input type="hidden" name="members[{{ $mid }}][error_kelurahan]" value="0">
                                                        <input type="checkbox" name="members[{{ $mid }}][error_kelurahan]" value="1" class="peer hidden" aria-label="Tandai Kelurahan salah" {{ $member->error_kelurahan ? 'checked' : '' }}>
                                                        <span class="inline-flex items-center justify-center h-6 w-6 border rounded text-red-600">
                                                            <i class="fas fa-times opacity-0 peer-checked:opacity-100 transition-opacity duration-150"></i>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="flex items-start justify-between">
                                                <div class="pr-4 w-full">
                                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Kota/Kabupaten</label>
                                                    <p class="mt-1 text-sm text-gray-900">{{ $member->kota_kabupaten ?: '-' }}</p>
                                                </div>
                                                <div class="flex-shrink-0 ml-3">
                                                    <label class="inline-flex items-center cursor-pointer">
                                                        <input type="hidden" name="members[{{ $mid }}][error_kota_kabupaten]" value="0">
                                                        <input type="checkbox" name="members[{{ $mid }}][error_kota_kabupaten]" value="1" class="peer hidden" aria-label="Tandai Kota/Kabupaten salah" {{ $member->error_kota_kabupaten ? 'checked' : '' }}>
                                                        <span class="inline-flex items-center justify-center h-6 w-6 border rounded text-red-600">
                                                            <i class="fas fa-times opacity-0 peer-checked:opacity-100 transition-opacity duration-150"></i>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="flex items-start justify-between">
                                                <div class="pr-4 w-full">
                                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Provinsi</label>
                                                    <p class="mt-1 text-sm text-gray-900">{{ $member->provinsi ?: '-' }}</p>
                                                </div>
                                                <div class="flex-shrink-0 ml-3">
                                                    <label class="inline-flex items-center cursor-pointer">
                                                        <input type="hidden" name="members[{{ $mid }}][error_provinsi]" value="0">
                                                        <input type="checkbox" name="members[{{ $mid }}][error_provinsi]" value="1" class="peer hidden" aria-label="Tandai Provinsi salah" {{ $member->error_provinsi ? 'checked' : '' }}>
                                                        <span class="inline-flex items-center justify-center h-6 w-6 border rounded text-red-600">
                                                            <i class="fas fa-times opacity-0 peer-checked:opacity-100 transition-opacity duration-150"></i>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="flex items-start justify-between">
                                                <div class="pr-4 w-full">
                                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Pos</label>
                                                    <p class="mt-1 text-sm text-gray-900">{{ $member->kode_pos ?: '-' }}</p>
                                                </div>
                                                <div class="flex-shrink-0 ml-3">
                                                    <label class="inline-flex items-center cursor-pointer">
                                                        <input type="hidden" name="members[{{ $mid }}][error_kode_pos]" value="0">
                                                        <input type="checkbox" name="members[{{ $mid }}][error_kode_pos]" value="1" class="peer hidden" aria-label="Tandai Kode Pos salah" {{ $member->error_kode_pos ? 'checked' : '' }}>
                                                        <span class="inline-flex items-center justify-center h-6 w-6 border rounded text-red-600">
                                                            <i class="fas fa-times opacity-0 peer-checked:opacity-100 transition-opacity duration-150"></i>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="flex items-start justify-between">
                                                <div class="pr-4 w-full">
                                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Email</label>
                                                    <p class="mt-1 text-sm text-gray-900">{{ $member->email ?: '-' }}</p>
                                                </div>
                                                <div class="flex-shrink-0 ml-3">
                                                    <label class="inline-flex items-center cursor-pointer">
                                                        <input type="hidden" name="members[{{ $mid }}][error_email]" value="0">
                                                        <input type="checkbox" name="members[{{ $mid }}][error_email]" value="1" class="peer hidden" aria-label="Tandai Email salah" {{ $member->error_email ? 'checked' : '' }}>
                                                        <span class="inline-flex items-center justify-center h-6 w-6 border rounded text-red-600">
                                                            <i class="fas fa-times opacity-0 peer-checked:opacity-100 transition-opacity duration-150"></i>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="flex items-start justify-between">
                                                <div class="pr-4 w-full">
                                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor HP</label>
                                                    <p class="mt-1 text-sm text-gray-900">{{ $member->nomor_hp ?: '-' }}</p>
                                                </div>
                                                <div class="flex-shrink-0 ml-3">
                                                    <label class="inline-flex items-center cursor-pointer">
                                                        <input type="hidden" name="members[{{ $mid }}][error_nomor_hp]" value="0">
                                                        <input type="checkbox" name="members[{{ $mid }}][error_nomor_hp]" value="1" class="peer hidden" aria-label="Tandai Nomor HP salah" {{ $member->error_nomor_hp ? 'checked' : '' }}>
                                                        <span class="inline-flex items-center justify-center h-6 w-6 border rounded text-red-600">
                                                            <i class="fas fa-times opacity-0 peer-checked:opacity-100 transition-opacity duration-150"></i>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="flex items-start justify-between">
                                                <div class="pr-4 w-full">
                                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Kewarganegaraan</label>
                                                    <p class="mt-1 text-sm text-gray-900">{{ $member->kewarganegaraan ?: '-' }}</p>
                                                </div>
                                                <div class="flex-shrink-0 ml-3">
                                                    <label class="inline-flex items-center cursor-pointer">
                                                        <input type="hidden" name="members[{{ $mid }}][error_kewarganegaraan]" value="0">
                                                        <input type="checkbox" name="members[{{ $mid }}][error_kewarganegaraan]" value="1" class="peer hidden" aria-label="Tandai Kewarganegaraan salah" {{ $member->error_kewarganegaraan ? 'checked' : '' }}>
                                                        <span class="inline-flex items-center justify-center h-6 w-6 border rounded text-red-600">
                                                            <i class="fas fa-times opacity-0 peer-checked:opacity-100 transition-opacity duration-150"></i>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-4 text-right">
                                            <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded">
                                                <i class="fas fa-save mr-2"></i>Simpan Tanda untuk Pencipta
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                            </div>
                        </div>
                    </div>

                    <!-- Review Panel -->
                    <div class="lg:col-span-1">
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
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-gavel mr-2 text-red-600"></i>Panel Review
                                </h3>
                        @endif
                            
                            @if($biodata->status === 'pending')
                                <form method="POST" action="{{ route('admin.biodata-pengaju.review', $biodata) }}" class="space-y-4">
                                    @csrf
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-3">Keputusan Review:</label>
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
                                            Catatan/Alasan Penolakan:
                                            <small class="text-gray-500">(Opsional untuk approval, wajib untuk rejection)</small>
                                        </label>
                                        <textarea 
                                            id="rejection_reason" 
                                            name="rejection_reason" 
                                            rows="4"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                            placeholder="Tulis catatan atau alasan penolakan di sini..."></textarea>
                                    </div>

                                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-4 px-6 rounded-lg transition duration-200 transform hover:scale-105 shadow-lg">
                                        <i class="fas fa-gavel mr-2"></i>SUBMIT REVIEW
                                    </button>
                                </form>
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
                                                Direview: {{ $biodata->reviewed_at->format('d F Y, H:i') }} WITA
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
                                        
                                        <form method="POST" action="{{ route('admin.biodata-pengaju.review', $biodata) }}" class="space-y-4">
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
                                    </div>
                                </div>
                            @endif
                        </div>

                        

                        <!-- Quick Actions -->
                        <div class="bg-gray-50 rounded-lg p-6 mt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-tools mr-2 text-red-600"></i>Aksi Cepat
                            </h3>
                            
                            <div class="space-y-3">
                                <a href="{{ route('admin.submissions.show', $biodata->submission) }}" 
                                   target="_blank"
                                   class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                    <i class="fas fa-file-alt mr-2"></i>Lihat Submission
                                </a>
                                
                                @if(function_exists('generateWhatsAppUrl'))
                                <a href="{{ generateWhatsAppUrl($biodata->user->phone_number, $biodata->user->country_code ?? '+62', 'Halo ' . $biodata->user->name . ', terkait biodata pengajuan HKI #' . $biodata->submission->id) }}" 
                                   target="_blank"
                                   class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                    <i class="fab fa-whatsapp mr-2"></i>Hubungi Pengaju
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
    // Auto-require rejection reason when reject is selected
    document.addEventListener('DOMContentLoaded', function() {
        // Handle both forms - pending review and edit review
        function setupFormValidation(formSelector) {
            const form = document.querySelector(formSelector);
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
        }
        
        // Setup validation for both forms
        setupFormValidation('form'); // This will handle all forms on the page
        
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
    </script>
</body>
</html>