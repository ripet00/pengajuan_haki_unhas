<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Password - Admin Panel</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('admin.partials.header', ['title' => 'Edit Password'])

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="px-4 sm:px-6 lg:px-8 py-6">
                    <!-- Success Message -->
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

                    <!-- Error Messages -->
                    @if($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-red-400"></i>
                                </div>
                                <div class="ml-3">
                                    <ul class="text-sm text-red-700 list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="max-w-2xl mx-auto">
                        <!-- Back Button -->
                        <div class="mb-6">
                            <a href="{{ route('admin.dashboard') }}" 
                               class="inline-flex items-center px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 rounded-lg font-medium transition duration-200">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali ke Dashboard
                            </a>
                        </div>

                        <!-- Edit Password Card -->
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                            <div class="px-6 py-5 bg-gradient-to-r from-red-600 to-red-700">
                                <h2 class="text-2xl font-bold text-white flex items-center">
                                    <i class="fas fa-key mr-3"></i>
                                    Ubah Password
                                </h2>
                                <p class="mt-2 text-red-100 text-sm">
                                    Ubah password akun Anda untuk menjaga keamanan
                                </p>
                            </div>
                            
                            <div class="p-6 md:p-8">
                                <form method="POST" action="{{ route('admin.profile.update-password') }}">
                                    @csrf
                                    
                                    <!-- Current Password -->
                                    <div class="mb-6">
                                        <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Password Lama
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="password" 
                                                   id="current_password" 
                                                   name="current_password" 
                                                   class="w-full px-4 py-3 pr-12 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition @error('current_password') border-red-500 @enderror"
                                                   placeholder="Masukkan password lama"
                                                   required>
                                            <button type="button" 
                                                    onclick="togglePassword('current_password')"
                                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                                                <i class="fas fa-eye" id="current_password_icon"></i>
                                            </button>
                                        </div>
                                        @error('current_password')
                                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- New Password -->
                                    <div class="mb-6">
                                        <label for="new_password" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Password Baru
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="password" 
                                                   id="new_password" 
                                                   name="new_password" 
                                                   class="w-full px-4 py-3 pr-12 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition @error('new_password') border-red-500 @enderror"
                                                   placeholder="Masukkan password baru (min. 6 karakter)"
                                                   required>
                                            <button type="button" 
                                                    onclick="togglePassword('new_password')"
                                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                                                <i class="fas fa-eye" id="new_password_icon"></i>
                                            </button>
                                        </div>
                                        @error('new_password')
                                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                            </p>
                                        @enderror
                                        <p class="mt-2 text-xs text-gray-500 flex items-center">
                                            <i class="fas fa-info-circle mr-1"></i>Password minimal 6 karakter
                                        </p>
                                    </div>

                                    <!-- Confirm New Password -->
                                    <div class="mb-8">
                                        <label for="new_password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Konfirmasi Password Baru
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="password" 
                                                   id="new_password_confirmation" 
                                                   name="new_password_confirmation" 
                                                   class="w-full px-4 py-3 pr-12 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition"
                                                   placeholder="Ketik ulang password baru"
                                                   required>
                                            <button type="button" 
                                                    onclick="togglePassword('new_password_confirmation')"
                                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                                                <i class="fas fa-eye" id="new_password_confirmation_icon"></i>
                                            </button>
                                        </div>
                                        <p class="mt-2 text-xs text-gray-500 flex items-center">
                                            <i class="fas fa-info-circle mr-1"></i>Ketik ulang password baru untuk konfirmasi
                                        </p>
                                    </div>

                                    <!-- Info Box -->
                                    <div class="mb-8 p-4 bg-blue-50 border-l-4 border-blue-400 rounded-r-lg">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-shield-alt text-blue-500 text-lg"></i>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-semibold text-blue-900 mb-2">Tips Keamanan Password:</h3>
                                                <ul class="text-sm text-blue-800 space-y-1">
                                                    <li class="flex items-start">
                                                        <i class="fas fa-check text-blue-600 mr-2 mt-0.5"></i>
                                                        <span>Gunakan kombinasi huruf besar dan kecil</span>
                                                    </li>
                                                    <li class="flex items-start">
                                                        <i class="fas fa-check text-blue-600 mr-2 mt-0.5"></i>
                                                        <span>Tambahkan angka dan simbol untuk keamanan ekstra</span>
                                                    </li>
                                                    <li class="flex items-start">
                                                        <i class="fas fa-check text-blue-600 mr-2 mt-0.5"></i>
                                                        <span>Jangan gunakan password yang mudah ditebak</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Submit Buttons -->
                                    <div class="flex flex-col sm:flex-row items-center justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                                        <a href="{{ route('admin.dashboard') }}" 
                                           class="w-full sm:w-auto px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-semibold transition duration-200 text-center">
                                            <i class="fas fa-times mr-2"></i>Batal
                                        </a>
                                        <button type="submit" 
                                                class="w-full sm:w-auto px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition duration-200 shadow-md hover:shadow-lg">
                                            <i class="fas fa-save mr-2"></i>Simpan Password Baru
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    @include('admin.partials.sidebar-script')

    <script>
        // Toggle password visibility
        function togglePassword(fieldId) {
            const input = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '_icon');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
