<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Baru - HKI Unhas</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                    <label for="document" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-file-pdf mr-2 text-gray-400"></i>Dokumen HKI (PDF)
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-red-400 transition duration-200">
                        <div class="space-y-1 text-center">
                            <div class="flex text-sm text-gray-600">
                                <label for="document" class="relative cursor-pointer bg-white rounded-md font-medium text-red-600 hover:text-red-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-red-500">
                                    <span class="px-3 py-2 bg-red-50 rounded-lg">
                                        <i class="fas fa-upload mr-2"></i>Upload dokumen PDF
                                    </span>
                                    <input 
                                        id="document" 
                                        name="document" 
                                        type="file" 
                                        class="sr-only" 
                                        accept=".pdf"
                                        required
                                        onchange="updateFileName(this)"
                                    >
                                </label>
                            </div>
                            <p class="text-xs text-gray-500">PDF maksimal 20MB</p>
                            <div id="file-info" class="text-sm text-gray-700 hidden">
                                <i class="fas fa-file-pdf text-red-500 mr-1"></i>
                                <span id="file-name"></span>
                                <span id="file-size" class="text-gray-500"></span>
                            </div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">
                        Pastikan dokumen berisi informasi lengkap tentang karya cipta yang akan didaftarkan.
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
        
        function updateFileName(input) {
            const fileInfo = document.getElementById('file-info');
            const fileName = document.getElementById('file-name');
            const fileSize = document.getElementById('file-size');
            const submitButton = document.querySelector('button[type="submit"]');
            
            if (input.files && input.files[0]) {
                const file = input.files[0];
                fileName.textContent = file.name;
                
                // Convert file size to readable format
                const sizeInMB = (file.size / (1024 * 1024)).toFixed(2);
                fileSize.textContent = ` (${sizeInMB} MB)`;
                
                fileInfo.classList.remove('hidden');
                
                // Reset classes
                fileSize.classList.remove('text-red-500', 'text-green-500');
                
                // Check file size limit (20MB)
                if (file.size > 20 * 1024 * 1024) {
                    fileSize.classList.add('text-red-500');
                    fileSize.textContent += ' - Ukuran terlalu besar! Maksimal 20MB';
                    isFileSizeValid = false;
                    
                    // Disable submit button
                    submitButton.disabled = true;
                    submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                    submitButton.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i>File Terlalu Besar';
                    
                    // Show error notification
                    showNotification('error', 'File PDF yang Anda pilih terlalu besar. Maksimal ukuran file adalah 20MB.');
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
                
                // Check file type
                if (!file.type.includes('pdf')) {
                    showNotification('error', 'Hanya file PDF yang diperbolehkan.');
                    input.value = '';
                    fileInfo.classList.add('hidden');
                    isFileSizeValid = false;
                    submitButton.disabled = true;
                    submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                    submitButton.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i>File Tidak Valid';
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
            if (!isFileSizeValid) {
                e.preventDefault();
                showNotification('error', 'Pastikan file PDF yang dipilih tidak melebihi 20MB.');
                return false;
            }
            
            // Show loading state
            const submitButton = document.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengupload...';
            
            showNotification('success', 'Sedang mengupload file, mohon tunggu...');
        });
    </script>
</body>
</html>