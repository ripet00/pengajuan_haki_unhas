<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Kelola User - Pengajuan HKI</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('admin.partials.header', ['title' => 'Kelola User'])

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

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6 mb-8">
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-4 md:p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 md:w-10 md:h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-check-circle text-white text-sm md:text-base"></i>
                                        </div>
                                    </div>
                                    <div class="ml-3 md:ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-xs md:text-sm font-medium text-gray-500 truncate">User Aktif</dt>
                                            <dd class="text-lg md:text-2xl font-bold text-gray-900">{{ $users->where('status', 'active')->count() }}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-4 md:p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 md:w-10 md:h-10 bg-yellow-500 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-clock text-white text-sm md:text-base"></i>
                                        </div>
                                    </div>
                                    <div class="ml-3 md:ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-xs md:text-sm font-medium text-gray-500 truncate">Pending</dt>
                                            <dd class="text-lg md:text-2xl font-bold text-gray-900">{{ $users->where('status', 'pending')->count() }}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-4 md:p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 md:w-10 md:h-10 bg-red-500 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-times-circle text-white text-sm md:text-base"></i>
                                        </div>
                                    </div>
                                    <div class="ml-3 md:ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-xs md:text-sm font-medium text-gray-500 truncate">Ditolak</dt>
                                            <dd class="text-lg md:text-2xl font-bold text-gray-900">{{ $users->where('status', 'denied')->count() }}</dd>
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
                                    <i class="fas fa-search mr-3 text-blue-600"></i>Pencarian User
                                </h2>
                            </div>
                            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                                <!-- Search Bar -->
                                <form method="GET" action="{{ route('admin.users.index') }}" class="flex">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-search text-gray-400"></i>
                                        </div>
                                        <input type="text" 
                                               name="search" 
                                               value="{{ request('search') }}" 
                                               placeholder="Cari User" 
                                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-64">
                                        <!-- Preserve existing filter when searching -->
                                        @if(request('status'))
                                            <input type="hidden" name="status" value="{{ request('status') }}">
                                        @endif
                                    </div>
                                    <button type="submit" class="ml-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                        <i class="fas fa-search mr-1"></i>Cari
                                    </button>
                                </form>
                                
                                <!-- Filter Status -->
                                <form method="GET" action="{{ route('admin.users.index') }}" class="flex space-x-2">
                                    <select name="status" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="this.form.submit()">
                                        <option value="">Semua Status</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
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
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded mb-6">
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
                                    <a href="{{ route('admin.users.index') }}" 
                                       class="inline-flex items-center px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm font-medium rounded-lg transition duration-200">
                                        <i class="fas fa-times mr-1"></i>Hapus Filter
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Users Table -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-4 md:px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h2 class="text-base md:text-lg font-medium text-gray-900">Daftar Semua User</h2>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $users->total() }} total user
                                </span>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto">
                            @if($users->count() > 0)
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Fakultas</th>
                                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Tanggal Daftar</th>
                                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($users as $user)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="w-8 h-8 md:w-10 md:h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                                            <span class="text-gray-600 font-medium text-xs md:text-sm">{{ substr($user->name, 0, 1) }}</span>
                                                        </div>
                                                        <div class="ml-3 md:ml-4">
                                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                                            <div class="text-xs md:text-sm text-gray-500">{{ $user->phone_number }}</div>
                                                            <div class="text-xs text-gray-500 md:hidden">{{ $user->faculty }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell">
                                                    {{ $user->faculty }}
                                                </td>
                                                <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden lg:table-cell">
                                                    {{ $user->created_at->format('d M Y H:i') }}
                                                </td>
                                                <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                                    @if($user->status === 'active')
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                            <i class="fas fa-check-circle mr-1"></i>Aktif
                                                        </span>
                                                    @elseif($user->status === 'pending')
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                            <i class="fas fa-clock mr-1"></i>Pending
                                                        </span>
                                                    @else
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                            <i class="fas fa-times-circle mr-1"></i>Ditolak
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm">
                                                    <div class="flex flex-col sm:flex-row gap-1 sm:gap-2">
                                                        @if($user->status !== 'active')
                                                            <form method="POST" action="{{ route('admin.users.update-status', $user) }}" class="inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="status" value="active">
                                                                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-2 md:px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                                    <i class="fas fa-check mr-1"></i><span class="hidden sm:inline">Aktifkan</span>
                                                                </button>
                                                            </form>
                                                        @endif
                                                        @if($user->status !== 'denied')
                                                            <form method="POST" action="{{ route('admin.users.update-status', $user) }}" class="inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="status" value="denied">
                                                                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-2 md:px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                                    <i class="fas fa-times mr-1"></i><span class="hidden sm:inline">Tolak</span>
                                                                </button>
                                                            </form>
                                                        @endif
                                                        @if($user->status !== 'pending')
                                                            <form method="POST" action="{{ route('admin.users.update-status', $user) }}" class="inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="status" value="pending">
                                                                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-2 md:px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                                                    <i class="fas fa-clock mr-1"></i><span class="hidden sm:inline">Pending</span>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <!-- Empty State -->
                                <div class="p-8 text-center">
                                    @if(request('search') || request('status'))
                                        <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                            Tidak ada user yang ditemukan
                                        </h3>
                                        <p class="text-gray-600 mb-4">
                                            Tidak ada user yang cocok dengan 
                                            @if(request('search') && request('status'))
                                                pencarian "{{ request('search') }}" dan status "{{ ucfirst(request('status')) }}"
                                            @elseif(request('search'))
                                                pencarian "{{ request('search') }}"
                                            @else
                                                status "{{ ucfirst(request('status')) }}"
                                            @endif
                                        </p>
                                        <a href="{{ route('admin.users.index') }}" 
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                            <i class="fas fa-times mr-2"></i>Hapus Filter
                                        </a>
                                    @else
                                        <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Tidak ada user</h3>
                                        <p class="text-gray-600">Belum ada user yang terdaftar di sistem.</p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Pagination -->
                        @if($users->hasPages())
                            <div class="px-4 md:px-6 py-4 border-t border-gray-200">
                                {{ $users->appends(request()->query())->links() }}
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