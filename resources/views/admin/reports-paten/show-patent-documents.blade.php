<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumen Paten PDF - {{ $biodataPaten->submissionPaten->judul_paten }}</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('admin.partials.header', ['title' => 'Dokumen Paten PDF'])

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="px-4 sm:px-6 lg:px-8 py-6">
                    <!-- Back Button -->
                    <div class="mb-4">
                        <a href="{{ route('admin.reports-paten.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                    </div>

                    <!-- Header Info -->
                    <div class="bg-white border-2 border-gray-300 rounded p-4 mb-4">
                        <h1 class="text-xl font-bold mb-3">Dokumen Paten PDF</h1>
                        
                        <table class="w-full text-sm">
                            <tr>
                                <td class="py-1 font-semibold" style="width: 150px">ID Biodata</td>
                                <td class="py-1">: #{{ $biodataPaten->id }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 font-semibold">Judul Paten</td>
                                <td class="py-1">: {{ $biodataPaten->submissionPaten->judul_paten }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 font-semibold">Pengaju</td>
                                <td class="py-1">: {{ $biodataPaten->user->name }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 font-semibold">Kategori</td>
                                <td class="py-1">: {{ $biodataPaten->submissionPaten->kategori_paten }}</td>
                            </tr>
                            @if($biodataPaten->patent_documents_uploaded_at)
                            <tr>
                                <td class="py-1 font-semibold">Terakhir Update</td>
                                <td class="py-1">: {{ $biodataPaten->patent_documents_uploaded_at->translatedFormat('d F Y H:i') }} WITA</td>
                            </tr>
                            @endif
                        </table>
                    </div>

                    <!-- Documents Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- 1. Deskripsi PDF -->
                        <div class="bg-white border-2 {{ $biodataPaten->deskripsi_pdf ? 'border-green-600' : 'border-gray-300' }} rounded p-4">
                            <div class="flex justify-between items-center mb-3 pb-2 border-b-2 {{ $biodataPaten->deskripsi_pdf ? 'border-green-600' : 'border-gray-300' }}">
                                <div>
                                    <h3 class="font-bold text-lg">1. Deskripsi</h3>
                                    <span class="text-xs bg-red-600 text-white px-2 py-1 rounded">WAJIB</span>
                                </div>
                                @if($biodataPaten->deskripsi_pdf)
                                    <i class="fas fa-check-circle text-3xl text-green-600"></i>
                                @else
                                    <i class="fas fa-times-circle text-3xl text-gray-400"></i>
                                @endif
                            </div>
                            @if($biodataPaten->deskripsi_pdf)
                                <div class="mb-3">
                                    <p class="text-sm font-semibold mb-1">File:</p>
                                    <p class="text-xs text-gray-600 break-all">{{ basename($biodataPaten->deskripsi_pdf) }}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <a href="{{ route('admin.reports-paten.view-patent-document', [$biodataPaten, 'deskripsi']) }}" 
                                       target="_blank"
                                       class="block text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded">
                                        <i class="fas fa-eye mr-2"></i>Lihat
                                    </a>
                                    <a href="{{ route('admin.reports-paten.download-patent-document', [$biodataPaten, 'deskripsi']) }}" 
                                       class="block text-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded">
                                        <i class="fas fa-download mr-2"></i>Download
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-6 text-gray-400">
                                    <i class="fas fa-file-pdf text-4xl mb-2"></i>
                                    <p class="text-sm">Belum diupload</p>
                                </div>
                            @endif
                        </div>

                        <!-- 2. Klaim PDF -->
                        <div class="bg-white border-2 {{ $biodataPaten->klaim_pdf ? 'border-green-600' : 'border-gray-300' }} rounded p-4">
                            <div class="flex justify-between items-center mb-3 pb-2 border-b-2 {{ $biodataPaten->klaim_pdf ? 'border-green-600' : 'border-gray-300' }}">
                                <div>
                                    <h3 class="font-bold text-lg">2. Klaim</h3>
                                    <span class="text-xs bg-red-600 text-white px-2 py-1 rounded">WAJIB</span>
                                </div>
                                @if($biodataPaten->klaim_pdf)
                                    <i class="fas fa-check-circle text-3xl text-green-600"></i>
                                @else
                                    <i class="fas fa-times-circle text-3xl text-gray-400"></i>
                                @endif
                            </div>
                            @if($biodataPaten->klaim_pdf)
                                <div class="mb-3">
                                    <p class="text-sm font-semibold mb-1">File:</p>
                                    <p class="text-xs text-gray-600 break-all">{{ basename($biodataPaten->klaim_pdf) }}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <a href="{{ route('admin.reports-paten.view-patent-document', [$biodataPaten, 'klaim']) }}" 
                                       target="_blank"
                                       class="block text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded">
                                        <i class="fas fa-eye mr-2"></i>Lihat
                                    </a>
                                    <a href="{{ route('admin.reports-paten.download-patent-document', [$biodataPaten, 'klaim']) }}" 
                                       class="block text-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded">
                                        <i class="fas fa-download mr-2"></i>Download
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-6 text-gray-400">
                                    <i class="fas fa-file-pdf text-4xl mb-2"></i>
                                    <p class="text-sm">Belum diupload</p>
                                </div>
                            @endif
                        </div>

                        <!-- 3. Abstrak PDF -->
                        <div class="bg-white border-2 {{ $biodataPaten->abstrak_pdf ? 'border-green-600' : 'border-gray-300' }} rounded p-4">
                            <div class="flex justify-between items-center mb-3 pb-2 border-b-2 {{ $biodataPaten->abstrak_pdf ? 'border-green-600' : 'border-gray-300' }}">
                                <div>
                                    <h3 class="font-bold text-lg">3. Abstrak</h3>
                                    <span class="text-xs bg-red-600 text-white px-2 py-1 rounded">WAJIB</span>
                                </div>
                                @if($biodataPaten->abstrak_pdf)
                                    <i class="fas fa-check-circle text-3xl text-green-600"></i>
                                @else
                                    <i class="fas fa-times-circle text-3xl text-gray-400"></i>
                                @endif
                            </div>
                            @if($biodataPaten->abstrak_pdf)
                                <div class="mb-3">
                                    <p class="text-sm font-semibold mb-1">File:</p>
                                    <p class="text-xs text-gray-600 break-all">{{ basename($biodataPaten->abstrak_pdf) }}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <a href="{{ route('admin.reports-paten.view-patent-document', [$biodataPaten, 'abstrak']) }}" 
                                       target="_blank"
                                       class="block text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded">
                                        <i class="fas fa-eye mr-2"></i>Lihat
                                    </a>
                                    <a href="{{ route('admin.reports-paten.download-patent-document', [$biodataPaten, 'abstrak']) }}" 
                                       class="block text-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded">
                                        <i class="fas fa-download mr-2"></i>Download
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-6 text-gray-400">
                                    <i class="fas fa-file-pdf text-4xl mb-2"></i>
                                    <p class="text-sm">Belum diupload</p>
                                </div>
                            @endif
                        </div>

                        <!-- 4. Gambar PDF (Optional) -->
                        <div class="bg-white border-2 {{ $biodataPaten->gambar_pdf ? 'border-blue-600' : 'border-gray-300' }} rounded p-4">
                            <div class="flex justify-between items-center mb-3 pb-2 border-b-2 {{ $biodataPaten->gambar_pdf ? 'border-blue-600' : 'border-gray-300' }}">
                                <div>
                                    <h3 class="font-bold text-lg">4. Gambar</h3>
                                    <span class="text-xs bg-blue-600 text-white px-2 py-1 rounded">OPSIONAL</span>
                                </div>
                                @if($biodataPaten->gambar_pdf)
                                    <i class="fas fa-check-circle text-3xl text-blue-600"></i>
                                @else
                                    <i class="fas fa-minus-circle text-3xl text-gray-400"></i>
                                @endif
                            </div>
                            @if($biodataPaten->gambar_pdf)
                                <div class="mb-3">
                                    <p class="text-sm font-semibold mb-1">File:</p>
                                    <p class="text-xs text-gray-600 break-all">{{ basename($biodataPaten->gambar_pdf) }}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <a href="{{ route('admin.reports-paten.view-patent-document', [$biodataPaten, 'gambar']) }}" 
                                       target="_blank"
                                       class="block text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded">
                                        <i class="fas fa-eye mr-2"></i>Lihat
                                    </a>
                                    <a href="{{ route('admin.reports-paten.download-patent-document', [$biodataPaten, 'gambar']) }}" 
                                       class="block text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded">
                                        <i class="fas fa-download mr-2"></i>Download
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-6 text-gray-400">
                                    <i class="fas fa-file-pdf text-4xl mb-2"></i>
                                    <p class="text-sm">Tidak ada file</p>
                                    <p class="text-xs">(Opsional)</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    @include('admin.partials.sidebar-script')
</body>
</html>
