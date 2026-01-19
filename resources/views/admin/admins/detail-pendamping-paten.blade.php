<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pendamping Paten - Pengajuan HKI</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('admin.partials.header', ['title' => 'Detail Pendamping Paten'])

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="px-4 sm:px-6 lg:px-8 py-6">
                    <!-- Back Button -->
                    <div class="mb-6">
                        <a href="{{ route('admin.admins.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali ke Kelola Admin
                        </a>
                    </div>

                    <!-- Profile Card -->
                    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg shadow-lg p-6 mb-6 text-white">
                        <div class="flex items-center">
                            <div class="w-16 h-16 bg-white bg-opacity-30 rounded-full flex items-center justify-center">
                                <span class="text-3xl font-bold">{{ substr($pendamping->name, 0, 1) }}</span>
                            </div>
                            <div class="ml-4">
                                <h1 class="text-2xl font-bold">{{ $pendamping->name }}</h1>
                                <p class="text-purple-100">{{ $pendamping->nip_nidn_nidk_nim }}</p>
                                <p class="text-sm text-purple-200 mt-1">
                                    <i class="fas fa-shield-alt mr-1"></i>Pendamping Paten
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Info Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-info-circle mr-2 text-purple-600"></i>Informasi Pendamping
                            </h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Fakultas:</span>
                                    <span class="font-medium text-gray-900">{{ $pendamping->fakultas ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Program Studi:</span>
                                    <span class="font-medium text-gray-900">{{ $pendamping->program_studi ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">No. WhatsApp:</span>
                                    <span class="font-medium text-gray-900">{{ $pendamping->phone_number }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status:</span>
                                    <span>
                                        @if($pendamping->is_active)
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i>Nonaktif
                                            </span>
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Bergabung:</span>
                                    <span class="font-medium text-gray-900">{{ $pendamping->created_at->translatedFormat('d F Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-chart-bar mr-2 text-purple-600"></i>Statistik Beban Kerja
                            </h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-hourglass-half text-blue-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Pending Review</p>
                                            <p class="text-xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-check-double text-green-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Disetujui</p>
                                            <p class="text-xl font-bold text-gray-900">{{ $stats['approved'] }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Ditolak</p>
                                            <p class="text-xl font-bold text-gray-900">{{ $stats['rejected'] }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-3 border-t border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-gray-700">Total Tugas</p>
                                        <p class="text-2xl font-bold text-purple-600">{{ $stats['total'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submissions Table -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-list mr-2 text-purple-600"></i>Daftar Tugas Review
                            </h2>
                        </div>

                        <div class="overflow-x-auto">
                            @if($submissions->count() > 0)
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Paten</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengusul</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ditugaskan</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Direview</th>
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
                                                    <div class="max-w-xs truncate text-sm font-medium text-gray-900">{{ $submission->judul_paten }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $submission->user->name }}</div>
                                                    <div class="text-xs text-gray-500">{{ $submission->user->faculty }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $submission->kategori_paten == 'Paten' ? 'bg-green-100 text-green-800' : 'bg-emerald-100 text-emerald-800' }}">
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
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $submission->substance_reviewed_at ? $submission->substance_reviewed_at->translatedFormat('d/m/Y') : '-' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="p-8 text-center">
                                    <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                                    <p class="text-gray-500">Belum ada tugas yang ditugaskan</p>
                                </div>
                            @endif
                        </div>

                        @if($submissions->hasPages())
                            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                                {{ $submissions->links() }}
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
