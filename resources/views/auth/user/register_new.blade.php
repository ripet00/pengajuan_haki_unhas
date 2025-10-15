<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Pengajuan HKI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15), 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .input-focus:focus {
            outline: none;
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-lg">
        <!-- Logo/Header Section -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-full shadow-lg mb-4">
                <img src="{{ asset('images/logo-unhas-kecil.png') }}" alt="Logo Unhas" class="w-16 h-16">
            </div>
            <h1 class="text-2xl font-bold text-white mb-2">Registrasi User</h1>
            <div class="text-red-100">
                <p class="font-semibold">Direktorat Inovasi dan Kekayaan Intelektual</p>
                <p class="text-sm">Universitas Hasanuddin</p>
            </div>
        </div>

        <!-- Register Form -->
        <div class="glass-effect rounded-2xl shadow-xl p-6 sm:p-8">
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <ul class="text-sm text-red-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="/register" class="space-y-6">
                @csrf
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-2 text-gray-400"></i>Nama Lengkap
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus transition duration-200"
                        placeholder="Masukkan nama lengkap"
                        required
                    >
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
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus transition duration-200"
                        placeholder="08123456789"
                        required
                    >
                </div>

                <div>
                    <label for="faculty" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-building mr-2 text-gray-400"></i>Fakultas
                    </label>
                    <select 
                        id="faculty" 
                        name="faculty"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus transition duration-200"
                        required
                    >
                        <option value="">Pilih Fakultas</option>
                        <option value="Umum" {{ old('faculty') == 'Umum' ? 'selected' : '' }}>Umum</option>
                        <option value="Fakultas Ekonomi dan Bisnis" {{ old('faculty') == 'Fakultas Ekonomi dan Bisnis' ? 'selected' : '' }}>Fakultas Ekonomi dan Bisnis</option>
                        <option value="Fakultas Hukum" {{ old('faculty') == 'Fakultas Hukum' ? 'selected' : '' }}>Fakultas Hukum</option>
                        <option value="Fakultas Kedokteran" {{ old('faculty') == 'Fakultas Kedokteran' ? 'selected' : '' }}>Fakultas Kedokteran</option>
                        <option value="Fakultas Teknik" {{ old('faculty') == 'Fakultas Teknik' ? 'selected' : '' }}>Fakultas Teknik</option>
                        <option value="Fakultas Ilmu Sosial dan Ilmu Politik" {{ old('faculty') == 'Fakultas Ilmu Sosial dan Ilmu Politik' ? 'selected' : '' }}>Fakultas Ilmu Sosial dan Ilmu Politik</option>
                        <option value="Fakultas Ilmu Budaya" {{ old('faculty') == 'Fakultas Ilmu Budaya' ? 'selected' : '' }}>Fakultas Ilmu Budaya</option>
                        <option value="Fakultas Pertanian" {{ old('faculty') == 'Fakultas Pertanian' ? 'selected' : '' }}>Fakultas Pertanian</option>
                        <option value="Fakultas Matematika dan Ilmu Pengetahuan Alam" {{ old('faculty') == 'Fakultas Matematika dan Ilmu Pengetahuan Alam' ? 'selected' : '' }}>Fakultas Matematika dan Ilmu Pengetahuan Alam</option>
                        <option value="Fakultas Peternakan" {{ old('faculty') == 'Fakultas Peternakan' ? 'selected' : '' }}>Fakultas Peternakan</option>
                        <option value="Fakultas Kedokteran Gigi" {{ old('faculty') == 'Fakultas Kedokteran Gigi' ? 'selected' : '' }}>Fakultas Kedokteran Gigi</option>
                        <option value="Fakultas Kesehatan Masyarakat" {{ old('faculty') == 'Fakultas Kesehatan Masyarakat' ? 'selected' : '' }}>Fakultas Kesehatan Masyarakat</option>
                        <option value="Fakultas Ilmu Kelautan dan Perikanan" {{ old('faculty') == 'Fakultas Ilmu Kelautan dan Perikanan' ? 'selected' : '' }}>Fakultas Ilmu Kelautan dan Perikanan</option>
                        <option value="Fakultas Kehutanan" {{ old('faculty') == 'Fakultas Kehutanan' ? 'selected' : '' }}>Fakultas Kehutanan</option>
                        <option value="Fakultas Farmasi" {{ old('faculty') == 'Fakultas Farmasi' ? 'selected' : '' }}>Fakultas Farmasi</option>
                        <option value="Fakultas Keperawatan" {{ old('faculty') == 'Fakultas Keperawatan' ? 'selected' : '' }}>Fakultas Keperawatan</option>
                        <option value="Fakultas Vokasi" {{ old('faculty') == 'Fakultas Vokasi' ? 'selected' : '' }}>Fakultas Vokasi</option>
                        <option value="Fakultas Teknologi Pertanian" {{ old('faculty') == 'Fakultas Teknologi Pertanian' ? 'selected' : '' }}>Fakultas Teknologi Pertanian</option>
                        <option value="Sekolah Pascasarjana" {{ old('faculty') == 'Sekolah Pascasarjana' ? 'selected' : '' }}>Sekolah Pascasarjana</option>
                    </select>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2 text-gray-400"></i>Password
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg input-focus transition duration-200"
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
                            class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg input-focus transition duration-200"
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

                <button 
                    type="submit"
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 transform hover:scale-105 shadow-lg"
                >
                    <i class="fas fa-user-plus mr-2"></i>Daftar Akun
                </button>
            </form>

            <!-- Spacing yang lebih baik antara form dan footer -->
            <div class="mt-6 pt-6 border-t border-gray-300">
                <div class="text-center space-y-3">
                    <a href="/login" class="block w-full bg-white hover:bg-gray-50 text-red-600 font-semibold py-3 px-4 rounded-lg border border-red-600 transition duration-200">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login di sini
                    </a>
                    <p class="text-sm text-gray-600">
                        Sudah punya akun? Silakan login untuk melanjutkan.
                    </p>
                </div>
            </div>
        </div>
    </div>

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