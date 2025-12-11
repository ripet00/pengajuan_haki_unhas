<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Paten Baru - HKI Unhas</title>
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
            <h1 class="text-2xl font-bold text-white mb-2">Pengajuan Paten Baru</h1>
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

            <form method="POST" action="{{ route('user.submissions-paten.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <div>
                    <label for="judul_paten" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lightbulb mr-2 text-gray-400"></i>Judul Paten
                    </label>
                    <input 
                        type="text" 
                        id="judul_paten" 
                        name="judul_paten" 
                        value="{{ old('judul_paten') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus transition duration-200"
                        placeholder="Masukkan judul paten Anda"
                        required
                    >
                    <p class="text-sm text-gray-500 mt-1">Contoh: "Alat Pengering Kopi Otomatis Berbasis IoT"</p>
                </div>

                <div>
                    <label for="kategori_paten" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-layer-group mr-2 text-gray-400"></i>Kategori Paten
                    </label>
                    <select 
                        id="kategori_paten" 
                        name="kategori_paten"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus transition duration-200"
                        required
                    >
                        <option value="">Pilih Kategori Paten</option>
                        <option value="Paten" {{ old('kategori_paten') == 'Paten' ? 'selected' : '' }}>Paten</option>
                        <option value="Paten Sederhana" {{ old('kategori_paten') == 'Paten Sederhana' ? 'selected' : '' }}>Paten Sederhana</option>
                    </select>
                    <p class="text-sm text-gray-500 mt-1">
                        <strong>Paten:</strong> Untuk penemuan baru di bidang teknologi (tarif: Rp 850.000)<br>
                        <strong>Paten Sederhana:</strong> Untuk pengembangan/perbaikan alat yang sudah ada (tarif: Rp 700.000)
                    </p>
                </div>

                <!-- Inventor Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="creator_name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-gray-400"></i>Nama Inventor Pertama <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="creator_name" 
                            name="creator_name" 
                            value="{{ old('creator_name') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus transition duration-200"
                            placeholder="Masukkan nama inventor pertama"
                            required
                        >
                    </div>
                    <div>
                        <label for="creator_whatsapp" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fab fa-whatsapp mr-2 text-gray-400"></i>Nomor WhatsApp Inventor <span class="text-red-500">*</span>
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

                <div>
                    <label for="document" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-file-pdf mr-2 text-gray-400"></i>Dokumen Paten (PDF)
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-green-400 transition duration-200">
                        <div class="space-y-1 text-center">
                            <div class="flex text-sm text-gray-600">
                                <label for="document" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
                                    <span class="px-3 py-2 bg-green-50 rounded-lg">
                                        <i class="fas fa-upload mr-2"></i>Upload File PDF
                                    </span>
                                    <input 
                                        id="document" 
                                        name="document" 
                                        type="file" 
                                        class="sr-only" 
                                        accept="application/pdf"
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
                        Dokumen harus berisi deskripsi penemuan, gambar teknis, dan klaim paten.
                    </p>
                </div>

                <!-- Information Box -->
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                <strong>Catatan Penting:</strong><br>
                                • Setelah dokumen disetujui, Anda akan diminta melengkapi biodata inventor<br>
                                • Pastikan dokumen paten berisi informasi lengkap tentang penemuan Anda<br>
                                • Proses review akan dilakukan oleh admin dalam 3-5 hari kerja
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 pt-6">
                    <button 
                        type="button"
                        onclick="window.location.href='{{ route('user.dashboard') }}'"
                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-3 px-6 rounded-lg font-medium transition duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </button>
                    <button 
                        type="submit"
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white py-3 px-6 rounded-lg font-medium transition duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-paper-plane mr-2"></i>Kirim Pengajuan
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-green-100">
            <p class="text-sm">&copy; 2025 Sistem Pengajuan HKI - Universitas Hasanuddin</p>
        </div>
    </div>

    <script>
        function updateFileName(input) {
            const fileInfo = document.getElementById('file-info');
            const fileName = document.getElementById('file-name');
            const fileSize = document.getElementById('file-size');
            
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                
                fileName.textContent = file.name;
                fileSize.textContent = ` (${fileSizeMB} MB)`;
                fileInfo.classList.remove('hidden');
                
                // Validate file size
                if (file.size > 20 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar! Maksimal 20MB.');
                    input.value = '';
                    fileInfo.classList.add('hidden');
                }
            }
        }
    </script>
</body>
</html>
