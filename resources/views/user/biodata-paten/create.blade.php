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
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
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

        <!-- Header Section -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-2xl font-semibold text-gray-900">
                    <i class="fas fa-lightbulb mr-3 text-green-600"></i>
                    {{ $isEdit ? 'Edit' : 'Buat' }} Biodata Paten
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Submission: <strong>{{ $submissionPaten->judul_paten }}</strong> (ID: #{{ $submissionPaten->id }})
                </p>
            </div>

            <!-- Progress Info -->
            <div class="px-6 py-4">
                @if($isEdit)
                    @if($biodataPaten && $biodataPaten->status == 'denied')
                        <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                            <h4 class="text-sm font-semibold text-red-800 mb-2">
                                <i class="fas fa-exclamation-triangle mr-1"></i>Biodata Ditolak
                            </h4>
                            <p class="text-sm text-red-700">{{ $biodataPaten ? $biodataPaten->rejection_reason : '' }}</p>
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
                        <p class="text-sm text-green-700">Lengkapi biodata untuk melanjutkan proses pengajuan Paten Anda.</p>
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
        <form method="POST" action="{{ route('user.biodata-paten.store', $submissionPaten) }}" class="space-y-6" onsubmit="return confirm('=== KONFIRMASI ===\n\nApakah Anda yakin ingin mengirim biodata Paten ini?\n\nSetelah dikirim, biodata akan diproses oleh admin untuk review.');">
            @csrf
            
            <!-- Biodata Information -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Paten</h3>
                </div>
                
                <div class="p-6 space-y-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Paten</label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               value="{{ $submissionPaten->judul_paten }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed"
                               readonly
                               disabled>
                        <p class="mt-1 text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Judul ini diambil otomatis dari submission yang telah disetujui
                        </p>
                    </div>
                </div>
            </div>

            <!-- Inventors Section -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Data Inventor</h3>
                        <button type="button" 
                                id="add-inventor-btn" 
                                class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition duration-200">
                            <i class="fas fa-plus mr-1"></i>Tambah Inventor
                        </button>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">Maksimal 6 orang inventor (termasuk inventor utama)</p>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-3 mt-2 space-y-2">
                        <p class="text-sm text-green-800">
                            <i class="fas fa-info-circle mr-1"></i>
                            <strong>Field Wajib Diisi: Semua field dengan tanda bintang (*) wajib diisi dan pastikan data sudah benar dan lengkap sebelum submit.</strong> 
                        </p>
                        <p class="text-sm text-green-800">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            <strong>Urutan Inventor: Pastikan urutan nama inventor sudah sesuai dengan yang tertera pada dokumen paten (Inventor ke-1 adalah inventor utama).</strong>
                        </p>
                    </div>
                </div>
                
                <div id="inventors-container" class="divide-y divide-gray-200">
                    <!-- Inventors will be added here by JavaScript -->
                </div>
            </div>

            <!-- Submit Section -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900">Review & Submit</h4>
                            <p class="text-sm text-gray-600">
                                @if($biodataPaten && $biodataPaten->status === 'denied')
                                    Perbaiki data yang ditandai admin dan submit ulang biodata.
                                @else
                                    Pastikan semua data telah diisi dengan benar sebelum submit.
                                @endif
                            </p>
                            <div class="mt-2 text-xs text-gray-500">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                Semua field bertanda (*) wajib diisi untuk setiap inventor
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('user.submissions-paten.show', $submissionPaten) }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 text-sm font-medium rounded-lg transition duration-200">
                                <i class="fas fa-times mr-2"></i>Batal
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2 {{ $biodataPaten && $biodataPaten->status === 'denied' ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white font-medium rounded-lg transition duration-200">
                                @if($biodataPaten && $biodataPaten->status === 'denied')
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
        let inventorCount = 0;
        const maxInventors = 4;
        
        // Existing inventors data from server
        const existingInventors = @json($inventors ? $inventors->toArray() : []);
        
        // Debug: Log existing inventors to console
        console.log('Existing Inventors Data:', existingInventors);
        
        // Check if this is first time submit (for auto-fill feature)
        const isFirstTimeSubmit = @json($isFirstTimeSubmit ?? false);
        
        // Creator data for auto-fill (inventor pertama from submission)
        const creatorData = @json($creatorData ?? ['name' => '', 'phone' => '', 'country_code' => '+62']);
        
        function createInventorForm(index, inventorData = {}) {
            const isLeader = index === 0;
            const inventor = inventorData || {};
            
            // Debug log untuk inventor yang sedang dibuat
            if (inventorData && Object.keys(inventorData).length > 0) {
                console.log(`Creating form for inventor ${index}:`, inventor);
                console.log('Error flags:', {
                    error_name: inventor.error_name,
                    error_pekerjaan: inventor.error_pekerjaan,
                    error_email: inventor.error_email
                });
            }
            
            // Auto-fill for first inventor (Inventor 1) only on first time submit
            if (isLeader && isFirstTimeSubmit && !inventorData.name && !inventorData.nomor_hp) {
                inventor.name = creatorData.name || '';
                inventor.nomor_hp = creatorData.phone || '';
            }
            
            // Check if fakultas is a predefined option or custom
            const predefinedFakultas = [
                'Umum',
                'Fakultas Ekonomi dan Bisnis',
                'Fakultas Hukum',
                'Fakultas Kedokteran',
                'Fakultas Teknik',
                'Fakultas Ilmu Sosial dan Ilmu Politik',
                'Fakultas Ilmu Budaya',
                'Fakultas Pertanian',
                'Fakultas Matematika dan Ilmu Pengetahuan Alam',
                'Fakultas Peternakan',
                'Fakultas Kedokteran Gigi',
                'Fakultas Kesehatan Masyarakat',
                'Fakultas Ilmu Kelautan dan Perikanan',
                'Fakultas Kehutanan',
                'Fakultas Farmasi',
                'Fakultas Keperawatan',
                'Fakultas Vokasi',
                'Fakultas Teknologi Pertanian',
                'Sekolah Pascasarjana'
            ];
            
            const isFakultasCustom = inventor.fakultas && !predefinedFakultas.includes(inventor.fakultas);
            const fakultasTypeValue = isFakultasCustom ? 'Isi Sendiri' : (inventor.fakultas || '');
            
            return `
                <div class="inventor-form p-6" data-inventor-index="${index}">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-md font-semibold text-gray-900">
                            <i class="fas fa-user-tie mr-2"></i>
                            Inventor ke-${index + 1} ${isLeader ? '(Inventor Utama)' : ''}
                        </h4>
                        ${!isLeader ? `
                            <button type="button" 
                                    class="remove-inventor-btn text-red-600 hover:text-red-800 transition duration-200"
                                    onclick="removeInventor(${index})">
                                <i class="fas fa-trash"></i>
                            </button>
                        ` : ''}
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Lengkap *
                                ${!!inventor.error_name ? `
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                    </span>
                                ` : ''}
                            </label>
                            <input type="text" 
                                   name="inventors[${index}][name]" 
                                   value="${inventor.name || ''}"
                                   placeholder="${!!inventor.error_name ? 'Admin menandai field ini perlu diperbaiki' : 'Contoh: Dr. Ir. Ahmad Sudirman, M.T.'}"
                                   class="w-full px-3 py-2 border ${!!inventor.error_name ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   required>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>${isLeader && isFirstTimeSubmit && inventor.name ? '✓ Terisi otomatis dari data submission (dapat diedit). Pastikan nama dan gelar sudah benar.' : 'Isi dengan nama lengkap beserta gelar (jika ada)'}
                            </p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Pekerjaan *
                                ${!!inventor.error_pekerjaan ? `
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                    </span>
                                ` : ''}
                            </label>
                            <input type="text" 
                                   name="inventors[${index}][pekerjaan]" 
                                   value="${inventor.pekerjaan || ''}"
                                   placeholder="${!!inventor.error_pekerjaan ? 'Admin menandai field ini perlu diperbaiki' : 'Contoh: Dosen, Mahasiswa, Peneliti, dll.'}"
                                   class="w-full px-3 py-2 border ${!!inventor.error_pekerjaan ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   required>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>Contoh: "Dosen Fakultas Teknik Universitas Hasanuddin"
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Universitas *
                                ${!!inventor.error_universitas ? `
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                    </span>
                                ` : ''}
                            </label>
                            <input type="text" 
                                   name="inventors[${index}][universitas]" 
                                   value="${inventor.universitas || ''}"
                                   placeholder="${!!inventor.error_universitas ? 'Admin menandai field ini perlu diperbaiki' : 'Masukkan universitas'}"
                                   class="w-full px-3 py-2 border ${!!inventor.error_universitas ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Fakultas *
                                ${!!inventor.error_fakultas ? `
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                    </span>
                                ` : ''}
                            </label>
                            <select name="inventors[${index}][fakultas_type]" 
                                    id="fakultas_type_${index}"
                                    data-inventor-index="${index}"
                                    class="fakultas-type-select w-full px-3 py-2 border ${!!inventor.error_fakultas ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    required>
                                <option value="">Pilih Fakultas</option>
                                <option value="Umum" ${fakultasTypeValue === 'Umum' ? 'selected' : ''}>Umum</option>
                                <option value="Fakultas Ekonomi dan Bisnis" ${fakultasTypeValue === 'Fakultas Ekonomi dan Bisnis' ? 'selected' : ''}>Fakultas Ekonomi dan Bisnis</option>
                                <option value="Fakultas Hukum" ${fakultasTypeValue === 'Fakultas Hukum' ? 'selected' : ''}>Fakultas Hukum</option>
                                <option value="Fakultas Kedokteran" ${fakultasTypeValue === 'Fakultas Kedokteran' ? 'selected' : ''}>Fakultas Kedokteran</option>
                                <option value="Fakultas Teknik" ${fakultasTypeValue === 'Fakultas Teknik' ? 'selected' : ''}>Fakultas Teknik</option>
                                <option value="Fakultas Ilmu Sosial dan Ilmu Politik" ${fakultasTypeValue === 'Fakultas Ilmu Sosial dan Ilmu Politik' ? 'selected' : ''}>Fakultas Ilmu Sosial dan Ilmu Politik</option>
                                <option value="Fakultas Ilmu Budaya" ${fakultasTypeValue === 'Fakultas Ilmu Budaya' ? 'selected' : ''}>Fakultas Ilmu Budaya</option>
                                <option value="Fakultas Pertanian" ${fakultasTypeValue === 'Fakultas Pertanian' ? 'selected' : ''}>Fakultas Pertanian</option>
                                <option value="Fakultas Matematika dan Ilmu Pengetahuan Alam" ${fakultasTypeValue === 'Fakultas Matematika dan Ilmu Pengetahuan Alam' ? 'selected' : ''}>Fakultas Matematika dan Ilmu Pengetahuan Alam</option>
                                <option value="Fakultas Peternakan" ${fakultasTypeValue === 'Fakultas Peternakan' ? 'selected' : ''}>Fakultas Peternakan</option>
                                <option value="Fakultas Kedokteran Gigi" ${fakultasTypeValue === 'Fakultas Kedokteran Gigi' ? 'selected' : ''}>Fakultas Kedokteran Gigi</option>
                                <option value="Fakultas Kesehatan Masyarakat" ${fakultasTypeValue === 'Fakultas Kesehatan Masyarakat' ? 'selected' : ''}>Fakultas Kesehatan Masyarakat</option>
                                <option value="Fakultas Ilmu Kelautan dan Perikanan" ${fakultasTypeValue === 'Fakultas Ilmu Kelautan dan Perikanan' ? 'selected' : ''}>Fakultas Ilmu Kelautan dan Perikanan</option>
                                <option value="Fakultas Kehutanan" ${fakultasTypeValue === 'Fakultas Kehutanan' ? 'selected' : ''}>Fakultas Kehutanan</option>
                                <option value="Fakultas Farmasi" ${fakultasTypeValue === 'Fakultas Farmasi' ? 'selected' : ''}>Fakultas Farmasi</option>
                                <option value="Fakultas Keperawatan" ${fakultasTypeValue === 'Fakultas Keperawatan' ? 'selected' : ''}>Fakultas Keperawatan</option>
                                <option value="Fakultas Vokasi" ${fakultasTypeValue === 'Fakultas Vokasi' ? 'selected' : ''}>Fakultas Vokasi</option>
                                <option value="Fakultas Teknologi Pertanian" ${fakultasTypeValue === 'Fakultas Teknologi Pertanian' ? 'selected' : ''}>Fakultas Teknologi Pertanian</option>
                                <option value="Sekolah Pascasarjana" ${fakultasTypeValue === 'Sekolah Pascasarjana' ? 'selected' : ''}>Sekolah Pascasarjana</option>
                                <option value="Isi Sendiri" ${fakultasTypeValue === 'Isi Sendiri' ? 'selected' : ''}>Isi Sendiri</option>
                            </select>
                        </div>
                        
                        <div id="fakultas_manual_div_${index}" class="fakultas-manual-container" style="display: ${isFakultasCustom ? 'block' : 'none'}">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Fakultas *
                                ${!!inventor.error_fakultas ? `
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                    </span>
                                ` : ''}
                            </label>
                            <input type="text" 
                                   id="fakultas_manual_${index}"
                                   value="${isFakultasCustom ? inventor.fakultas : ''}"
                                   placeholder="Masukkan nama fakultas"
                                   class="fakultas-manual-input w-full px-3 py-2 border ${!!inventor.error_fakultas ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>Isi manual jika fakultas tidak ada di daftar
                            </p>
                        </div>
                        
                        <!-- Hidden input to store final fakultas value -->
                        <input type="hidden" name="inventors[${index}][fakultas]" id="fakultas_final_${index}" value="${inventor.fakultas || ''}">
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Alamat *
                                ${!!inventor.error_alamat ? `
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                    </span>
                                ` : ''}
                            </label>
                            <textarea name="inventors[${index}][alamat]" 
                                      rows="2"
                                      placeholder="${!!inventor.error_alamat ? 'Admin menandai field ini perlu diperbaiki' : 'Masukkan alamat lengkap'}"
                                      class="w-full px-3 py-2 border ${!!inventor.error_alamat ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                      required>${inventor.alamat || ''}</textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Kewarganegaraan *
                                ${!!inventor.error_kewarganegaraan ? `
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                    </span>
                                ` : ''}
                            </label>
                            <select name="inventors[${index}][kewarganegaraan_type]" 
                                    id="kewarganegaraan_type_${index}"
                                    data-inventor-index="${index}"
                                    class="kewarganegaraan-type-select w-full px-3 py-2 border ${!!inventor.error_kewarganegaraan ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    required>
                                <option value="">Pilih Kewarganegaraan</option>
                                <option value="Indonesia" ${(inventor.kewarganegaraan || 'Indonesia') === 'Indonesia' ? 'selected' : ''}>Indonesia</option>
                                <option value="Warga Negara Asing" ${(inventor.kewarganegaraan && inventor.kewarganegaraan !== 'Indonesia') ? 'selected' : ''}>Warga Negara Asing</option>
                            </select>
                        </div>
                        
                        <div id="kewarganegaraan_asing_div_${index}" class="kewarganegaraan-asing-container" style="display: ${(inventor.kewarganegaraan && inventor.kewarganegaraan !== 'Indonesia') ? 'block' : 'none'}">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Negara Asal *
                                ${!!inventor.error_kewarganegaraan ? `
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                    </span>
                                ` : ''}
                            </label>
                            <input type="text" 
                                   name="inventors[${index}][kewarganegaraan_asing]" 
                                   id="kewarganegaraan_asing_${index}"
                                   value="${(inventor.kewarganegaraan && inventor.kewarganegaraan !== 'Indonesia') ? inventor.kewarganegaraan : ''}"
                                   placeholder="Contoh: Malaysia, Singapura, Amerika Serikat"
                                   class="kewarganegaraan-asing-input w-full px-3 py-2 border ${!!inventor.error_kewarganegaraan ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                        
                        <!-- Hidden input to store final kewarganegaraan value -->
                        <input type="hidden" name="inventors[${index}][kewarganegaraan]" id="kewarganegaraan_final_${index}" value="${inventor.kewarganegaraan || 'Indonesia'}">
                        
                        <div class="wilayah-container" id="wilayah_container_${index}" style="display: ${(inventor.kewarganegaraan || 'Indonesia') === 'Indonesia' ? 'contents' : 'none'}">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Provinsi *
                                    ${!!inventor.error_provinsi ? `
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                        </span>
                                    ` : ''}
                                </label>
                                <select name="inventors[${index}][provinsi]" 
                                        id="provinsi_${index}"
                                        data-inventor-index="${index}"
                                        class="provinsi-select w-full px-3 py-2 border ${!!inventor.error_provinsi ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    <option value="">Pilih Provinsi</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Kota/Kabupaten *
                                    ${!!inventor.error_kota_kabupaten ? `
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                        </span>
                                    ` : ''}
                                </label>
                                <select name="inventors[${index}][kota_kabupaten]" 
                                        id="kota_kabupaten_${index}"
                                        data-inventor-index="${index}"
                                        class="kota-select w-full px-3 py-2 border ${!!inventor.error_kota_kabupaten ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        disabled>
                                    <option value="">Pilih Kota/Kabupaten</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Kecamatan *
                                    ${!!inventor.error_kecamatan ? `
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                        </span>
                                    ` : ''}
                                </label>
                                <select name="inventors[${index}][kecamatan]" 
                                        id="kecamatan_${index}"
                                        data-inventor-index="${index}"
                                        class="kecamatan-select w-full px-3 py-2 border ${!!inventor.error_kecamatan ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        disabled>
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Kelurahan *
                                    ${!!inventor.error_kelurahan ? `
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                        </span>
                                    ` : ''}
                                </label>
                                <select name="inventors[${index}][kelurahan]" 
                                        id="kelurahan_${index}"
                                        data-inventor-index="${index}"
                                        class="kelurahan-select w-full px-3 py-2 border ${!!inventor.error_kelurahan ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        disabled>
                                    <option value="">Pilih Kelurahan</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="non-wilayah-container" id="non_wilayah_container_${index}" style="display: ${inventor.kewarganegaraan === 'Asing' ? 'contents' : 'none'}">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Provinsi/Negara Bagian *
                                    ${!!inventor.error_provinsi ? `
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                        </span>
                                    ` : ''}
                                </label>
                                <input type="text" 
                                       name="inventors[${index}][provinsi_manual]" 
                                       id="provinsi_manual_${index}"
                                       value="${inventor.kewarganegaraan === 'Asing' ? inventor.provinsi || '' : ''}"
                                       placeholder="Masukkan provinsi/negara bagian"
                                       class="w-full px-3 py-2 border ${!!inventor.error_provinsi ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Kota *
                                    ${!!inventor.error_kota_kabupaten ? `
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                        </span>
                                    ` : ''}
                                </label>
                                <input type="text" 
                                       name="inventors[${index}][kota_manual]" 
                                       id="kota_manual_${index}"
                                       value="${inventor.kewarganegaraan === 'Asing' ? inventor.kota_kabupaten || '' : ''}"
                                       placeholder="Masukkan nama kota"
                                       class="w-full px-3 py-2 border ${!!inventor.error_kota_kabupaten ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Kecamatan/Distrik
                                    ${!!inventor.error_kecamatan ? `
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                        </span>
                                    ` : ''}
                                </label>
                                <input type="text" 
                                       name="inventors[${index}][kecamatan_manual]" 
                                       id="kecamatan_manual_${index}"
                                       value="${inventor.kewarganegaraan === 'Asing' ? inventor.kecamatan || '' : ''}"
                                       placeholder="Masukkan kecamatan/distrik (opsional)"
                                       class="w-full px-3 py-2 border ${!!inventor.error_kecamatan ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Kelurahan/Desa
                                    ${!!inventor.error_kelurahan ? `
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                        </span>
                                    ` : ''}
                                </label>
                                <input type="text" 
                                       name="inventors[${index}][kelurahan_manual]" 
                                       id="kelurahan_manual_${index}"
                                       value="${inventor.kewarganegaraan === 'Asing' ? inventor.kelurahan || '' : ''}"
                                       placeholder="Masukkan kelurahan/desa (opsional)"
                                       class="w-full px-3 py-2 border ${!!inventor.error_kelurahan ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Kode Pos *
                                ${!!inventor.error_kode_pos ? `
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                    </span>
                                ` : ''}
                            </label>
                            <input type="text" 
                                   name="inventors[${index}][kode_pos]" 
                                   value="${inventor.kode_pos || ''}"
                                   placeholder="${!!inventor.error_kode_pos ? 'Admin menandai field ini perlu diperbaiki' : 'Masukkan kode pos'}"
                                   class="w-full px-3 py-2 border ${!!inventor.error_kode_pos ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Email *
                                ${!!inventor.error_email ? `
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                    </span>
                                ` : ''}
                            </label>
                            <input type="email" 
                                   name="inventors[${index}][email]" 
                                   value="${inventor.email || ''}"
                                   placeholder="${!!inventor.error_email ? 'Admin menandai field ini perlu diperbaiki' : 'Masukkan email inventor'}"
                                   class="w-full px-3 py-2 border ${!!inventor.error_email ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nomor HP *
                                ${!!inventor.error_nomor_hp ? `
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Diperbaiki
                                    </span>
                                ` : ''}
                            </label>
                            <input type="text" 
                                   name="inventors[${index}][nomor_hp]" 
                                   value="${inventor.nomor_hp || ''}"
                                   placeholder="${!!inventor.error_nomor_hp ? 'Admin menandai field ini perlu diperbaiki' : 'Masukkan nomor HP aktif'}"
                                   class="w-full px-3 py-2 border ${!!inventor.error_nomor_hp ? 'border-red-300 bg-red-50' : 'border-gray-300'} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   required>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>${isLeader && isFirstTimeSubmit && inventor.nomor_hp ? '✓ Terisi otomatis dari data submission (dapat diedit). Pastikan nomor Whatsapp sudah benar.' : 'Nomor HP harus aktif dan dapat dihubungi'}
                            </p>
                        </div>
                    </div>
                </div>
            `;
        }
        
        function addInventor() {
            if (inventorCount >= maxInventors) {
                alert('Maksimal 6 inventor diperbolehkan.');
                return;
            }
            
            const container = document.getElementById('inventors-container');
            const inventorHtml = createInventorForm(inventorCount);
            container.insertAdjacentHTML('beforeend', inventorHtml);
            
            // Populate provinces for the new inventor
            if (window.provincesData) {
                const provinsiSelect = document.getElementById(`provinsi_${inventorCount}`);
                if (provinsiSelect) {
                    populateSelect(provinsiSelect, window.provincesData, 'Pilih Provinsi');
                }
            }
            
            inventorCount++;
            updateAddButton();
        }
        
        function removeInventor(index) {
            const inventorForm = document.querySelector(`[data-inventor-index="${index}"]`);
            if (inventorForm) {
                inventorForm.remove();
                updateInventorIndexes();
                updateAddButton();
            }
        }
        
        function updateInventorIndexes() {
            const inventorForms = document.querySelectorAll('.inventor-form');
            inventorCount = 0;
            
            inventorForms.forEach((form, newIndex) => {
                form.setAttribute('data-inventor-index', newIndex);
                
                // Update form title
                const title = form.querySelector('h4');
                const isLeader = newIndex === 0;
                title.innerHTML = `
                    <i class="fas fa-user-tie mr-2"></i>
                    Inventor ke-${newIndex + 1} ${isLeader ? '(Inventor Utama)' : ''}
                `;
                
                // Update remove button
                const removeBtn = form.querySelector('.remove-inventor-btn');
                if (removeBtn) {
                    if (isLeader) {
                        removeBtn.style.display = 'none';
                    } else {
                        removeBtn.style.display = 'block';
                        removeBtn.setAttribute('onclick', `removeInventor(${newIndex})`);
                    }
                }
                
                // Update all input names
                const inputs = form.querySelectorAll('input, textarea');
                inputs.forEach(input => {
                    const name = input.getAttribute('name');
                    if (name && name.includes('inventors[')) {
                        const newName = name.replace(/inventors\[\d+\]/, `inventors[${newIndex}]`);
                        input.setAttribute('name', newName);
                    }
                });
                
                inventorCount++;
            });
        }
        
        function updateAddButton() {
            const addButton = document.getElementById('add-inventor-btn');
            if (inventorCount >= maxInventors) {
                addButton.style.display = 'none';
            } else {
                addButton.style.display = 'inline-flex';
            }
        }
        
        // Function to load existing wilayah data for an inventor
        async function loadExistingWilayahData(index, inventor) {
            if (!inventor.provinsi) return;
            
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
                    if (option.value === inventor.provinsi) {
                        provinsiSelect.value = inventor.provinsi;
                        const provinceCode = option.getAttribute('data-kode');
                        
                        // Load and set kota
                        if (provinceCode && inventor.kota_kabupaten) {
                            const citiesResponse = await fetch(`{{ url('users/api/wilayah/cities') }}/${provinceCode}`);
                            const citiesData = await citiesResponse.json();
                            populateSelect(kotaSelect, citiesData, 'Pilih Kota/Kabupaten');
                            kotaSelect.disabled = false;
                            
                            // Set kota
                            for (let option of kotaSelect.options) {
                                if (option.value === inventor.kota_kabupaten) {
                                    kotaSelect.value = inventor.kota_kabupaten;
                                    const cityCode = option.getAttribute('data-kode');
                                    
                                    // Load and set kecamatan
                                    if (cityCode && inventor.kecamatan) {
                                        const districtsResponse = await fetch(`{{ url('users/api/wilayah/districts') }}/${cityCode}`);
                                        const districtsData = await districtsResponse.json();
                                        populateSelect(kecamatanSelect, districtsData, 'Pilih Kecamatan');
                                        kecamatanSelect.disabled = false;
                                        
                                        // Set kecamatan
                                        for (let option of kecamatanSelect.options) {
                                            if (option.value === inventor.kecamatan) {
                                                kecamatanSelect.value = inventor.kecamatan;
                                                const districtCode = option.getAttribute('data-kode');
                                                
                                                // Load and set kelurahan
                                                if (districtCode && inventor.kelurahan) {
                                                    const villagesResponse = await fetch(`{{ url('users/api/wilayah/villages') }}/${districtCode}`);
                                                    const villagesData = await villagesResponse.json();
                                                    populateSelect(kelurahanSelect, villagesData, 'Pilih Kelurahan');
                                                    kelurahanSelect.disabled = false;
                                                    kelurahanSelect.value = inventor.kelurahan;
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
            // Load provinces data for all inventors
            loadProvinces();
            
            // Add existing inventors or create first inventor
            if (existingInventors.length > 0) {
                existingInventors.forEach((inventor, index) => {
                    const container = document.getElementById('inventors-container');
                    const inventorHtml = createInventorForm(index, inventor);
                    container.insertAdjacentHTML('beforeend', inventorHtml);
                    inventorCount++;
                    
                    // Initialize wilayah handlers for this inventor
                    initializeWilayahHandlers(index);
                    
                    // Load existing wilayah data if inventor is WNI
                    if (!inventor.kewarganegaraan || inventor.kewarganegaraan === 'Indonesia') {
                        loadExistingWilayahData(index, inventor);
                    }
                });
            } else {
                // Create first inventor (leader) with user data
                addInventor();
                initializeWilayahHandlers(0);
            }
            
            updateAddButton();
            
            // Add event listener for add inventor button
            document.getElementById('add-inventor-btn').addEventListener('click', function() {
                addInventor();
                initializeWilayahHandlers(inventorCount - 1);
            });
            
            // Add form validation before submit
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                console.log('Form submit triggered');
                
                // Handle kewarganegaraan_final and manual inputs for Asing nationality
                document.querySelectorAll('.kewarganegaraan-type-select').forEach((typeSelect, idx) => {
                    const inventorIndex = typeSelect.id.replace('kewarganegaraan_type_', '');
                    
                    if (typeSelect.value === 'Warga Negara Asing') {
                        const kewarganegaraanAsingInput = document.getElementById(`kewarganegaraan_asing_${inventorIndex}`);
                        const kewarganegaraanFinal = document.getElementById(`kewarganegaraan_final_${inventorIndex}`);
                        
                        // Validate kewarganegaraan_asing is filled when visible
                        const kewarganegaraanAsingDiv = document.getElementById(`kewarganegaraan_asing_div_${inventorIndex}`);
                        if (kewarganegaraanAsingDiv && kewarganegaraanAsingDiv.style.display !== 'none') {
                            if (!kewarganegaraanAsingInput || !kewarganegaraanAsingInput.value.trim()) {
                                alert('Mohon isi Negara Asal untuk Warga Negara Asing pada inventor ke-' + (parseInt(inventorIndex) + 1));
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
                        const provinsiManual = document.getElementById(`provinsi_manual_${inventorIndex}`);
                        const kotaManual = document.getElementById(`kota_manual_${inventorIndex}`);
                        const kecamatanManual = document.getElementById(`kecamatan_manual_${inventorIndex}`);
                        const kelurahanManual = document.getElementById(`kelurahan_manual_${inventorIndex}`);
                        
                        const provinsiSelect = document.getElementById(`provinsi_${inventorIndex}`);
                        const kotaSelect = document.getElementById(`kota_kabupaten_${inventorIndex}`);
                        const kecamatanSelect = document.getElementById(`kecamatan_${inventorIndex}`);
                        const kelurahanSelect = document.getElementById(`kelurahan_${inventorIndex}`);
                        
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
                        const kewarganegaraanFinal = document.getElementById(`kewarganegaraan_final_${inventorIndex}`);
                        if (kewarganegaraanFinal) {
                            kewarganegaraanFinal.value = 'Indonesia';
                        }
                    }
                });
                
                // Handle fakultas_final for all inventors
                document.querySelectorAll('.fakultas-type-select').forEach((fakultasTypeSelect, idx) => {
                    const inventorIndex = fakultasTypeSelect.id.replace('fakultas_type_', '');
                    const fakultasFinal = document.getElementById(`fakultas_final_${inventorIndex}`);
                    const fakultasManualInput = document.getElementById(`fakultas_manual_${inventorIndex}`);
                    
                    if (fakultasTypeSelect.value === 'Isi Sendiri') {
                        // Validate fakultas_manual is filled when visible
                        const fakultasManualDiv = document.getElementById(`fakultas_manual_div_${inventorIndex}`);
                        if (fakultasManualDiv && fakultasManualDiv.style.display !== 'none') {
                            if (!fakultasManualInput || !fakultasManualInput.value.trim()) {
                                e.preventDefault();
                                alert('Mohon isi Nama Fakultas pada inventor ke-' + (parseInt(inventorIndex) + 1));
                                fakultasManualInput.focus();
                                fakultasManualInput.classList.add('border-red-500', 'bg-red-50');
                                return false;
                            }
                        }
                        
                        // Update final fakultas value from manual input
                        if (fakultasManualInput && fakultasManualInput.value) {
                            fakultasFinal.value = fakultasManualInput.value;
                        }
                    } else if (fakultasTypeSelect.value) {
                        // Use selected fakultas from dropdown
                        fakultasFinal.value = fakultasTypeSelect.value;
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
                        const inventorIndex = field.id.split('_').pop();
                        const nonWilayahContainer = document.getElementById(`non_wilayah_container_${inventorIndex}`);
                        if (nonWilayahContainer && nonWilayahContainer.style.display === 'none') {
                            return; // Skip manual fields when hidden
                        }
                    }
                    
                    // Skip dropdown fields if wilayah container is hidden
                    if (field.id && (field.id.includes('provinsi_') || field.id.includes('kota_kabupaten_') || 
                                     field.id.includes('kecamatan_') || field.id.includes('kelurahan_'))) {
                        if (field.id.includes('_manual_')) return; // Already handled above
                        
                        const inventorIndex = field.id.split('_').pop();
                        const wilayahContainer = document.getElementById(`wilayah_container_${inventorIndex}`);
                        if (wilayahContainer && wilayahContainer.style.display === 'none') {
                            return; // Skip dropdown fields when hidden
                        }
                    }
                    
                    // Skip kewarganegaraan_asing field if Indonesia is selected
                    if (field.id && field.id.includes('kewarganegaraan_asing_')) {
                        const inventorIndex = field.id.replace('kewarganegaraan_asing_', '');
                        const kewarganegaraanAsingDiv = document.getElementById(`kewarganegaraan_asing_div_${inventorIndex}`);
                        if (kewarganegaraanAsingDiv && kewarganegaraanAsingDiv.style.display === 'none') {
                            return; // Skip kewarganegaraan_asing when hidden
                        }
                    }
                    
                    // Skip fakultas_manual field if "Isi Sendiri" is not selected
                    if (field.id && field.id.includes('fakultas_manual_')) {
                        const inventorIndex = field.id.replace('fakultas_manual_', '');
                        const fakultasManualDiv = document.getElementById(`fakultas_manual_div_${inventorIndex}`);
                        if (fakultasManualDiv && fakultasManualDiv.style.display === 'none') {
                            return; // Skip fakultas_manual when hidden
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
            
            // Fakultas type change handler
            const fakultasTypeSelect = document.getElementById(`fakultas_type_${index}`);
            if (fakultasTypeSelect) {
                fakultasTypeSelect.addEventListener('change', function() {
                    const fakultasManualDiv = document.getElementById(`fakultas_manual_div_${index}`);
                    const fakultasFinal = document.getElementById(`fakultas_final_${index}`);
                    const fakultasManualInput = document.getElementById(`fakultas_manual_${index}`);
                    
                    if (this.value === 'Isi Sendiri') {
                        // Show manual input field
                        fakultasManualDiv.style.display = 'block';
                        fakultasFinal.value = '';
                    } else {
                        // Hide manual input field and set final value
                        fakultasManualDiv.style.display = 'none';
                        fakultasFinal.value = this.value;
                        fakultasManualInput.value = '';
                    }
                });
                
                // Fakultas manual input handler
                const fakultasManualInput = document.getElementById(`fakultas_manual_${index}`);
                if (fakultasManualInput) {
                    fakultasManualInput.addEventListener('input', function() {
                        const fakultasFinal = document.getElementById(`fakultas_final_${index}`);
                        fakultasFinal.value = this.value;
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
