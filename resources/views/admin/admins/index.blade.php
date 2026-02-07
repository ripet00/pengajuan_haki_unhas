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
                                            <dd class="text-sm font-medium text-gray-900">{{ $admins->first()?->created_at?->translatedFormat('d M Y') ?? 'Belum ada' }}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Search Bar Section -->
                    <div class="bg-white rounded-lg shadow p-6 mb-6">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                                    <i class="fas fa-search mr-3 text-indigo-600"></i>Pencarian Admin
                                </h2>
                                
                            </div>
                            <div class="flex">
                                <!-- Search Bar -->
                                <form method="GET" action="{{ route('admin.admins.index') }}" class="flex">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-search text-gray-400"></i>
                                        </div>
                                        <input type="text" 
                                               name="search" 
                                               value="{{ request('search') }}" 
                                               placeholder="Cari Admin" 
                                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 w-80">
                                    </div>
                                    <button type="submit" class="ml-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                        <i class="fas fa-search mr-1"></i>Cari
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Active Search Notification -->
                    @if(request('search'))
                        <div class="bg-indigo-50 border-l-4 border-indigo-400 p-4 rounded mb-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-search text-indigo-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-indigo-700">
                                            Pencarian aktif: <span class="font-medium">"{{ request('search') }}"</span>
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    <a href="{{ route('admin.admins.index') }}" 
                                       class="inline-flex items-center px-3 py-1 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 text-sm font-medium rounded-lg transition duration-200">
                                        <i class="fas fa-times mr-1"></i>Hapus Pencarian
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

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
                            @if($admins->count() > 0)
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin</th>
                                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">NIP/NIDN/NIDK/NIM</th>
                                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Role</th>
                                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Nomor WhatsApp</th>
                                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden xl:table-cell">Bergabung</th>
                                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
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
                                                            @if($adminItem->id === Auth::guard('admin')->id())
                                                                <div class="text-xs text-indigo-600 font-medium">(Anda)</div>
                                                            @endif
                                                            <div class="text-xs text-gray-500 md:hidden">{{ $adminItem->nip_nidn_nidk_nim }}</div>
                                                            <div class="text-xs text-gray-500 sm:hidden">
                                                                <i class="fas fa-shield-alt mr-1"></i>{{ $adminItem->role_name }}
                                                            </div>
                                                            <div class="text-xs text-gray-500 lg:hidden">{{ $adminItem->phone_number }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell">
                                                    {{ $adminItem->nip_nidn_nidk_nim }}
                                                </td>
                                                <td class="px-4 md:px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full
                                                        @if($adminItem->role === 'super_admin') bg-purple-100 text-purple-800

                                                        @elseif($adminItem->role === 'admin_paten') bg-green-100 text-green-800
                                                        @else bg-orange-100 text-orange-800
                                                        @endif">
                                                        <i class="fas fa-shield-alt mr-1"></i>{{ $adminItem->role_name }}
                                                    </span>
                                                </td>
                                                <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden lg:table-cell">
                                                    <div class="flex items-center space-x-2">
                                                        <span>{{ $adminItem->phone_number }}</span>
                                                        <a href="https://wa.me/{{ $adminItem->country_code ?? '62' }}{{ ltrim($adminItem->phone_number, '0') }}" 
                                                           target="_blank"
                                                           class="inline-flex items-center px-2 py-1 bg-green-500 hover:bg-green-600 text-white text-xs font-medium rounded transition duration-200"
                                                           title="Hubungi via WhatsApp">
                                                            <i class="fab fa-whatsapp"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                                <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden xl:table-cell">
                                                    {{ $adminItem->created_at->translatedFormat('d M Y H:i') }}
                                                </td>
                                                <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                                    @if($adminItem->is_active)
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                            <i class="fas fa-check-circle mr-1"></i>Aktif
                                                        </span>
                                                    @else
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                            <i class="fas fa-times-circle mr-1"></i>Nonaktif
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm">
                                                    @if($adminItem->id !== Auth::guard('admin')->id())
                                                        <div class="flex items-center gap-2">
                                                            <!-- Edit Button -->
                                                            <a href="{{ route('admin.admins.edit', $adminItem) }}" 
                                                               class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition duration-200">
                                                                <i class="fas fa-edit mr-1"></i>Edit
                                                            </a>

                                                            <!-- Status Toggle Form -->
                                                            <form action="{{ route('admin.admins.update-status', $adminItem) }}" 
                                                                  method="POST" 
                                                                  class="inline-block"
                                                                  onsubmit="return confirm('Apakah Anda yakin ingin {{ $adminItem->is_active ? 'menonaktifkan' : 'mengaktifkan' }} admin ini?')">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="is_active" value="{{ $adminItem->is_active ? '0' : '1' }}">
                                                                
                                                                @if($adminItem->is_active)
                                                                    <button type="submit" 
                                                                            class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-lg transition duration-200">
                                                                        <i class="fas fa-ban mr-1"></i>Nonaktifkan
                                                                    </button>
                                                                @else
                                                                    <button type="submit" 
                                                                            class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg transition duration-200">
                                                                        <i class="fas fa-check mr-1"></i>Aktifkan
                                                                    </button>
                                                                @endif
                                                            </form>
                                                        </div>
                                                    @else
                                                        <span class="text-xs text-gray-500 italic">
                                                            <i class="fas fa-info-circle mr-1"></i>Anda sendiri
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <!-- Empty State -->
                                <div class="p-8 text-center">
                                    @if(request('search'))
                                        <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                            Tidak ada admin yang ditemukan
                                        </h3>
                                        <p class="text-gray-600 mb-4">
                                            Tidak ada admin yang cocok dengan pencarian "{{ request('search') }}"
                                        </p>
                                        <a href="{{ route('admin.admins.index') }}" 
                                           class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                            <i class="fas fa-times mr-2"></i>Hapus Pencarian
                                        </a>
                                    @else
                                        <i class="fas fa-user-shield text-6xl text-gray-300 mb-4"></i>
                                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Tidak ada admin</h3>
                                        <p class="text-gray-600 mb-4">Belum ada admin yang terdaftar di sistem.</p>
                                        <a href="{{ route('admin.create') }}" 
                                           class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                            <i class="fas fa-plus mr-2"></i>Tambah Admin
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Pagination -->
                        @if($admins->hasPages())
                            <div class="px-4 md:px-6 py-4 border-t border-gray-200">
                                {{ $admins->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>

                    <!-- Pendamping Paten Table -->
                    <div class="bg-white shadow rounded-lg overflow-hidden mt-8">
                        <div class="px-4 md:px-6 py-4 border-b border-purple-200 bg-purple-50">
                            <div class="flex items-center justify-between">
                                <h2 class="text-base md:text-lg font-medium text-purple-900 flex items-center">
                                    <i class="fas fa-user-tie mr-2"></i>Daftar Pendamping Paten
                                </h2>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    {{ $pendampingPatenList->count() }} total pendamping
                                </span>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto">
                            @if($pendampingPatenList->count() > 0)
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pendamping</th>
                                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">NIP/NIDN/NIDK/NIM</th>
                                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Fakultas</th>
                                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden xl:table-cell">Program Studi</th>
                                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Beban Kerja</th>
                                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($pendampingPatenList as $pendamping)
                                            <tr class="hover:bg-purple-50">
                                                <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="w-8 h-8 md:w-10 md:h-10 bg-purple-500 rounded-full flex items-center justify-center">
                                                            <span class="text-white font-medium text-xs md:text-sm">{{ substr($pendamping->name, 0, 1) }}</span>
                                                        </div>
                                                        <div class="ml-3 md:ml-4">
                                                            <div class="text-sm font-medium text-gray-900">{{ $pendamping->name }}</div>
                                                            <div class="text-xs text-gray-500 md:hidden">{{ $pendamping->nip_nidn_nidk_nim }}</div>
                                                            <div class="text-xs text-gray-500 lg:hidden">{{ $pendamping->fakultas ?? '-' }}</div>
                                                            <div class="text-xs text-gray-500 xl:hidden">{{ $pendamping->program_studi ?? '-' }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell">
                                                    {{ $pendamping->nip_nidn_nidk_nim }}
                                                </td>
                                                <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden lg:table-cell">
                                                    {{ $pendamping->fakultas ?? '-' }}
                                                </td>
                                                <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden xl:table-cell">
                                                    {{ $pendamping->program_studi ?? '-' }}
                                                </td>
                                                <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                        {{ $pendamping->active_paten_count == 0 ? 'bg-green-100 text-green-800' : ($pendamping->active_paten_count <= 3 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                        <i class="fas fa-tasks mr-1"></i>{{ $pendamping->active_paten_count }} tugas aktif
                                                    </span>
                                                </td>
                                                <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                                    @if($pendamping->is_active)
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                            <i class="fas fa-check-circle mr-1"></i>Aktif
                                                        </span>
                                                    @else
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                            <i class="fas fa-times-circle mr-1"></i>Nonaktif
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                                    <a href="{{ route('admin.pendamping-paten.detail', $pendamping) }}" 
                                                       class="inline-flex items-center px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-medium rounded-lg transition duration-200">
                                                        <i class="fas fa-eye mr-1"></i>Detail
                                                    </a>

                                                    <a href="{{ route('admin.admins.edit', $pendamping) }}" 
                                                       class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition duration-200">
                                                        <i class="fas fa-edit mr-1"></i>Edit
                                                    </a>
                                                    
                                                    @if($pendamping->id !== Auth::guard('admin')->id())
                                                        <form action="{{ route('admin.admins.update-status', $pendamping) }}" 
                                                              method="POST" 
                                                              class="inline-block"
                                                              onsubmit="return confirm('Apakah Anda yakin ingin {{ $pendamping->is_active ? 'menonaktifkan' : 'mengaktifkan' }} pendamping paten ini?')">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="is_active" value="{{ $pendamping->is_active ? '0' : '1' }}">
                                                            
                                                            @if($pendamping->is_active)
                                                                <button type="submit" 
                                                                        class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-lg transition duration-200">
                                                                    <i class="fas fa-ban mr-1"></i>Nonaktifkan
                                                                </button>
                                                            @else
                                                                <button type="submit" 
                                                                        class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg transition duration-200">
                                                                    <i class="fas fa-check mr-1"></i>Aktifkan
                                                                </button>
                                                            @endif
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <!-- Empty State -->
                                <div class="p-8 text-center">
                                    <i class="fas fa-user-tie text-6xl text-gray-300 mb-4"></i>
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Tidak ada Pendamping Paten</h3>
                                    <p class="text-gray-600 mb-4">Belum ada admin dengan role Pendamping Paten yang terdaftar.</p>
                                    <a href="{{ route('admin.create') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                        <i class="fas fa-plus mr-2"></i>Tambah Pendamping Paten
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    @include('admin.partials.sidebar-script')
</body>
</html>