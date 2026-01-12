<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Substansi Pengajuan Paten - Pengajuan HKI</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('admin.partials.header', ['title' => 'Review Substansi Pengajuan Paten'])

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
    <div class="flex justify-between items-center">
        <a href="{{ route('admin.pendamping-paten.index') }}" class="inline-flex items-center px-5 py-3 bg-white hover:bg-gray-50 text-gray-800 hover:text-gray-900 border-2 border-gray-500 hover:border-gray-700 rounded-lg font-bold transition duration-200 shadow-md hover:shadow-lg">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Tugas
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-indigo-50 border-b border-purple-200">
            <h2 class="text-xl font-semibold text-purple-900 flex items-center">
                <i class="fas fa-microscope mr-3 text-purple-600"></i>Review Substansi Pengajuan Paten #{{ $submissionPaten->id }}
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
                                    @if($submissionPaten->status == \App\Models\SubmissionPaten::STATUS_PENDING_SUBSTANCE_REVIEW)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-hourglass-half mr-1"></i>Menunggu Review Substansi
                                        </span>
                                    @elseif($submissionPaten->status == \App\Models\SubmissionPaten::STATUS_APPROVED_SUBSTANCE)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-double mr-1"></i>Substansi Disetujui
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Substansi Ditolak
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="font-medium text-gray-700">Ditugaskan:</div>
                                <div class="sm:col-span-2 text-gray-900">{{ $submissionPaten->assigned_at ? $submissionPaten->assigned_at->format('d F Y, H:i') . ' WITA' : '-' }}</div>
                            </div>

                            @if($submissionPaten->substance_reviewed_at)
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="font-medium text-gray-700">Direview:</div>
                                <div class="sm:col-span-2 text-gray-900">{{ $submissionPaten->substance_reviewed_at->format('d F Y, H:i') }} WITA</div>
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
                                        <a href="{{ route('admin.pendamping-paten.download', $submissionPaten) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                            <i class="fas fa-download mr-2"></i>Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($submissionPaten->substance_review_notes)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Catatan Review Substansi</h3>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex">
                                <i class="fas fa-comment-dots text-blue-500 mr-3 mt-1"></i>
                                <p class="text-blue-800">{{ $submissionPaten->substance_review_notes }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Review Panel -->
                <div class="lg:col-span-1">
                    @if($submissionPaten->status == \App\Models\SubmissionPaten::STATUS_PENDING_SUBSTANCE_REVIEW)
                        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 border-2 border-purple-200 rounded-lg p-6 shadow-lg">
                            <h3 class="text-xl font-bold text-purple-900 mb-4 flex items-center">
                                <i class="fas fa-exclamation-circle mr-2 text-purple-600 animate-pulse"></i>
                                REVIEW DIPERLUKAN
                            </h3>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                <p class="text-yellow-800 font-medium text-sm">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Pengajuan paten ini membutuhkan review substansi. Silakan periksa dokumen dan berikan keputusan.
                                </p>
                            </div>
                    @else
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-gavel mr-2 text-purple-600"></i>Panel Review Substansi
                            </h3>
                    @endif
                        
                        @if($submissionPaten->status == \App\Models\SubmissionPaten::STATUS_PENDING_SUBSTANCE_REVIEW)
                            <form method="POST" action="{{ route('admin.pendamping-paten.review', $submissionPaten) }}" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Keputusan Review:</label>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="radio" name="status" value="approved_substance" required class="text-green-600 focus:ring-green-500 border-gray-300">
                                            <span class="ml-2 text-green-700 font-medium">
                                                <i class="fas fa-check-double mr-1"></i>Setujui Substansi
                                            </span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="status" value="rejected_substance_review" required class="text-red-600 focus:ring-red-500 border-gray-300">
                                            <span class="ml-2 text-red-700 font-medium">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>Tolak Substansi
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label for="substance_review_notes" class="block text-sm font-medium text-gray-700 mb-1">
                                        Catatan Review Substansi: <span class="text-red-500">*</span>
                                        <small class="text-gray-500">(Wajib diisi)</small>
                                    </label>
                                    <textarea 
                                        id="substance_review_notes" 
                                        name="substance_review_notes" 
                                        rows="4"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                        placeholder="Tulis catatan review substansi, saran perbaikan, atau komentar di sini..."></textarea>
                                </div>

                                <div>
                                    <label for="substance_review_file" class="block text-sm font-medium text-gray-700 mb-1">
                                        <i class="fas fa-file-word text-blue-600 mr-1"></i>File Review Substansi:
                                        <small class="text-gray-500">(Opsional - Format: DOCX/PDF)</small>
                                    </label>
                                    <div class="mt-1">
                                        <input 
                                            type="file" 
                                            id="substance_review_file" 
                                            name="substance_review_file" 
                                            accept=".docx,.doc,.pdf"
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 border border-gray-300 rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                        <p class="mt-1 text-xs text-gray-500">
                                            <i class="fas fa-info-circle mr-1"></i>Upload file berisi review substansi detail (bila diperlukan)
                                        </p>
                                    </div>
                                </div>

                                <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-indigo-700 hover:from-purple-700 hover:to-indigo-800 text-white font-bold py-4 px-6 rounded-lg transition duration-200 transform hover:scale-105 shadow-lg">
                                    <i class="fas fa-gavel mr-2"></i>SUBMIT REVIEW
                                </button>
                            </form>
                        @else
                            <div class="mb-6">
                                <div class="text-center mb-4">
                                    @if($submissionPaten->status == \App\Models\SubmissionPaten::STATUS_APPROVED_SUBSTANCE)
                                        <i class="fas fa-check-double text-green-500 text-3xl"></i>
                                        <h4 class="text-green-700 font-semibold mt-2">Status: Substansi Disetujui</h4>
                                    @else
                                        <i class="fas fa-exclamation-triangle text-red-500 text-3xl"></i>
                                        <h4 class="text-red-700 font-semibold mt-2">Status: Substansi Ditolak</h4>
                                    @endif
                                    
                                    <div class="text-sm text-gray-600 mt-2">
                                        Direview: {{ $submissionPaten->substance_reviewed_at ? $submissionPaten->substance_reviewed_at->format('d F Y, H:i') . ' WITA' : '-' }}
                                    </div>
                                </div>

                                @if($submissionPaten->substance_review_file)
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i class="fas fa-file-alt text-blue-600 text-lg mr-2"></i>
                                            <div>
                                                <p class="text-xs font-medium text-blue-900">File Review Substansi</p>
                                                <p class="text-xs text-blue-700">File ter-upload</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('admin.pendamping-paten.download-review', $submissionPaten) }}" 
                                           class="inline-flex items-center px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition duration-200">
                                            <i class="fas fa-download mr-1"></i>Download
                                        </a>
                                    </div>
                                </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-gray-50 rounded-lg p-6 mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-tools mr-2 text-purple-600"></i>Aksi Cepat
                        </h3>
                        
                        <div class="space-y-3">
                            <a href="{{ Storage::disk('public')->url($submissionPaten->file_path) }}" 
                               target="_blank"
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                <i class="fas fa-external-link-alt mr-2"></i>Buka DOCX di Tab Baru
                            </a>
                            
                            <a href="{{ generateWhatsAppUrl($submissionPaten->user->phone_number, $submissionPaten->user->country_code ?? '+62', 'Halo ' . $submissionPaten->user->name . ', terkait review substansi Paten #' . $submissionPaten->id) }}" 
                               target="_blank"
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                <i class="fab fa-whatsapp mr-2"></i>Hubungi Pengusul
                            </a>
                            
                            @if($submissionPaten->creator_whatsapp)
                            <a href="{{ generateWhatsAppUrl($submissionPaten->creator_whatsapp, $submissionPaten->creator_country_code ?? '+62', 'Halo ' . ($submissionPaten->creator_name ?? 'Inventor') . ', terkait review substansi Paten #' . $submissionPaten->id) }}" 
                               target="_blank"
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                <i class="fab fa-whatsapp mr-2"></i>Hubungi Inventor
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
                </div>
            </main>
        </div>
    </div>

    @include('admin.partials.sidebar-script')
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form[action*="review"]');
        if (!form) return;
        
        const rejectedRadio = document.querySelector('input[value="rejected_substance_review"]');
        const approvedRadio = document.querySelector('input[value="approved_substance"]');
        
        // Add confirmation dialog before form submission
        form.addEventListener('submit', function(e) {
            // Check if reject is selected
            if (rejectedRadio && rejectedRadio.checked) {
                e.preventDefault();
                const confirmReject = confirm(
                    '🚫 KONFIRMASI PENOLAKAN SUBSTANSI PATEN\n\n' +
                    '⚠️ Apakah Anda yakin ingin MENOLAK substansi pengajuan paten ini?\n\n' +
                    'Pastikan:\n' +
                    '✓ Catatan review sudah jelas dan spesifik\n' +
                    '✓ User dapat memahami kekurangan substansi dan memperbaikinya\n' +
                    '✓ Dokumen sudah diperiksa substansinya dengan teliti\n\n' +
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
                    '✅ KONFIRMASI PERSETUJUAN SUBSTANSI PATEN\n\n' +
                    '⚠️ Apakah Anda yakin ingin MENYETUJUI substansi pengajuan paten ini?\n\n' +
                    'Pastikan:\n' +
                    '✓ Substansi dokumen sudah diperiksa dengan teliti\n' +
                    '✓ Konten paten memenuhi standar\n' +
                    '✓ Catatan review sudah diisi\n' +
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
    });
    </script>
</body>
</html>
