<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tugas Review Substansi - Pengajuan HKI</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('admin.partials.header', ['title' => 'Daftar Tugas Review Substansi'])

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
                                        <i class="fas fa-clipboard-list mr-3 text-purple-600"></i>Daftar Tugas Review Substansi
                                    </h1>
                                    <p class="text-gray-600 mt-1">Kelola pengajuan paten yang ditugaskan kepada Anda</p>
                                </div>
                                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                                    <!-- Search Bar -->
                                    <form method="GET" action="{{ route('admin.pendamping-paten.index') }}" class="flex">
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-search text-gray-400"></i>
                                            </div>
                                            <input type="text" 
                                                   name="search" 
                                                   value="{{ request('search') }}" 
                                                   placeholder="Cari Judul Paten" 
                                                   class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 w-64">
                                            @if(request('filter'))
                                                <input type="hidden" name="filter" value="{{ request('filter') }}">
                                            @endif
                                        </div>
                                        <button type="submit" class="ml-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition duration-200 cursor-pointer">
                                            <i class="fas fa-search mr-1"></i>Cari
                                        </button>
                                    </form>
                                    
                                    <!-- Filter Status -->
                                    <form method="GET" action="{{ route('admin.pendamping-paten.index') }}" class="flex space-x-2">
                                        <select name="filter" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500" onchange="this.form.submit()">
                                            <option value="">Semua Tugas</option>
                                            <option value="active" {{ request('filter') == 'active' ? 'selected' : '' }}>Tugas Aktif</option>
                                            <option value="completed" {{ request('filter') == 'completed' ? 'selected' : '' }}>Tugas Selesai</option>
                                        </select>
                                        @if(request('search'))
                                            <input type="hidden" name="search" value="{{ request('search') }}">
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Active Filters Notification -->
                        @if(request('search') || request('filter'))
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
                                                    @if(request('filter'))
                                                        , 
                                                    @endif
                                                @endif
                                                @if(request('filter'))
                                                    <span class="font-medium">
                                                        @if(request('filter') == 'active')
                                                            Tugas Aktif
                                                        @elseif(request('filter') == 'completed')
                                                            Tugas Selesai
                                                        @endif
                                                    </span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.pendamping-paten.index') }}" 
                                           class="inline-flex items-center px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm font-medium rounded-lg transition duration-200">
                                            <i class="fas fa-times mr-1"></i>Hapus Filter
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($submissions->count() > 0)
                            <!-- Statistics Cards -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="bg-white rounded-lg shadow p-6">
                                    <div class="flex items-center">
                                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                            <i class="fas fa-hourglass-half text-xl"></i>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-lg font-semibold text-gray-900">
                                                {{ $submissions->where('status', \App\Models\SubmissionPaten::STATUS_PENDING_SUBSTANCE_REVIEW)->count() }}
                                            </h3>
                                            <p class="text-gray-600">Pending Review</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white rounded-lg shadow p-6">
                                    <div class="flex items-center">
                                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                                            <i class="fas fa-check-double text-xl"></i>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-lg font-semibold text-gray-900">
                                                {{ $submissions->where('status', \App\Models\SubmissionPaten::STATUS_APPROVED_SUBSTANCE)->count() }}
                                            </h3>
                                            <p class="text-gray-600">Disetujui</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white rounded-lg shadow p-6">
                                    <div class="flex items-center">
                                        <div class="p-3 rounded-full bg-red-100 text-red-600">
                                            <i class="fas fa-exclamation-triangle text-xl"></i>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-lg font-semibold text-gray-900">
                                                {{ $submissions->where('status', \App\Models\SubmissionPaten::STATUS_REJECTED_SUBSTANCE_REVIEW)->count() }}
                                            </h3>
                                            <p class="text-gray-600">Ditolak</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submissions Table -->
                            <div class="bg-white rounded-lg shadow overflow-hidden">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengusul</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Paten</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ditugaskan</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($submissions as $submission)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        #{{ $submission->id }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">{{ $submission->user->name }}</div>
                                                        <div class="text-sm text-gray-500">{{ $submission->user->faculty }}</div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="max-w-xs">
                                                        <div class="text-sm font-medium text-gray-900 truncate">{{ $submission->judul_paten }}</div>
                                                        <div class="text-sm text-gray-500">
                                                            <i class="fas fa-file-word text-blue-500 mr-1"></i>
                                                            {{ Str::limit($submission->original_filename ?? $submission->file_name, 30) }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $submission->kategori_paten == 'Paten' ? 'bg-green-100 text-green-800' : 'bg-emerald-100 text-emerald-800' }}">
                                                        <i class="fas fa-{{ $submission->kategori_paten == 'Paten' ? 'certificate' : 'award' }} mr-1"></i>
                                                        {{ $submission->kategori_paten }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($submission->status == \App\Models\SubmissionPaten::STATUS_PENDING_SUBSTANCE_REVIEW)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            <i class="fas fa-hourglass-half mr-1"></i>Pending
                                                        </span>
                                                    @elseif($submission->status == \App\Models\SubmissionPaten::STATUS_APPROVED_SUBSTANCE)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            <i class="fas fa-check-double mr-1"></i>Disetujui
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                            <i class="fas fa-exclamation-triangle mr-1"></i>Ditolak
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $submission->assigned_at ? $submission->assigned_at->translatedFormat('d/m/Y') : '-' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <a href="{{ route('admin.pendamping-paten.show', $submission) }}" 
                                                       class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition duration-200">
                                                        <i class="fas fa-eye mr-1"></i>Detail
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                @if($submissions->hasPages())
                                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                                        {{ $submissions->links() }}
                                    </div>
                                @endif
                            </div>
                        @else
                            <!-- Empty State -->
                            <div class="bg-white rounded-lg shadow p-12 text-center">
                                <i class="fas fa-inbox text-gray-300 text-6xl mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Tugas</h3>
                                <p class="text-gray-500">
                                    @if(request('search') || request('filter'))
                                        Tidak ada tugas yang sesuai dengan filter. 
                                        <a href="{{ route('admin.pendamping-paten.index') }}" class="text-indigo-600 hover:text-indigo-800">Hapus filter</a>
                                    @else
                                        Belum ada tugas review substansi yang ditugaskan kepada Anda.
                                    @endif
                                </p>
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
