<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Reset Password - Pengajuan HKI</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('admin.partials.header', ['title' => 'Kelola Reset Password'])

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
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 md:gap-6 mb-8">
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
                                            <dd class="text-lg md:text-2xl font-bold text-gray-900">{{ \App\Models\PasswordResetRequest::where('status', 'pending')->count() }}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-4 md:p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 md:w-10 md:h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-paper-plane text-white text-sm md:text-base"></i>
                                        </div>
                                    </div>
                                    <div class="ml-3 md:ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-xs md:text-sm font-medium text-gray-500 truncate">Terkirim</dt>
                                            <dd class="text-lg md:text-2xl font-bold text-gray-900">{{ \App\Models\PasswordResetRequest::where('status', 'sent')->count() }}</dd>
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
                                            <i class="fas fa-check text-white text-sm md:text-base"></i>
                                        </div>
                                    </div>
                                    <div class="ml-3 md:ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-xs md:text-sm font-medium text-gray-500 truncate">Digunakan</dt>
                                            <dd class="text-lg md:text-2xl font-bold text-gray-900">{{ \App\Models\PasswordResetRequest::where('status', 'used')->count() }}</dd>
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
                                            <i class="fas fa-times text-white text-sm md:text-base"></i>
                                        </div>
                                    </div>
                                    <div class="ml-3 md:ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-xs md:text-sm font-medium text-gray-500 truncate">Ditolak</dt>
                                            <dd class="text-lg md:text-2xl font-bold text-gray-900">{{ \App\Models\PasswordResetRequest::where('status', 'rejected')->count() }}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="bg-white p-4 md:p-5 rounded-lg shadow mb-6">
                        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select name="status" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua Status</option>
                                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="sent" {{ $status === 'sent' ? 'selected' : '' }}>Link Terkirim</option>
                                    <option value="used" {{ $status === 'used' ? 'selected' : '' }}>Sudah Digunakan</option>
                                    <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                    <option value="expired" {{ $status === 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                                </select>
                            </div>

                            @if($admin->role === \App\Models\Admin::ROLE_SUPER_ADMIN)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Akun</label>
                                <select name="user_type" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                                    <option value="all" {{ $userType === 'all' ? 'selected' : '' }}>Semua Tipe</option>
                                    <option value="user" {{ $userType === 'user' ? 'selected' : '' }}>User</option>
                                    <option value="admin" {{ $userType === 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </div>
                            @endif

                            <div class="flex items-end">
                                <button type="submit" class="w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-4 py-2 rounded-md transition duration-200 shadow-md">
                                    <i class="fas fa-filter mr-2"></i>Filter
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Requests Table -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-red-50 to-red-100">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Waktu</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Tipe</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nomor WA</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nama</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($requests as $request)
                                        <tr class="hover:bg-gray-50 transition duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <div class="flex items-center">
                                                    <i class="fas fa-calendar-alt text-gray-400 mr-2"></i>
                                                    {{ $request->requested_at->format('d/m/Y H:i') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($request->user_type === 'admin')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                                        <i class="fas fa-user-shield mr-1"></i> Admin
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        <i class="fas fa-user mr-1"></i> User
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <div class="flex items-center">
                                                    <i class="fab fa-whatsapp text-green-500 mr-2"></i>
                                                    ({{ $request->country_code }}) {{ $request->phone_number }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($request->user_type === 'user' && $request->user)
                                                    <div class="font-medium">{{ $request->user->name }}</div>
                                                @elseif($request->user_type === 'admin' && $request->admin)
                                                    <div class="font-medium">{{ $request->admin->name }}</div>
                                                @else
                                                    <span class="text-gray-400 italic">Tidak ditemukan</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($request->status === 'pending')
                                                    <span class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        <i class="fas fa-clock mr-1"></i> Pending
                                                    </span>
                                                @elseif($request->status === 'sent')
                                                    <span class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        <i class="fas fa-paper-plane mr-1"></i> Terkirim
                                                    </span>
                                                @elseif($request->status === 'used')
                                                    <span class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        <i class="fas fa-check-circle mr-1"></i> Digunakan
                                                    </span>
                                                @elseif($request->status === 'rejected')
                                                    <span class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        <i class="fas fa-times-circle mr-1"></i> Ditolak
                                                    </span>
                                                @elseif($request->status === 'expired')
                                                    <span class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        <i class="fas fa-hourglass-end mr-1"></i> Kadaluarsa
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.password-reset.show', $request->id) }}" 
                                                   class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-md transition duration-200 shadow-sm">
                                                    <i class="fas fa-eye mr-1"></i>Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-12 text-center">
                                                <div class="flex flex-col items-center justify-center">
                                                    <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                                                    <p class="text-gray-500 font-medium">Tidak ada permintaan reset password</p>
                                                    <p class="text-gray-400 text-sm mt-1">Permintaan akan muncul di sini ketika user meminta reset password</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($requests->hasPages())
                            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                                {{ $requests->links() }}
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
