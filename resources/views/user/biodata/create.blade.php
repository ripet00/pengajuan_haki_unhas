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
                                  placeholder="{{ $biodata && $biodata->error_uraian_singkat ? 'Admin menandai field ini perlu diperbaiki' : 'Masukkan uraian singkat karya cipta' }}"
                                  class="w-full px-3 py-2 border {{ $biodata && $biodata->error_uraian_singkat ? 'border-red-300 bg-red-50' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  required>{{ old('uraian_singkat', $biodata ? $biodata->uraian_singkat : '') }}</textarea>
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
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mt-2">
                        <p class="text-sm text-blue-800">
                            <i class="fas fa-info-circle mr-1"></i>
                            <strong>Field Wajib Diisi:</strong> Nama Lengkap (*), NIK (*), Pekerjaan (*), Alamat (*), Email (*), dan Nomor HP (*)
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
                            <input type="text" 
                                   name="members[${index}][kewarganegaraan]" 
                                   value="${member.kewarganegaraan || 'Indonesia'}"
                                   placeholder="${member.error_kewarganegaraan ? 'Admin menandai field ini perlu diperbaiki' : 'Masukkan kewarganegaraan'}"
                                   class="w-full px-3 py-2 border ${member.error_kewarganegaraan ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Provinsi *
                                ${member.error_provinsi ? `
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                    </span>
                                ` : ''}
                            </label>
                            <input type="text" 
                                   name="members[${index}][provinsi]" 
                                   value="${member.provinsi || ''}"
                                   placeholder="${member.error_provinsi ? 'Admin menandai field ini perlu diperbaiki' : 'Masukkan provinsi'}"
                                   class="w-full px-3 py-2 border ${member.error_provinsi ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
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
                            <input type="text" 
                                   name="members[${index}][kota_kabupaten]" 
                                   value="${member.kota_kabupaten || ''}"
                                   placeholder="${member.error_kota_kabupaten ? 'Admin menandai field ini perlu diperbaiki' : 'Masukkan kota/kabupaten'}"
                                   class="w-full px-3 py-2 border ${member.error_kota_kabupaten ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
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
                            <input type="text" 
                                   name="members[${index}][kecamatan]" 
                                   value="${member.kecamatan || ''}"
                                   placeholder="${member.error_kecamatan ? 'Admin menandai field ini perlu diperbaiki' : 'Masukkan kecamatan'}"
                                   class="w-full px-3 py-2 border ${member.error_kecamatan ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
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
                            <input type="text" 
                                   name="members[${index}][kelurahan]" 
                                   value="${member.kelurahan || ''}"
                                   placeholder="${member.error_kelurahan ? 'Admin menandai field ini perlu diperbaiki' : 'Masukkan kelurahan'}"
                                   class="w-full px-3 py-2 border ${member.error_kelurahan ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
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
        
        // Initialize form
        document.addEventListener('DOMContentLoaded', function() {
            // Add existing members or create first member
            if (existingMembers.length > 0) {
                existingMembers.forEach((member, index) => {
                    const container = document.getElementById('members-container');
                    const memberHtml = createMemberForm(index, member);
                    container.insertAdjacentHTML('beforeend', memberHtml);
                    memberCount++;
                });
            } else {
                // Create first member (leader) with user data
                addMember();
            }
            
            updateAddButton();
            
            // Add event listener for add member button
            document.getElementById('add-member-btn').addEventListener('click', addMember);
            
            // Add form validation before submit
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const requiredFields = form.querySelectorAll('input[required], textarea[required]');
                let isValid = true;
                let firstInvalidField = null;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('border-red-500', 'bg-red-50');
                        if (!firstInvalidField) {
                            firstInvalidField = field;
                        }
                    } else {
                        field.classList.remove('border-red-500', 'bg-red-50');
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    alert('Mohon lengkapi semua field yang wajib diisi (*)');
                    if (firstInvalidField) {
                        firstInvalidField.focus();
                        firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            });
        });
    </script>
</body>
</html>