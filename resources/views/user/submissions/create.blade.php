<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Baru - HKI Unhas</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15), 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .input-focus:focus {
            outline: none;
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-2xl">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-full shadow-lg mb-4">
                <img src="{{ asset('images/logo-unhas-kecil.png') }}" alt="Logo Unhas" class="w-16 h-16">
            </div>
            <h1 class="text-2xl font-bold text-white mb-2">Pengajuan HKI Baru</h1>
            <div class="text-red-100">
                <p class="font-semibold">Direktorat Inovasi dan Kekayaan Intelektual</p>
                <p class="text-sm">Universitas Hasanuddin</p>
            </div>
        </div>

        <!-- Form Section -->
        <div class="glass-effect rounded-2xl shadow-xl p-6 sm:p-8">
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

            <form method="POST" action="{{ route('user.submissions.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lightbulb mr-2 text-gray-400"></i>Judul Karya Cipta
                    </label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title" 
                        value="{{ old('title') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus transition duration-200"
                        placeholder="Masukkan judul karya cipta Anda"
                        required
                    >
                    <p class="text-sm text-gray-500 mt-1">Contoh: "Sistem Informasi Akademik Berbasis Web"</p>
                </div>

                <div>
                    <label for="categories" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-layer-group mr-2 text-gray-400"></i>Kategori Pengajuan
                    </label>
                    <select 
                        id="categories" 
                        name="categories"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus transition duration-200"
                        required
                    >
                        <option value="">Pilih Kategori</option>
                        <option value="Universitas" {{ old('categories') == 'Universitas' ? 'selected' : '' }}>Universitas</option>
                        <option value="Umum" {{ old('categories') == 'Umum' ? 'selected' : '' }}>Umum</option>
                    </select>
                    <p class="text-sm text-gray-500 mt-1">
                        <strong>Universitas:</strong> Untuk karya yang dibuat dalam lingkup universitas<br>
                        <strong>Umum:</strong> Untuk karya pribadi atau di luar lingkup universitas
                    </p>
                </div>

                <div>
                    <label for="jenis_karya_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-file-alt mr-2 text-gray-400"></i>Jenis Karya
                    </label>
                    <select 
                        id="jenis_karya_id" 
                        name="jenis_karya_id"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus transition duration-200"
                        required
                    >
                        <option value="">Pilih Jenis Karya</option>
                        @foreach($jenisKaryas as $jenisKarya)
                            <option value="{{ $jenisKarya->id }}" {{ old('jenis_karya_id') == $jenisKarya->id ? 'selected' : '' }}>
                                {{ $jenisKarya->nama }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-sm text-gray-500 mt-1">
                        Pilih jenis karya yang sesuai dengan submission Anda
                    </p>
                </div>

                <!-- File Type Selection -->
                <div>
                    <label for="file_type" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-file mr-2 text-gray-400"></i>Jenis File
                    </label>
                    <select 
                        id="file_type" 
                        name="file_type"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus transition duration-200"
                        required
                        onchange="toggleFileType()"
                    >
                        <option value="">Pilih Jenis File</option>
                        <option value="pdf" {{ old('file_type') == 'pdf' ? 'selected' : '' }}>PDF Document</option>
                        <option value="video" {{ old('file_type') == 'video' ? 'selected' : '' }}>Video MP4</option>
                    </select>
                    <p class="text-sm text-gray-500 mt-1">
                        <strong>PDF:</strong> Untuk karya cipta dalam bentuk dokumen PDF (max. 20MB)<br>
                        <strong>Video:</strong> Untuk karya cipta dalam bentuk video MP4 (max. 20MB)
                    </p>
                </div>

                <!-- Creator Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="creator_name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-gray-400"></i>Nama Pencipta Pertama <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="creator_name" 
                            name="creator_name" 
                            value="{{ old('creator_name') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus transition duration-200"
                            placeholder="Masukkan nama pencipta pertama"
                            required
                        >
                    </div>
                    <div>
                        <label for="creator_whatsapp" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fab fa-whatsapp mr-2 text-gray-400"></i>Nomor WhatsApp Pencipta <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="col-span-1">
                                <select 
                                    id="creator_country_code" 
                                    name="creator_country_code"
                                    class="w-full px-3 py-3 border border-gray-300 rounded-lg input-focus transition duration-200 text-sm"
                                    required
                                >
                                    @foreach(getCountryCodes() as $code => $label)
                                        <option value="{{ $code }}" {{ old('creator_country_code', '+62') == $code ? 'selected' : '' }}>
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
                                    value="{{ old('creator_whatsapp') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus transition duration-200"
                                    placeholder="081234567890"
                                    pattern="^0[0-9]{8,13}$"
                                    title="Nomor harus dimulai dengan 0 dan berisi 9-14 digit"
                                    required
                                >
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Masukkan nomor dengan format 0xxxxxxxx</p>
                    </div>
                </div>

                <!-- YouTube Link (conditional) -->
                <div id="youtube_section" class="hidden">
                    <label for="youtube_link" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fab fa-youtube mr-2 text-gray-400"></i>Link YouTube (Opsional)
                    </label>
                    <input 
                        type="url" 
                        id="youtube_link" 
                        name="youtube_link" 
                        value="{{ old('youtube_link') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus transition duration-200"
                        placeholder="https://youtube.com/watch?v=..."
                    >
                    <p class="text-sm text-gray-500 mt-1">Sertakan link YouTube jika video karya cipta sudah dipublikasikan.</p>
                </div>

                <div>
                    <label for="document" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-file mr-2 text-gray-400"></i><span id="file-label">File Dokumen</span>
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-red-400 transition duration-200">
                        <div class="space-y-1 text-center">
                            <div class="flex text-sm text-gray-600">
                                <label for="document" class="relative cursor-pointer bg-white rounded-md font-medium text-red-600 hover:text-red-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-red-500">
                                    <span class="px-3 py-2 bg-red-50 rounded-lg">
                                        <i class="fas fa-upload mr-2"></i><span id="upload-text">Pilih jenis file terlebih dahulu</span>
                                    </span>
                                    <input 
                                        id="document" 
                                        name="document" 
                                        type="file" 
                                        class="sr-only" 
                                        accept=""
                                        required
                                        onchange="updateFileName(this)"
                                        disabled
                                    >
                                </label>
                            </div>
                            <p id="file-size-limit" class="text-xs text-gray-500">Pilih jenis file terlebih dahulu</p>
                            <div id="file-info" class="text-sm text-gray-700 hidden">
                                <i id="file-icon" class="fas fa-file mr-1"></i>
                                <span id="file-name"></span>
                                <span id="file-size" class="text-gray-500"></span>
                            </div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">
                        Pastikan file berisi informasi lengkap tentang karya cipta yang akan didaftarkan.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 pt-6">
                    <button 
                        type="button"
                        onclick="window.location.href='{{ route('user.dashboard') }}'"
                        class="w-full sm:w-1/2 bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200"
                    >
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </button>
                    <button 
                        type="submit"
                        class="w-full sm:w-1/2 bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 transform hover:scale-105 shadow-lg"
                    >
                        <i class="fas fa-paper-plane mr-2"></i>Ajukan Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let isFileSizeValid = false;
        let currentFileType = 'pdf';
        
        function toggleFileType() {
            const fileTypeSelect = document.getElementById('file_type');
            const youtubeSection = document.getElementById('youtube_section');
            const documentInput = document.getElementById('document');
            const fileLabel = document.getElementById('file-label');
            const uploadText = document.getElementById('upload-text');
            const fileSizeLimit = document.getElementById('file-size-limit');
            const fileIcon = document.getElementById('file-icon');
            
            currentFileType = fileTypeSelect.value;
            
            if (currentFileType === 'pdf') {
                youtubeSection.classList.add('hidden');
                documentInput.accept = '.pdf';
                documentInput.disabled = false;
                fileLabel.innerHTML = '<i class="fas fa-file-pdf mr-2 text-gray-400"></i>Dokumen PDF';
                uploadText.textContent = 'Upload dokumen PDF';
                fileSizeLimit.textContent = 'PDF maksimal 20MB';
                fileIcon.className = 'fas fa-file-pdf text-red-500 mr-1';
            } else if (currentFileType === 'video') {
                youtubeSection.classList.remove('hidden');
                documentInput.accept = '.mp4';
                documentInput.disabled = false;
                fileLabel.innerHTML = '<i class="fas fa-video mr-2 text-gray-400"></i>Video MP4';
                uploadText.textContent = 'Upload video MP4';
                fileSizeLimit.textContent = 'Video MP4 maksimal 20MB';
                fileIcon.className = 'fas fa-video text-blue-500 mr-1';
            } else {
                youtubeSection.classList.add('hidden');
                documentInput.accept = '';
                documentInput.disabled = true;
                fileLabel.innerHTML = '<i class="fas fa-file mr-2 text-gray-400"></i>File Dokumen';
                uploadText.textContent = 'Pilih jenis file terlebih dahulu';
                fileSizeLimit.textContent = 'Pilih jenis file terlebih dahulu';
            }
            
            // Clear file input when changing type
            documentInput.value = '';
            document.getElementById('file-info').classList.add('hidden');
            isFileSizeValid = false;
        }
        
        function updateFileName(input) {
            const fileInfo = document.getElementById('file-info');
            const fileName = document.getElementById('file-name');
            const fileSize = document.getElementById('file-size');
            const submitButton = document.querySelector('button[type="submit"]');
            const fileTypeSelect = document.getElementById('file_type');
            
            // Get current file type from select - IMPORTANT!
            currentFileType = fileTypeSelect.value;
            
            if (input.files && input.files[0]) {
                const file = input.files[0];
                fileName.textContent = file.name;
                
                // Convert file size to readable format
                const sizeInMB = (file.size / (1024 * 1024)).toFixed(2);
                fileSize.textContent = ` (${sizeInMB} MB)`;
                
                fileInfo.classList.remove('hidden');
                
                // Reset classes
                fileSize.classList.remove('text-red-500', 'text-green-500');
                
                // Check file type first - IMPORTANT VALIDATION!
                const allowedTypes = currentFileType === 'pdf' ? ['pdf'] : currentFileType === 'video' ? ['mp4'] : [];
                const fileExtension = file.name.split('.').pop().toLowerCase();
                
                if (!allowedTypes.includes(fileExtension)) {
                    showNotification('error', `Hanya file ${currentFileType.toUpperCase()} yang diperbolehkan. Anda memilih file ${fileExtension.toUpperCase()}.`);
                    input.value = '';
                    fileInfo.classList.add('hidden');
                    isFileSizeValid = false;
                    submitButton.disabled = true;
                    submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                    submitButton.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i>File Tidak Valid';
                    return;
                }
                
                // Check file size limit (20MB)
                if (file.size > 20 * 1024 * 1024) {
                    fileSize.classList.add('text-red-500');
                    fileSize.textContent += ` - Ukuran terlalu besar! Maksimal 20MB`;
                    isFileSizeValid = false;
                    
                    // Disable submit button
                    submitButton.disabled = true;
                    submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                    submitButton.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i>File Terlalu Besar';
                    
                    // Show error notification
                    showNotification('error', `File yang Anda pilih terlalu besar. Maksimal ukuran file adalah 20MB untuk ${currentFileType.toUpperCase()}.`);
                } else {
                    fileSize.classList.add('text-green-500');
                    fileSize.textContent += ' ✓';
                    isFileSizeValid = true;
                    
                    // Enable submit button
                    submitButton.disabled = false;
                    submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    submitButton.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Ajukan Sekarang';
                    
                    // Show success notification
                    showNotification('success', `File "${file.name}" siap untuk diupload (${sizeInMB} MB)`);
                }
            } else {
                fileInfo.classList.add('hidden');
                isFileSizeValid = false;
            }
        }
        
        function showNotification(type, message) {
            // Remove existing notifications
            const existingNotifications = document.querySelectorAll('.notification');
            existingNotifications.forEach(notification => notification.remove());
            
            const notification = document.createElement('div');
            notification.className = `notification fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
                type === 'error' ? 'bg-red-100 border-l-4 border-red-500' : 'bg-green-100 border-l-4 border-green-500'
            }`;
            
            notification.innerHTML = `
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-${type === 'error' ? 'exclamation-circle text-red-400' : 'check-circle text-green-400'}"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium ${type === 'error' ? 'text-red-800' : 'text-green-800'}">${message}</p>
                    </div>
                    <div class="ml-auto pl-3">
                        <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-${type === 'error' ? 'red' : 'green'}-400 hover:text-${type === 'error' ? 'red' : 'green'}-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }
        
        // Form submission validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const fileTypeSelect = document.getElementById('file_type');
            const creatorName = document.getElementById('creator_name');
            const creatorWhatsapp = document.getElementById('creator_whatsapp');
            
            // Check required fields
            if (!fileTypeSelect.value) {
                e.preventDefault();
                showNotification('error', 'Harap pilih jenis file terlebih dahulu.');
                return false;
            }
            
            if (!creatorName.value.trim()) {
                e.preventDefault();
                showNotification('error', 'Nama pencipta pertama wajib diisi.');
                return false;
            }
            
            if (!creatorWhatsapp.value.trim()) {
                e.preventDefault();
                showNotification('error', 'Nomor WhatsApp pencipta wajib diisi.');
                return false;
            }
            
            if (!isFileSizeValid) {
                e.preventDefault();
                const maxSize = '20MB';
                showNotification('error', `Pastikan file ${currentFileType.toUpperCase()} yang dipilih tidak melebihi ${maxSize}.`);
                return false;
            }
            
            // Show loading state
            const submitButton = document.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengupload...';
            
            showNotification('success', 'Sedang mengupload file, mohon tunggu...');
        });
        
        // Initialize file type on page load
        document.addEventListener('DOMContentLoaded', function() {
            const fileTypeSelect = document.getElementById('file_type');
            if (fileTypeSelect.value) {
                toggleFileType();
            }
        });
    </script>
</body>
</html>