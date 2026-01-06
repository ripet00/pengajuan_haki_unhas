<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Pengajuan HKI</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
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
            <h1 class="text-2xl font-bold text-white mb-2">Lupa Password</h1>
            <div class="text-red-100">
                <p class="font-semibold">Direktorat Inovasi dan Kekayaan Intelektual</p>
                <p class="text-sm">Universitas Hasanuddin</p>
            </div>
        </div>

        <!-- Forgot Password Form -->
        <div class="glass-effect rounded-2xl shadow-xl p-6 sm:p-8">
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

            @if(session('info'))
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">{{ session('info') }}</p>
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

            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-2">Reset Password Anda</h2>
                <p class="text-gray-600 text-sm">
                    Masukkan nomor WhatsApp yang terdaftar. Admin akan memverifikasi dan mengirimkan link reset password kepada Anda.
                </p>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-600"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Proses Reset Password:</h3>
                        <ol class="mt-2 text-sm text-blue-700 list-decimal list-inside space-y-1">
                            <li>Request akan dikirim ke admin untuk verifikasi</li>
                            <li>Admin akan menghubungi Anda via WhatsApp</li>
                            <li>Setelah verifikasi, link reset akan dikirimkan</li>
                            <li>Link berlaku selama 60 menit</li>
                        </ol>
                    </div>
                </div>
            </div>

            <form action="{{ route('password.request.submit') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="country_code" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-globe mr-2 text-gray-400"></i>Kode Negara
                    </label>
                    <select 
                        id="country_code" 
                        name="country_code" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus transition duration-200"
                        required
                    >
                        <option value="+62" selected>Indonesia (+62)</option>
                        <option value="+1">United States (+1)</option>
                        <option value="+44">United Kingdom (+44)</option>
                        <option value="+61">Australia (+61)</option>
                        <option value="+65">Singapore (+65)</option>
                        <option value="+60">Malaysia (+60)</option>
                        <option value="+66">Thailand (+66)</option>
                        <option value="+63">Philippines (+63)</option>
                        <option value="+84">Vietnam (+84)</option>
                        <option value="+81">Japan (+81)</option>
                        <option value="+82">South Korea (+82)</option>
                        <option value="+86">China (+86)</option>
                        <option value="+91">India (+91)</option>
                        <option value="+971">UAE (+971)</option>
                        <option value="+966">Saudi Arabia (+966)</option>
                    </select>
                </div>

                <div class="mb-6">
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
                    <p class="mt-1 text-xs text-gray-500">Masukkan nomor yang terdaftar di akun Anda</p>
                </div>

                <button 
                    type="submit"
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 shadow-md hover:shadow-lg"
                >
                    <i class="fas fa-paper-plane mr-2"></i>Kirim Permintaan Reset Password
                </button>
            </form>

            <div class="mt-6 text-center space-y-2">
                <a href="{{ route('login') }}" class="text-sm text-red-600 hover:text-red-800 font-medium block">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali ke Login
                </a>
                <a href="{{ route('user.register') }}" class="text-sm text-gray-600 hover:text-gray-800 block">
                    Belum punya akun? <span class="font-medium">Daftar di sini</span>
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-red-100 text-sm">
            <p>&copy; {{ date('Y') }} Universitas Hasanuddin. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
