<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register User - Pengajuan HAKI</title>
    @filamentStyles
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            max-width: 1000px;
            width: 90%;
            min-height: 600px;
        }
        .auth-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 600px;
        }
        .form-section {
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .info-section {
            background: linear-gradient(45deg, #a8edea 0%, #fed6e3 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        @media (max-width: 768px) {
            .auth-grid {
                grid-template-columns: 1fr;
            }
            .info-section {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-grid">
            <!-- Form Section -->
            <div class="form-section">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Daftar User</h1>
                    <p class="text-gray-600">Buat akun untuk mengajukan HAKI</p>
                </div>

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="/register" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            placeholder="Masukkan nama lengkap"
                            required
                        >
                    </div>

                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor WhatsApp
                        </label>
                        <input 
                            type="text" 
                            id="phone_number" 
                            name="phone_number" 
                            value="{{ old('phone_number') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            placeholder="Contoh: 081234567890"
                            required
                        >
                    </div>

                    <div>
                        <label for="faculty" class="block text-sm font-medium text-gray-700 mb-2">
                            Fakultas
                        </label>
                        <select 
                            id="faculty" 
                            name="faculty"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            required
                        >
                            <option value="">Pilih Fakultas</option>
                            <option value="Fakultas Kedokteran" {{ old('faculty') == 'Fakultas Kedokteran' ? 'selected' : '' }}>Fakultas Kedokteran</option>
                            <option value="Fakultas Teknik" {{ old('faculty') == 'Fakultas Teknik' ? 'selected' : '' }}>Fakultas Teknik</option>
                            <option value="Fakultas Hukum" {{ old('faculty') == 'Fakultas Hukum' ? 'selected' : '' }}>Fakultas Hukum</option>
                            <option value="Fakultas Ekonomi dan Bisnis" {{ old('faculty') == 'Fakultas Ekonomi dan Bisnis' ? 'selected' : '' }}>Fakultas Ekonomi dan Bisnis</option>
                            <option value="Fakultas Ilmu Sosial dan Ilmu Politik" {{ old('faculty') == 'Fakultas Ilmu Sosial dan Ilmu Politik' ? 'selected' : '' }}>Fakultas Ilmu Sosial dan Ilmu Politik</option>
                            <option value="Fakultas Ilmu Budaya" {{ old('faculty') == 'Fakultas Ilmu Budaya' ? 'selected' : '' }}>Fakultas Ilmu Budaya</option>
                            <option value="Fakultas Pertanian" {{ old('faculty') == 'Fakultas Pertanian' ? 'selected' : '' }}>Fakultas Pertanian</option>
                            <option value="Fakultas Matematika dan Ilmu Pengetahuan Alam" {{ old('faculty') == 'Fakultas Matematika dan Ilmu Pengetahuan Alam' ? 'selected' : '' }}>Fakultas MIPA</option>
                            <option value="Fakultas Peternakan" {{ old('faculty') == 'Fakultas Peternakan' ? 'selected' : '' }}>Fakultas Peternakan</option>
                            <option value="Fakultas Kedokteran Gigi" {{ old('faculty') == 'Fakultas Kedokteran Gigi' ? 'selected' : '' }}>Fakultas Kedokteran Gigi</option>
                            <option value="Fakultas Kesehatan Masyarakat" {{ old('faculty') == 'Fakultas Kesehatan Masyarakat' ? 'selected' : '' }}>Fakultas Kesehatan Masyarakat</option>
                            <option value="Fakultas Farmasi" {{ old('faculty') == 'Fakultas Farmasi' ? 'selected' : '' }}>Fakultas Farmasi</option>
                            <option value="Fakultas Kehutanan" {{ old('faculty') == 'Fakultas Kehutanan' ? 'selected' : '' }}>Fakultas Kehutanan</option>
                            <option value="Fakultas Ilmu Kelautan dan Perikanan" {{ old('faculty') == 'Fakultas Ilmu Kelautan dan Perikanan' ? 'selected' : '' }}>Fakultas Ilmu Kelautan dan Perikanan</option>
                            <option value="Fakultas Psikologi" {{ old('faculty') == 'Fakultas Psikologi' ? 'selected' : '' }}>Fakultas Psikologi</option>
                            <option value="Fakultas Keperawatan" {{ old('faculty') == 'Fakultas Keperawatan' ? 'selected' : '' }}>Fakultas Keperawatan</option>
                        </select>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            placeholder="Masukkan password (min. 8 karakter)"
                            required
                        >
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Konfirmasi Password
                        </label>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            placeholder="Ulangi password"
                            required
                        >
                    </div>

                    <button 
                        type="submit"
                        class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200"
                    >
                        Daftar
                    </button>
                </form>

                <div class="text-center mt-6">
                    <p class="text-gray-600">
                        Sudah punya akun? 
                        <a href="/login" class="text-primary-600 hover:text-primary-700 font-semibold">
                            Login di sini
                        </a>
                    </p>
                </div>
            </div>

            <!-- Info Section -->
            <div class="info-section">
                <div class="text-center text-gray-800">
                    <h2 class="text-2xl font-bold mb-4">Bergabung dengan Kami</h2>
                    <p class="text-lg">
                        Daftarkan diri Anda untuk mulai mengajukan Hak Kekayaan Intelektual
                    </p>
                </div>
            </div>
        </div>
    </div>

    @filamentScripts
</body>
</html>