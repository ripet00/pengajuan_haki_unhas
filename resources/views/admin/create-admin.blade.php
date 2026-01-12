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
                                
                                <!-- Role Selection - FIRST -->
                                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 border-2 border-indigo-200 rounded-lg p-6">
                                    <label for="role" class="block text-sm font-medium text-gray-900 mb-2">
                                        <i class="fas fa-user-tag mr-2 text-indigo-600"></i>Role / Peran Admin
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <select 
                                        id="role" 
                                        name="role"
                                        class="w-full px-4 py-3 border-2 border-indigo-300 rounded-lg focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-30 transition duration-200 bg-white font-medium"
                                        required
                                    >
                                        <option value="">-- Pilih Role / Peran Admin --</option>
                                        @foreach(\App\Models\Admin::getRoles() as $roleValue => $roleLabel)
                                            <option value="{{ $roleValue }}" {{ old('role') == $roleValue ? 'selected' : '' }}>
                                                {{ $roleLabel }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Notice: Select Role First -->
                                <div id="selectRoleNotice" class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-exclamation-triangle text-yellow-500 mr-3 text-xl"></i>
                                        <p class="text-sm text-yellow-800 font-medium">
                                            Silakan pilih Role / Peran Admin terlebih dahulu untuk melanjutkan pengisian form
                                        </p>
                                    </div>
                                </div>

                                <!-- Main Form Fields (Initially Disabled) -->
                                <div id="mainFormFields" class="space-y-6 opacity-50 pointer-events-none">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fas fa-user mr-2 text-gray-400"></i>Nama Lengkap
                                                <span class="text-red-500">*</span>
                                            </label>
                                            <input 
                                                type="text" 
                                                id="name" 
                                                name="name" 
                                                value="{{ old('name') }}"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-20 transition duration-200"
                                                placeholder="Masukkan nama lengkap"
                                                disabled
                                                required
                                            >
                                        </div>

                                        <div>
                                            <label for="nip_nidn_nidk_nim" class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fas fa-id-card mr-2 text-gray-400"></i>NIP/NIDN/NIDK/NIM
                                                <span class="text-red-500">*</span>
                                            </label>
                                            <input 
                                                type="text" 
                                                id="nip_nidn_nidk_nim" 
                                                name="nip_nidn_nidk_nim" 
                                                value="{{ old('nip_nidn_nidk_nim') }}"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-20 transition duration-200"
                                                placeholder="Masukkan NIP/NIDN/NIDK/NIM"
                                                disabled
                                                required
                                            >
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                        <div class="sm:col-span-1">
                                            <label for="country_code" class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fas fa-globe mr-2 text-gray-400"></i>Kode Negara
                                                <span class="text-red-500">*</span>
                                            </label>
                                            <select 
                                                id="country_code" 
                                                name="country_code"
                                                class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-20 transition duration-200 text-sm"
                                                disabled
                                                required
                                            >
                                                @foreach(getCountryCodes() as $code => $label)
                                                    <option value="{{ $code }}" {{ old('country_code', '+62') == $code ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fas fa-phone mr-2 text-gray-400"></i>Nomor WhatsApp
                                                <span class="text-red-500">*</span>
                                            </label>
                                            <input 
                                                type="text" 
                                                id="phone_number" 
                                                name="phone_number" 
                                                value="{{ old('phone_number') }}"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-20 transition duration-200"
                                                placeholder="08123456789"
                                                pattern="^0[0-9]{8,13}$"
                                                title="Nomor harus dimulai dengan 0 dan berisi 9-14 digit"
                                                disabled
                                                required
                                            >
                                            <p class="text-xs text-gray-500 mt-1">Masukkan nomor dengan format 0xxxxxxxx</p>
                                        </div>
                                    </div>

                                    <!-- Pendamping Paten Fields (Conditional) -->
                                    <div id="pendampingPatenFields" class="space-y-6 hidden">
                                        <div class="bg-purple-50 border-2 border-purple-300 rounded-lg p-6">
                                            <h4 class="text-sm font-medium text-purple-900 mb-4 flex items-center">
                                                <i class="fas fa-user-tie mr-2 text-lg"></i>
                                                Informasi Khusus Pendamping Paten
                                            </h4>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label for="fakultas" class="block text-sm font-medium text-gray-700 mb-2">
                                                        <i class="fas fa-university mr-2 text-gray-400"></i>Fakultas <span class="text-red-500">*</span>
                                                    </label>
                                                    <select 
                                                        id="fakultas" 
                                                        name="fakultas"
                                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500 focus:ring-opacity-20 transition duration-200"
                                                    >
                                                        <option value="">Pilih Fakultas</option>
                                                        <option value="Fakultas Ekonomi dan Bisnis" {{ old('fakultas') == 'Fakultas Ekonomi dan Bisnis' ? 'selected' : '' }}>Fakultas Ekonomi dan Bisnis</option>
                                                        <option value="Fakultas Hukum" {{ old('fakultas') == 'Fakultas Hukum' ? 'selected' : '' }}>Fakultas Hukum</option>
                                                        <option value="Fakultas Kedokteran" {{ old('fakultas') == 'Fakultas Kedokteran' ? 'selected' : '' }}>Fakultas Kedokteran</option>
                                                        <option value="Fakultas Teknik" {{ old('fakultas') == 'Fakultas Teknik' ? 'selected' : '' }}>Fakultas Teknik</option>
                                                        <option value="Fakultas Ilmu Sosial dan Ilmu Politik" {{ old('fakultas') == 'Fakultas Ilmu Sosial dan Ilmu Politik' ? 'selected' : '' }}>Fakultas Ilmu Sosial dan Ilmu Politik</option>
                                                        <option value="Fakultas Ilmu Budaya" {{ old('fakultas') == 'Fakultas Ilmu Budaya' ? 'selected' : '' }}>Fakultas Ilmu Budaya</option>
                                                        <option value="Fakultas Pertanian" {{ old('fakultas') == 'Fakultas Pertanian' ? 'selected' : '' }}>Fakultas Pertanian</option>
                                                        <option value="Fakultas Matematika dan Ilmu Pengetahuan Alam" {{ old('fakultas') == 'Fakultas Matematika dan Ilmu Pengetahuan Alam' ? 'selected' : '' }}>Fakultas Matematika dan Ilmu Pengetahuan Alam</option>
                                                        <option value="Fakultas Peternakan" {{ old('fakultas') == 'Fakultas Peternakan' ? 'selected' : '' }}>Fakultas Peternakan</option>
                                                        <option value="Fakultas Kedokteran Gigi" {{ old('fakultas') == 'Fakultas Kedokteran Gigi' ? 'selected' : '' }}>Fakultas Kedokteran Gigi</option>
                                                        <option value="Fakultas Kesehatan Masyarakat" {{ old('fakultas') == 'Fakultas Kesehatan Masyarakat' ? 'selected' : '' }}>Fakultas Kesehatan Masyarakat</option>
                                                        <option value="Fakultas Ilmu Kelautan dan Perikanan" {{ old('fakultas') == 'Fakultas Ilmu Kelautan dan Perikanan' ? 'selected' : '' }}>Fakultas Ilmu Kelautan dan Perikanan</option>
                                                        <option value="Fakultas Kehutanan" {{ old('fakultas') == 'Fakultas Kehutanan' ? 'selected' : '' }}>Fakultas Kehutanan</option>
                                                        <option value="Fakultas Farmasi" {{ old('fakultas') == 'Fakultas Farmasi' ? 'selected' : '' }}>Fakultas Farmasi</option>
                                                        <option value="Fakultas Keperawatan" {{ old('fakultas') == 'Fakultas Keperawatan' ? 'selected' : '' }}>Fakultas Keperawatan</option>
                                                        <option value="Fakultas Vokasi" {{ old('fakultas') == 'Fakultas Vokasi' ? 'selected' : '' }}>Fakultas Vokasi</option>
                                                        <option value="Fakultas Teknologi Pertanian" {{ old('fakultas') == 'Fakultas Teknologi Pertanian' ? 'selected' : '' }}>Fakultas Teknologi Pertanian</option>
                                                        <option value="Sekolah Pascasarjana" {{ old('fakultas') == 'Sekolah Pascasarjana' ? 'selected' : '' }}>Sekolah Pascasarjana</option>
                                                    </select>
                                                </div>

                                                <div>
                                                    <label for="program_studi" class="block text-sm font-medium text-gray-700 mb-2">
                                                        <i class="fas fa-graduation-cap mr-2 text-gray-400"></i>Program Studi <span class="text-red-500">*</span>
                                                    </label>
                                                    <input 
                                                        type="text" 
                                                        id="program_studi" 
                                                        name="program_studi"
                                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500 focus:ring-opacity-20 transition duration-200"
                                                        placeholder="Contoh: Teknik Informatika"
                                                        value="{{ old('program_studi') }}"
                                                    >
                                                    <p class="text-xs text-gray-500 mt-1">Ketik nama program studi secara manual</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fas fa-lock mr-2 text-gray-400"></i>Password
                                                <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <input 
                                                    type="password" 
                                                    id="password" 
                                                    name="password"
                                                    class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-20 transition duration-200"
                                                    placeholder="••••••••"
                                                    disabled
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
                                                <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <input 
                                                    type="password" 
                                                    id="password_confirmation" 
                                                    name="password_confirmation"
                                                    class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-20 transition duration-200"
                                                    placeholder="••••••••"
                                                    disabled
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
                                                        <li>Admin yang dibuat akan memiliki akses sesuai role yang dipilih</li>
                                                        <li>Pastikan data yang dimasukkan sudah benar dan valid</li>
                                                        <li>Password minimal 8 karakter untuk keamanan</li>
                                                    </ul>
                                                </div>
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

        // Main Form Logic
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const selectRoleNotice = document.getElementById('selectRoleNotice');
            const mainFormFields = document.getElementById('mainFormFields');
            const pendampingFields = document.getElementById('pendampingPatenFields');
            const fakultasSelect = document.getElementById('fakultas');
            const prodiSelect = document.getElementById('program_studi');

            // All input fields that should be disabled initially
            const formInputs = [
                document.getElementById('name'),
                document.getElementById('nip_nidn_nidk_nim'),
                document.getElementById('country_code'),
                document.getElementById('phone_number'),
                document.getElementById('password'),
                document.getElementById('password_confirmation')
            ];

            // Enable/Disable form fields based on role selection
            function toggleFormFields() {
                const hasRole = roleSelect.value !== '';
                
                if (hasRole) {
                    // Hide notice, enable form
                    selectRoleNotice.classList.add('hidden');
                    mainFormFields.classList.remove('opacity-50', 'pointer-events-none');
                    
                    // Enable all inputs
                    formInputs.forEach(input => {
                        if (input) input.removeAttribute('disabled');
                    });
                } else {
                    // Show notice, disable form
                    selectRoleNotice.classList.remove('hidden');
                    mainFormFields.classList.add('opacity-50', 'pointer-events-none');
                    
                    // Disable all inputs
                    formInputs.forEach(input => {
                        if (input) input.setAttribute('disabled', 'disabled');
                    });
                }

                // Handle Pendamping Paten specific fields
                togglePendampingFields();
            }

            // Toggle Pendamping Paten fields based on role selection
            function togglePendampingFields() {
                if (roleSelect.value === 'pendamping_paten') {
                    pendampingFields.classList.remove('hidden');
                    fakultasSelect.setAttribute('required', 'required');
                    prodiSelect.setAttribute('required', 'required');
                } else {
                    pendampingFields.classList.add('hidden');
                    fakultasSelect.removeAttribute('required');
                    prodiSelect.removeAttribute('required');
                    fakultasSelect.value = '';
                    prodiSelect.value = '';
                }
            }

            // Fakultas options are now hardcoded in HTML
            // Program studi is now a manual text input field
            // No loading function needed - users can type directly

            // Event Listeners
            roleSelect.addEventListener('change', toggleFormFields);
            // Program studi is now manual text input, no need to load options on fakultas change

            // Initial state - check if role is already selected (from old() values)
            const oldRole = '{{ old("role") }}';
            if (oldRole) {
                roleSelect.value = oldRole;
            }
            toggleFormFields();
        });
    </script>
</body>
</html>