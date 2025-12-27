<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengajuan Paten - Pengajuan HKI</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('admin.partials.header', ['title' => 'Detail Pengajuan Paten'])

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="px-4 sm:px-6 lg:px-8 py-6">
                    @if(session('success'))
                        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded">
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

@php
use Illuminate\Support\Facades\Storage;
@endphp
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.submissions-paten.index') }}" class="inline-flex items-center px-5 py-3 bg-white hover:bg-gray-50 text-gray-800 hover:text-gray-900 border-2 border-gray-500 hover:border-gray-700 rounded-lg font-bold transition duration-200 shadow-md hover:shadow-lg">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Pengajuan Paten
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                <i class="fas fa-lightbulb mr-3 text-green-600"></i>Detail Pengajuan Paten #{{ $submissionPaten->id }}
                @if($submissionPaten->revisi)
                    <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        <i class="fas fa-redo mr-1"></i>Revisi
                    </span>
                @endif
            </h2>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Submission Details -->
                <div class="lg:col-span-2 space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Informasi Pengajuan</h3>
                        
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="font-medium text-gray-700">Judul Paten:</div>
                                <div class="sm:col-span-2 text-gray-900">{{ $submissionPaten->judul_paten }}</div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="font-medium text-gray-700">Kategori Paten:</div>
                                <div class="sm:col-span-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $submissionPaten->kategori_paten == 'Paten' ? 'bg-green-100 text-green-800' : 'bg-emerald-100 text-emerald-800' }}">
                                        <i class="fas fa-{{ $submissionPaten->kategori_paten == 'Paten' ? 'certificate' : 'award' }} mr-1"></i>
                                        {{ $submissionPaten->kategori_paten }}
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="font-medium text-gray-700">Status:</div>
                                <div class="sm:col-span-2">
                                    @if($submissionPaten->status == 'pending')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>Menunggu Review
                                        </span>
                                    @elseif($submissionPaten->status == 'approved')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Disetujui
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i>Ditolak
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="font-medium text-gray-700">Tanggal Pengajuan:</div>
                                <div class="sm:col-span-2 text-gray-900">{{ $submissionPaten->created_at->format('d F Y, H:i') }} WITA</div>
                            </div>

                            @if($submissionPaten->reviewed_at)
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="font-medium text-gray-700">Tanggal Review:</div>
                                <div class="sm:col-span-2 text-gray-900">{{ $submissionPaten->reviewed_at->format('d F Y, H:i') }} WITA</div>
                            </div>
                            @endif

                            @if($submissionPaten->reviewedByAdmin)
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="font-medium text-gray-700">Direview oleh:</div>
                                <div class="sm:col-span-2 text-gray-900">{{ $submissionPaten->reviewedByAdmin->name }}</div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Informasi Pengusul</h3>
                        
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="font-medium text-gray-700">Nama:</div>
                                <div class="sm:col-span-2 text-gray-900">{{ $submissionPaten->user->name }}</div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="font-medium text-gray-700">Fakultas:</div>
                                <div class="sm:col-span-2 text-gray-900">{{ $submissionPaten->user->faculty }}</div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="font-medium text-gray-700">No. WhatsApp:</div>
                                <div class="sm:col-span-2 text-gray-900">{{ $submissionPaten->user->phone_number }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Inventor Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Informasi Inventor Pertama</h3>
                        
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="font-medium text-gray-700">Nama Inventor:</div>
                                <div class="sm:col-span-2 text-gray-900">{{ $submissionPaten->creator_name ?? 'Tidak ada informasi' }}</div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="font-medium text-gray-700">No. WhatsApp Inventor:</div>
                                <div class="sm:col-span-2 text-gray-900">{{ $submissionPaten->creator_whatsapp ?? 'Tidak ada informasi' }}</div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Informasi File</h3>
                        
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="font-medium text-gray-700">Jenis File:</div>
                                <div class="sm:col-span-2 text-gray-900">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-file-word mr-1"></i>DOCX Document
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="font-medium text-gray-700">Nama File:</div>
                                <div class="sm:col-span-2 text-gray-900">{{ $submissionPaten->file_name }}</div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="font-medium text-gray-700">Ukuran File:</div>
                                <div class="sm:col-span-2 text-gray-900">{{ number_format($submissionPaten->file_size / 1024 / 1024, 2) }} MB</div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="font-medium text-gray-700">Aksi File:</div>
                                <div class="sm:col-span-2">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ Storage::disk('public')->url($submissionPaten->file_path) }}" 
                                           target="_blank"
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                            <i class="fas fa-eye mr-2"></i>Lihat DOCX
                                        </a>
                                        <a href="{{ route('admin.submissions-paten.download', $submissionPaten) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                            <i class="fas fa-download mr-2"></i>Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($submissionPaten->rejection_reason)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Alasan Penolakan</h3>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex">
                                <i class="fas fa-exclamation-triangle text-red-500 mr-3 mt-1"></i>
                                <p class="text-red-700">{{ $submissionPaten->rejection_reason }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Review Panel -->
                <div class="lg:col-span-1">
                    @if($submissionPaten->status == 'pending')
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-lg p-6 shadow-lg">
                            <h3 class="text-xl font-bold text-green-900 mb-4 flex items-center">
                                <i class="fas fa-exclamation-circle mr-2 text-green-600 animate-pulse"></i>
                                REVIEW DIPERLUKAN
                            </h3>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                <p class="text-yellow-800 font-medium text-sm">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Pengajuan paten ini membutuhkan review Anda. Silakan periksa dokumen dan berikan keputusan.
                                </p>
                            </div>
                    @else
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-gavel mr-2 text-green-600"></i>Panel Review
                            </h3>
                    @endif
                        
                        @if($submissionPaten->status == 'pending')
                            <form method="POST" action="{{ route('admin.submissions-paten.review', $submissionPaten) }}" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Keputusan Review:</label>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="radio" name="status" value="approved" required class="text-green-600 focus:ring-green-500 border-gray-300">
                                            <span class="ml-2 text-green-700 font-medium">
                                                <i class="fas fa-check-circle mr-1"></i>Setujui Pengajuan
                                            </span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="status" value="rejected" required class="text-red-600 focus:ring-red-500 border-gray-300">
                                            <span class="ml-2 text-red-700 font-medium">
                                                <i class="fas fa-times-circle mr-1"></i>Tolak Pengajuan
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-1">
                                        Catatan/Alasan Penolakan:
                                        <small class="text-gray-500">(Opsional untuk approval, wajib untuk rejection)</small>
                                    </label>
                                    <textarea 
                                        id="rejection_reason" 
                                        name="rejection_reason" 
                                        rows="4"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        placeholder="Tulis catatan atau alasan penolakan di sini..."></textarea>
                                </div>

                                <div>
                                    <label for="file_review" class="block text-sm font-medium text-gray-700 mb-1">
                                        <i class="fas fa-file-word text-blue-600 mr-1"></i>File Koreksi untuk Pengusul:
                                        <small class="text-gray-500">(Opsional - Format: DOCX)</small>
                                    </label>
                                    <div class="mt-1">
                                        <input 
                                            type="file" 
                                            id="file_review" 
                                            name="file_review" 
                                            accept=".docx,.doc"
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                        <p class="mt-1 text-xs text-gray-500">
                                            <i class="fas fa-info-circle mr-1"></i>Upload file DOCX berisi koreksi/catatan untuk pengusul (bila diperlukan)
                                        </p>
                                    </div>
                                </div>

                                <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-bold py-4 px-6 rounded-lg transition duration-200 transform hover:scale-105 shadow-lg">
                                    <i class="fas fa-gavel mr-2"></i>SUBMIT REVIEW
                                </button>
                            </form>
                        @else
                            <div class="mb-6">
                                <div class="text-center mb-4">
                                    @if($submissionPaten->status == 'approved')
                                        <i class="fas fa-check-circle text-green-500 text-3xl"></i>
                                        <h4 class="text-green-700 font-semibold mt-2">Status: Disetujui</h4>
                                    @else
                                        <i class="fas fa-times-circle text-red-500 text-3xl"></i>
                                        <h4 class="text-red-700 font-semibold mt-2">Status: Ditolak</h4>
                                    @endif
                                    
                                    <div class="text-sm text-gray-600 mt-2">
                                        Direview: {{ $submissionPaten->reviewed_at->format('d F Y, H:i') }} WITA
                                        <br>
                                        oleh: {{ $submissionPaten->reviewedByAdmin->name ?? 'Admin' }}
                                    </div>
                                </div>

                                @if($submissionPaten->rejection_reason)
                                <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                                    <p class="text-sm text-red-700"><strong>Catatan:</strong> {{ $submissionPaten->rejection_reason }}</p>
                                </div>
                                @endif

                                @if($submissionPaten->file_review_path)
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i class="fas fa-file-word text-blue-600 text-lg mr-2"></i>
                                            <div>
                                                <p class="text-xs font-medium text-blue-900">{{ $submissionPaten->file_review_name ?? 'File Koreksi.docx' }}</p>
                                                <p class="text-xs text-blue-700">File koreksi ter-upload</p>
                                            </div>
                                        </div>
                                        <a href="{{ Storage::disk('public')->url($submissionPaten->file_review_path) }}" 
                                           target="_blank"
                                           class="inline-flex items-center px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition duration-200">
                                            <i class="fas fa-eye mr-1"></i>Lihat
                                        </a>
                                    </div>
                                </div>
                                @endif

                                <div class="border-t pt-4">
                                    <p class="text-sm text-gray-600 mb-4 text-center">
                                        <i class="fas fa-edit mr-1"></i>Perlu mengubah keputusan review?
                                    </p>
                                    
                                    @if($submissionPaten->biodataPaten)
                                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                            <div class="flex items-start">
                                                <i class="fas fa-lock text-yellow-600 mt-1 mr-2"></i>
                                                <div>
                                                    <p class="text-sm text-yellow-800 font-medium">Update Review Tidak Tersedia</p>
                                                    <p class="text-xs text-yellow-700 mt-1">User sudah mengupload biodata. Untuk mencegah inkonsistensi data, perubahan review tidak diizinkan. Silakan hubungi user untuk koordinasi lebih lanjut.</p>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <form method="POST" action="{{ route('admin.submissions-paten.update-review', $submissionPaten) }}" enctype="multipart/form-data" class="space-y-4">
                                            @csrf
                                            
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-3">Ubah Keputusan:</label>
                                                <div class="space-y-2">
                                                    <label class="flex items-center">
                                                        <input type="radio" name="status" value="approved" {{ $submissionPaten->status == 'approved' ? 'checked' : '' }} required class="text-green-600 focus:ring-green-500 border-gray-300">
                                                        <span class="ml-2 text-green-700 font-medium">
                                                            <i class="fas fa-check-circle mr-1"></i>Setujui Pengajuan
                                                        </span>
                                                    </label>
                                                    <label class="flex items-center">
                                                        <input type="radio" name="status" value="rejected" {{ $submissionPaten->status == 'rejected' ? 'checked' : '' }} required class="text-red-600 focus:ring-red-500 border-gray-300">
                                                        <span class="ml-2 text-red-700 font-medium">
                                                            <i class="fas fa-times-circle mr-1"></i>Tolak Pengajuan
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div>
                                                <label for="rejection_reason_edit" class="block text-sm font-medium text-gray-700 mb-1">
                                                    Catatan/Alasan Penolakan:
                                                    <small class="text-gray-500">(Opsional untuk approval, wajib untuk rejection)</small>
                                                </label>
                                                <textarea 
                                                    id="rejection_reason_edit" 
                                                    name="rejection_reason" 
                                                    rows="3"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm"
                                                    placeholder="Tulis catatan atau alasan penolakan di sini...">{{ $submissionPaten->rejection_reason }}</textarea>
                                            </div>

                                            <div>
                                                <label for="file_review_edit" class="block text-sm font-medium text-gray-700 mb-1">
                                                    <i class="fas fa-file-word text-blue-600 mr-1"></i>File Koreksi untuk Pengusul:
                                                    <small class="text-gray-500">(Opsional - Format: DOCX)</small>
                                                </label>
                                                @if($submissionPaten->file_review_path)
                                                <div class="mb-2 p-2 bg-blue-50 border border-blue-200 rounded-lg">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center">
                                                            <i class="fas fa-file-word text-blue-600 mr-2"></i>
                                                            <span class="text-xs text-blue-900">{{ $submissionPaten->file_review_name ?? 'File Koreksi.docx' }}</span>
                                                        </div>
                                                        <a href="{{ Storage::disk('public')->url($submissionPaten->file_review_path) }}" 
                                                           target="_blank"
                                                           class="text-xs text-blue-600 hover:text-blue-800">
                                                            <i class="fas fa-eye mr-1"></i>Lihat
                                                        </a>
                                                    </div>
                                                    <p class="text-xs text-blue-700 mt-1">Upload file baru untuk mengganti file lama</p>
                                                </div>
                                                @endif
                                                <div class="mt-1">
                                                    <input 
                                                        type="file" 
                                                        id="file_review_edit" 
                                                        name="file_review" 
                                                        accept=".docx,.doc"
                                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                                    <p class="mt-1 text-xs text-gray-500">
                                                        <i class="fas fa-info-circle mr-1"></i>Upload file DOCX berisi koreksi/catatan untuk pengusul (bila diperlukan)
                                                    </p>
                                                </div>
                                            </div>

                                            <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
                                                <i class="fas fa-edit mr-2"></i>UPDATE REVIEW
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-gray-50 rounded-lg p-6 mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-tools mr-2 text-green-600"></i>Aksi Cepat
                        </h3>
                        
                        <div class="space-y-3">
                            <a href="{{ Storage::disk('public')->url($submissionPaten->file_path) }}" 
                               target="_blank"
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                <i class="fas fa-external-link-alt mr-2"></i>Buka DOCX di Tab Baru
                            </a>
                            
                            <a href="{{ generateWhatsAppUrl($submissionPaten->user->phone_number, $submissionPaten->user->country_code ?? '+62', 'Halo ' . $submissionPaten->user->name . ', terkait pengajuan Paten #' . $submissionPaten->id) }}" 
                               target="_blank"
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                <i class="fab fa-whatsapp mr-2"></i>Hubungi Pengusul
                            </a>
                            
                            @if($submissionPaten->creator_whatsapp)
                            <a href="{{ generateWhatsAppUrl($submissionPaten->creator_whatsapp, $submissionPaten->creator_country_code ?? '+62', 'Halo ' . ($submissionPaten->creator_name ?? 'Inventor') . ', terkait pengajuan Paten #' . $submissionPaten->id) }}" 
                               target="_blank"
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                <i class="fab fa-whatsapp mr-2"></i>Hubungi Inventor
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                </div>
            </main>
        </div>
    </div>

    @include('admin.partials.sidebar-script')
    
    <script>
    // Auto-require rejection reason when reject is selected
    document.addEventListener('DOMContentLoaded', function() {
        // Handle both forms - pending review and edit review
        function setupFormValidation(form) {
            if (!form) return;
            
            const rejectedRadio = form.querySelector('input[value="rejected"]');
            const approvedRadio = form.querySelector('input[value="approved"]');
            const rejectionReasonTextarea = form.querySelector('textarea[name="rejection_reason"]');
            
            if (rejectedRadio && rejectionReasonTextarea) {
                rejectedRadio.addEventListener('change', function() {
                    if (this.checked) {
                        rejectionReasonTextarea.required = true;
                        rejectionReasonTextarea.focus();
                    }
                });
                
                if (approvedRadio) {
                    approvedRadio.addEventListener('change', function() {
                        if (this.checked) {
                            rejectionReasonTextarea.required = false;
                        }
                    });
                }
            }
            
            // Add confirmation dialog before form submission
            form.addEventListener('submit', function(e) {
                // Check if reject is selected
                if (rejectedRadio && rejectedRadio.checked) {
                    // Validate rejection reason is filled
                    if (!rejectionReasonTextarea.value.trim()) {
                        e.preventDefault();
                        alert('⚠️ Alasan penolakan harus diisi!');
                        rejectionReasonTextarea.focus();
                        rejectionReasonTextarea.style.borderColor = '#ef4444';
                        rejectionReasonTextarea.style.borderWidth = '2px';
                        
                        setTimeout(function() {
                            rejectionReasonTextarea.style.borderColor = '';
                            rejectionReasonTextarea.style.borderWidth = '';
                        }, 3000);
                        
                        return false;
                    }
                    
                    // Show confirmation for rejection
                    e.preventDefault();
                    const confirmReject = confirm(
                        '🚫 KONFIRMASI PENOLAKAN PENGAJUAN PATEN\n\n' +
                        '⚠️ Apakah Anda yakin ingin MENOLAK pengajuan paten ini?\n\n' +
                        'Pastikan:\n' +
                        '✓ Alasan penolakan sudah jelas dan spesifik\n' +
                        '✓ User dapat memahami kesalahan dan memperbaikinya\n' +
                        '✓ Dokumen sudah diperiksa dengan teliti\n\n' +
                        'Klik OK untuk melanjutkan penolakan, atau Cancel untuk kembali.'
                    );
                    
                    if (confirmReject) {
                        form.submit();
                    }
                    return false;
                }
                
                // Check if approve is selected
                if (approvedRadio && approvedRadio.checked) {
                    e.preventDefault();
                    const confirmApprove = confirm(
                        '✅ KONFIRMASI PERSETUJUAN PENGAJUAN PATEN\n\n' +
                        '⚠️ Apakah Anda yakin ingin MENYETUJUI pengajuan paten ini?\n\n' +
                        'Pastikan:\n' +
                        '✓ Dokumen PDF sudah diperiksa dengan teliti\n' +
                        '✓ Judul paten dan informasi sudah sesuai\n' +
                        '✓ Kategori paten (Paten/Paten Sederhana) sudah benar\n' +
                        '✓ Pengajuan siap diproses ke tahap selanjutnya\n\n' +
                        'Setelah disetujui, user dapat melanjutkan ke proses biodata paten.\n\n' +
                        'Klik OK untuk menyetujui, atau Cancel untuk kembali memeriksa.'
                    );
                    
                    if (confirmApprove) {
                        form.submit();
                    }
                    return false;
                }
            });
        }
        
        // Setup validation for all forms on the page
        const forms = document.querySelectorAll('form[action*="review"]');
        forms.forEach(form => {
            setupFormValidation(form);
        });
    });
    </script>
</body>
</html>
