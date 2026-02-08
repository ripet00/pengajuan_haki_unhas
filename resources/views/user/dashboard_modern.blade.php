<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">
    <title>Dashboard User - Pengajuan HKI</title>
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

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 sm:py-8 px-4 sm:px-6 lg:px-8">
        <!-- Welcome Section -->
        <div class="bg-white rounded-xl shadow-lg p-6 sm:p-8 mb-6 sm:mb-8">
            <div class="text-center">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user text-2xl sm:text-3xl text-white"></i>
                </div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">Selamat Datang, {{ Auth::user()->name }}!</h2>
                <p class="text-gray-600 mb-6 text-sm sm:text-base">Kelola pengajuan HKI Anda dengan mudah melalui dashboard ini.</p>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-2xl mx-auto">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <i class="fas fa-id-card text-xl sm:text-2xl text-blue-600 mb-2"></i>
                        <h3 class="font-semibold text-gray-900 text-sm sm:text-base">Status Akun</h3>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 mt-2">
                            <i class="fas fa-check-circle mr-1"></i>Aktif
                        </span>
                    </div>
                    
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <i class="fas fa-building text-xl sm:text-2xl text-purple-600 mb-2"></i>
                        <h3 class="font-semibold text-gray-900 text-sm sm:text-base">Fakultas</h3>
                        <p class="text-xs sm:text-sm text-gray-600 mt-2">{{ Auth::user()->faculty }}</p>
                    </div>
                    
                    <div class="bg-green-50 p-4 rounded-lg">
                        <i class="fas fa-phone text-xl sm:text-2xl text-green-600 mb-2"></i>
                        <h3 class="font-semibold text-gray-900 text-sm sm:text-base">Kontak</h3>
                        <p class="text-xs sm:text-sm text-gray-600 mt-2">{{ Auth::user()->phone_number }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
            <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 hover:shadow-xl transition duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-plus text-lg sm:text-xl text-blue-600"></i>
                    </div>
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Baru</span>
                </div>
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Pengajuan Hak Cipta</h3>
                <p class="text-gray-600 text-xs sm:text-sm mb-4">Ajukan permohonan Hak Cipta untuk karya Anda</p>
                <button 
                    onclick="window.location.href='{{ route('user.submissions.create') }}'"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-medium transition duration-200 text-sm sm:text-base">
                    <i class="fas fa-plus mr-2"></i>Buat Pengajuan Hak Cipta
                </button>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 hover:shadow-xl transition duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-lightbulb text-lg sm:text-xl text-green-600"></i>
                    </div>
                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Paten</span>
                </div>
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Pengajuan Paten</h3>
                <p class="text-gray-600 text-xs sm:text-sm mb-4">Ajukan permohonan Paten untuk penemuan Anda</p>
                <button 
                    onclick="window.location.href='{{ route('user.submissions-paten.create') }}'"
                    class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg font-medium transition duration-200 text-sm sm:text-base">
                    <i class="fas fa-plus mr-2"></i>Buat Pengajuan Paten
                </button>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 hover:shadow-xl transition duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-history text-lg sm:text-xl text-purple-600"></i>
                    </div>
                    <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Hak Cipta</span>
                </div>
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Riwayat Hak Cipta</h3>
                <p class="text-gray-600 text-xs sm:text-sm mb-4">Lihat semua riwayat pengajuan Hak Cipta Anda</p>
                <button 
                    onclick="window.location.href='{{ route('user.submissions.index') }}'"
                    class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded-lg font-medium transition duration-200 text-sm sm:text-base">
                    <i class="fas fa-list mr-2"></i>Lihat Riwayat
                </button>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 hover:shadow-xl transition duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-teal-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-history text-lg sm:text-xl text-teal-600"></i>
                    </div>
                    <span class="bg-teal-100 text-teal-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Paten</span>
                </div>
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Riwayat Paten</h3>
                <p class="text-gray-600 text-xs sm:text-sm mb-4">Lihat semua riwayat pengajuan Paten Anda</p>
                <button 
                    onclick="window.location.href='{{ route('user.submissions-paten.index') }}'"
                    class="w-full bg-teal-600 hover:bg-teal-700 text-white py-2 px-4 rounded-lg font-medium transition duration-200 text-sm sm:text-base">
                    <i class="fas fa-list mr-2"></i>Lihat Riwayat
                </button>
            </div>
        </div>

        <!-- Information Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6">
            <!-- Panduan Hak Cipta -->
            <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6">
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-book text-orange-600"></i>
                    </div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900">Panduan Hak Cipta</h3>
                </div>
                <div class="space-y-3">
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                            <span class="text-blue-600 text-xs font-semibold">1</span>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 text-sm sm:text-base">Upload Dokumen</h4>
                            <p class="text-xs sm:text-sm text-gray-600">Upload file karya Anda dan tunggu review admin</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                            <span class="text-purple-600 text-xs font-semibold">2</span>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 text-sm sm:text-base">Upload Biodata</h4>
                            <p class="text-xs sm:text-sm text-gray-600">Isi biodata pencipta setelah dokumen disetujui</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-orange-100 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                            <span class="text-orange-600 text-xs font-semibold">3</span>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 text-sm sm:text-base">Setor Berkas</h4>
                            <p class="text-xs sm:text-sm text-gray-600">Download & setor form ke kantor</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                            <span class="text-yellow-600 text-xs font-semibold">4</span>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 text-sm sm:text-base">Tunggu Kode Billing dari Admin</h4>
                            <p class="text-xs sm:text-sm text-gray-600">Admin akan mengirimkan kode billing untuk pembayaran</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                            <span class="text-green-600 text-xs font-semibold">5</span>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 text-sm sm:text-base">Terima Sertifikat</h4>
                            <p class="text-xs sm:text-sm text-gray-600">Sertifikat Hak Cipta akan diterbitkan</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panduan Paten -->
            <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6">
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-lightbulb text-green-600"></i>
                    </div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900">Panduan Paten</h3>
                </div>
                <div class="space-y-3">
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                            <span class="text-blue-600 text-xs font-semibold">1</span>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 text-sm sm:text-base">Upload Draft Paten</h4>
                            <p class="text-xs sm:text-sm text-gray-600">Upload dokumen draft paten Anda</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                            <span class="text-purple-600 text-xs font-semibold">2</span>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 text-sm sm:text-base">Upload Biodata</h4>
                            <p class="text-xs sm:text-sm text-gray-600">Isi biodata inventor setelah draft disetujui</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-orange-100 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                            <span class="text-orange-600 text-xs font-semibold">3</span>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 text-sm sm:text-base">Setor Berkas</h4>
                            <p class="text-xs sm:text-sm text-gray-600">Download & setor form ke kantor</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                            <span class="text-yellow-600 text-xs font-semibold">4</span>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 text-sm sm:text-base">Tunggu Info Pembayaran dari Admin</h4>
                            <p class="text-xs sm:text-sm text-gray-600">Admin akan mengirimkan nomor rekening pimpinan</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                            <span class="text-green-600 text-xs font-semibold">5</span>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 text-sm sm:text-base">Dokumen Selesai</h4>
                            <p class="text-xs sm:text-sm text-gray-600">Proses paten telah selesai</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarif dan Waktu Pelayanan -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6">
            <!-- Tarif Pendaftaran -->
            <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6">
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-money-bill-wave text-yellow-600"></i>
                    </div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900">Tarif Pendaftaran HKI 2025</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-2 font-semibold text-gray-700">Jenis HKI</th>
                                <th class="text-right py-2 font-semibold text-gray-700">Universitas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-100">
                                <td class="py-2 text-gray-600">Hak Cipta (Buku, Buku Panduan, Booklet, dll)</td>
                                <td class="py-2 text-right font-semibold text-gray-900">Rp 200.000</td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <td class="py-2 text-gray-600">Paten Sederhana</td>
                                <td class="py-2 text-right font-semibold text-gray-900">Rp 700.000</td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <td class="py-2 text-gray-600">Paten</td>
                                <td class="py-2 text-right font-semibold text-gray-900">Rp 850.000</td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <td class="py-2 text-gray-600">Desain Industri</td>
                                <td class="py-2 text-right font-semibold text-gray-900">Rp 250.000</td>
                            </tr>
                            <tr>
                                <td class="py-2 text-gray-600">Merek</td>
                                <td class="py-2 text-right font-semibold text-gray-900">Rp 500.000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Waktu Pelayanan -->
            <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6">
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-clock text-blue-600"></i>
                    </div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900">Waktu Pelayanan Pendaftaran HKI</h3>
                </div>
                <div class="space-y-4">
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <div class="font-semibold text-blue-800 mb-2">
                            <i class="fas fa-calendar-week mr-2"></i>Senin - Kamis
                        </div>
                        <div class="text-blue-700 space-y-1">
                            <div class="flex items-center">
                                <i class="fas fa-sun mr-2 text-yellow-500"></i>
                                <span>08.00 - 12.00 WITA</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-moon mr-2 text-indigo-500"></i>
                                <span>13.00 - 15.30 WITA</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 bg-green-50 rounded-lg">
                        <div class="font-semibold text-green-800 mb-2">
                            <i class="fas fa-calendar-day mr-2"></i>Jumat
                        </div>
                        <div class="text-green-700 space-y-1">
                            <div class="flex items-center">
                                <i class="fas fa-sun mr-2 text-yellow-500"></i>
                                <span>08.00 - 12.00 WITA</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-moon mr-2 text-indigo-500"></i>
                                <span>13.30 - 16.00 WITA</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Pengajuan -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            <!-- Statistik Hak Cipta -->
            <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6">
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-chart-bar text-orange-600"></i>
                    </div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900">Statistik Pengajuan Hak Cipta</h3>
                </div>
                
                <!-- Summary Card -->
                <div class="mb-4 p-4 bg-gradient-to-r from-orange-50 to-red-50 rounded-lg border border-orange-200">
                    <div class="text-center">
                        <div class="text-3xl sm:text-4xl font-bold text-orange-600 mb-1">
                            {{ Auth::user()->submissions()->count() }}
                        </div>
                        <div class="text-sm text-gray-600 font-medium">Total Pengajuan Hak Cipta</div>
                    </div>
                </div>

                <!-- Detailed Stats Grid -->
                <div class="grid grid-cols-2 gap-3">
                    <!-- 1. Menunggu Review Dokumen -->
                    <div class="text-center p-3 bg-yellow-50 rounded-lg">
                        <div class="flex items-center justify-center mb-2">
                            <i class="fas fa-file-alt text-yellow-600 mr-2"></i>
                            <div class="text-xl font-bold text-yellow-700">
                                {{ Auth::user()->submissions()->where('status', 'pending')->count() }}
                            </div>
                        </div>
                        <div class="text-xs text-gray-700 font-medium">Menunggu Review Dokumen</div>
                    </div>

                    <!-- 2. Dokumen Disetujui -->
                    <div class="text-center p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center justify-center mb-2">
                            <i class="fas fa-check-circle text-green-600 mr-2"></i>
                            <div class="text-xl font-bold text-green-700">
                                {{ Auth::user()->submissions()->where('status', 'approved')->where('biodata_status', 'not_started')->count() }}
                            </div>
                        </div>
                        <div class="text-xs text-gray-700 font-medium">Dokumen Disetujui</div>
                    </div>

                    <!-- 3. Dokumen Ditolak -->
                    <div class="text-center p-3 bg-red-50 rounded-lg">
                        <div class="flex items-center justify-center mb-2">
                            <i class="fas fa-times-circle text-red-600 mr-2"></i>
                            <div class="text-xl font-bold text-red-700">
                                {{ Auth::user()->submissions()->where('status', 'rejected')->count() }}
                            </div>
                        </div>
                        <div class="text-xs text-gray-700 font-medium">Dokumen Ditolak</div>
                    </div>

                    <!-- 4. Menunggu Review Biodata -->
                    <div class="text-center p-3 bg-orange-50 rounded-lg">
                        <div class="flex items-center justify-center mb-2">
                            <i class="fas fa-user-clock text-orange-600 mr-2"></i>
                            <div class="text-xl font-bold text-orange-700">
                                {{ Auth::user()->submissions()->where('biodata_status', 'pending')->count() }}
                            </div>
                        </div>
                        <div class="text-xs text-gray-700 font-medium">Menunggu Review Biodata</div>
                    </div>

                    <!-- 5. Biodata Disetujui -->
                    <div class="text-center p-3 bg-teal-50 rounded-lg">
                        <div class="flex items-center justify-center mb-2">
                            <i class="fas fa-user-check text-teal-600 mr-2"></i>
                            <div class="text-xl font-bold text-teal-700">
                                {{ Auth::user()->submissions()->where('biodata_status', 'approved')->count() }}
                            </div>
                        </div>
                        <div class="text-xs text-gray-700 font-medium">Biodata Disetujui</div>
                    </div>

                    <!-- 6. Biodata Ditolak -->
                    <div class="text-center p-3 bg-pink-50 rounded-lg">
                        <div class="flex items-center justify-center mb-2">
                            <i class="fas fa-user-times text-pink-600 mr-2"></i>
                            <div class="text-xl font-bold text-pink-700">
                                {{ Auth::user()->submissions()->where('biodata_status', 'rejected')->count() }}
                            </div>
                        </div>
                        <div class="text-xs text-gray-700 font-medium">Biodata Ditolak</div>
                    </div>

                    <!-- 7. Selesai -->
                    <div class="text-center p-3 bg-blue-50 rounded-lg col-span-2">
                        <div class="flex items-center justify-center mb-2">
                            <i class="fas fa-certificate text-blue-600 mr-2"></i>
                            <div class="text-xl font-bold text-blue-700">
                                {{ Auth::user()->submissions()->where('status', 'completed')->count() }}
                            </div>
                        </div>
                        <div class="text-xs text-gray-700 font-medium">Selesai (Sertifikat Diterima)</div>
                    </div>
                </div>
            </div>

            <!-- Statistik Paten -->
            <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6">
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-chart-line text-green-600"></i>
                    </div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900">Statistik Pengajuan Paten</h3>
                </div>

                <!-- Summary Card -->
                <div class="mb-4 p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200">
                    <div class="text-center">
                        <div class="text-3xl sm:text-4xl font-bold text-green-600 mb-1">
                            {{ Auth::user()->submissionsPaten()->count() }}
                        </div>
                        <div class="text-sm text-gray-600 font-medium">Total Pengajuan Paten</div>
                    </div>
                </div>

                <!-- Detailed Stats Grid -->
                <div class="grid grid-cols-2 gap-3">
                    <!-- 1. Menunggu Review Dokumen -->
                    <div class="text-center p-3 bg-yellow-50 rounded-lg">
                        <div class="flex items-center justify-center mb-2">
                            <i class="fas fa-file-alt text-yellow-600 mr-2"></i>
                            <div class="text-xl font-bold text-yellow-700">
                                {{ Auth::user()->submissionsPaten()->where('status', 'pending_format_review')->count() }}
                            </div>
                        </div>
                        <div class="text-xs text-gray-700 font-medium">Menunggu Review Format</div>
                    </div>

                    <!-- 2. Dokumen Disetujui -->
                    <div class="text-center p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center justify-center mb-2">
                            <i class="fas fa-check-circle text-green-600 mr-2"></i>
                            <div class="text-xl font-bold text-green-700">
                                {{ Auth::user()->submissionsPaten()->whereIn('status', ['approved_format', 'approved_substance'])->where('biodata_status', 'not_started')->count() }}
                            </div>
                        </div>
                        <div class="text-xs text-gray-700 font-medium">Format/Substansi Disetujui</div>
                    </div>

                    <!-- 3. Dokumen Ditolak -->
                    <div class="text-center p-3 bg-red-50 rounded-lg">
                        <div class="flex items-center justify-center mb-2">
                            <i class="fas fa-times-circle text-red-600 mr-2"></i>
                            <div class="text-xl font-bold text-red-700">
                                {{ Auth::user()->submissionsPaten()->whereIn('status', ['rejected_format_review', 'rejected_substance_review'])->count() }}
                            </div>
                        </div>
                        <div class="text-xs text-gray-700 font-medium">Dokumen Ditolak</div>
                    </div>

                    <!-- 4. Review Substansi -->
                    <div class="text-center p-3 bg-blue-50 rounded-lg">
                        <div class="flex items-center justify-center mb-2">
                            <i class="fas fa-microscope text-blue-600 mr-2"></i>
                            <div class="text-xl font-bold text-blue-700">
                                {{ Auth::user()->submissionsPaten()->where('status', 'pending_substance_review')->count() }}
                            </div>
                        </div>
                        <div class="text-xs text-gray-700 font-medium">Review Substansi</div>
                    </div>

                    <!-- 5. Menunggu Review Biodata -->
                    <div class="text-center p-3 bg-orange-50 rounded-lg">
                        <div class="flex items-center justify-center mb-2">
                            <i class="fas fa-user-clock text-orange-600 mr-2"></i>
                            <div class="text-xl font-bold text-orange-700">
                                {{ Auth::user()->submissionsPaten()->where('biodata_status', 'pending')->count() }}
                            </div>
                        </div>
                        <div class="text-xs text-gray-700 font-medium">Menunggu Review Biodata</div>
                    </div>

                    <!-- 6. Biodata Disetujui -->
                    <div class="text-center p-3 bg-teal-50 rounded-lg">
                        <div class="flex items-center justify-center mb-2">
                            <i class="fas fa-user-check text-teal-600 mr-2"></i>
                            <div class="text-xl font-bold text-teal-700">
                                {{ Auth::user()->submissionsPaten()->where('biodata_status', 'approved')->count() }}
                            </div>
                        </div>
                        <div class="text-xs text-gray-700 font-medium">Biodata Disetujui</div>
                    </div>

                    <!-- 7. Biodata Ditolak -->
                    <div class="text-center p-3 bg-pink-50 rounded-lg">
                        <div class="flex items-center justify-center mb-2">
                            <i class="fas fa-user-times text-pink-600 mr-2"></i>
                            <div class="text-xl font-bold text-pink-700">
                                {{ Auth::user()->submissionsPaten()->where('biodata_status', 'rejected')->count() }}
                            </div>
                        </div>
                        <div class="text-xs text-gray-700 font-medium">Biodata Ditolak</div>
                    </div>

                    <!-- 8. Selesai -->
                    <div class="text-center p-3 bg-purple-50 rounded-lg">
                        <div class="flex items-center justify-center mb-2">
                            <i class="fas fa-award text-purple-600 mr-2"></i>
                            <div class="text-xl font-bold text-purple-700">
                                {{ Auth::user()->submissionsPaten()->where('status', 'approved_substance')->where('biodata_status', 'approved')->count() }}
                            </div>
                        </div>
                        <div class="text-xs text-gray-700 font-medium">Selesai (Substansi & Biodata Approved)</div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-8 sm:mt-12">
        <div class="max-w-7xl mx-auto py-4 sm:py-6 px-4 sm:px-6 lg:px-8">
            <div class="text-center text-gray-500 text-xs sm:text-sm">
                <p>&copy; 2025 Sistem Pengajuan HKI - Universitas Hasanuddin. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>