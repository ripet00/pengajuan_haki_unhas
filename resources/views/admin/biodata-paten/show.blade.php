<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Biodata Paten - Pengajuan Paten</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('admin.partials.header', ['title' => 'Detail Biodata Paten'])

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
                            <a href="{{ route('admin.biodata-paten.index') }}" class="inline-flex items-center px-5 py-3 bg-white hover:bg-gray-50 text-gray-800 hover:text-gray-900 border-2 border-gray-500 hover:border-gray-700 rounded-lg font-bold transition duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali ke Daftar Biodata
                            </a>
                        </div>

                        <!-- Main Content Grid -->
                        @if($biodataPaten->status === 'pending')
                            <!-- Wrap entire content in review form for pending biodata -->
                            <form id="pendingReviewForm" method="POST" action="{{ route('admin.biodata-paten.review', $biodataPaten) }}">
                                @csrf
                        @endif
                        
                        <!-- Single Column Layout -->
                        <div class="space-y-6">

                        <!-- Header Card -->
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                                    <i class="fas fa-user-friends mr-3 text-red-600"></i>Detail Biodata #{{ $biodataPaten->id }}
                                    @php
                                        $status = $biodataPaten->status;
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
                                                <p class="mt-1 text-sm text-gray-900">{{ $biodataPaten->user->name }}</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                                <p class="mt-1 text-sm text-gray-900">{{ $biodataPaten->user->email }}</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                                                <p class="mt-1 text-sm text-gray-900">{{ $biodataPaten->user->phone_number }}</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Fakultas</label>
                                                <p class="mt-1 text-sm text-gray-900">{{ $biodataPaten->user->faculty }}</p>
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
                                                <p class="mt-1 text-sm text-gray-900">{{ $biodataPaten->submissionPaten->judul_paten }}</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Jenis Karya</label>
                                                <p class="mt-1 text-sm text-gray-900">
                                                    {{ $biodataPaten->submissionPaten->jenisKarya->nama ?? 'Tidak ada' }}
                                                </p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Kategori</label>
                                                <p class="mt-1 text-sm text-gray-900">{{ $biodataPaten->submissionPaten->categories }}</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Jenis File</label>
                                                <p class="mt-1 text-sm text-gray-900">
                                                    @if($biodataPaten->submissionPaten->file_type === 'video')
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
                                <p class="text-sm text-gray-600">Data biodata untuk paten <strong>{{ $submissionPaten->judul_paten }}</strong></p>
                            </div>
                        </div>

                        <!-- Members Information (integrated with per-member cross-check) -->
                        @if($biodataPaten->inventors && $biodataPaten->inventors->count() > 0)
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center justify-between">
                                    <span><i class="fas fa-users mr-2 text-purple-600"></i>Inventor ({{ $biodataPaten->inventors->count() }} Orang)</span>
                                </h3>
                            </div>
                            <div class="p-6 space-y-8">
                                @foreach($biodataPaten->inventors->sortBy('is_leader', SORT_REGULAR, true) as $index => $member)
                                <div class="bg-gray-50 rounded-lg p-6 {{ !$loop->last ? 'mb-6' : '' }}">
                                    <!-- Member Header -->
                                    <div class="flex items-center justify-between mb-6 pb-4 border-b-2 border-gray-300">
                                        <span class="inline-flex items-center px-4 py-2 rounded-full text-base font-bold {{ $member->is_leader ? 'bg-blue-600 text-white' : 'bg-gray-700 text-white' }}">
                                            <i class="fas fa-user mr-2"></i>
                                            Inventor {{ $index + 1 }}{{ $member->is_leader ? ' (Ketua)' : '' }}
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
                                                    Tandai Field dengan Error untuk Inventor {{ $index + 1 }} (Klik untuk expand)
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
                                                    <input type="hidden" name="members[{{ $mid }}][error_pekerjaan]" value="0">
                                                    <input type="hidden" name="members[{{ $mid }}][error_universitas]" value="0">
                                                    <input type="hidden" name="members[{{ $mid }}][error_fakultas]" value="0">
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
                        @if($biodataPaten->status === 'pending')
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
                            
                            @if($biodataPaten->status === 'pending')
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
                                                        <li>Pastikan Anda sudah memeriksa <strong>semua {{ $biodataPaten->inventors->count() }} Inventor</strong> yang terdaftar</li>
                                                        <li>Jika ada data yang bermasalah, <strong>wajib tandai field dengan error</strong> pada tombol toggle di setiap Inventor</li>
                                                        <li>Field yang ditandai error akan ditampilkan dengan latar belakang merah untuk memudahkan user memperbaiki</li>
                                                        <li>Review yang menyeluruh akan mempercepat proses persetujuan Paten</li>
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
                                        @if($biodataPaten->status == 'approved')
                                            <i class="fas fa-check-circle text-green-500 text-3xl"></i>
                                            <h4 class="text-green-700 font-semibold mt-2">Status: Disetujui</h4>
                                        @else
                                            <i class="fas fa-times-circle text-red-500 text-3xl"></i>
                                            <h4 class="text-red-700 font-semibold mt-2">Status: Ditolak</h4>
                                        @endif
                                        
                                        <div class="text-sm text-gray-600 mt-2">
                                            @if($biodataPaten->reviewed_at)
                                                Direview: {{ $biodataPaten->reviewed_at->translatedFormat('d F Y, H:i') }} WITA
                                                <br>
                                            @endif
                                            @if($biodataPaten->reviewedBy)
                                                oleh: {{ $biodataPaten->reviewedBy->name ?? 'Admin' }}
                                            @endif
                                        </div>
                                    </div>

                                    @if($biodataPaten->rejection_reason && in_array($biodataPaten->status, ['rejected', 'denied']))
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                                        <p class="text-sm text-red-700"><strong>Catatan:</strong> {{ $biodataPaten->rejection_reason }}</p>
                                    </div>
                                    @endif

                                    <div class="border-t pt-4">
                                        <p class="text-sm text-gray-600 mb-4 text-center">
                                            <i class="fas fa-edit mr-1"></i>Perlu mengubah keputusan review?
                                        </p>
                                        
                                        @if($biodataPaten->document_submitted)
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
                                        <form id="editReviewForm" method="POST" action="{{ route('admin.biodata-paten.review', $biodataPaten) }}" class="space-y-4">
                                            @csrf

                                            <!-- Error Flags untuk Inventors -->
                                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-4">
                                                <h4 class="text-sm font-bold text-gray-800 mb-3 flex items-center">
                                                    <i class="fas fa-users mr-2 text-purple-600"></i>
                                                    Tandai Error pada Inventor
                                                </h4>
                                                @foreach($biodataPaten->inventors->sortBy('is_leader', SORT_REGULAR, true) as $invIdx => $inv)
                                                <details class="mb-3">
                                                    <summary class="cursor-pointer list-none bg-white border border-gray-300 rounded p-3 hover:bg-gray-50">
                                                        <span class="font-medium text-gray-800">
                                                            <i class="fas fa-user mr-1"></i>
                                                            Inventor {{ $invIdx + 1 }}: {{ $inv->name }}{{ $inv->is_leader ? ' (Ketua)' : '' }}
                                                        </span>
                                                        <i class="fas fa-chevron-down float-right mt-1"></i>
                                                    </summary>
                                                    <div class="mt-2 p-3 bg-white border border-gray-200 rounded">
                                                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2">
                                                            @php
                                                                $errorFields = [
                                                                    'error_name' => 'Nama',
                                                                    'error_pekerjaan' => 'Pekerjaan',
                                                                    'error_universitas' => 'Universitas',
                                                                    'error_fakultas' => 'Fakultas',
                                                                    'error_alamat' => 'Alamat',
                                                                    'error_kelurahan' => 'Kelurahan',
                                                                    'error_kecamatan' => 'Kecamatan',
                                                                    'error_kota_kabupaten' => 'Kota/Kabupaten',
                                                                    'error_provinsi' => 'Provinsi',
                                                                    'error_kode_pos' => 'Kode Pos',
                                                                    'error_email' => 'Email',
                                                                    'error_nomor_hp' => 'Nomor HP',
                                                                    'error_kewarganegaraan' => 'Kewarganegaraan',
                                                                ];
                                                            @endphp
                                                            @foreach($errorFields as $field => $label)
                                                            <label class="flex items-center space-x-2 text-sm cursor-pointer hover:bg-red-50 p-2 rounded">
                                                                <input type="hidden" name="members[{{ $inv->id }}][{{ $field }}]" value="0">
                                                                <input type="checkbox" name="members[{{ $inv->id }}][{{ $field }}]" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500" {{ $inv->$field ? 'checked' : '' }}>
                                                                <span class="text-gray-700">{{ $label }}</span>
                                                            </label>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </details>
                                                @endforeach
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-3">Ubah Keputusan:</label>
                                                <div class="space-y-2">
                                                    <label class="flex items-center">
                                                        <input type="radio" name="action" value="approve" {{ $biodataPaten->status == 'approved' ? 'checked' : '' }} required class="text-green-600 focus:ring-green-500 border-gray-300">
                                                        <span class="ml-2 text-green-700 font-medium">
                                                            <i class="fas fa-check-circle mr-1"></i>Setujui Biodata
                                                        </span>
                                                    </label>
                                                    <label class="flex items-center">
                                                        <input type="radio" name="action" value="reject" {{ in_array($biodataPaten->status, ['rejected', 'denied']) ? 'checked' : '' }} required class="text-red-600 focus:ring-red-500 border-gray-300">
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
                                                    placeholder="Tulis catatan atau alasan penolakan di sini...">{{ $biodataPaten->rejection_reason }}</textarea>
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
                        @if($biodataPaten->status == 'approved')
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
                                    @if($biodataPaten->document_submitted)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>Sudah Disetor
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>Belum Disetor
                                        </span>
                                    @endif
                                </div>

                                @if($biodataPaten->document_submitted)
                                    <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-3">
                                        <p class="text-sm text-green-800">
                                            <i class="fas fa-calendar-check mr-1"></i>
                                            Disetor pada: <strong>{{ $biodataPaten->document_submitted_at->translatedFormat('d F Y, H:i') }} WITA</strong>
                                        </p>
                                        <p class="text-xs text-green-700 mt-1">
                                            {{ $biodataPaten->document_submitted_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    
                                    {{-- Cancel Document Submitted Button (only if patent docs not uploaded AND application document not issued) --}}
                                    @php
                                        $hasAnyPatentDoc = $biodataPaten->deskripsi_pdf || $biodataPaten->klaim_pdf || $biodataPaten->abstrak_pdf || $biodataPaten->gambar_pdf;
                                        $canCancel = !$hasAnyPatentDoc && !$biodataPaten->application_document;
                                    @endphp
                                    
                                    @if($canCancel)
                                        <form method="POST" action="{{ route('admin.biodata-paten.cancel-document-submitted', $biodataPaten) }}" class="mt-3">
                                            @csrf
                                            <button type="submit" 
                                                    onclick="return confirm('⚠️ PERINGATAN!\n\nApakah Anda yakin ingin MEMBATALKAN status \'Berkas Disetor\'?\n\nBiodata akan kembali ke tahap sebelumnya.\n\nLanjutkan pembatalan?')"
                                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 text-sm">
                                                <i class="fas fa-times-circle mr-2"></i>Batalkan "Berkas Disetor"
                                            </button>
                                        </form>
                                        <p class="text-xs text-gray-600 mt-2 text-center">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Gunakan jika admin salah klik. Hanya bisa dibatalkan jika dokumen paten belum diupload dan dokumen permohonan belum terbit.
                                        </p>
                                    @endif
                                @else
                                    @php
                                        $deadline = $biodataPaten->getDocumentDeadline();
                                        $daysRemaining = $biodataPaten->getDaysUntilDocumentDeadline();
                                        $isOverdue = $biodataPaten->isDocumentOverdue();
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
                                @if(!$biodataPaten->document_submitted)
                                    <form method="POST" action="{{ route('admin.biodata-paten.mark-document-submitted', $biodataPaten) }}" class="mt-3">
                                        @csrf
                                        <button type="submit" 
                                                onclick="return confirm('Apakah Anda yakin berkas telah disetor oleh user?')"
                                                class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 text-sm">
                                            <i class="fas fa-check-circle mr-2"></i>Tandai Berkas Sudah Disetor
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <!-- Upload Application Document Status -->
                            @if($biodataPaten->document_submitted)
                            <div class="mb-6 pb-6 border-b border-gray-200">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-semibold text-gray-800">
                                        <i class="fas fa-file-pdf mr-2 text-purple-500"></i>Dokumen Permohonan Paten
                                    </h4>
                                    @if($biodataPaten->application_document)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            <i class="fas fa-check-double mr-1"></i>Sudah Terbit
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-hourglass-half mr-1"></i>Belum Upload
                                        </span>
                                    @endif
                                </div>

                                @if($biodataPaten->application_document)
                                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-3 mb-3">
                                        <p class="text-sm text-purple-800">
                                            <i class="fas fa-calendar-check mr-1"></i>
                                            Terbit pada: <strong>{{ $biodataPaten->document_issued_at->translatedFormat('d F Y, H:i') }} WITA</strong>
                                        </p>
                                        <p class="text-xs text-purple-700 mt-1">
                                            {{ $biodataPaten->document_issued_at->diffForHumans() }}
                                        </p>
                                        <div class="mt-3">
                                            <a href="{{ Storage::url($biodataPaten->application_document) }}" 
                                               target="_blank"
                                               class="inline-flex items-center px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold rounded transition duration-200">
                                                <i class="fas fa-download mr-2"></i>Download Dokumen Permohonan
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    {{-- Check if 3 required patent documents are uploaded first --}}
                                    @php
                                        $hasRequiredPatentDocs = $biodataPaten->deskripsi_pdf && $biodataPaten->klaim_pdf && $biodataPaten->abstrak_pdf;
                                    @endphp
                                    
                                    @if(!$hasRequiredPatentDocs)
                                        {{-- User hasn't uploaded 3 required patent documents yet --}}
                                        <div class="bg-yellow-50 border-2 border-yellow-400 rounded-lg p-4 mb-3">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-exclamation-triangle text-2xl text-yellow-600"></i>
                                                </div>
                                                <div class="ml-3">
                                                    <h5 class="text-sm font-semibold text-yellow-900 mb-2">
                                                        <i class="fas fa-lock mr-1"></i>User Harus Upload Dokumen Paten Terlebih Dahulu
                                                    </h5>
                                                    <p class="text-sm text-yellow-800 mb-3">
                                                        Dokumen permohonan paten hanya dapat diupload setelah user mengupload 3 dokumen paten wajib:
                                                    </p>
                                                    <ul class="text-sm text-yellow-800 space-y-1 ml-5 list-disc">
                                                        <li class="{{ $biodataPaten->deskripsi_pdf ? 'text-green-700 font-semibold' : '' }}">
                                                            {{ $biodataPaten->deskripsi_pdf ? '✓' : '✗' }} Deskripsi (PDF)
                                                        </li>
                                                        <li class="{{ $biodataPaten->klaim_pdf ? 'text-green-700 font-semibold' : '' }}">
                                                            {{ $biodataPaten->klaim_pdf ? '✓' : '✗' }} Klaim (PDF)
                                                        </li>
                                                        <li class="{{ $biodataPaten->abstrak_pdf ? 'text-green-700 font-semibold' : '' }}">
                                                            {{ $biodataPaten->abstrak_pdf ? '✓' : '✗' }} Abstrak (PDF)
                                                        </li>
                                                    </ul>
                                                    <p class="text-xs text-yellow-700 mt-3">
                                                        <i class="fas fa-info-circle mr-1"></i>
                                                        Hubungi user untuk segera mengupload dokumen paten yang kurang.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <button type="button" 
                                                disabled
                                                class="w-full bg-gray-400 text-white text-sm font-semibold py-3 px-4 rounded-lg cursor-not-allowed">
                                            <i class="fas fa-lock mr-2"></i>Menunggu User Upload 3 Dokumen Paten Wajib
                                        </button>
                                    @else
                                        {{-- User has uploaded 3 required docs, admin can now upload application document --}}
                                        <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-3">
                                            <p class="text-sm text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                User telah mengupload 3 dokumen paten wajib. Silakan upload dokumen permohonan paten yang sudah ditandatangani pimpinan (PDF, Max 20MB)
                                            </p>
                                        </div>

                                        <!-- Upload Form -->
                                    <form method="POST" 
                                          action="{{ route('admin.reports-paten.upload-application-document', $biodataPaten) }}"
                                          enctype="multipart/form-data"
                                          id="upload-form-{{ $biodataPaten->id }}"
                                          onsubmit="return confirmUpload{{ $biodataPaten->id }}()"
                                          class="mt-3">
                                        @csrf
                                        <div class="flex gap-2">
                                            <input type="file" 
                                                   name="application_document" 
                                                   id="file-{{ $biodataPaten->id }}"
                                                   accept=".pdf"
                                                   required
                                                   onchange="validateFile{{ $biodataPaten->id }}(this)"
                                                   class="flex-1 text-sm border-4 border-orange-500 bg-yellow-50 rounded-lg px-3 py-2 font-medium shadow-lg hover:border-orange-600 hover:bg-yellow-100 focus:ring-4 focus:ring-orange-300 focus:border-orange-600 cursor-pointer transition-all duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-gradient-to-r file:from-orange-500 file:to-orange-600 file:text-white hover:file:from-orange-600 hover:file:to-orange-700 file:cursor-pointer file:transition-all file:duration-200 file:shadow-md hover:file:shadow-lg">
                                            <button type="submit" 
                                                    id="submit-btn-{{ $biodataPaten->id }}"
                                                    disabled
                                                    class="bg-purple-600 hover:bg-purple-700 disabled:bg-gray-400 text-white text-sm font-semibold py-2 px-4 rounded transition duration-200">
                                                <i class="fas fa-upload mr-2"></i>Upload Dokumen
                                            </button>
                                        </div>
                                        <small class="text-gray-500 text-xs mt-1 block">Format: PDF, Ukuran Maksimal: 20MB</small>
                                    </form>
                                    
                                    <script>
                                        function validateFile{{ $biodataPaten->id }}(input) {
                                            const submitBtn = document.getElementById('submit-btn-{{ $biodataPaten->id }}');
                                            
                                            if (input.files && input.files[0]) {
                                                const file = input.files[0];
                                                
                                                // Validate file type - accept various PDF MIME types
                                                const validPdfMimeTypes = [
                                                    'application/pdf',
                                                    'application/x-pdf',
                                                    'application/acrobat',
                                                    'applications/vnd.pdf',
                                                    'text/pdf',
                                                    'text/x-pdf'
                                                ];
                                                
                                                const isValidMime = validPdfMimeTypes.includes(file.type);
                                                const isValidExtension = file.name.toLowerCase().endsWith('.pdf');
                                                
                                                if (!isValidMime && !isValidExtension) {
                                                    alert('ERROR: File harus berformat PDF!\n\nFile yang dipilih: ' + file.name + '\nTipe: ' + file.type);
                                                    input.value = '';
                                                    submitBtn.disabled = true;
                                                    return false;
                                                }
                                                
                                                // Validate file size (20MB = 20 * 1024 * 1024 bytes)
                                                const maxSize = 20 * 1024 * 1024;
                                                if (file.size > maxSize) {
                                                    const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                                                    alert('ERROR: Ukuran file melebihi batas maksimal!\n\n' +
                                                          'File: ' + file.name + '\n' +
                                                          'Ukuran: ' + fileSizeMB + ' MB\n' +
                                                          'Maksimal: 20 MB');
                                                    input.value = '';
                                                    submitBtn.disabled = true;
                                                    return false;
                                                }
                                                
                                                // File is valid - enable submit button
                                                submitBtn.disabled = false;
                                            } else {
                                                submitBtn.disabled = true;
                                            }
                                        }
                                        
                                        function confirmUpload{{ $biodataPaten->id }}() {
                                            const input = document.getElementById('file-{{ $biodataPaten->id }}');
                                            if (!input.files || !input.files[0]) {
                                                alert('Silakan pilih file terlebih dahulu!');
                                                return false;
                                            }
                                            
                                            const file = input.files[0];
                                            const fileName = file.name;
                                            const fileSize = (file.size / (1024 * 1024)).toFixed(2);
                                            
                                            const confirmMessage = '=== KONFIRMASI UPLOAD DOKUMEN PERMOHONAN PATEN ===\n\n' +
                                                       '================================================\n\n' +
                                                       'Biodata ID: {{ $biodataPaten->id }}\n' +
                                                       'Judul Paten: {{ Str::limit($biodataPaten->submissionPaten->judul_paten, 40) }}\n\n' +
                                                       'File yang akan diupload:\n' +
                                                       '> Nama File: ' + fileName + '\n' +
                                                       '> Ukuran: ' + fileSize + ' MB\n\n' +
                                                       '================================================\n\n' +
                                                       '*** PASTIKAN FILE SUDAH BENAR! ***\n\n' +
                                                       'Lanjutkan upload dokumen permohonan?';
                                            
                                            return confirm(confirmMessage);
                                        }
                                    </script>
                                    @endif
                                @endif
                            </div>
                            @endif

                            <!-- Patent Documents (4 PDFs) Section -->
                            <div class="mb-6 pb-6 border-b border-gray-200">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-semibold text-gray-800">
                                        <i class="fas fa-file-pdf mr-2 text-indigo-500"></i>Dokumen Paten (PDF)
                                    </h4>
                                    @php
                                        $hasRequiredDocs = $biodataPaten->deskripsi_pdf && $biodataPaten->klaim_pdf && $biodataPaten->abstrak_pdf;
                                        $hasAnyDoc = $biodataPaten->deskripsi_pdf || $biodataPaten->klaim_pdf || $biodataPaten->abstrak_pdf || $biodataPaten->gambar_pdf;
                                    @endphp
                                    @if($hasRequiredDocs)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-double mr-1"></i>3 Dokumen Wajib Lengkap
                                        </span>
                                    @elseif($hasAnyDoc)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>Sebagian Terupload
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                            <i class="fas fa-info-circle mr-1"></i>Belum Upload
                                        </span>
                                    @endif
                                </div>

                                @if($hasAnyDoc)
                                    <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4 mb-3">
                                        <div class="grid grid-cols-2 gap-3 mb-3">
                                            <!-- Deskripsi PDF -->
                                            <div class="flex items-center {{ $biodataPaten->deskripsi_pdf ? 'text-green-700' : 'text-gray-400' }}">
                                                <i class="fas {{ $biodataPaten->deskripsi_pdf ? 'fa-check-circle' : 'fa-circle' }} mr-2"></i>
                                                <span class="text-sm font-medium">Deskripsi (Wajib)</span>
                                            </div>
                                            
                                            <!-- Klaim PDF -->
                                            <div class="flex items-center {{ $biodataPaten->klaim_pdf ? 'text-green-700' : 'text-gray-400' }}">
                                                <i class="fas {{ $biodataPaten->klaim_pdf ? 'fa-check-circle' : 'fa-circle' }} mr-2"></i>
                                                <span class="text-sm font-medium">Klaim (Wajib)</span>
                                            </div>
                                            
                                            <!-- Abstrak PDF -->
                                            <div class="flex items-center {{ $biodataPaten->abstrak_pdf ? 'text-green-700' : 'text-gray-400' }}">
                                                <i class="fas {{ $biodataPaten->abstrak_pdf ? 'fa-check-circle' : 'fa-circle' }} mr-2"></i>
                                                <span class="text-sm font-medium">Abstrak (Wajib)</span>
                                            </div>
                                            
                                            <!-- Gambar PDF -->
                                            <div class="flex items-center {{ $biodataPaten->gambar_pdf ? 'text-blue-700' : 'text-gray-400' }}">
                                                <i class="fas {{ $biodataPaten->gambar_pdf ? 'fa-check-circle' : 'fa-circle' }} mr-2"></i>
                                                <span class="text-sm font-medium">Gambar (Opsional)</span>
                                            </div>
                                        </div>

                                        @if($biodataPaten->patent_documents_uploaded_at)
                                            <p class="text-xs text-indigo-700 mb-3">
                                                <i class="fas fa-calendar-check mr-1"></i>
                                                Terakhir diupdate: <strong>{{ $biodataPaten->patent_documents_uploaded_at->translatedFormat('d F Y, H:i') }} WITA</strong>
                                                ({{ $biodataPaten->patent_documents_uploaded_at->diffForHumans() }})
                                            </p>
                                        @endif
                                        
                                        <div class="mt-3">
                                            <a href="{{ route('admin.reports-paten.show-patent-documents', $biodataPaten) }}" 
                                               target="_blank"
                                               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-sm font-semibold rounded-lg transition duration-200 shadow-md hover:shadow-lg">
                                                <i class="fas fa-eye mr-2"></i>Lihat & Download 4 Dokumen PDF
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                                        <p class="text-sm text-gray-600 text-center">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            User belum mengupload dokumen paten
                                        </p>
                                    </div>
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
                                <a href="{{ route('admin.submissions.show', $biodataPaten->submissionPaten) }}" 
                                   target="_blank"
                                   class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                    <i class="fas fa-file-alt mr-2"></i>Lihat Submission
                                </a>
                                
                                @if(function_exists('generateWhatsAppUrl'))
                                <a href="{{ generateWhatsAppUrl($biodataPaten->user->phone_number, $biodataPaten->user->country_code ?? '+62', 'Halo ' . $biodataPaten->user->name . ', terkait biodata pengajuan Paten #' . $biodataPaten->submissionPaten->id) }}" 
                                   target="_blank"
                                   class="inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                    <i class="fab fa-whatsapp mr-2"></i>Hubungi Pengaju
                                </a>
                                
                                @php
                                    $leaderMember = $biodataPaten->inventors->firstWhere('is_leader', true);
                                @endphp
                                @if($leaderMember && $leaderMember->nomor_hp && function_exists('generateWhatsAppUrl'))
                                <a href="{{ generateWhatsAppUrl($leaderMember->nomor_hp, '+62', 'Halo ' . $leaderMember->name . ', terkait biodata pengajuan Paten #' . $biodataPaten->submissionPaten->id . ' sebagai Inventor 1 (Ketua)') }}" 
                                   target="_blank"
                                   class="inline-flex items-center justify-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                    <i class="fab fa-whatsapp mr-2"></i>Hubungi Inventor 1
                                </a>
                                @endif
                                @endif
                            </div>
                        </div>
                    
                    @if($biodataPaten->status === 'pending')
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
                        alert('*** PERINGATAN! ***\n\nAnda memilih untuk MENOLAK biodata ini.\nHarap isi Catatan/Alasan Penolakan terlebih dahulu sebelum submit review.\n\nCatatan penolakan wajib diisi agar user mengetahui alasan penolakan dan dapat memperbaiki data yang bermasalah.');
                        
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
                        '=== KONFIRMASI PENOLAKAN BIODATA ===\n\n' +
                        'Apakah Anda yakin ingin MENOLAK biodata ini?\n\n' +
                        'Pastikan:\n' +
                        '- Anda sudah menandai SEMUA field data Inventor yang bermasalah\n' +
                        '- Alasan penolakan sudah jelas dan spesifik\n' +
                        '- User dapat memahami kesalahan dan memperbaikinya\n\n' +
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
                        '=== KONFIRMASI PERSETUJUAN BIODATA ===\n\n' +
                        'Apakah Anda yakin ingin MENYETUJUI biodata ini?\n\n' +
                        'Pastikan:\n' +
                        '- Semua data Inventor sudah diperiksa dengan teliti\n' +
                        '- Tidak ada kesalahan data pada semua field\n' +
                        '- Data sudah sesuai dengan dokumen yang diajukan\n' +
                        '- Biodata siap diproses ke tahap selanjutnya\n\n' +
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
    </script>

    @include('admin.partials.sidebar-script')
</body>
</html>