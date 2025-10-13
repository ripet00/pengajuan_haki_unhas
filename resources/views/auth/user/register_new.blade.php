<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Pengajuan HAKI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #059669 0%, #0d9488 100%);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .input-focus:focus {
            outline: none;
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo/Header Section -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full shadow-lg mb-4">
                <i class="fas fa-user-plus text-2xl text-emerald-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Daftar Akun</h1>
            <p class="text-emerald-100">Bergabung dengan Sistem Pengajuan HAKI</p>
        </div>

        <!-- Register Form -->
        <div class="glass-effect rounded-2xl shadow-xl p-8">
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
                    <input 
                        type="password" 
                        id="password" 
                        name="password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus transition duration-200"
                        placeholder="••••••••"
                        required
                    >
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2 text-gray-400"></i>Konfirmasi Password
                    </label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus transition duration-200"
                        placeholder="••••••••"
                        required
                    >
                </div>

                <button 
                    type="submit"
                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 transform hover:scale-105 shadow-lg"
                >
                    <i class="fas fa-user-plus mr-2"></i>Daftar Akun
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-2">
                        Sudah punya akun?
                    </p>
                    <a href="/login" class="text-emerald-600 hover:text-emerald-800 text-sm font-medium">
                        <i class="fas fa-sign-in-alt mr-1"></i>Login di sini
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>