<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pengajuan HKI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .input-focus:focus {
            outline: none;
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo/Header Section -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-full shadow-lg mb-4">
                <img src="{{ asset('images/logo-unhas-kecil.png') }}" alt="Logo Unhas" class="w-16 h-16">
            </div>
            <h1 class="text-2xl font-bold text-white mb-2">Portal Login User</h1>
            <div class="text-red-100">
                <p class="font-semibold">Direktorat Inovasi dan Kekayaan Intelektual</p>
                <p class="text-sm">Universitas Hasanuddin</p>
            </div>
        </div>

        <!-- Login Form -->
        <div class="glass-effect rounded-2xl shadow-xl p-6 sm:p-8">
            @if(isset($message))
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">{{ $message }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded">
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

            <form method="POST" action="/login" class="space-y-6">
                @csrf
                
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
                            id="togglePassword"
                            class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-600 transition duration-200"
                            onclick="togglePasswordVisibility('password', 'eyeIcon')"
                        >
                            <i id="eyeIcon" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        id="remember" 
                        name="remember" 
                        value="1"
                        class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded"
                    >
                    <label for="remember" class="ml-2 block text-sm text-gray-900">
                    Ingat Saya
                    </label>
                </div>

                <button 
                    type="submit"
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 transform hover:scale-105 shadow-lg"
                >
                    <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-gray-300">
                <div class="text-center space-y-3">
                    <a href="/register" class="block w-full bg-white hover:bg-gray-50 text-red-600 font-semibold py-3 px-4 rounded-lg border border-red-600 transition duration-200">
                        <i class="fas fa-user-plus mr-2"></i>Buat Akun Baru
                    </a>
                    <a href="/admin/login" class="text-red-600 hover:text-red-800 text-sm font-medium">
                        <i class="fas fa-shield-alt mr-1"></i>Login sebagai Admin
                    </a>
                </div>
            </div>
        </div>

        <!-- Information Cards -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Tarif Pendaftaran -->
            <div class="glass-effect rounded-xl p-6 shadow-lg">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-money-bill-wave text-yellow-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Tarif Pendaftaran HKI 2025</h3>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between py-1">
                        <span class="text-gray-600">Hak Cipta</span>
                        <span class="font-semibold text-gray-900">Rp 200.000</span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span class="text-gray-600">Paten Sederhana</span>
                        <span class="font-semibold text-gray-900">Rp 700.000</span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span class="text-gray-600">Paten</span>
                        <span class="font-semibold text-gray-900">Rp 850.000</span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span class="text-gray-600">Desain Industri</span>
                        <span class="font-semibold text-gray-900">Rp 250.000</span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span class="text-gray-600">Merek</span>
                        <span class="font-semibold text-gray-900">Rp 500.000</span>
                    </div>
                    <div class="text-xs text-gray-500 mt-3 italic">
                        *Tarif untuk Universitas
                    </div>
                </div>
            </div>

            <!-- Waktu Pelayanan -->
            <div class="glass-effect rounded-xl p-6 shadow-lg">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-clock text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Waktu Pelayanan</h3>
                </div>
                <div class="space-y-3 text-sm">
                    <div>
                        <div class="font-semibold text-gray-700 mb-1">Senin - Kamis</div>
                        <div class="text-gray-600">08.00 - 12.00 WITA</div>
                        <div class="text-gray-600">13.00 - 15.30 WITA</div>
                    </div>
                    <div>
                        <div class="font-semibold text-gray-700 mb-1">Jumat</div>
                        <div class="text-gray-600">08.00 - 12.00 WITA</div>
                        <div class="text-gray-600">13.30 - 16.00 WITA</div>
                    </div>
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