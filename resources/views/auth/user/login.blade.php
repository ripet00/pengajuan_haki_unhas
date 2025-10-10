<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pengajuan HAKI</title>
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
            background: linear-gradient(45deg, #f093fb 0%, #f5576c 100%);
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
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Login User</h1>
                    <p class="text-gray-600">Masuk ke sistem pengajuan HAKI</p>
                </div>

                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="/login" class="space-y-6">
                    @csrf
                    
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
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            placeholder="Masukkan password"
                            required
                        >
                    </div>

                    <button 
                        type="submit"
                        class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200"
                    >
                        Login
                    </button>
                </form>

                <div class="text-center mt-6">
                    <p class="text-gray-600">
                        Belum punya akun? 
                        <a href="/register" class="text-primary-600 hover:text-primary-700 font-semibold">
                            Daftar di sini
                        </a>
                    </p>
                </div>

                <div class="text-center mt-4">
                    <p class="text-gray-500 text-sm">
                        Login sebagai admin? 
                        <a href="/admin/login" class="text-gray-700 hover:text-gray-900 font-medium">
                            Klik di sini
                        </a>
                    </p>
                </div>
            </div>

            <!-- Info Section -->
            <div class="info-section">
                <div class="text-center text-white">
                    <h2 class="text-2xl font-bold mb-4">Sistem Pengajuan HAKI</h2>
                    <p class="text-lg opacity-90">
                        Platform digital untuk mengajukan Hak Kekayaan Intelektual
                    </p>
                </div>
            </div>
        </div>
    </div>

    @filamentScripts
</body>
</html>