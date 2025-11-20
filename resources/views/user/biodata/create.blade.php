<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isEdit ? 'Edit' : 'Buat' }} Biodata - HKI Unhas</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
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
            <a href="{{ route('user.submissions.show', $submission) }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Detail Submission
            </a>
        </div>

        <!-- Header Section -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-2xl font-semibold text-gray-900">
                    <i class="fas fa-user-friends mr-3 text-blue-600"></i>
                    {{ $isEdit ? 'Edit' : 'Buat' }} Biodata Karya Cipta
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Submission: <strong>{{ $submission->title }}</strong> (ID: #{{ $submission->id }})
                </p>
            </div>

            <!-- Progress Info -->
            <div class="px-6 py-4">
                @if($isEdit)
                    @if($biodata && $biodata->status == 'denied')
                        <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                            <h4 class="text-sm font-semibold text-red-800 mb-2">
                                <i class="fas fa-exclamation-triangle mr-1"></i>Biodata Ditolak
                            </h4>
                            <p class="text-sm text-red-700">{{ $biodata ? $biodata->rejection_reason : '' }}</p>
                            <p class="text-sm text-red-600 mt-2">Silakan perbaiki biodata sesuai dengan catatan admin.</p>
                        </div>
                    @else
                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <h4 class="text-sm font-semibold text-blue-800 mb-2">
                                <i class="fas fa-edit mr-1"></i>Mode Edit Biodata
                            </h4>
                            <p class="text-sm text-blue-700">Anda sedang mengedit biodata yang telah dibuat sebelumnya.</p>
                        </div>
                    @endif
                @else
                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                        <h4 class="text-sm font-semibold text-green-800 mb-2">
                            <i class="fas fa-check-circle mr-1"></i>Submission Disetujui
                        </h4>
                        <p class="text-sm text-green-700">Lengkapi biodata untuk melanjutkan proses pengajuan HKI Anda.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terjadi kesalahan validasi:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form Section -->
        <form method="POST" action="{{ route('user.biodata.store', $submission) }}" class="space-y-6">
            @csrf
            
            <!-- Biodata Information -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Karya Cipta</h3>
                </div>
                
                <div class="p-6 space-y-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Karya Cipta</label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               value="{{ $submission->title }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed"
                               readonly
                               disabled>
                        <p class="mt-1 text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Judul ini diambil otomatis dari submission yang telah disetujui
                        </p>
                    </div>                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="tempat_ciptaan" class="block text-sm font-medium text-gray-700 mb-1">
                                Tempat Ciptaan *
                                @if($biodata && $biodata->error_tempat_ciptaan)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                    </span>
                                @endif
                            </label>
                            <input type="text" 
                                   id="tempat_ciptaan" 
                                   name="tempat_ciptaan" 
                                   value="{{ old('tempat_ciptaan', $biodata ? $biodata->tempat_ciptaan : '') }}"
                                   placeholder="{{ $biodata && $biodata->error_tempat_ciptaan ? 'Admin menandai field ini perlu diperbaiki' : 'Contoh: Makassar, Jakarta, Bandung' }}"
                                   class="w-full px-3 py-2 border {{ $biodata && $biodata->error_tempat_ciptaan ? 'border-red-300 bg-red-50' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>Isi dengan nama kota/kabupaten tempat karya cipta dibuat
                            </p>
                            @error('tempat_ciptaan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tanggal_ciptaan" class="block text-sm font-medium text-gray-700 mb-1">
                                Tanggal Ciptaan *
                                @if($biodata && $biodata->error_tanggal_ciptaan)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                    </span>
                                @endif
                            </label>
                            <input type="date" 
                                   id="tanggal_ciptaan" 
                                   name="tanggal_ciptaan" 
                                   value="{{ old('tanggal_ciptaan', $biodata && $biodata->tanggal_ciptaan ? $biodata->tanggal_ciptaan->format('Y-m-d') : '') }}"
                                   class="w-full px-3 py-2 border {{ $biodata && $biodata->error_tanggal_ciptaan ? 'border-red-300 bg-red-50' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                            @error('tanggal_ciptaan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="uraian_singkat" class="block text-sm font-medium text-gray-700 mb-1">
                            Uraian Singkat Karya Cipta *
                            @if($biodata && $biodata->error_uraian_singkat)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                </span>
                            @endif
                        </label>
                        <textarea id="uraian_singkat" 
                                  name="uraian_singkat" 
                                  rows="4"
                                  placeholder="{{ $biodata && $biodata->error_uraian_singkat ? 'Admin menandai field ini perlu diperbaiki' : 'Masukkan uraian singkat karya cipta maksimal 2 kalimat.' }}"
                                  class="w-full px-3 py-2 border {{ $biodata && $biodata->error_uraian_singkat ? 'border-red-300 bg-red-50' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  required>{{ old('uraian_singkat', $biodata ? $biodata->uraian_singkat : '') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>Cukup tuliskan maksimal 2 kalimat yang menjelaskan inti dari karya cipta
                        </p>
                        @error('uraian_singkat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Members Section -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Data Pencipta</h3>
                        <button type="button" 
                                id="add-member-btn" 
                                class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200">
                            <i class="fas fa-plus mr-1"></i>Tambah Anggota
                        </button>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">Maksimal 10 orang pencipta (termasuk ketua)</p>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mt-2 space-y-2">
                        <p class="text-sm text-blue-800">
                            <i class="fas fa-info-circle mr-1"></i>
                            <strong>Field Wajib Diisi: Semua field dengan tanda bintang (*) wajib diisi dan pastikan data sudah benar dan lengkap sebelum submit.</strong> 
                        </p>
                        <p class="text-sm text-blue-800">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            <strong>Urutan Pencipta: Pastikan urutan nama pencipta sudah sesuai dengan yang tertera pada karya cipta (Pencipta ke-1 adalah ketua/penulis pertama).</strong>
                        </p>
                    </div>
                </div>
                
                <div id="members-container" class="divide-y divide-gray-200">
                    <!-- Members will be added here by JavaScript -->
                </div>
            </div>

            <!-- Submit Section -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900">Review & Submit</h4>
                            <p class="text-sm text-gray-600">
                                @if($biodata && $biodata->status === 'denied')
                                    Perbaiki data yang ditandai admin dan submit ulang biodata.
                                @else
                                    Pastikan semua data telah diisi dengan benar sebelum submit.
                                @endif
                            </p>
                            <div class="mt-2 text-xs text-gray-500">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                Semua field bertanda (*) wajib diisi untuk setiap pencipta
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('user.submissions.show', $submission) }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 text-sm font-medium rounded-lg transition duration-200">
                                <i class="fas fa-times mr-2"></i>Batal
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2 {{ $biodata && $biodata->status === 'denied' ? 'bg-red-600 hover:bg-red-700' : 'bg-blue-600 hover:bg-blue-700' }} text-white font-medium rounded-lg transition duration-200">
                                @if($biodata && $biodata->status === 'denied')
                                    <i class="fas fa-redo mr-2"></i>Submit Revisi Biodata
                                @else
                                    <i class="fas fa-save mr-2"></i>{{ $isEdit ? 'Update' : 'Simpan' }} Biodata
                                @endif
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>

    <script>
        let memberCount = 0;
        const maxMembers = 10;
        
        // Existing members data from server
        const existingMembers = @json($members ? $members->toArray() : []);
        
        function createMemberForm(index, memberData = {}) {
            const isLeader = index === 0;
            const member = memberData || {};
            
            return `
                <div class="member-form p-6" data-member-index="${index}">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-md font-semibold text-gray-900">
                            <i class="fas fa-user mr-2"></i>
                            Pencipta ke-${index + 1} ${isLeader ? '(Ketua)' : ''}
                        </h4>
                        ${!isLeader ? `
                            <button type="button" 
                                    class="remove-member-btn text-red-600 hover:text-red-800 transition duration-200"
                                    onclick="removeMember(${index})">
                                <i class="fas fa-trash"></i>
                            </button>
                        ` : ''}
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Lengkap *
                                ${member.error_name ? `
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                    </span>
                                ` : ''}
                            </label>
                            <input type="text" 
                                   name="members[${index}][name]" 
                                   value="${member.name || ''}"
                                   placeholder="${member.error_name ? 'Admin menandai field ini perlu diperbaiki' : 'Contoh: Dr. Ir. Ahmad Sudirman, M.T.'}"
                                   class="w-full px-3 py-2 border ${member.error_name ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>Isi dengan nama lengkap beserta gelar (jika ada)
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                NIK *
                                ${member.error_nik ? `
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                    </span>
                                ` : ''}
                            </label>
                            <input type="text" 
                                   name="members[${index}][nik]" 
                                   value="${member.nik || ''}"
                                   placeholder="${member.error_nik ? 'Admin menandai field ini perlu diperbaiki' : 'Masukkan NIK 16 digit'}"
                                   pattern="[0-9]{16}"
                                   maxlength="16"
                                   class="w-full px-3 py-2 border ${member.error_nik ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>NIK harus 16 digit angka
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                NPWP
                                ${member.error_npwp ? `
                                    <span class="text-red-600 text-xs ml-1">
                                        <i class="fas fa-exclamation-circle"></i> Admin menandai field ini perlu diperbaiki
                                    </span>
                                ` : ''}
                            </label>
                            <input type="text" 
                                   name="members[${index}][npwp]" 
                                   value="${member.npwp || ''}"
                                   placeholder="${member.error_npwp ? 'Admin menandai field ini perlu diperbaiki' : 'Masukkan NPWP (opsional)'}"
                                   class="w-full px-3 py-2 border ${member.error_npwp ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>Opsional - Nomor Pokok Wajib Pajak
                            </p>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Jenis Kelamin *
                        </label>
                        <select name="members[${index}][jenis_kelamin]" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Pria" ${member.jenis_kelamin === 'Pria' ? 'selected' : ''}>Pria</option>
                            <option value="Wanita" ${member.jenis_kelamin === 'Wanita' ? 'selected' : ''}>Wanita</option>
                        </select>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Pekerjaan *
                                ${member.error_pekerjaan ? `
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                    </span>
                                ` : ''}
                            </label>
                            <input type="text" 
                                   name="members[${index}][pekerjaan]" 
                                   value="${member.pekerjaan || ''}"
                                   placeholder="${member.error_pekerjaan ? 'Admin menandai field ini perlu diperbaiki' : 'Masukkan pekerjaan'}"
                                   class="w-full px-3 py-2 border ${member.error_pekerjaan ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Universitas *
                                ${member.error_universitas ? `
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                    </span>
                                ` : ''}
                            </label>
                            <input type="text" 
                                   name="members[${index}][universitas]" 
                                   value="${member.universitas || ''}"
                                   placeholder="${member.error_universitas ? 'Admin menandai field ini perlu diperbaiki' : 'Masukkan universitas'}"
                                   class="w-full px-3 py-2 border ${member.error_universitas ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Fakultas *
                                ${member.error_fakultas ? `
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                    </span>
                                ` : ''}
                            </label>
                            <input type="text" 
                                   name="members[${index}][fakultas]" 
                                   value="${member.fakultas || ''}"
                                   placeholder="${member.error_fakultas ? 'Admin menandai field ini perlu diperbaiki' : 'Masukkan fakultas'}"
                                   class="w-full px-3 py-2 border ${member.error_fakultas ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Program Studi *
                                ${member.error_program_studi ? `
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                    </span>
                                ` : ''}
                            </label>
                            <input type="text" 
                                   name="members[${index}][program_studi]" 
                                   value="${member.program_studi || ''}"
                                   placeholder="${member.error_program_studi ? 'Admin menandai field ini perlu diperbaiki' : 'Masukkan program studi'}"
                                   class="w-full px-3 py-2 border ${member.error_program_studi ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Alamat *
                                ${member.error_alamat ? `
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                    </span>
                                ` : ''}
                            </label>
                            <textarea name="members[${index}][alamat]" 
                                      rows="2"
                                      placeholder="${member.error_alamat ? 'Admin menandai field ini perlu diperbaiki' : 'Masukkan alamat lengkap'}"
                                      class="w-full px-3 py-2 border ${member.error_alamat ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      required>${member.alamat || ''}</textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Kewarganegaraan *
                                ${member.error_kewarganegaraan ? `
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                    </span>
                                ` : ''}
                            </label>
                            <select name="members[${index}][kewarganegaraan_type]" 
                                    id="kewarganegaraan_type_${index}"
                                    data-member-index="${index}"
                                    class="kewarganegaraan-type-select w-full px-3 py-2 border ${member.error_kewarganegaraan ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                                <option value="">Pilih Kewarganegaraan</option>
                                <option value="Indonesia" ${(member.kewarganegaraan || 'Indonesia') === 'Indonesia' ? 'selected' : ''}>Indonesia</option>
                                <option value="Warga Negara Asing" ${(member.kewarganegaraan && member.kewarganegaraan !== 'Indonesia') ? 'selected' : ''}>Warga Negara Asing</option>
                            </select>
                        </div>
                        
                        <div id="kewarganegaraan_asing_div_${index}" class="kewarganegaraan-asing-container" style="display: ${(member.kewarganegaraan && member.kewarganegaraan !== 'Indonesia') ? 'block' : 'none'}">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Negara Asal *
                                ${member.error_kewarganegaraan ? `
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                    </span>
                                ` : ''}
                            </label>
                            <input type="text" 
                                   name="members[${index}][kewarganegaraan_asing]" 
                                   id="kewarganegaraan_asing_${index}"
                                   value="${(member.kewarganegaraan && member.kewarganegaraan !== 'Indonesia') ? member.kewarganegaraan : ''}"
                                   placeholder="Contoh: Malaysia, Singapura, Amerika Serikat"
                                   class="kewarganegaraan-asing-input w-full px-3 py-2 border ${member.error_kewarganegaraan ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <!-- Hidden input to store final kewarganegaraan value -->
                        <input type="hidden" name="members[${index}][kewarganegaraan]" id="kewarganegaraan_final_${index}" value="${member.kewarganegaraan || 'Indonesia'}">
                        
                        <div class="wilayah-container" id="wilayah_container_${index}" style="display: ${(member.kewarganegaraan || 'Indonesia') === 'Indonesia' ? 'contents' : 'none'}">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Provinsi *
                                    ${member.error_provinsi ? `
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                        </span>
                                    ` : ''}
                                </label>
                                <select name="members[${index}][provinsi]" 
                                        id="provinsi_${index}"
                                        data-member-index="${index}"
                                        class="provinsi-select w-full px-3 py-2 border ${member.error_provinsi ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Pilih Provinsi</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Kota/Kabupaten *
                                    ${member.error_kota_kabupaten ? `
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                        </span>
                                    ` : ''}
                                </label>
                                <select name="members[${index}][kota_kabupaten]" 
                                        id="kota_kabupaten_${index}"
                                        data-member-index="${index}"
                                        class="kota-select w-full px-3 py-2 border ${member.error_kota_kabupaten ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        disabled>
                                    <option value="">Pilih Kota/Kabupaten</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Kecamatan *
                                    ${member.error_kecamatan ? `
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                        </span>
                                    ` : ''}
                                </label>
                                <select name="members[${index}][kecamatan]" 
                                        id="kecamatan_${index}"
                                        data-member-index="${index}"
                                        class="kecamatan-select w-full px-3 py-2 border ${member.error_kecamatan ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        disabled>
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Kelurahan *
                                    ${member.error_kelurahan ? `
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                        </span>
                                    ` : ''}
                                </label>
                                <select name="members[${index}][kelurahan]" 
                                        id="kelurahan_${index}"
                                        data-member-index="${index}"
                                        class="kelurahan-select w-full px-3 py-2 border ${member.error_kelurahan ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        disabled>
                                    <option value="">Pilih Kelurahan</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="non-wilayah-container" id="non_wilayah_container_${index}" style="display: ${member.kewarganegaraan === 'Asing' ? 'contents' : 'none'}">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Provinsi/Negara Bagian *
                                    ${member.error_provinsi ? `
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                        </span>
                                    ` : ''}
                                </label>
                                <input type="text" 
                                       name="members[${index}][provinsi_manual]" 
                                       id="provinsi_manual_${index}"
                                       value="${member.kewarganegaraan === 'Asing' ? member.provinsi || '' : ''}"
                                       placeholder="Masukkan provinsi/negara bagian"
                                       class="w-full px-3 py-2 border ${member.error_provinsi ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Kota *
                                    ${member.error_kota_kabupaten ? `
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                        </span>
                                    ` : ''}
                                </label>
                                <input type="text" 
                                       name="members[${index}][kota_manual]" 
                                       id="kota_manual_${index}"
                                       value="${member.kewarganegaraan === 'Asing' ? member.kota_kabupaten || '' : ''}"
                                       placeholder="Masukkan nama kota"
                                       class="w-full px-3 py-2 border ${member.error_kota_kabupaten ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Kecamatan/Distrik
                                    ${member.error_kecamatan ? `
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                        </span>
                                    ` : ''}
                                </label>
                                <input type="text" 
                                       name="members[${index}][kecamatan_manual]" 
                                       id="kecamatan_manual_${index}"
                                       value="${member.kewarganegaraan === 'Asing' ? member.kecamatan || '' : ''}"
                                       placeholder="Masukkan kecamatan/distrik (opsional)"
                                       class="w-full px-3 py-2 border ${member.error_kecamatan ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Kelurahan/Desa
                                    ${member.error_kelurahan ? `
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                        </span>
                                    ` : ''}
                                </label>
                                <input type="text" 
                                       name="members[${index}][kelurahan_manual]" 
                                       id="kelurahan_manual_${index}"
                                       value="${member.kewarganegaraan === 'Asing' ? member.kelurahan || '' : ''}"
                                       placeholder="Masukkan kelurahan/desa (opsional)"
                                       class="w-full px-3 py-2 border ${member.error_kelurahan ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Kode Pos *
                                ${member.error_kode_pos ? `
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                    </span>
                                ` : ''}
                            </label>
                            <input type="text" 
                                   name="members[${index}][kode_pos]" 
                                   value="${member.kode_pos || ''}"
                                   placeholder="${member.error_kode_pos ? 'Admin menandai field ini perlu diperbaiki' : 'Masukkan kode pos'}"
                                   class="w-full px-3 py-2 border ${member.error_kode_pos ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Email *
                                ${member.error_email ? `
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                    </span>
                                ` : ''}
                            </label>
                            <input type="email" 
                                   name="members[${index}][email]" 
                                   value="${member.email || ''}"
                                   placeholder="${member.error_email ? 'Admin menandai field ini perlu diperbaiki' : 'Masukkan email'}"
                                   class="w-full px-3 py-2 border ${member.error_email ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nomor HP *
                                ${member.error_nomor_hp ? `
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                    </span>
                                ` : ''}
                            </label>
                            <input type="text" 
                                   name="members[${index}][nomor_hp]" 
                                   value="${member.nomor_hp || ''}"
                                   placeholder="${member.error_nomor_hp ? 'Admin menandai field ini perlu diperbaiki' : 'Masukkan nomor HP'}"
                                   class="w-full px-3 py-2 border ${member.error_nomor_hp ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                        </div>
                    </div>
                </div>
            `;
        }
        
        function addMember() {
            if (memberCount >= maxMembers) {
                alert('Maksimal 10 anggota diperbolehkan.');
                return;
            }
            
            const container = document.getElementById('members-container');
            const memberHtml = createMemberForm(memberCount);
            container.insertAdjacentHTML('beforeend', memberHtml);
            
            // Populate provinces for the new member
            if (window.provincesData) {
                const provinsiSelect = document.getElementById(`provinsi_${memberCount}`);
                if (provinsiSelect) {
                    populateSelect(provinsiSelect, window.provincesData, 'Pilih Provinsi');
                }
            }
            
            memberCount++;
            updateAddButton();
        }
        
        function removeMember(index) {
            const memberForm = document.querySelector(`[data-member-index="${index}"]`);
            if (memberForm) {
                memberForm.remove();
                updateMemberIndexes();
                updateAddButton();
            }
        }
        
        function updateMemberIndexes() {
            const memberForms = document.querySelectorAll('.member-form');
            memberCount = 0;
            
            memberForms.forEach((form, newIndex) => {
                form.setAttribute('data-member-index', newIndex);
                
                // Update form title
                const title = form.querySelector('h4');
                const isLeader = newIndex === 0;
                title.innerHTML = `
                    <i class="fas fa-user mr-2"></i>
                    Pencipta ke-${newIndex + 1} ${isLeader ? '(Ketua)' : ''}
                `;
                
                // Update remove button
                const removeBtn = form.querySelector('.remove-member-btn');
                if (removeBtn) {
                    if (isLeader) {
                        removeBtn.style.display = 'none';
                    } else {
                        removeBtn.style.display = 'block';
                        removeBtn.setAttribute('onclick', `removeMember(${newIndex})`);
                    }
                }
                
                // Update all input names
                const inputs = form.querySelectorAll('input, textarea');
                inputs.forEach(input => {
                    const name = input.getAttribute('name');
                    if (name && name.includes('members[')) {
                        const newName = name.replace(/members\[\d+\]/, `members[${newIndex}]`);
                        input.setAttribute('name', newName);
                    }
                });
                
                memberCount++;
            });
        }
        
        function updateAddButton() {
            const addButton = document.getElementById('add-member-btn');
            if (memberCount >= maxMembers) {
                addButton.style.display = 'none';
            } else {
                addButton.style.display = 'inline-flex';
            }
        }
        
        // Function to load existing wilayah data for a member
        async function loadExistingWilayahData(index, member) {
            if (!member.provinsi) return;
            
            const provinsiSelect = document.getElementById(`provinsi_${index}`);
            const kotaSelect = document.getElementById(`kota_kabupaten_${index}`);
            const kecamatanSelect = document.getElementById(`kecamatan_${index}`);
            const kelurahanSelect = document.getElementById(`kelurahan_${index}`);
            
            try {
                // Wait for provinces to load first
                await new Promise(resolve => {
                    const checkProvinces = setInterval(() => {
                        if (window.provincesData && provinsiSelect.options.length > 1) {
                            clearInterval(checkProvinces);
                            resolve();
                        }
                    }, 100);
                });
                
                // Set provinsi
                for (let option of provinsiSelect.options) {
                    if (option.value === member.provinsi) {
                        provinsiSelect.value = member.provinsi;
                        const provinceCode = option.getAttribute('data-kode');
                        
                        // Load and set kota
                        if (provinceCode && member.kota_kabupaten) {
                            const citiesResponse = await fetch(`{{ url('users/api/wilayah/cities') }}/${provinceCode}`);
                            const citiesData = await citiesResponse.json();
                            populateSelect(kotaSelect, citiesData, 'Pilih Kota/Kabupaten');
                            kotaSelect.disabled = false;
                            
                            // Set kota
                            for (let option of kotaSelect.options) {
                                if (option.value === member.kota_kabupaten) {
                                    kotaSelect.value = member.kota_kabupaten;
                                    const cityCode = option.getAttribute('data-kode');
                                    
                                    // Load and set kecamatan
                                    if (cityCode && member.kecamatan) {
                                        const districtsResponse = await fetch(`{{ url('users/api/wilayah/districts') }}/${cityCode}`);
                                        const districtsData = await districtsResponse.json();
                                        populateSelect(kecamatanSelect, districtsData, 'Pilih Kecamatan');
                                        kecamatanSelect.disabled = false;
                                        
                                        // Set kecamatan
                                        for (let option of kecamatanSelect.options) {
                                            if (option.value === member.kecamatan) {
                                                kecamatanSelect.value = member.kecamatan;
                                                const districtCode = option.getAttribute('data-kode');
                                                
                                                // Load and set kelurahan
                                                if (districtCode && member.kelurahan) {
                                                    const villagesResponse = await fetch(`{{ url('users/api/wilayah/villages') }}/${districtCode}`);
                                                    const villagesData = await villagesResponse.json();
                                                    populateSelect(kelurahanSelect, villagesData, 'Pilih Kelurahan');
                                                    kelurahanSelect.disabled = false;
                                                    kelurahanSelect.value = member.kelurahan;
                                                }
                                                break;
                                            }
                                        }
                                    }
                                    break;
                                }
                            }
                        }
                        break;
                    }
                }
            } catch (error) {
                console.error('Error loading existing wilayah data:', error);
            }
        }
        
        // Initialize form
        document.addEventListener('DOMContentLoaded', function() {
            // Load provinces data for all members
            loadProvinces();
            
            // Add existing members or create first member
            if (existingMembers.length > 0) {
                existingMembers.forEach((member, index) => {
                    const container = document.getElementById('members-container');
                    const memberHtml = createMemberForm(index, member);
                    container.insertAdjacentHTML('beforeend', memberHtml);
                    memberCount++;
                    
                    // Initialize wilayah handlers for this member
                    initializeWilayahHandlers(index);
                    
                    // Load existing wilayah data if member is WNI
                    if (!member.kewarganegaraan || member.kewarganegaraan === 'Indonesia') {
                        loadExistingWilayahData(index, member);
                    }
                });
            } else {
                // Create first member (leader) with user data
                addMember();
                initializeWilayahHandlers(0);
            }
            
            updateAddButton();
            
            // Add event listener for add member button
            document.getElementById('add-member-btn').addEventListener('click', function() {
                addMember();
                initializeWilayahHandlers(memberCount - 1);
            });
            
            // Add form validation before submit
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                console.log('Form submit triggered');
                
                // Handle kewarganegaraan_final and manual inputs for Asing nationality
                document.querySelectorAll('.kewarganegaraan-type-select').forEach((typeSelect, idx) => {
                    const memberIndex = typeSelect.id.replace('kewarganegaraan_type_', '');
                    
                    if (typeSelect.value === 'Warga Negara Asing') {
                        const kewarganegaraanAsingInput = document.getElementById(`kewarganegaraan_asing_${memberIndex}`);
                        const kewarganegaraanFinal = document.getElementById(`kewarganegaraan_final_${memberIndex}`);
                        
                        // Validate kewarganegaraan_asing is filled when visible
                        const kewarganegaraanAsingDiv = document.getElementById(`kewarganegaraan_asing_div_${memberIndex}`);
                        if (kewarganegaraanAsingDiv && kewarganegaraanAsingDiv.style.display !== 'none') {
                            if (!kewarganegaraanAsingInput || !kewarganegaraanAsingInput.value.trim()) {
                                alert('Mohon isi Negara Asal untuk Warga Negara Asing pada anggota ke-' + (parseInt(memberIndex) + 1));
                                kewarganegaraanAsingInput.focus();
                                kewarganegaraanAsingInput.classList.add('border-red-500', 'bg-red-50');
                                return false;
                            }
                        }
                        
                        // Update final kewarganegaraan value
                        if (kewarganegaraanAsingInput && kewarganegaraanAsingInput.value) {
                            kewarganegaraanFinal.value = kewarganegaraanAsingInput.value;
                        }
                        
                        // Copy values from manual inputs to main inputs for wilayah
                        const provinsiManual = document.getElementById(`provinsi_manual_${memberIndex}`);
                        const kotaManual = document.getElementById(`kota_manual_${memberIndex}`);
                        const kecamatanManual = document.getElementById(`kecamatan_manual_${memberIndex}`);
                        const kelurahanManual = document.getElementById(`kelurahan_manual_${memberIndex}`);
                        
                        const provinsiSelect = document.getElementById(`provinsi_${memberIndex}`);
                        const kotaSelect = document.getElementById(`kota_kabupaten_${memberIndex}`);
                        const kecamatanSelect = document.getElementById(`kecamatan_${memberIndex}`);
                        const kelurahanSelect = document.getElementById(`kelurahan_${memberIndex}`);
                        
                        if (provinsiManual && provinsiManual.value) {
                            // Create temporary option and select it
                            provinsiSelect.innerHTML = `<option value="${provinsiManual.value}" selected>${provinsiManual.value}</option>`;
                            provinsiSelect.disabled = false;
                        }
                        if (kotaManual && kotaManual.value) {
                            kotaSelect.innerHTML = `<option value="${kotaManual.value}" selected>${kotaManual.value}</option>`;
                            kotaSelect.disabled = false;
                        }
                        if (kecamatanManual && kecamatanManual.value) {
                            kecamatanSelect.innerHTML = `<option value="${kecamatanManual.value}" selected>${kecamatanManual.value}</option>`;
                            kecamatanSelect.disabled = false;
                        }
                        if (kelurahanManual && kelurahanManual.value) {
                            kelurahanSelect.innerHTML = `<option value="${kelurahanManual.value}" selected>${kelurahanManual.value}</option>`;
                            kelurahanSelect.disabled = false;
                        }
                    } else if (typeSelect.value === 'Indonesia') {
                        // Ensure kewarganegaraan_final is set to Indonesia
                        const kewarganegaraanFinal = document.getElementById(`kewarganegaraan_final_${memberIndex}`);
                        if (kewarganegaraanFinal) {
                            kewarganegaraanFinal.value = 'Indonesia';
                        }
                    }
                });
                
                const requiredFields = form.querySelectorAll('input[required], textarea[required], select[required]');
                let isValid = true;
                let firstInvalidField = null;
                
                requiredFields.forEach(field => {
                    // Skip disabled fields
                    if (field.disabled) return;
                    
                    // Skip hidden fields
                    if (field.type === 'hidden') return;
                    
                    // Skip manual input fields if wilayah container is hidden
                    if (field.id && field.id.includes('_manual_')) {
                        const memberIndex = field.id.split('_').pop();
                        const nonWilayahContainer = document.getElementById(`non_wilayah_container_${memberIndex}`);
                        if (nonWilayahContainer && nonWilayahContainer.style.display === 'none') {
                            return; // Skip manual fields when hidden
                        }
                    }
                    
                    // Skip dropdown fields if wilayah container is hidden
                    if (field.id && (field.id.includes('provinsi_') || field.id.includes('kota_kabupaten_') || 
                                     field.id.includes('kecamatan_') || field.id.includes('kelurahan_'))) {
                        if (field.id.includes('_manual_')) return; // Already handled above
                        
                        const memberIndex = field.id.split('_').pop();
                        const wilayahContainer = document.getElementById(`wilayah_container_${memberIndex}`);
                        if (wilayahContainer && wilayahContainer.style.display === 'none') {
                            return; // Skip dropdown fields when hidden
                        }
                    }
                    
                    // Skip kewarganegaraan_asing field if Indonesia is selected
                    if (field.id && field.id.includes('kewarganegaraan_asing_')) {
                        const memberIndex = field.id.replace('kewarganegaraan_asing_', '');
                        const kewarganegaraanAsingDiv = document.getElementById(`kewarganegaraan_asing_div_${memberIndex}`);
                        if (kewarganegaraanAsingDiv && kewarganegaraanAsingDiv.style.display === 'none') {
                            return; // Skip kewarganegaraan_asing when hidden
                        }
                    }
                    
                    if (!field.value || !field.value.trim()) {
                        isValid = false;
                        field.classList.add('border-red-500', 'bg-red-50');
                        if (!firstInvalidField) {
                            firstInvalidField = field;
                        }
                        console.log('Invalid field:', field.id || field.name);
                    } else {
                        field.classList.remove('border-red-500', 'bg-red-50');
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    console.log('Form validation failed');
                    alert('Mohon lengkapi semua field yang wajib diisi (*)');
                    if (firstInvalidField) {
                        firstInvalidField.focus();
                        firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                } else {
                    console.log('Form validation passed, submitting...');
                }
            });
        });
        
        // Wilayah Functions
        function loadProvinces() {
            fetch('{{ route("api.wilayah.provinces") }}')
                .then(response => response.json())
                .then(data => {
                    window.provincesData = data;
                    // Populate all province selects
                    document.querySelectorAll('.provinsi-select').forEach(select => {
                        populateSelect(select, data, 'Pilih Provinsi');
                    });
                })
                .catch(error => console.error('Error loading provinces:', error));
        }
        
        function populateSelect(selectElement, data, placeholder = 'Pilih') {
            selectElement.innerHTML = `<option value="">${placeholder}</option>`;
            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.nama;
                option.setAttribute('data-kode', item.kode);
                option.textContent = item.nama;
                selectElement.appendChild(option);
            });
        }
        
        function initializeWilayahHandlers(index) {
            // Kewarganegaraan type change handler
            const kewarganegaraanTypeSelect = document.getElementById(`kewarganegaraan_type_${index}`);
            if (kewarganegaraanTypeSelect) {
                kewarganegaraanTypeSelect.addEventListener('change', function() {
                    const wilayahContainer = document.getElementById(`wilayah_container_${index}`);
                    const nonWilayahContainer = document.getElementById(`non_wilayah_container_${index}`);
                    const kewarganegaraanAsingDiv = document.getElementById(`kewarganegaraan_asing_div_${index}`);
                    const kewarganegaraanFinal = document.getElementById(`kewarganegaraan_final_${index}`);
                    const kewarganegaraanAsingInput = document.getElementById(`kewarganegaraan_asing_${index}`);
                    
                    if (this.value === 'Indonesia') {
                        // Show wilayah dropdowns, hide manual inputs
                        wilayahContainer.style.display = 'contents';
                        nonWilayahContainer.style.display = 'none';
                        kewarganegaraanAsingDiv.style.display = 'none';
                        
                        // Set final kewarganegaraan to Indonesia
                        kewarganegaraanFinal.value = 'Indonesia';
                        
                        // Enable dropdown selects
                        document.getElementById(`provinsi_${index}`).disabled = false;
                        
                        // Clear manual inputs
                        document.getElementById(`provinsi_manual_${index}`).value = '';
                        document.getElementById(`kota_manual_${index}`).value = '';
                        document.getElementById(`kecamatan_manual_${index}`).value = '';
                        document.getElementById(`kelurahan_manual_${index}`).value = '';
                        kewarganegaraanAsingInput.value = '';
                    } else if (this.value === 'Warga Negara Asing') {
                        // Hide wilayah dropdowns, show manual inputs
                        wilayahContainer.style.display = 'none';
                        nonWilayahContainer.style.display = 'contents';
                        kewarganegaraanAsingDiv.style.display = 'block';
                        
                        // Disable dropdown selects
                        document.getElementById(`provinsi_${index}`).disabled = true;
                        document.getElementById(`kota_kabupaten_${index}`).disabled = true;
                        document.getElementById(`kecamatan_${index}`).disabled = true;
                        document.getElementById(`kelurahan_${index}`).disabled = true;
                        
                        // Clear dropdown values
                        document.getElementById(`provinsi_${index}`).value = '';
                        document.getElementById(`kota_kabupaten_${index}`).value = '';
                        document.getElementById(`kecamatan_${index}`).value = '';
                        document.getElementById(`kelurahan_${index}`).value = '';
                        
                        // Clear final value until user fills in
                        kewarganegaraanFinal.value = '';
                    } else {
                        // "Pilih Kewarganegaraan" selected - hide everything
                        wilayahContainer.style.display = 'none';
                        nonWilayahContainer.style.display = 'none';
                        kewarganegaraanAsingDiv.style.display = 'none';
                        
                        // Clear final value
                        kewarganegaraanFinal.value = '';
                        
                        // Clear all inputs
                        kewarganegaraanAsingInput.value = '';
                        document.getElementById(`provinsi_${index}`).value = '';
                        document.getElementById(`kota_kabupaten_${index}`).value = '';
                        document.getElementById(`kecamatan_${index}`).value = '';
                        document.getElementById(`kelurahan_${index}`).value = '';
                        document.getElementById(`provinsi_manual_${index}`).value = '';
                        document.getElementById(`kota_manual_${index}`).value = '';
                        document.getElementById(`kecamatan_manual_${index}`).value = '';
                        document.getElementById(`kelurahan_manual_${index}`).value = '';
                    }
                });
                
                // Kewarganegaraan asing input handler
                const kewarganegaraanAsingInput = document.getElementById(`kewarganegaraan_asing_${index}`);
                if (kewarganegaraanAsingInput) {
                    kewarganegaraanAsingInput.addEventListener('input', function() {
                        const kewarganegaraanFinal = document.getElementById(`kewarganegaraan_final_${index}`);
                        kewarganegaraanFinal.value = this.value;
                    });
                }
            }
            
            // Provinsi change handler
            const provinsiSelect = document.getElementById(`provinsi_${index}`);
            if (provinsiSelect) {
                provinsiSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const provinceCode = selectedOption.getAttribute('data-kode');
                    const kotaSelect = document.getElementById(`kota_kabupaten_${index}`);
                    const kecamatanSelect = document.getElementById(`kecamatan_${index}`);
                    const kelurahanSelect = document.getElementById(`kelurahan_${index}`);
                    
                    // Reset dependent selects
                    kotaSelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                    kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                    kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
                    kotaSelect.disabled = true;
                    kecamatanSelect.disabled = true;
                    kelurahanSelect.disabled = true;
                    
                    if (provinceCode) {
                        fetch(`{{ url('users/api/wilayah/cities') }}/${provinceCode}`)
                            .then(response => response.json())
                            .then(data => {
                                populateSelect(kotaSelect, data, 'Pilih Kota/Kabupaten');
                                kotaSelect.disabled = false;
                            })
                            .catch(error => console.error('Error loading cities:', error));
                    }
                });
            }
            
            // Kota change handler
            const kotaSelect = document.getElementById(`kota_kabupaten_${index}`);
            if (kotaSelect) {
                kotaSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const cityCode = selectedOption.getAttribute('data-kode');
                    const kecamatanSelect = document.getElementById(`kecamatan_${index}`);
                    const kelurahanSelect = document.getElementById(`kelurahan_${index}`);
                    
                    // Reset dependent selects
                    kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                    kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
                    kecamatanSelect.disabled = true;
                    kelurahanSelect.disabled = true;
                    
                    if (cityCode) {
                        fetch(`{{ url('users/api/wilayah/districts') }}/${cityCode}`)
                            .then(response => response.json())
                            .then(data => {
                                populateSelect(kecamatanSelect, data, 'Pilih Kecamatan');
                                kecamatanSelect.disabled = false;
                            })
                            .catch(error => console.error('Error loading districts:', error));
                    }
                });
            }
            
            // Kecamatan change handler
            const kecamatanSelect = document.getElementById(`kecamatan_${index}`);
            if (kecamatanSelect) {
                kecamatanSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const districtCode = selectedOption.getAttribute('data-kode');
                    const kelurahanSelect = document.getElementById(`kelurahan_${index}`);
                    
                    // Reset dependent select
                    kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
                    kelurahanSelect.disabled = true;
                    
                    if (districtCode) {
                        fetch(`{{ url('users/api/wilayah/villages') }}/${districtCode}`)
                            .then(response => response.json())
                            .then(data => {
                                populateSelect(kelurahanSelect, data, 'Pilih Kelurahan');
                                kelurahanSelect.disabled = false;
                            })
                            .catch(error => console.error('Error loading villages:', error));
                    }
                });
            }
        }
    </script>
</body>
</html>