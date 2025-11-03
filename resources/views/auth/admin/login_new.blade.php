<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Pengajuan HKI</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #991b1b 0%, #7f1d1d 100%);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .input-focus:focus {
            outline: none;
            border-color: #991b1b;
            box-shadow: 0 0 0 3px rgba(153, 27, 27, 0.1);
        }
        .btn-admin {
            background: linear-gradient(135deg, #991b1b 0%, #7f1d1d 100%);
        }
        .btn-admin:hover {
            background: linear-gradient(135deg, #7f1d1d 0%, #6b1c1c 100%);
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
            <h1 class="text-2xl font-bold text-white mb-2">Portal Admin</h1>
            <div class="text-red-100">
                <p class="font-semibold">Direktorat Inovasi dan Kekayaan Intelektual</p>
                <p class="text-sm">Universitas Hasanuddin</p>
            </div>
        </div>

        <!-- Login Form -->
        <div class="glass-effect rounded-2xl shadow-xl p-8">
            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded">
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

            <form method="POST" action="/admin/login" class="space-y-6">
                @csrf
                
                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-phone mr-2 text-gray-400"></i>Nomor WhatsApp
                    </label>
                    <input 
                        type="text" 
                        id="phone_number" 
                        name="phone_number" 
                        value="{{ $remembered_phone ?? old('phone_number') }}"
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
                        {{ isset($remembered_phone) ? 'checked' : '' }}
                        class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded"
                    >
                    <label for="remember" class="ml-2 block text-sm text-gray-900">
                    Ingat Saya
                    </label>
                </div>

                <button 
                    type="submit"
                    class="w-full btn-admin text-white font-semibold py-3 px-4 rounded-lg transition duration-200 transform hover:scale-105 shadow-lg"
                >
                    <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                </button>
            </form>

            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-2">
                        Tidak punya akun admin? Hubungi administrator lain.
                    </p>
                    <a href="/login" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                        <i class="fas fa-user mr-1"></i>Login sebagai User
                    </a>
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