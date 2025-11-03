<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Jenis Karya - Pengajuan HKI</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('admin.partials.header', ['title' => 'Edit Jenis Karya'])

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="px-4 sm:px-6 lg:px-8 py-6">
                    @if($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
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

                    <!-- Header Section -->
                    <div class="mb-6">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                <div class="mb-4 sm:mb-0">
                                    <h1 class="text-2xl font-bold text-gray-900 mb-2">Edit Jenis Karya</h1>
                                    <p class="text-gray-600">Edit informasi jenis karya: <span class="font-semibold">{{ $jenisKarya->nama }}</span></p>
                                </div>
                                <div>
                                    <a href="{{ route('admin.jenis-karyas.index') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200">
                                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Info Alert -->
                    <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    <strong>Informasi:</strong> Jenis karya ini digunakan dalam <strong>{{ $jenisKarya->submissions()->count() }}</strong> submission(s).
                                    @if($jenisKarya->submissions()->count() > 0)
                                        Perubahan nama akan mempengaruhi semua submission yang menggunakan jenis karya ini.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Main Form Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-edit text-blue-600 mr-2"></i>
                                Form Edit Jenis Karya
                            </h3>
                        </div>

                        <div class="p-6">
                            <form action="{{ route('admin.jenis-karyas.update', $jenisKarya) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-6">
                                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-file-alt text-blue-600 mr-2"></i>
                                        Nama Jenis Karya
                                    </label>
                                    <input type="text" 
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('nama') border-red-500 @enderror" 
                                           id="nama" 
                                           name="nama" 
                                           value="{{ old('nama', $jenisKarya->nama) }}" 
                                           placeholder="Masukkan nama jenis karya"
                                           required>
                                    @error('nama')
                                        <p class="mt-2 text-sm text-red-600">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                    <p class="mt-2 text-sm text-gray-600">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Nama jenis karya harus unik dan deskriptif
                                    </p>
                                </div>

                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        <i class="fas fa-toggle-on text-green-600 mr-2"></i>
                                        Status
                                    </label>
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                        <!-- Hidden input to ensure a value is always sent -->
                                        <input type="hidden" name="is_active" value="0">
                                        <div class="flex items-center">
                                            <input id="is_active" 
                                                   name="is_active" 
                                                   type="checkbox" 
                                                   value="1"
                                                   {{ old('is_active', $jenisKarya->is_active) ? 'checked' : '' }} 
                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                            <label for="is_active" class="ml-3 block text-sm font-medium text-gray-700">
                                                Jenis karya aktif
                                            </label>
                                        </div>
                                        <p class="mt-2 text-sm text-gray-600">
                                            <i class="fas fa-lightbulb mr-1"></i>
                                            Jenis karya nonaktif tidak akan ditampilkan dalam form submission
                                        </p>
                                    </div>
                                </div>

                                @if($jenisKarya->submissions()->count() > 0)
                                    <div class="mb-6">
                                        <label class="block text-sm font-medium text-gray-700 mb-3">
                                            <i class="fas fa-list text-blue-600 mr-2"></i>
                                            Submissions yang Menggunakan Jenis Karya Ini
                                        </label>
                                        <div class="bg-blue-50 rounded-lg border border-blue-200">
                                            <div class="p-4 space-y-3">
                                                @foreach($jenisKarya->submissions()->latest()->take(5)->get() as $submission)
                                                    <div class="flex items-center justify-between bg-white rounded-md p-3 shadow-sm">
                                                        <div>
                                                            <p class="text-sm font-medium text-gray-900">{{ $submission->title }}</p>
                                                            <p class="text-xs text-gray-500">
                                                                oleh {{ $submission->user->name }} - {{ $submission->created_at->format('d/m/Y') }}
                                                            </p>
                                                        </div>
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                            {{ $submission->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                                               ($submission->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                            {{ ucfirst($submission->status) }}
                                                        </span>
                                                    </div>
                                                @endforeach
                                                @if($jenisKarya->submissions()->count() > 5)
                                                    <div class="text-center py-2">
                                                        <span class="text-sm text-gray-500">
                                                            Dan {{ $jenisKarya->submissions()->count() - 5 }} submission lainnya...
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="border-t border-gray-200 pt-6">
                                    <div class="flex justify-end space-x-3">
                                        <a href="{{ route('admin.jenis-karyas.index') }}" 
                                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200">
                                            <i class="fas fa-times mr-2"></i>Batal
                                        </a>
                                        <button type="submit" 
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                                            <i class="fas fa-save mr-2"></i>Update Jenis Karya
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>

    @include('admin.partials.sidebar-script')
</body>
</html>