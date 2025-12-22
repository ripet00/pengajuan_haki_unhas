<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biodata Inventor - Pengajuan Paten</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('admin.partials.header', ['title' => 'Biodata Inventor'])

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="px-4 sm:px-6 lg:px-8 py-6">
                    @if(session('success'))
                        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-green-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
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

                    <div class="space-y-6">
                        <!-- Header Section -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                                <div>
                                    <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                                        <i class="fas fa-user-friends mr-3 text-red-600"></i>Manajemen Biodata Inventor
                                    </h1>
                                    <p class="text-gray-600 mt-1">Kelola dan review biodata yang disubmit oleh pengguna</p>
                                </div>
                                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                                    <!-- Search Bar -->
                                    <form method="GET" action="{{ route('admin.biodata-paten.index') }}" class="flex">
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-search text-gray-400"></i>
                                            </div>
                                            <input type="text" 
                                                   name="search" 
                                                   value="{{ request('search') }}" 
                                                   placeholder="Cari Nama, Telepon, atau Judul" 
                                                   class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 w-64">
                                            <!-- Preserve existing filter when searching -->
                                            @if(request('status'))
                                                <input type="hidden" name="status" value="{{ request('status') }}">
                                            @endif
                                        </div>
                                        <button type="submit" class="ml-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition duration-200 cursor-pointer">
                                            <i class="fas fa-search mr-1"></i>Cari
                                        </button>
                                    </form>
                                    
                                    <!-- Filter Status -->
                                    <form method="GET" action="{{ route('admin.biodata-paten.index') }}" class="flex space-x-2">
                                        <select name="status" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500" onchange="this.form.submit()">
                                            <option value="">Semua Status</option>
                                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                                            <option value="denied" {{ request('status') == 'denied' ? 'selected' : '' }}>Ditolak</option>
                                        </select>
                                        <!-- Preserve existing search when filtering -->
                                        @if(request('search'))
                                            <input type="hidden" name="search" value="{{ request('search') }}">
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Active Filters Notification -->
                        @if(request('search') || request('status'))
                            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-filter text-blue-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-blue-700">
                                                Filter aktif: 
                                                @if(request('search'))
                                                    <span class="font-medium">Pencarian: "{{ request('search') }}"</span>
                                                    @if(request('status'))
                                                        , 
                                                    @endif
                                                @endif
                                                @if(request('status'))
                                                    <span class="font-medium">Status: {{ ucfirst(request('status')) }}</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.biodata-paten.index') }}" 
                                           class="inline-flex items-center px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm font-medium rounded-lg transition duration-200">
                                            <i class="fas fa-times mr-1"></i>Hapus Filter
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Statistics Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div class="bg-white rounded-lg shadow p-6">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                        <i class="fas fa-user-friends text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $totalBiodatas }}</h3>
                                        <p class="text-gray-600">Total Biodata</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white rounded-lg shadow p-6">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                                        <i class="fas fa-check text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $approvedBiodatas }}</h3>
                                        <p class="text-gray-600">Disetujui</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white rounded-lg shadow p-6">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                        <i class="fas fa-clock text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $pendingBiodatas }}</h3>
                                        <p class="text-gray-600">Pending Review</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white rounded-lg shadow p-6">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                                        <i class="fas fa-times text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $rejectedBiodatas }}</h3>
                                        <p class="text-gray-600">Ditolak</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Overdue Warnings -->
                        {{-- @if($documentOverdue > 0 || $certificateOverdue > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if($documentOverdue > 0)
                            <div class="bg-red-50 border-2 border-red-300 rounded-lg shadow-lg p-6">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="p-3 rounded-full bg-red-200">
                                            <i class="fas fa-exclamation-triangle text-red-700 text-2xl"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <h3 class="text-lg font-bold text-red-900 mb-2">
                                            <i class="fas fa-file-upload mr-2"></i>Penyetoran Berkas Terlambat
                                        </h3>
                                        <p class="text-sm text-red-800 mb-2">
                                            <strong class="text-2xl">{{ $documentOverdue }}</strong> biodata sudah melewati deadline 1 bulan untuk penyetoran berkas!
                                        </p>
                                        <p class="text-xs text-red-700">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Segera hubungi user terkait untuk konfirmasi status penyetoran berkas.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif --}}

                            {{-- @if($certificateOverdue > 0)
                            <div class="bg-orange-50 border-2 border-orange-300 rounded-lg shadow-lg p-6">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="p-3 rounded-full bg-orange-200">
                                            <i class="fas fa-certificate text-orange-700 text-2xl"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <h3 class="text-lg font-bold text-orange-900 mb-2">
                                            <i class="fas fa-hourglass-end mr-2"></i>Penerbitan Sertifikat Terlambat
                                        </h3>
                                        <p class="text-sm text-orange-800 mb-2">
                                            <strong class="text-2xl">{{ $certificateOverdue }}</strong> sertifikat sudah melewati estimasi 2 minggu sejak berkas disetor!
                                        </p>
                                        <p class="text-xs text-orange-700">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Segera proses penerbitan sertifikat Paten untuk biodata terkait.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif --}}

                        @if($biodataPatens->count() > 0)
                            <!-- Biodata Table -->
                            <div class="bg-white rounded-lg shadow overflow-hidden">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengaju</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Paten</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Review</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tracking</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Submit</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($biodataPatens as $biodata)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        #{{ $biodata->id }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">{{ $biodata->user->name }}</div>
                                                        <div class="text-sm text-gray-500">{{ $biodata->user->phone_number }}</div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ Str::limit($biodata->submissionPaten->judul_paten, 40) }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    @php
                                                        $status = $biodata->status;
                                                        $statusClasses = [
                                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                                            'approved' => 'bg-green-100 text-green-800', 
                                                            'rejected' => 'bg-red-100 text-red-800',
                                                            'denied' => 'bg-red-100 text-red-800'
                                                        ];
                                                        $statusIcons = [
                                                            'pending' => 'fas fa-clock',
                                                            'approved' => 'fas fa-check',
                                                            'rejected' => 'fas fa-times',
                                                            'denied' => 'fas fa-times'
                                                        ];
                                                    @endphp
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClasses[$status] ?? 'bg-gray-100 text-gray-800' }}">
                                                        <i class="{{ $statusIcons[$status] ?? 'fas fa-question' }} mr-1"></i>
                                                        {{ ucfirst($status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    @if($biodata->status == 'approved')
                                                        <div class="space-y-1">
                                                            <!-- Document Status -->
                                                            @if($biodata->document_submitted)
                                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                                                    <i class="fas fa-check-circle mr-1"></i>Berkas ✓
                                                                </span>
                                                            @elseif($biodata->isDocumentOverdue())
                                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800">
                                                                    <i class="fas fa-exclamation-triangle mr-1"></i>Terlambat!
                                                                </span>
                                                            @else
                                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                    <i class="fas fa-clock mr-1"></i>Menunggu
                                                                </span>
                                                            @endif
                                                            
                                                            <!-- Certificate Status -->
                                                            @if($biodata->certificate_issued)
                                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                                    <i class="fas fa-certificate mr-1"></i>Sertifikat ✓
                                                                </span>
                                                            @elseif($biodata->document_submitted && $biodata->isSigningOverdue())
                                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-orange-100 text-orange-800">
                                                                    <i class="fas fa-hourglass-end mr-1"></i>Proses Telat
                                                                </span>
                                                            @elseif($biodata->document_submitted)
                                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-50 text-blue-700">
                                                                    <i class="fas fa-hourglass-half mr-1"></i>Proses...
                                                                </span>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <span class="text-xs text-gray-500">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500">
                                                    {{ $biodata->created_at->format('d M Y H:i') }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    <a href="{{ route('admin.biodata-paten.show', $biodata) }}" 
                                                       class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                                        <i class="fas fa-eye mr-1"></i>View
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Pagination -->
                            @if($biodataPatens->hasPages())
                                <div class="bg-white px-6 py-3 border-t border-gray-200 rounded-b-lg">
                                    {{ $biodataPatens->appends(request()->all())->links() }}
                                </div>
                            @endif
                        @else
                            <!-- Empty State -->
                            <div class="bg-white rounded-lg shadow p-12 text-center">
                                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-user-friends text-gray-400 text-3xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Biodata</h3>
                                <p class="text-gray-500 mb-6">
                                    @if(request('search') || request('status'))
                                        Tidak ada biodata yang cocok dengan filter yang diterapkan.
                                    @else
                                        Belum ada biodata yang disubmit oleh pengguna.
                                    @endif
                                </p>
                                @if(request('search') || request('status'))
                                    <a href="{{ route('admin.biodata-paten.index') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition duration-200">
                                        <i class="fas fa-times mr-2"></i>Hapus Filter
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>

    @include('admin.partials.sidebar-script')
</body>
</html>
