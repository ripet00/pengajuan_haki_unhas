<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Pengajuan HAKI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('admin.partials.header', ['title' => 'Dashboard'])

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
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-4 md:p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 md:w-10 md:h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-users text-white text-sm md:text-base"></i>
                                        </div>
                                    </div>
                                    <div class="ml-3 md:ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-xs md:text-sm font-medium text-gray-500 truncate">Total User</dt>
                                            <dd class="text-lg md:text-2xl font-bold text-gray-900">{{ $totalUsers }}</dd>
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
                                            <i class="fas fa-check-circle text-white text-sm md:text-base"></i>
                                        </div>
                                    </div>
                                    <div class="ml-3 md:ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-xs md:text-sm font-medium text-gray-500 truncate">User Aktif</dt>
                                            <dd class="text-lg md:text-2xl font-bold text-gray-900">{{ $activeUsers }}</dd>
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
                                            <dt class="text-xs md:text-sm font-medium text-gray-500 truncate">Menunggu Approval</dt>
                                            <dd class="text-lg md:text-2xl font-bold text-gray-900">{{ $pendingUsers->count() }}</dd>
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
                                            <dt class="text-xs md:text-sm font-medium text-gray-500 truncate">User Ditolak</dt>
                                            <dd class="text-lg md:text-2xl font-bold text-gray-900">{{ $deniedUsers }}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Users Table -->
                    @if($pendingUsers->count() > 0)
                        <div class="bg-white shadow rounded-lg overflow-hidden">
                            <div class="px-4 md:px-6 py-4 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-base md:text-lg font-medium text-gray-900">User Menunggu Approval</h2>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        {{ $pendingUsers->count() }} pending
                                    </span>
                                </div>
                            </div>
                            
                            <div class="overflow-x-auto">
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
                                        @foreach($pendingUsers as $user)
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
                                                    {{ $user->created_at->format('d M Y') }}
                                                </td>
                                                <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Pending
                                                    </span>
                                                </td>
                                                <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm">
                                                    <div class="flex flex-col sm:flex-row gap-1 sm:gap-2">
                                                        <form method="POST" action="{{ route('admin.users.update-status', $user) }}" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="active">
                                                            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-2 md:px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                                <i class="fas fa-check mr-1"></i><span class="hidden sm:inline">Setujui</span>
                                                            </button>
                                                        </form>
                                                        <form method="POST" action="{{ route('admin.users.update-status', $user) }}" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="denied">
                                                            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-2 md:px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                                <i class="fas fa-times mr-1"></i><span class="hidden sm:inline">Tolak</span>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="bg-white shadow rounded-lg p-6">
                            <div class="text-center">
                                <i class="fas fa-check-circle text-4xl text-green-500 mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Semua User Telah Diproses</h3>
                                <p class="text-gray-500">Tidak ada user yang menunggu approval saat ini.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </main>
        </div>
    </div>

    @include('admin.partials.sidebar-script')
</body>
</html>