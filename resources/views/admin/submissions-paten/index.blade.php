<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengajuan Paten - Pengajuan HKI</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('admin.partials.header', ['title' => 'Pengajuan Paten'])

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

@php
use Illuminate\Support\Facades\Storage;
@endphp
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-lightbulb mr-3 text-green-600"></i>Manajemen Pengajuan Paten
                </h1>
                <p class="text-gray-600 mt-1">Kelola dan review pengajuan paten dari pengguna</p>
            </div>
            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                <!-- Search Bar -->
                <form method="GET" action="{{ route('admin.submissions-paten.index') }}" class="flex">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari Judul Paten" 
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 w-64">
                        <!-- Preserve existing filter when searching -->
                        @if(request('status'))
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                    </div>
                    <button type="submit" class="ml-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition duration-200 cursor-pointer">
                        <i class="fas fa-search mr-1"></i>Cari
                    </button>
                </form>
                
                <!-- Filter Status -->
                <form method="GET" action="{{ route('admin.submissions-paten.index') }}" class="flex space-x-2">
                    <select name="status" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="pending_format_review" {{ request('status') == 'pending_format_review' ? 'selected' : '' }}>Menunggu Review Format</option>
                        <option value="rejected_format_review" {{ request('status') == 'rejected_format_review' ? 'selected' : '' }}>Format Ditolak</option>
                        <option value="approved_format" {{ request('status') == 'approved_format' ? 'selected' : '' }}>Format Disetujui</option>
                        <option value="pending_substance_review" {{ request('status') == 'pending_substance_review' ? 'selected' : '' }}>Menunggu Review Substansi</option>
                        <option value="rejected_substance_review" {{ request('status') == 'rejected_substance_review' ? 'selected' : '' }}>Substansi Ditolak</option>
                        <option value="approved_substance" {{ request('status') == 'approved_substance' ? 'selected' : '' }}>Substansi Disetujui</option>
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
                    <a href="{{ route('admin.submissions-paten.index') }}" 
                       class="inline-flex items-center px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm font-medium rounded-lg transition duration-200">
                        <i class="fas fa-times mr-1"></i>Hapus Filter
                    </a>
                </div>
            </div>
        </div>
    @endif

    @if($submissionsPaten->count() > 0)
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $submissionsPaten->where('status', 'pending_format_review')->count() }}</h3>
                        <p class="text-gray-600">Menunggu Review Format</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                        <i class="fas fa-user-check text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $submissionsPaten->where('status', 'approved_format')->count() }}</h3>
                        <p class="text-gray-600">Siap Ditugaskan</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-clipboard-check text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $submissionsPaten->where('status', 'pending_substance_review')->count() }}</h3>
                        <p class="text-gray-600">Review Substansi</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-lightbulb text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $submissionsPaten->count() }}</h3>
                        <p class="text-gray-600">Total Pengajuan</p>
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori Paten</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Dokumen</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Biodata</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($submissionsPaten as $submission)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 mb-1">
                                        #{{ $submission->id }}
                                    </span>
                                    @if($submission->revisi)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-redo mr-1"></i>Revisi
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $submission->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $submission->user->faculty }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ Str::limit($submission->judul_paten, 40) }}</div>
                                    <div class="text-sm text-gray-500">
                                        <i class="fas fa-file-word text-blue-500 mr-1"></i>
                                        {{ $submission->original_filename ?? $submission->file_name }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $submission->kategori_paten == 'Paten' ? 'bg-green-100 text-green-800' : 'bg-emerald-100 text-emerald-800' }}">
                                    <i class="fas fa-{{ $submission->kategori_paten == 'Paten' ? 'certificate' : 'award' }} mr-1"></i>
                                    {{ $submission->kategori_paten }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($submission->status == 'pending_format_review')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>Menunggu Review Format
                                    </span>
                                @elseif($submission->status == 'rejected_format_review')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>Format Ditolak
                                    </span>
                                @elseif($submission->status == 'approved_format')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>Format Disetujui
                                    </span>
                                @elseif($submission->status == 'pending_substance_review')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-user-clock mr-1"></i>Review Substansi
                                    </span>
                                @elseif($submission->status == 'rejected_substance_review')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        <i class="fas fa-exclamation-circle mr-1"></i>Substansi Ditolak
                                    </span>
                                @elseif($submission->status == 'approved_substance')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                        <i class="fas fa-check-double mr-1"></i>Substansi Disetujui
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-question mr-1"></i>{{ $submission->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($submission->biodata_status == 'not_started')
                                    @if($submission->status == 'approved_substance')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                            <i class="fas fa-hourglass-start mr-1"></i>Siap Upload
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                            <i class="fas fa-lock mr-1"></i>Menunggu Review
                                        </span>
                                    @endif
                                @elseif($submission->biodata_status == 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-clock mr-1"></i>Review Biodata
                                    </span>
                                @elseif($submission->biodata_status == 'approved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-double mr-1"></i>Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times mr-1"></i>Biodata Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <div>{{ $submission->created_at->translatedFormat('d/m/Y') }}</div>
                                <div class="text-xs">{{ $submission->created_at->translatedFormat('H:i') }} WITA</div>
                                @if($submission->reviewed_at)
                                    <div class="text-xs text-gray-400 mt-1">
                                        Review: {{ $submission->reviewed_at->translatedFormat('d/m/Y') }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-2">
                                    @if($submission->status == 'pending_format_review' || $submission->status == 'rejected_format_review')
                                        <a href="{{ route('admin.submissions-paten.show', $submission) }}" 
                                           class="inline-flex items-center px-3 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                            <i class="fas fa-gavel mr-1"></i>
                                            Review Format
                                        </a>
                                    @elseif($submission->status == 'approved_format')
                                        <a href="{{ route('admin.submissions-paten.show', $submission) }}" 
                                           class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                            <i class="fas fa-user-plus mr-1"></i>
                                            Tugaskan
                                        </a>
                                    @else
                                        <a href="{{ route('admin.submissions-paten.show', $submission) }}" 
                                           class="inline-flex items-center px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                            <i class="fas fa-eye mr-1"></i>
                                            Detail
                                        </a>
                                    @endif
                                    
                                    <a href="{{ Storage::disk('public')->url($submission->file_path) }}" 
                                       target="_blank" 
                                       class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                        <i class="fas fa-file-word mr-1"></i>
                                        DOCX
                                    </a>
                                    
                                    @if(in_array($submission->status, ['pending_format_review', 'rejected_format_review', 'rejected_substance_review']) && !$submission->biodataPaten)
                                        <form method="POST" action="{{ route('admin.submissions-paten.destroy', $submission) }}" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengajuan paten ini? Tindakan ini tidak dapat dibatalkan.');"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                                <i class="fas fa-trash mr-1"></i>
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($submissionsPaten->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $submissionsPaten->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow p-8 text-center">
            @if(request('search') || request('status'))
                <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">
                    Tidak ada hasil yang ditemukan
                </h3>
                <p class="text-gray-600 mb-4">
                    Tidak ada pengajuan yang cocok dengan 
                    @if(request('search') && request('status'))
                        pencarian "{{ request('search') }}" dan status "{{ ucfirst(request('status')) }}"
                    @elseif(request('search'))
                        pencarian "{{ request('search') }}"
                    @else
                        status "{{ ucfirst(request('status')) }}"
                    @endif
                </p>
                <a href="{{ route('admin.submissions-paten.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition duration-200">
                    <i class="fas fa-times mr-2"></i>Hapus Filter
                </a>
            @else
                <i class="fas fa-lightbulb text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Tidak ada pengajuan paten</h3>
                <p class="text-gray-600">Pengajuan paten akan muncul di sini setelah user melakukan submission.</p>
            @endif
        </div>
    @endif
                </div>
            </main>
        </div>
    </div>

    @include('admin.partials.sidebar-script')
</body>
</html>
