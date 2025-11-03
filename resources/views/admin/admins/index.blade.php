<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Kelola Admin - Pengajuan HKI</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('admin.partials.header', ['title' => 'Kelola Admin'])

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

                    <!-- Action Bar -->
                    <div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                        <div>
                            <h2 class="text-lg md:text-xl font-semibold text-gray-800">Daftar Admin</h2>
                            <p class="text-gray-600 mt-1 text-sm">Kelola akun administrator sistem</p>
                        </div>
                        <a href="{{ route('admin.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Admin
                        </a>
                    </div>

                    <!-- Stats Card -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-8">
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-4 md:p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 md:w-10 md:h-10 bg-indigo-500 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-user-shield text-white text-sm md:text-base"></i>
                                        </div>
                                    </div>
                                    <div class="ml-3 md:ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-xs md:text-sm font-medium text-gray-500 truncate">Total Admin</dt>
                                            <dd class="text-lg md:text-2xl font-bold text-gray-900">{{ $admins->total() }}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-4 md:p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 md:w-10 md:h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-calendar text-white text-sm md:text-base"></i>
                                        </div>
                                    </div>
                                    <div class="ml-3 md:ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-xs md:text-sm font-medium text-gray-500 truncate">Admin Terbaru</dt>
                                            <dd class="text-sm font-medium text-gray-900">{{ $admins->first()?->created_at?->format('d M Y') ?? 'Belum ada' }}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Admins Table -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-4 md:px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h2 class="text-base md:text-lg font-medium text-gray-900">Daftar Semua Admin</h2>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    {{ $admins->total() }} total admin
                                </span>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin</th>
                                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">NIP/NIDN/NIDK/NIM</th>
                                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Nomor WhatsApp</th>
                                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Bergabung</th>
                                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($admins as $adminItem)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 md:w-10 md:h-10 bg-indigo-500 rounded-full flex items-center justify-center">
                                                        <span class="text-white font-medium text-xs md:text-sm">{{ substr($adminItem->name, 0, 1) }}</span>
                                                    </div>
                                                    <div class="ml-3 md:ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ $adminItem->name }}</div>
                                                        @if($adminItem->id === session('admin_id'))
                                                            <div class="text-xs text-indigo-600 font-medium">(Anda)</div>
                                                        @endif
                                                        <div class="text-xs text-gray-500 md:hidden">{{ $adminItem->nip_nidn_nidk_nim }}</div>
                                                        <div class="text-xs text-gray-500 lg:hidden">{{ $adminItem->phone_number }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell">
                                                {{ $adminItem->nip_nidn_nidk_nim }}
                                            </td>
                                            <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden lg:table-cell">
                                                {{ $adminItem->phone_number }}
                                            </td>
                                            <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden lg:table-cell">
                                                {{ $adminItem->created_at->format('d M Y H:i') }}
                                            </td>
                                            <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i>Aktif
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($admins->hasPages())
                            <div class="px-4 md:px-6 py-4 border-t border-gray-200">
                                {{ $admins->links() }}
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