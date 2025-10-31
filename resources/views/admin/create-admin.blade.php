<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Admin - Pengajuan HKI</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('admin.partials.header', ['title' => 'Tambah Admin'])

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="px-4 sm:px-6 lg:px-8 py-6">
                    <!-- Back Button -->
                    <div class="mb-6">
                        <a href="{{ route('admin.admins.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali ke Daftar Admin
                        </a>
                    </div>

                    <!-- Form Section -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 md:px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-user-shield text-white"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-base md:text-lg font-medium text-gray-900">Tambah Admin Baru</h3>
                                    <p class="text-sm text-gray-500">Buat akun administrator untuk sistem HKI</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 md:p-6">
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

                            @if ($errors->any())
                                <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-exclamation-circle text-red-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <ul class="text-sm text-red-700 space-y-1">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('admin.store') }}" class="space-y-6">
                                @csrf
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-user mr-2 text-gray-400"></i>Nama Lengkap
                                        </label>
                                        <input 
                                            type="text" 
                                            id="name" 
                                            name="name" 
                                            value="{{ old('name') }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-20 transition duration-200"
                                            placeholder="Masukkan nama lengkap"
                                            required
                                        >
                                    </div>

                                    <div>
                                        <label for="nip_nidn_nidk_nim" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-id-card mr-2 text-gray-400"></i>NIP/NIDN/NIDK/NIM
                                        </label>
                                        <input 
                                            type="text" 
                                            id="nip_nidn_nidk_nim" 
                                            name="nip_nidn_nidk_nim" 
                                            value="{{ old('nip_nidn_nidk_nim') }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-20 transition duration-200"
                                            placeholder="Masukkan NIP/NIDN/NIDK/NIM"
                                            required
                                        >
                                    </div>
                                </div>

                                <div>
                                    <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-phone mr-2 text-gray-400"></i>Nomor WhatsApp
                                    </label>
                                    <input 
                                        type="text" 
                                        id="phone_number" 
                                        name="phone_number" 
                                        value="{{ old('phone_number') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-20 transition duration-200"
                                        placeholder="08123456789"
                                        required
                                    >
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-lock mr-2 text-gray-400"></i>Password
                                        </label>
                                        <div class="relative">
                                            <input 
                                                type="password" 
                                                id="password" 
                                                name="password"
                                                class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-20 transition duration-200"
                                                placeholder="••••••••"
                                                required
                                            >
                                            <button 
                                                type="button" 
                                                class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-600 transition duration-200"
                                                onclick="togglePasswordVisibility('password', 'eyeIconPassword')"
                                            >
                                                <i id="eyeIconPassword" class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-lock mr-2 text-gray-400"></i>Konfirmasi Password
                                        </label>
                                        <div class="relative">
                                            <input 
                                                type="password" 
                                                id="password_confirmation" 
                                                name="password_confirmation"
                                                class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-20 transition duration-200"
                                                placeholder="••••••••"
                                                required
                                            >
                                            <button 
                                                type="button" 
                                                class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-600 transition duration-200"
                                                onclick="togglePasswordVisibility('password_confirmation', 'eyeIconPasswordConfirm')"
                                            >
                                                <i id="eyeIconPasswordConfirm" class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    </div>
                                </div>

                                <!-- Info Box -->
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-info-circle text-blue-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-blue-800">Informasi Admin</h3>
                                            <div class="mt-2 text-sm text-blue-700">
                                                <ul class="list-disc list-inside space-y-1">
                                                    <li>Admin yang dibuat akan memiliki akses penuh ke sistem</li>
                                                    <li>Pastikan data yang dimasukkan sudah benar dan valid</li>
                                                    <li>Password minimal 8 karakter untuk keamanan</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-4 pt-6">
                                    <button 
                                        type="submit"
                                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                    >
                                        <i class="fas fa-user-plus mr-2"></i>Buat Admin
                                    </button>
                                    <a 
                                        href="{{ route('admin.admins.index') }}"
                                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 text-center"
                                    >
                                        <i class="fas fa-times mr-2"></i>Batal
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    @include('admin.partials.sidebar-script')

    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>