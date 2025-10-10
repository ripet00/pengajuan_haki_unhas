<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User - Pengajuan HAKI</title>
    @filamentStyles
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-semibold text-gray-900">Dashboard User - Pengajuan HAKI</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('user.logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Selamat Datang!</h2>
                        <p class="text-gray-600 mb-6">
                            Anda berhasil login ke sistem pengajuan HAKI. Halaman ini akan dikembangkan lebih lanjut untuk fitur pengajuan HAKI.
                        </p>
                        
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-blue-800 mb-2">Informasi Akun</h3>
                            <div class="text-sm text-blue-700 space-y-1">
                                <p><span class="font-medium">Nama:</span> {{ Auth::user()->name }}</p>
                                <p><span class="font-medium">No. WhatsApp:</span> {{ Auth::user()->phone_number }}</p>
                                <p><span class="font-medium">Fakultas:</span> {{ Auth::user()->faculty }}</p>
                                <p><span class="font-medium">Status:</span> 
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ Auth::user()->status === 'verified' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst(Auth::user()->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @filamentScripts
</body>
</html>