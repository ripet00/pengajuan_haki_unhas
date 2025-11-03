<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jenis Karya - Pengajuan HKI</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('admin.partials.header', ['title' => 'Tambah Jenis Karya'])

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
                                    <h1 class="text-2xl font-bold text-gray-900 mb-2">Tambah Jenis Karya</h1>
                                    <p class="text-gray-600">Tambahkan jenis karya baru untuk submissions</p>
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

                    <!-- Main Form Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-plus-circle text-blue-600 mr-2"></i>
                                Form Tambah Jenis Karya
                            </h3>
                        </div>

                        <div class="p-6">
                            <form action="{{ route('admin.jenis-karyas.store') }}" method="POST">
                                @csrf

                                <div class="mb-6">
                                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-file-alt text-blue-600 mr-2"></i>
                                        Nama Jenis Karya
                                    </label>
                                    <input type="text" 
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('nama') border-red-300 @enderror" 
                                           id="nama" 
                                           name="nama" 
                                           value="{{ old('nama') }}" 
                                           placeholder="Masukkan nama jenis karya"
                                           required>
                                    @error('nama')
                                        <p class="mt-2 text-sm text-red-600">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                    <p class="mt-2 text-sm text-gray-600">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Contoh: Buku, Artikel, Program Komputer, dll.
                                    </p>
                                </div>

                                <!-- Example buttons -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                                        Contoh Jenis Karya
                                    </label>
                                    <div class="flex flex-wrap gap-2">
                                        @php
                                            $examples = [
                                                'Buku', 'Buku Saku', 'Buku Panduan/Petunjuk', 'Modul', 'Booklet',
                                                'Karya tulis', 'Artikel', 'Disertasi', 'Flyer', 'Poster',
                                                'Leaflet', 'Alat peraga', 'Program komputer', 'Karya rekaman video'
                                            ];
                                        @endphp
                                        @foreach($examples as $example)
                                            <button type="button" 
                                                    class="inline-flex items-center px-3 py-1 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 example-btn" 
                                                    onclick="setExample('{{ $example }}')">
                                                {{ $example }}
                                            </button>
                                        @endforeach
                                    </div>
                                    <p class="mt-2 text-sm text-gray-600">
                                        <i class="fas fa-hand-pointer mr-1"></i>
                                        Klik salah satu contoh untuk mengisi form
                                    </p>
                                </div>

                                <div class="border-t border-gray-200 pt-6">
                                    <div class="flex justify-end space-x-3">
                                        <a href="{{ route('admin.jenis-karyas.index') }}" 
                                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200">
                                            <i class="fas fa-times mr-2"></i>Batal
                                        </a>
                                        <button type="submit" 
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                                            <i class="fas fa-save mr-2"></i>Simpan Jenis Karya
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

<script>
function setExample(example) {
    document.getElementById('nama').value = example;
    document.getElementById('nama').focus();
    
    // Add visual feedback
    const input = document.getElementById('nama');
    input.classList.add('border-success');
    setTimeout(() => {
        input.classList.remove('border-success');
    }, 1500);
}

// Add hover effects to example buttons
document.addEventListener('DOMContentLoaded', function() {
    const exampleBtns = document.querySelectorAll('.example-btn');
    exampleBtns.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>

                </div>
            </main>
        </div>
    </div>

    @include('admin.partials.sidebar-script')
</body>
</html>