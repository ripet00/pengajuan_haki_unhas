<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengajuan Paten - HKI Unhas</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        }
        
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
        <!-- Back Button and Actions -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-3 sm:space-y-0">
            <a href="{{ route('user.dashboard') }}" class="inline-flex items-center px-5 py-3 bg-white hover:bg-gray-50 text-gray-800 hover:text-gray-900 border-2 border-gray-500 hover:border-gray-700 rounded-lg font-bold transition duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Dashboard
            </a>
            <a href="{{ route('user.submissions-paten.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-lg font-bold transition duration-200 shadow-md hover:shadow-xl">
                <i class="fas fa-plus mr-2"></i>Pengajuan Paten Baru
            </a>
        </div>

        <!-- Page Title -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-2 flex items-center">
                <i class="fas fa-lightbulb text-green-600 mr-3"></i>Daftar Pengajuan Paten
            </h2>
            <p class="text-gray-600">Berikut adalah daftar semua pengajuan paten yang pernah Anda buat.</p>
        </div>

        @if($submissionsPaten->count() > 0)
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-lightbulb text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $submissionsPaten->total() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Pending</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $submissionsPaten->where('status', 'pending')->count() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Disetujui</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $submissionsPaten->where('status', 'approved')->count() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-times-circle text-red-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Ditolak</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $submissionsPaten->where('status', 'rejected')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submissions List -->
            <div class="space-y-4">
                @foreach($submissionsPaten as $submission)
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <span class="text-sm font-medium text-gray-500 mr-3">ID: #{{ $submission->id }}</span>
                                    @if($submission->revisi)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-redo mr-1"></i>Revisi
                                        </span>
                                    @endif
                                </div>
                                
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $submission->judul_paten }}</h3>
                                
                                <div class="flex flex-wrap gap-2 mb-3">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $submission->kategori_paten == 'Paten' ? 'bg-green-100 text-green-800' : 'bg-emerald-100 text-emerald-800' }}">
                                        <i class="fas fa-{{ $submission->kategori_paten == 'Paten' ? 'certificate' : 'award' }} mr-1"></i>
                                        {{ $submission->kategori_paten }}
                                    </span>
                                    
                                    @if($submission->status == 'pending')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>Pending
                                        </span>
                                    @elseif($submission->status == 'approved')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Disetujui
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i>Ditolak
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="text-sm text-gray-600">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    Diajukan: {{ $submission->created_at->format('d F Y, H:i') }} WITA
                                </div>
                            </div>
                            
                            <div class="flex flex-col space-y-2 w-full md:w-auto">
                                <a href="{{ route('user.submissions-paten.show', $submission) }}" 
                                   class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                    <i class="fas fa-eye mr-2"></i>Lihat Detail
                                </a>
                                
                                @if($submission->status == 'rejected')
                                    <a href="{{ route('user.submissions-paten.create') }}" 
                                       class="inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                        <i class="fas fa-redo mr-2"></i>Upload Ulang
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $submissionsPaten->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <i class="fas fa-lightbulb text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Pengajuan Paten</h3>
                <p class="text-gray-600 mb-6">Anda belum memiliki pengajuan paten. Mulai ajukan paten pertama Anda sekarang!</p>
                <a href="{{ route('user.submissions-paten.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition duration-200 shadow-md hover:shadow-xl">
                    <i class="fas fa-plus mr-2"></i>Buat Pengajuan Paten
                </a>
            </div>
        @endif
    </main>
</body>
</html>
