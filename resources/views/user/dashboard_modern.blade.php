<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User - Pengajuan HAKI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <header class="gradient-bg shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <i class="fas fa-graduation-cap text-2xl text-white mr-3"></i>
                    <div>
                        <h1 class="text-xl font-bold text-white">Sistem Pengajuan HAKI</h1>
                        <p class="text-indigo-100 text-sm">Universitas Hasanuddin</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="flex items-center text-white">
                        <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-2">
                            <span class="text-white font-medium text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <span class="font-medium">{{ Auth::user()->name }}</span>
                    </div>
                    
                    <form method="POST" action="{{ route('user.logout') }}">
                        @csrf
                        <button type="submit" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg font-medium transition duration-200">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Welcome Section -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <div class="text-center">
                <div class="w-20 h-20 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user text-3xl text-white"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Selamat Datang, {{ Auth::user()->name }}!</h2>
                <p class="text-gray-600 mb-6">Kelola pengajuan HAKI Anda dengan mudah melalui dashboard ini.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-2xl mx-auto">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <i class="fas fa-id-card text-2xl text-blue-600 mb-2"></i>
                        <h3 class="font-semibold text-gray-900">Status Akun</h3>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 mt-2">
                            <i class="fas fa-check-circle mr-1"></i>Aktif
                        </span>
                    </div>
                    
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <i class="fas fa-building text-2xl text-purple-600 mb-2"></i>
                        <h3 class="font-semibold text-gray-900">Fakultas</h3>
                        <p class="text-sm text-gray-600 mt-2">{{ Auth::user()->faculty }}</p>
                    </div>
                    
                    <div class="bg-green-50 p-4 rounded-lg">
                        <i class="fas fa-phone text-2xl text-green-600 mb-2"></i>
                        <h3 class="font-semibold text-gray-900">Kontak</h3>
                        <p class="text-sm text-gray-600 mt-2">{{ Auth::user()->phone_number }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-plus text-xl text-blue-600"></i>
                    </div>
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Baru</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Pengajuan Baru</h3>
                <p class="text-gray-600 text-sm mb-4">Ajukan permohonan HAKI baru untuk karya Anda</p>
                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-medium transition duration-200">
                    <i class="fas fa-plus mr-2"></i>Buat Pengajuan
                </button>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-list text-xl text-green-600"></i>
                    </div>
                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">0</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Riwayat Pengajuan</h3>
                <p class="text-gray-600 text-sm mb-4">Lihat semua pengajuan HAKI yang pernah Anda buat</p>
                <button class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg font-medium transition duration-200">
                    <i class="fas fa-history mr-2"></i>Lihat Riwayat
                </button>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-line text-xl text-purple-600"></i>
                    </div>
                    <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Info</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Status Pengajuan</h3>
                <p class="text-gray-600 text-sm mb-4">Pantau progress pengajuan HAKI Anda</p>
                <button class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded-lg font-medium transition duration-200">
                    <i class="fas fa-eye mr-2"></i>Cek Status
                </button>
            </div>
        </div>

        <!-- Information Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Panduan HAKI -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-book text-orange-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Panduan HAKI</h3>
                </div>
                <div class="space-y-3">
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <span class="text-blue-600 text-xs font-semibold">1</span>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">Persiapan Dokumen</h4>
                            <p class="text-sm text-gray-600">Siapkan dokumen pendukung yang diperlukan</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <span class="text-blue-600 text-xs font-semibold">2</span>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">Pengajuan Online</h4>
                            <p class="text-sm text-gray-600">Lengkapi formulir pengajuan melalui sistem</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <span class="text-blue-600 text-xs font-semibold">3</span>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">Review & Approval</h4>
                            <p class="text-sm text-gray-600">Tunggu proses review dari tim HAKI</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik Pengajuan -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-chart-bar text-indigo-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Statistik Pengajuan Anda</h3>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-2xl font-bold text-gray-400">0</div>
                        <div class="text-sm text-gray-600">Total Pengajuan</div>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-2xl font-bold text-gray-400">0</div>
                        <div class="text-sm text-gray-600">Disetujui</div>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-2xl font-bold text-gray-400">0</div>
                        <div class="text-sm text-gray-600">Dalam Proses</div>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-2xl font-bold text-gray-400">0</div>
                        <div class="text-sm text-gray-600">Ditolak</div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="text-center text-gray-500 text-sm">
                <p>&copy; 2025 Sistem Pengajuan HAKI - Universitas Hasanuddin. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>