<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pendamping Paten - Pengajuan HKI</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('admin.partials.header', ['title' => 'Dashboard Pendamping Paten'])

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="px-4 sm:px-6 lg:px-8 py-6">
                    <!-- Welcome Banner -->
                    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg shadow-lg p-6 mb-6 text-white">
                        <h1 class="text-3xl font-bold flex items-center">
                            <i class="fas fa-user-tie mr-3"></i>Selamat Datang, {{ session('admin_name') }}!
                        </h1>
                        <p class="mt-2 text-purple-100">Dashboard Pendamping Paten - Review Substansi Pengajuan Paten</p>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                    <i class="fas fa-hourglass-half text-2xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</h3>
                                    <p class="text-gray-600 text-sm">Pending Review</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-100 text-green-600">
                                    <i class="fas fa-check-double text-2xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['approved'] }}</h3>
                                    <p class="text-gray-600 text-sm">Disetujui</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-red-100 text-red-600">
                                    <i class="fas fa-exclamation-triangle text-2xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['rejected'] }}</h3>
                                    <p class="text-gray-600 text-sm">Ditolak</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                    <i class="fas fa-tasks text-2xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</h3>
                                    <p class="text-gray-600 text-sm">Total Tugas</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-lg shadow p-6 mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-bolt mr-2 text-yellow-500"></i>Aksi Cepat
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <a href="{{ route('admin.pendamping-paten.index', ['filter' => 'active']) }}" 
                               class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 border-2 border-blue-200 rounded-lg transition duration-200">
                                <i class="fas fa-clipboard-list text-blue-600 text-2xl mr-3"></i>
                                <div>
                                    <p class="font-semibold text-blue-900">Tugas Aktif</p>
                                    <p class="text-sm text-blue-700">{{ $stats['pending'] }} pengajuan</p>
                                </div>
                            </a>

                            <a href="{{ route('admin.pendamping-paten.index', ['filter' => 'completed']) }}" 
                               class="flex items-center p-4 bg-green-50 hover:bg-green-100 border-2 border-green-200 rounded-lg transition duration-200">
                                <i class="fas fa-check-circle text-green-600 text-2xl mr-3"></i>
                                <div>
                                    <p class="font-semibold text-green-900">Tugas Selesai</p>
                                    <p class="text-sm text-green-700">{{ $stats['approved'] + $stats['rejected'] }} pengajuan</p>
                                </div>
                            </a>

                            <a href="{{ route('admin.pendamping-paten.index') }}" 
                               class="flex items-center p-4 bg-purple-50 hover:bg-purple-100 border-2 border-purple-200 rounded-lg transition duration-200">
                                <i class="fas fa-list text-purple-600 text-2xl mr-3"></i>
                                <div>
                                    <p class="font-semibold text-purple-900">Semua Tugas</p>
                                    <p class="text-sm text-purple-700">{{ $stats['total'] }} pengajuan</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Recent Submissions -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-clock mr-2 text-blue-600"></i>Pengajuan Terbaru
                            </h2>
                        </div>
                        <div class="overflow-x-auto">
                            @if($recentSubmissions->count() > 0)
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Paten</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengusul</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ditugaskan</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($recentSubmissions as $submission)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $submission->id }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    <div class="max-w-xs truncate">{{ $submission->judul_paten }}</div>
                                                    <div class="text-xs text-gray-500">{{ $submission->kategori_paten }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $submission->user->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($submission->status == \App\Models\SubmissionPaten::STATUS_PENDING_SUBSTANCE_REVIEW)
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                            <i class="fas fa-hourglass-half mr-1"></i>Pending
                                                        </span>
                                                    @elseif($submission->status == \App\Models\SubmissionPaten::STATUS_APPROVED_SUBSTANCE)
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                            <i class="fas fa-check-circle mr-1"></i>Disetujui
                                                        </span>
                                                    @else
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                            <i class="fas fa-times-circle mr-1"></i>Ditolak
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $submission->assigned_at ? $submission->assigned_at->format('d/m/Y') : '-' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <a href="{{ route('admin.pendamping-paten.show', $submission) }}" 
                                                       class="text-indigo-600 hover:text-indigo-900">
                                                        <i class="fas fa-eye mr-1"></i>Detail
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="p-8 text-center">
                                    <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                                    <p class="text-gray-500">Belum ada tugas yang ditugaskan kepada Anda</p>
                                </div>
                            @endif
                        </div>
                        
                        @if($recentSubmissions->count() > 0)
                            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                                <a href="{{ route('admin.pendamping-paten.index') }}" 
                                   class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                    Lihat Semua Tugas <i class="fas fa-arrow-right ml-1"></i>
                                </a>
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
