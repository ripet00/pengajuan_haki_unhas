<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User - Pengajuan HKI</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
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
                        <h1 class="text-sm sm:text-lg font-bold text-white leading-tight">Direktorat Inovasi dan Kekayaan Intelektual</h1>
                        <p class="text-red-100 text-xs sm:text-sm">Universitas Hasanuddin</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <div class="flex items-center text-white min-w-0 flex-1 sm:flex-initial">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-2 flex-shrink-0">
                            <span class="text-white font-medium text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <div class="min-w-0 flex-1 sm:flex-initial">
                            <span class="font-medium text-sm sm:text-base hidden sm:block">{{ Auth::user()->name }}</span>
                            <span class="font-medium text-sm block sm:hidden truncate">{{ explode(' ', Auth::user()->name)[0] }}</span>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('user.logout') }}">
                        @csrf
                        <button type="submit" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-3 sm:px-4 py-2 rounded-lg font-medium transition duration-200 text-sm sm:text-base">
                            <i class="fas fa-sign-out-alt mr-1 sm:mr-2"></i><span class="hidden sm:inline">Logout</span>
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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
            <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 hover:shadow-xl transition duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-plus text-lg sm:text-xl text-blue-600"></i>
                    </div>
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Baru</span>
                </div>
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Pengajuan Baru</h3>
                <p class="text-gray-600 text-xs sm:text-sm mb-4">Ajukan permohonan HKI baru untuk karya Anda</p>
                <button 
                    onclick="window.location.href='{{ route('user.submissions.create') }}'"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-medium transition duration-200 text-sm sm:text-base">
                    <i class="fas fa-plus mr-2"></i>Buat Pengajuan
                </button>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 hover:shadow-xl transition duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-list text-lg sm:text-xl text-green-600"></i>
                    </div>
                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ Auth::user()->submissions()->count() ?? 0 }}</span>
                </div>
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Riwayat Pengajuan</h3>
                <p class="text-gray-600 text-xs sm:text-sm mb-4">Lihat semua pengajuan HKI yang pernah Anda buat</p>
                <button 
                    onclick="window.location.href='{{ route('user.submissions.index') }}'"
                    class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg font-medium transition duration-200 text-sm sm:text-base">
                    <i class="fas fa-history mr-2"></i>Lihat Riwayat
                </button>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 hover:shadow-xl transition duration-300 md:col-span-2 lg:col-span-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-line text-lg sm:text-xl text-purple-600"></i>
                    </div>
                    <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Info</span>
                </div>
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Status Pengajuan</h3>
                <p class="text-gray-600 text-xs sm:text-sm mb-4">Pantau progress pengajuan HKI Anda</p>
                <button 
                    onclick="window.location.href='{{ route('user.submissions.index') }}'"
                    class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded-lg font-medium transition duration-200 text-sm sm:text-base">
                    <i class="fas fa-eye mr-2"></i>Cek Status
                </button>
            </div>
        </div>

        <!-- Information Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            <!-- Panduan HKI -->
            <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6">
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-book text-orange-600"></i>
                    </div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900">Panduan HKI</h3>
                </div>
                <div class="space-y-3">
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                            <span class="text-blue-600 text-xs font-semibold">1</span>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 text-sm sm:text-base">Upload Dokumen HKI</h4>
                            <p class="text-xs sm:text-sm text-gray-600">Upload file PDF dokumen pendukung HKI</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                            <span class="text-blue-600 text-xs font-semibold">2</span>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 text-sm sm:text-base">Review Dokumen</h4>
                            <p class="text-xs sm:text-sm text-gray-600">Admin review dan approval dokumen HKI</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-orange-100 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                            <span class="text-orange-600 text-xs font-semibold">3</span>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 text-sm sm:text-base">Upload Biodata</h4>
                            <p class="text-xs sm:text-sm text-gray-600">Upload biodata lengkap setelah dokumen disetujui</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                            <span class="text-green-600 text-xs font-semibold">4</span>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 text-sm sm:text-base">Finalisasi</h4>
                            <p class="text-xs sm:text-sm text-gray-600">Review biodata dan penyelesaian proses HKI</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik Pengajuan -->
            <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6">
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-chart-bar text-indigo-600"></i>
                    </div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900">Statistik Pengajuan Anda</h3>
                </div>
                <div class="grid grid-cols-2 gap-3 sm:gap-4">
                    <div class="text-center p-3 sm:p-4 bg-gray-50 rounded-lg">
                        <div class="text-xl sm:text-2xl font-bold text-gray-900">{{ Auth::user()->submissions()->count() }}</div>
                        <div class="text-xs sm:text-sm text-gray-600">Total Pengajuan</div>
                    </div>
                    <div class="text-center p-3 sm:p-4 bg-gray-50 rounded-lg">
                        <div class="text-xl sm:text-2xl font-bold text-green-600">{{ Auth::user()->submissions()->where('status', 'approved')->count() }}</div>
                        <div class="text-xs sm:text-sm text-gray-600">Disetujui</div>
                    </div>
                    <div class="text-center p-3 sm:p-4 bg-gray-50 rounded-lg">
                        <div class="text-xl sm:text-2xl font-bold text-yellow-600">{{ Auth::user()->submissions()->where('status', 'pending')->count() }}</div>
                        <div class="text-xs sm:text-sm text-gray-600">Dalam Proses</div>
                    </div>
                    <div class="text-center p-3 sm:p-4 bg-gray-50 rounded-lg">
                        <div class="text-xl sm:text-2xl font-bold text-red-600">{{ Auth::user()->submissions()->where('status', 'rejected')->count() }}</div>
                        <div class="text-xs sm:text-sm text-gray-600">Ditolak</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Information Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 sm:mt-12">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Tarif Pendaftaran -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-money-bill-wave text-yellow-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Tarif Pendaftaran HKI 2025</h3>
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
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-clock text-blue-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Waktu Pelayanan Pendaftaran HKI</h3>
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