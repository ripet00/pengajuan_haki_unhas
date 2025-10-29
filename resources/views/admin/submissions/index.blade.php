@extends('admin.layouts.app')

@section('title', 'Daftar Pengajuan HKI')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-file-upload mr-3 text-red-600"></i>Manajemen Pengajuan HKI
                </h1>
                <p class="text-gray-600 mt-1">Kelola dan review pengajuan HKI dari pengguna</p>
            </div>
            <div class="flex space-x-3">
                <!-- Filter Status -->
                <form method="GET" action="{{ route('admin.submissions.index') }}" class="flex space-x-2">
                    <select name="status" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </form>
            </div>
        </div>
    </div>

    @if($submissions->count() > 0)
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $submissions->where('status', 'pending')->count() }}</h3>
                        <p class="text-gray-600">Pending Review</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-check text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $submissions->where('status', 'approved')->count() }}</h3>
                        <p class="text-gray-600">Disetujui</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                        <i class="fas fa-times text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $submissions->where('status', 'rejected')->count() }}</h3>
                        <p class="text-gray-600">Ditolak</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-file-upload text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $submissions->count() }}</h3>
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Karya</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Dokumen</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Biodata</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($submissions as $submission)
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
                                    <div class="text-sm font-medium text-gray-900">{{ Str::limit($submission->title, 40) }}</div>
                                    <div class="text-sm text-gray-500">
                                        @if($submission->file_type === 'video')
                                            <i class="fas fa-video text-purple-500 mr-1"></i>
                                        @else
                                            <i class="fas fa-file-pdf text-red-500 mr-1"></i>
                                        @endif
                                        {{ $submission->file_name }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $submission->categories == 'Universitas' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                    <i class="fas fa-{{ $submission->categories == 'Universitas' ? 'university' : 'globe' }} mr-1"></i>
                                    {{ $submission->categories }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($submission->status == 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>Pending
                                    </span>
                                @elseif($submission->status == 'approved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>Disetujui
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times mr-1"></i>Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($submission->biodata_status == 'not_started')
                                    @if($submission->status == 'approved')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                            <i class="fas fa-hourglass-start mr-1"></i>Siap Upload
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                            <i class="fas fa-lock mr-1"></i>Menunggu Dokumen
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
                                <div>{{ $submission->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs">{{ $submission->created_at->format('H:i') }} WITA</div>
                                @if($submission->reviewed_at)
                                    <div class="text-xs text-gray-400 mt-1">
                                        Review: {{ $submission->reviewed_at->format('d/m/Y') }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-2">
                                    @if($submission->status == 'pending')
                                        <a href="{{ route('admin.submissions.show', $submission) }}" 
                                           class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                            <i class="fas fa-gavel mr-1"></i>
                                            Review
                                        </a>
                                    @else
                                        <a href="{{ route('admin.submissions.show', $submission) }}" 
                                           class="inline-flex items-center px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                            <i class="fas fa-eye mr-1"></i>
                                            Detail
                                        </a>
                                    @endif
                                    
                                    <a href="{{ Storage::disk('public')->url($submission->file_path) }}" 
                                       target="_blank" 
                                       class="inline-flex items-center px-3 py-2 {{ $submission->file_type === 'video' ? 'bg-purple-600 hover:bg-purple-700' : 'bg-red-600 hover:bg-red-700' }} text-white text-sm font-medium rounded-lg transition duration-200">
                                        @if($submission->file_type === 'video')
                                            <i class="fas fa-video mr-1"></i>
                                            Video
                                        @else
                                            <i class="fas fa-file-pdf mr-1"></i>
                                            PDF
                                        @endif
                                    </a>
                                    
                                    @if($submission->status != 'pending')
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full {{ $submission->status == 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            <i class="fas fa-{{ $submission->status == 'approved' ? 'check' : 'times' }} mr-1"></i>
                                            {{ $submission->status == 'approved' ? 'Disetujui' : 'Ditolak' }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($submissions->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $submissions->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Tidak ada pengajuan
                @if(request('status'))
                    dengan status "{{ ucfirst(request('status')) }}"
                @endif
            </h3>
            <p class="text-gray-600">Pengajuan HKI akan muncul di sini setelah user melakukan submission.</p>
        </div>
    @endif
</div>
@endsection