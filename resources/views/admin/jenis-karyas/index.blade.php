<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Jenis Karya - Pengajuan HKI</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('admin.partials.header', ['title' => 'Kelola Jenis Karya'])

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

                    @if(session('error'))
                        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-red-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Header Section -->
                    <div class="mb-6">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                <div class="mb-4 sm:mb-0">
                                    <h1 class="text-2xl font-bold text-gray-900 mb-2">Kelola Jenis Karya</h1>
                                    <p class="text-gray-600">Manage jenis karya untuk submissions</p>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <a href="{{ route('admin.jenis-karyas.create') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                                        <i class="fas fa-plus mr-2"></i>Tambah Jenis Karya
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-100">
                                    <i class="fas fa-list text-blue-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Total Jenis Karya</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $totalJenisKarya }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-100">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Aktif</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $activeJenisKarya }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-gray-100">
                                    <i class="fas fa-times-circle text-gray-600"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Nonaktif</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $inactiveJenisKarya }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Table Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Daftar Jenis Karya</h3>
                                    <p class="text-sm text-gray-600 mt-1">Total: {{ $jenisKaryas->total() }} jenis karya</p>
                                </div>
                            </div>
                        </div>

                        @if($jenisKaryas->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Jenis Karya</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Submissions</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($jenisKaryas as $index => $jenisKarya)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-700">
                                                    {{ $jenisKaryas->firstItem() + $index }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-10 w-10">
                                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                                <i class="fas fa-file-alt text-blue-600"></i>
                                                            </div>
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-semibold text-gray-900">{{ $jenisKarya->nama }}</div>
                                                            <div class="text-sm font-medium text-gray-600">ID: {{ $jenisKarya->id }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($jenisKarya->is_active)
                                                        <span class="inline-flex px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800">
                                                            <i class="fas fa-check-circle mr-1"></i>Aktif
                                                        </span>
                                                    @else
                                                        <span class="inline-flex px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800">
                                                            <i class="fas fa-times-circle mr-1"></i>Nonaktif
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex px-3 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800">
                                                        {{ $jenisKarya->submissions()->count() }} submissions
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <div class="font-semibold">{{ $jenisKarya->created_at->translatedFormat('d/m/Y') }}</div>
                                                    <div class="text-xs font-medium text-gray-600">{{ $jenisKarya->created_at->translatedFormat('H:i') }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                    <div class="flex justify-center space-x-2">
                                                        <a href="{{ route('admin.jenis-karyas.edit', $jenisKarya) }}" 
                                                           class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-semibold rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                                                            <i class="fas fa-edit mr-1"></i>Edit
                                                        </a>
                                                        @if($jenisKarya->submissions()->count() == 0)
                                                            <form action="{{ route('admin.jenis-karyas.destroy', $jenisKarya) }}" 
                                                                  method="POST" 
                                                                  class="inline"
                                                                  onsubmit="return confirm('Yakin ingin menghapus jenis karya ini?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" 
                                                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-semibold rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                                                                    <i class="fas fa-trash mr-1"></i>Hapus
                                                                </button>
                                                            </form>
                                                        @else
                                                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-semibold rounded-md text-gray-600 bg-gray-200 cursor-not-allowed" 
                                                                    disabled 
                                                                    title="Tidak dapat dihapus karena masih digunakan">
                                                                <i class="fas fa-lock mr-1"></i>Terkunci
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            @if($jenisKaryas->hasPages())
                                <div class="px-6 py-4 border-t border-gray-200">
                                    {{ $jenisKaryas->links() }}
                                </div>
                            @endif
                        @else
                            <div class="text-center py-12">
                                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100">
                                    <i class="fas fa-file-alt text-gray-400 text-xl"></i>
                                </div>
                                <h3 class="mt-4 text-sm font-medium text-gray-900">Belum ada jenis karya</h3>
                                <p class="mt-1 text-sm text-gray-500">Tambahkan jenis karya pertama untuk memulai</p>
                                <div class="mt-6">
                                    <a href="{{ route('admin.jenis-karyas.create') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                                        <i class="fas fa-plus mr-2"></i>Tambah Jenis Karya
                                    </a>
                                </div>
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