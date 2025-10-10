<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Pengajuan HAKI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .input-focus:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo/Header Section -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full shadow-lg mb-4">
                <i class="fas fa-shield-alt text-2xl text-indigo-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Admin Portal</h1>
            <p class="text-indigo-100">Sistem Pengajuan HAKI</p>
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
                    <input 
                        type="password" 
                        id="password" 
                        name="password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus transition duration-200"
                        placeholder="••••••••"
                        required
                    >
                </div>

                <button 
                    type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 transform hover:scale-105 shadow-lg"
                >
                    <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-gray-200">
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
</body>
</html>