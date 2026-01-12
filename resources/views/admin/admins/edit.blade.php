<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Admin - Pengajuan HKI</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('admin.partials.header', ['title' => 'Edit Admin'])

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
                                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-user-edit text-white"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-base md:text-lg font-medium text-gray-900">Edit Data Admin</h3>
                                    <p class="text-sm text-gray-500">Ubah informasi administrator sistem (kecuali nomor telepon)</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 md:p-6">
                            @if(session('error'))
                                <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-exclamation-circle text-red-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-red-700">{{ session('error') }}</p>
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

                            <form id="editAdminForm" method="POST" action="{{ route('admin.admins.update', $admin) }}" class="space-y-6">
                                @csrf
                                @method('PUT')

                                <!-- Original data (hidden) for comparison -->
                                <input type="hidden" id="original_name" value="{{ $admin->name }}">
                                <input type="hidden" id="original_nip" value="{{ $admin->nip_nidn_nidk_nim }}">
                                <input type="hidden" id="original_country_code" value="{{ $admin->country_code }}">
                                <input type="hidden" id="original_role" value="{{ $admin->role }}">
                                <input type="hidden" id="original_fakultas" value="{{ $admin->fakultas ?? '' }}">
                                <input type="hidden" id="original_program_studi" value="{{ $admin->program_studi ?? '' }}">
                                
                                <!-- Role Selection -->
                                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-lg p-6">
                                    <label for="role" class="block text-sm font-medium text-gray-900 mb-2">
                                        <i class="fas fa-user-tag mr-2 text-blue-600"></i>Role / Peran Admin
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <select 
                                        id="role" 
                                        name="role"
                                        class="w-full px-4 py-3 border-2 border-blue-300 rounded-lg focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-30 transition duration-200 bg-white font-medium"
                                        required
                                    >
                                        @foreach(\App\Models\Admin::getRoles() as $roleValue => $roleLabel)
                                            <option value="{{ $roleValue }}" {{ old('role', $admin->role) == $roleValue ? 'selected' : '' }}>
                                                {{ $roleLabel }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Main Form Fields -->
                                <div class="space-y-6">
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
                                                value="{{ old('name', $admin->name) }}"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 transition duration-200"
                                                placeholder="Masukkan nama lengkap"
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
                                                value="{{ old('nip_nidn_nidk_nim', $admin->nip_nidn_nidk_nim) }}"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 transition duration-200"
                                                placeholder="Masukkan NIP/NIDN/NIDK/NIM"
                                                required
                                            >
                                        </div>
                                    </div>

                                    <!-- Phone Number (Read Only) -->
                                    <div class="bg-gray-50 border-2 border-gray-200 rounded-lg p-6">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-phone mr-2 text-gray-400"></i>Nomor WhatsApp
                                            <span class="ml-2 text-xs text-gray-500">(Tidak dapat diubah)</span>
                                        </label>
                                        <div class="flex items-center space-x-3">
                                            <input 
                                                type="text" 
                                                value="{{ $admin->country_code }}"
                                                class="w-24 px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed"
                                                disabled
                                            >
                                            <input 
                                                type="text" 
                                                value="{{ $admin->phone_number }}"
                                                class="flex-1 px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed"
                                                disabled
                                            >
                                        </div>
                                        <p class="text-xs text-gray-500 mt-2">
                                            <i class="fas fa-lock mr-1"></i>Nomor telepon tidak dapat diubah karena digunakan sebagai identitas unik
                                        </p>
                                    </div>

                                    <!-- Country Code (Hidden, still updatable if needed) -->
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                        <div class="sm:col-span-1">
                                            <label for="country_code" class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fas fa-globe mr-2 text-gray-400"></i>Kode Negara
                                                <span class="text-red-500">*</span>
                                            </label>
                                            <select 
                                                id="country_code" 
                                                name="country_code"
                                                class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 transition duration-200 text-sm"
                                                required
                                            >
                                                @foreach(getCountryCodes() as $code => $label)
                                                    <option value="{{ $code }}" {{ old('country_code', $admin->country_code) == $code ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Pendamping Paten Fields (Conditional) -->
                                    <div id="pendampingPatenFields" class="space-y-6 {{ old('role', $admin->role) === 'pendamping_paten' ? '' : 'hidden' }}">
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
                                                        <option value="Umum" {{ old('fakultas', $admin->fakultas) == 'Umum' ? 'selected' : '' }}>Umum</option>
                                                        <option value="Fakultas Ekonomi dan Bisnis" {{ old('fakultas', $admin->fakultas) == 'Fakultas Ekonomi dan Bisnis' ? 'selected' : '' }}>Fakultas Ekonomi dan Bisnis</option>
                                                        <option value="Fakultas Hukum" {{ old('fakultas', $admin->fakultas) == 'Fakultas Hukum' ? 'selected' : '' }}>Fakultas Hukum</option>
                                                        <option value="Fakultas Kedokteran" {{ old('fakultas', $admin->fakultas) == 'Fakultas Kedokteran' ? 'selected' : '' }}>Fakultas Kedokteran</option>
                                                        <option value="Fakultas Teknik" {{ old('fakultas', $admin->fakultas) == 'Fakultas Teknik' ? 'selected' : '' }}>Fakultas Teknik</option>
                                                        <option value="Fakultas Ilmu Sosial dan Ilmu Politik" {{ old('fakultas', $admin->fakultas) == 'Fakultas Ilmu Sosial dan Ilmu Politik' ? 'selected' : '' }}>Fakultas Ilmu Sosial dan Ilmu Politik</option>
                                                        <option value="Fakultas Ilmu Budaya" {{ old('fakultas', $admin->fakultas) == 'Fakultas Ilmu Budaya' ? 'selected' : '' }}>Fakultas Ilmu Budaya</option>
                                                        <option value="Fakultas Pertanian" {{ old('fakultas', $admin->fakultas) == 'Fakultas Pertanian' ? 'selected' : '' }}>Fakultas Pertanian</option>
                                                        <option value="Fakultas Matematika dan Ilmu Pengetahuan Alam" {{ old('fakultas', $admin->fakultas) == 'Fakultas Matematika dan Ilmu Pengetahuan Alam' ? 'selected' : '' }}>Fakultas Matematika dan Ilmu Pengetahuan Alam</option>
                                                        <option value="Fakultas Peternakan" {{ old('fakultas', $admin->fakultas) == 'Fakultas Peternakan' ? 'selected' : '' }}>Fakultas Peternakan</option>
                                                        <option value="Fakultas Kedokteran Gigi" {{ old('fakultas', $admin->fakultas) == 'Fakultas Kedokteran Gigi' ? 'selected' : '' }}>Fakultas Kedokteran Gigi</option>
                                                        <option value="Fakultas Kesehatan Masyarakat" {{ old('fakultas', $admin->fakultas) == 'Fakultas Kesehatan Masyarakat' ? 'selected' : '' }}>Fakultas Kesehatan Masyarakat</option>
                                                        <option value="Fakultas Ilmu Kelautan dan Perikanan" {{ old('fakultas', $admin->fakultas) == 'Fakultas Ilmu Kelautan dan Perikanan' ? 'selected' : '' }}>Fakultas Ilmu Kelautan dan Perikanan</option>
                                                        <option value="Fakultas Kehutanan" {{ old('fakultas', $admin->fakultas) == 'Fakultas Kehutanan' ? 'selected' : '' }}>Fakultas Kehutanan</option>
                                                        <option value="Fakultas Farmasi" {{ old('fakultas', $admin->fakultas) == 'Fakultas Farmasi' ? 'selected' : '' }}>Fakultas Farmasi</option>
                                                        <option value="Fakultas Keperawatan" {{ old('fakultas', $admin->fakultas) == 'Fakultas Keperawatan' ? 'selected' : '' }}>Fakultas Keperawatan</option>
                                                        <option value="Fakultas Vokasi" {{ old('fakultas', $admin->fakultas) == 'Fakultas Vokasi' ? 'selected' : '' }}>Fakultas Vokasi</option>
                                                        <option value="Fakultas Teknologi Pertanian" {{ old('fakultas', $admin->fakultas) == 'Fakultas Teknologi Pertanian' ? 'selected' : '' }}>Fakultas Teknologi Pertanian</option>
                                                        <option value="Sekolah Pascasarjana" {{ old('fakultas', $admin->fakultas) == 'Sekolah Pascasarjana' ? 'selected' : '' }}>Sekolah Pascasarjana</option>
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
                                                        value="{{ old('program_studi', $admin->program_studi) }}"
                                                    >
                                                    <p class="text-xs text-gray-500 mt-1">Ketik nama program studi secara manual</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Password Update (Optional) -->
                                    <div class="bg-amber-50 border-2 border-amber-200 rounded-lg p-6">
                                        <h4 class="text-sm font-medium text-amber-900 mb-4 flex items-center">
                                            <i class="fas fa-key mr-2 text-lg"></i>
                                            Ubah Password (Opsional)
                                        </h4>
                                        <p class="text-xs text-amber-700 mb-4">Kosongkan jika tidak ingin mengubah password</p>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                                    <i class="fas fa-lock mr-2 text-gray-400"></i>Password Baru
                                                </label>
                                                <div class="relative">
                                                    <input 
                                                        type="password" 
                                                        id="password" 
                                                        name="password"
                                                        class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 transition duration-200"
                                                        placeholder="••••••••"
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
                                                        class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 transition duration-200"
                                                        placeholder="••••••••"
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
                                                <h3 class="text-sm font-medium text-blue-800">Informasi Penting</h3>
                                                <div class="mt-2 text-sm text-blue-700">
                                                    <ul class="list-disc list-inside space-y-1">
                                                        <li>Nomor telepon tidak dapat diubah karena digunakan sebagai identitas unik</li>
                                                        <li>Perubahan data akan ditampilkan sebelum disimpan</li>
                                                        <li>Password minimal 8 karakter (opsional)</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-4 pt-6">
                                    <button 
                                        type="button"
                                        id="submitBtn"
                                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                    >
                                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
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

    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-lg bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <i class="fas fa-exclamation-triangle text-amber-500 mr-2"></i>
                        Konfirmasi Perubahan Data
                    </h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="mt-4 mb-6">
                    <p class="text-sm text-gray-600 mb-4">Anda akan mengubah data berikut:</p>
                    
                    <div id="changesList" class="bg-gray-50 rounded-lg p-4 space-y-3 max-h-96 overflow-y-auto">
                        <!-- Changes will be inserted here -->
                    </div>

                    <div id="noChanges" class="hidden bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                        <i class="fas fa-info-circle text-yellow-600 mr-2"></i>
                        <span class="text-sm text-yellow-800">Tidak ada perubahan yang terdeteksi</span>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button 
                        type="button"
                        onclick="confirmSubmit()"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200"
                    >
                        <i class="fas fa-check mr-2"></i>Ya, Simpan Perubahan
                    </button>
                    <button 
                        type="button"
                        onclick="closeModal()"
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-200"
                    >
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                </div>
            </div>
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
            const pendampingFields = document.getElementById('pendampingPatenFields');
            const fakultasSelect = document.getElementById('fakultas');
            const prodiSelect = document.getElementById('program_studi');
            const submitBtn = document.getElementById('submitBtn');
            const editAdminForm = document.getElementById('editAdminForm');

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
                }
            }

            roleSelect.addEventListener('change', togglePendampingFields);
            togglePendampingFields(); // Initial check

            // Handle form submission with confirmation
            submitBtn.addEventListener('click', function(e) {
                e.preventDefault();
                showConfirmationModal();
            });
        });

        function showConfirmationModal() {
            const changes = detectChanges();
            const modal = document.getElementById('confirmationModal');
            const changesList = document.getElementById('changesList');
            const noChanges = document.getElementById('noChanges');

            if (changes.length === 0) {
                changesList.classList.add('hidden');
                noChanges.classList.remove('hidden');
            } else {
                changesList.classList.remove('hidden');
                noChanges.classList.add('hidden');
                
                let html = '';
                changes.forEach(change => {
                    html += `
                        <div class="bg-white border border-gray-200 rounded-lg p-3">
                            <div class="flex items-start">
                                <i class="fas fa-arrow-right text-blue-500 mt-1 mr-3"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900 mb-1">${change.field}</p>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <p class="text-xs text-gray-500">Sebelum:</p>
                                            <p class="text-sm text-red-600 font-medium">${change.oldValue || '<span class="italic">kosong</span>'}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Sesudah:</p>
                                            <p class="text-sm text-green-600 font-medium">${change.newValue || '<span class="italic">kosong</span>'}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                changesList.innerHTML = html;
            }

            modal.classList.remove('hidden');
        }

        function detectChanges() {
            const changes = [];
            const roleLabels = {
                'super_admin': 'Super Admin',
                'admin_paten': 'Admin Paten',
                'admin_hakcipta': 'Admin Hak Cipta',
                'pendamping_paten': 'Pendamping Paten'
            };

            // Check name
            const originalName = document.getElementById('original_name').value;
            const newName = document.getElementById('name').value;
            if (originalName !== newName) {
                changes.push({
                    field: 'Nama Lengkap',
                    oldValue: originalName,
                    newValue: newName
                });
            }

            // Check NIP
            const originalNip = document.getElementById('original_nip').value;
            const newNip = document.getElementById('nip_nidn_nidk_nim').value;
            if (originalNip !== newNip) {
                changes.push({
                    field: 'NIP/NIDN/NIDK/NIM',
                    oldValue: originalNip,
                    newValue: newNip
                });
            }

            // Check country code
            const originalCountryCode = document.getElementById('original_country_code').value;
            const newCountryCode = document.getElementById('country_code').value;
            if (originalCountryCode !== newCountryCode) {
                const oldLabel = document.querySelector(`#country_code option[value="${originalCountryCode}"]`)?.text || originalCountryCode;
                const newLabel = document.querySelector(`#country_code option[value="${newCountryCode}"]`)?.text || newCountryCode;
                changes.push({
                    field: 'Kode Negara',
                    oldValue: oldLabel,
                    newValue: newLabel
                });
            }

            // Check role
            const originalRole = document.getElementById('original_role').value;
            const newRole = document.getElementById('role').value;
            if (originalRole !== newRole) {
                changes.push({
                    field: 'Role/Peran',
                    oldValue: roleLabels[originalRole] || originalRole,
                    newValue: roleLabels[newRole] || newRole
                });
            }

            // Check fakultas (if applicable)
            const originalFakultas = document.getElementById('original_fakultas').value;
            const newFakultas = document.getElementById('fakultas').value;
            if (newRole === 'pendamping_paten' && originalFakultas !== newFakultas) {
                changes.push({
                    field: 'Fakultas',
                    oldValue: originalFakultas,
                    newValue: newFakultas
                });
            }

            // Check program studi (if applicable)
            const originalProdi = document.getElementById('original_program_studi').value;
            const newProdi = document.getElementById('program_studi').value;
            if (newRole === 'pendamping_paten' && originalProdi !== newProdi) {
                changes.push({
                    field: 'Program Studi',
                    oldValue: originalProdi,
                    newValue: newProdi
                });
            }

            // Check password
            const password = document.getElementById('password').value;
            if (password && password.length > 0) {
                changes.push({
                    field: 'Password',
                    oldValue: '••••••••',
                    newValue: 'Password baru (tersembunyi)'
                });
            }

            return changes;
        }

        function closeModal() {
            document.getElementById('confirmationModal').classList.add('hidden');
        }

        function confirmSubmit() {
            document.getElementById('editAdminForm').submit();
        }

        // Close modal when clicking outside
        document.getElementById('confirmationModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>
