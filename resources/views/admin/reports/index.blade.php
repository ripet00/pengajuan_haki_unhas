<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Tracking HKI - Admin Panel</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('admin.partials.header', ['title' => 'Laporan Tracking HKI'])

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="px-4 sm:px-6 lg:px-8 py-6">
                    <!-- Success/Error Messages -->
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

                    @if(session('error'))
                        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
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

                    <div class="space-y-6">
                        <!-- Page Header -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">
                                <i class="fas fa-clipboard-check mr-3 text-blue-600"></i>
                                Laporan & Tracking HKI
                            </h2>
                            <p class="text-gray-600">
                                Monitor status penyetoran berkas dan penerbitan sertifikat HKI yang telah disetujui
                            </p>
                        </div>

                        <!-- Statistics Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-4 text-white">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-blue-100 text-xs font-medium uppercase">Total Approved</p>
                                        <h3 class="text-3xl font-bold mt-1">{{ $totalApproved }}</h3>
                                    </div>
                                    <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                                        <i class="fas fa-check-double text-2xl"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-4 text-white">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-yellow-100 text-xs font-medium uppercase">Menunggu Berkas</p>
                                        <h3 class="text-3xl font-bold mt-1">{{ $documentPending }}</h3>
                                    </div>
                                    <div class="bg-yellow-400 bg-opacity-30 rounded-full p-3">
                                        <i class="fas fa-clock text-2xl"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-4 text-white">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-green-100 text-xs font-medium uppercase">Berkas Disetor</p>
                                        <h3 class="text-3xl font-bold mt-1">{{ $documentSubmitted }}</h3>
                                    </div>
                                    <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                                        <i class="fas fa-file-check text-2xl"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-4 text-white">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-purple-100 text-xs font-medium uppercase">Sertifikat Terbit</p>
                                        <h3 class="text-3xl font-bold mt-1">{{ $certificateIssued }}</h3>
                                    </div>
                                    <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
                                        <i class="fas fa-certificate text-2xl"></i>
                                    </div>
                                </div>
                            </div>

                            @if($documentOverdue > 0)
                            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg p-4 text-white">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-red-100 text-xs font-medium uppercase">Berkas Terlambat</p>
                                        <h3 class="text-3xl font-bold mt-1">{{ $documentOverdue }}</h3>
                                        <p class="text-red-100 text-xs mt-1">Lewat 1 bulan</p>
                                    </div>
                                    <div class="bg-red-400 bg-opacity-30 rounded-full p-3">
                                        <i class="fas fa-exclamation-triangle text-2xl"></i>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($certificateOverdue > 0)
                            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-4 text-white">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-orange-100 text-xs font-medium uppercase">Sertifikat Terlambat</p>
                                        <h3 class="text-3xl font-bold mt-1">{{ $certificateOverdue }}</h3>
                                        <p class="text-orange-100 text-xs mt-1">Lewat 2 minggu</p>
                                    </div>
                                    <div class="bg-orange-400 bg-opacity-30 rounded-full p-3">
                                        <i class="fas fa-hourglass-end text-2xl"></i>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Filters & Search -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <form method="GET" action="{{ route('admin.reports.index') }}" class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                                        <input type="text" 
                                               id="search" 
                                               name="search" 
                                               value="{{ request('search') }}"
                                               placeholder="Nama user, judul karya, atau nomor HP..."
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>

                                    <div>
                                        <label for="tracking_status" class="block text-sm font-medium text-gray-700 mb-2">Status Tracking</label>
                                        <select id="tracking_status" 
                                                name="tracking_status"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <option value="">Semua Status</option>
                                            <option value="document_pending" {{ request('tracking_status') == 'document_pending' ? 'selected' : '' }}>Menunggu Berkas</option>
                                            <option value="document_overdue" {{ request('tracking_status') == 'document_overdue' ? 'selected' : '' }}>Berkas Terlambat</option>
                                            <option value="document_submitted" {{ request('tracking_status') == 'document_submitted' ? 'selected' : '' }}>Berkas Disetor (Proses Sertifikat)</option>
                                            <option value="certificate_overdue" {{ request('tracking_status') == 'certificate_overdue' ? 'selected' : '' }}>Sertifikat Terlambat</option>
                                            <option value="certificate_issued" {{ request('tracking_status') == 'certificate_issued' ? 'selected' : '' }}>Sertifikat Terbit</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="flex justify-end space-x-3">
                                    <a href="{{ route('admin.reports.index') }}" 
                                       class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg transition duration-200">
                                        <i class="fas fa-redo mr-2"></i>Reset
                                    </a>
                                    <button type="submit" 
                                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200">
                                        <i class="fas fa-search mr-2"></i>Cari
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Active Filters -->
                        @if(request('search') || request('tracking_status'))
                            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-filter text-blue-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-blue-700">
                                                Filter aktif: 
                                                @if(request('search'))
                                                    <span class="font-medium">Pencarian: "{{ request('search') }}"</span>
                                                    @if(request('tracking_status'))
                                                        , 
                                                    @endif
                                                @endif
                                                @if(request('tracking_status'))
                                                    <span class="font-medium">Status: {{ ucfirst(str_replace('_', ' ', request('tracking_status'))) }}</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.reports.index') }}" 
                                           class="inline-flex items-center px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm font-medium rounded-lg transition duration-200">
                                            <i class="fas fa-times mr-1"></i>Hapus Filter
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Biodata List -->
                        @if($biodatas->count() > 0)
                            <div class="space-y-4">
                                @foreach($biodatas as $biodata)
                                    <div class="bg-white rounded-lg shadow-lg overflow-hidden border-l-4 {{ $biodata->certificate_issued ? 'border-purple-500' : ($biodata->document_submitted ? 'border-green-500' : ($biodata->isDocumentOverdue() ? 'border-red-500' : 'border-yellow-500')) }}">
                                        <div class="p-6">
                                            <div class="space-y-4">
                                                <!-- Header Section -->
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
                                                        <h3 class="text-xl font-bold text-gray-900 mb-2">
                                                            {{ $biodata->submission->title }}
                                                        </h3>
                                                        <div class="flex flex-wrap items-center gap-2 text-sm text-gray-600">
                                                            <span class="inline-flex items-center">
                                                                <i class="fas fa-user mr-1 text-blue-500"></i>
                                                                {{ $biodata->user->name }}
                                                            </span>
                                                            <span>•</span>
                                                            <span class="inline-flex items-center">
                                                                <i class="fas fa-phone mr-1 text-green-500"></i>
                                                                {{ $biodata->user->phone_number }}
                                                            </span>
                                                            <span>•</span>
                                                            <span class="inline-flex items-center">
                                                                <i class="fas fa-hashtag mr-1 text-gray-500"></i>
                                                                ID: {{ $biodata->id }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Timeline Info -->
                                                <div class="bg-gray-50 rounded-lg p-3">
                                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                                                        <div>
                                                            <p class="text-gray-500 text-xs mb-1">Biodata Approved</p>
                                                            <p class="font-semibold text-gray-900">
                                                                <i class="fas fa-calendar-check mr-1 text-green-500"></i>
                                                                {{ $biodata->reviewed_at->format('d M Y') }}
                                                            </p>
                                                        </div>
                                                        
                                                        @if($biodata->document_submitted)
                                                        <div>
                                                            <p class="text-gray-500 text-xs mb-1">Berkas Disetor</p>
                                                            <p class="font-semibold text-gray-900">
                                                                <i class="fas fa-file-upload mr-1 text-green-500"></i>
                                                                {{ $biodata->document_submitted_at->format('d M Y') }}
                                                            </p>
                                                        </div>
                                                        @endif

                                                        @if($biodata->certificate_issued)
                                                        <div>
                                                            <p class="text-gray-500 text-xs mb-1">Sertifikat Terbit</p>
                                                            <p class="font-semibold text-gray-900">
                                                                <i class="fas fa-certificate mr-1 text-purple-500"></i>
                                                                {{ $biodata->certificate_issued_at->format('d M Y') }}
                                                            </p>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Tracking Actions Grid -->
                                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                                                    <!-- Document Tracking -->
                                                    <div class="bg-gray-50 rounded-lg p-3">
                                                        <h4 class="font-semibold text-gray-900 text-sm mb-2 flex items-center">
                                                            <i class="fas fa-file-alt mr-2 text-orange-500"></i>
                                                            Penyetoran Berkas
                                                        </h4>
                                                        
                                                        @if($biodata->document_submitted)
                                                            <div class="flex items-center text-green-700 mb-2">
                                                                <i class="fas fa-check-circle mr-1"></i>
                                                                <span class="text-xs font-medium">Sudah Disetor</span>
                                                            </div>
                                                            <p class="text-xs text-gray-600">
                                                                {{ $biodata->document_submitted_at->diffForHumans() }}
                                                            </p>
                                                        @else
                                                            @php
                                                                $deadline = $biodata->getDocumentDeadline();
                                                                $daysRemaining = $biodata->getDaysUntilDocumentDeadline();
                                                                $isOverdue = $biodata->isDocumentOverdue();
                                                            @endphp
                                                            
                                                            @if($isOverdue)
                                                                <div class="bg-red-100 border border-red-300 rounded p-2 mb-2">
                                                                    <p class="text-xs font-semibold text-red-900">
                                                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                                                        TERLAMBAT {{ abs($daysRemaining) }} hari!
                                                                    </p>
                                                                </div>
                                                            @else
                                                                <div class="bg-yellow-50 border border-yellow-200 rounded p-2 mb-2">
                                                                    <p class="text-xs text-yellow-800">
                                                                        <i class="fas fa-clock mr-1"></i>
                                                                        Sisa {{ $daysRemaining }} hari
                                                                    </p>
                                                                </div>
                                                            @endif

                                                            <form method="POST" action="{{ route('admin.reports.mark-document-submitted', $biodata) }}">
                                                                @csrf
                                                                <button type="submit" 
                                                                        onclick="return confirm('Tandai berkas sudah disetor?')"
                                                                        class="w-full bg-orange-600 hover:bg-orange-700 text-white text-xs font-semibold py-2 px-3 rounded transition duration-200">
                                                                    <i class="fas fa-check mr-1"></i>Tandai Disetor
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>

                                                    <!-- Certificate Tracking -->
                                                    <div class="bg-gray-50 rounded-lg p-3">
                                                        <h4 class="font-semibold text-gray-900 text-sm mb-2 flex items-center">
                                                            <i class="fas fa-certificate mr-2 text-blue-500"></i>
                                                            Sertifikat HKI
                                                        </h4>
                                                        
                                                        @if($biodata->certificate_issued)
                                                            <div class="flex items-center text-blue-700 mb-2">
                                                                <i class="fas fa-check-double mr-1"></i>
                                                                <span class="text-xs font-medium">Sudah Terbit</span>
                                                            </div>
                                                            <p class="text-xs text-gray-600">
                                                                {{ $biodata->certificate_issued_at->diffForHumans() }}
                                                            </p>
                                                        @elseif($biodata->document_submitted)
                                                            @php
                                                                $certDeadline = $biodata->getCertificateDeadline();
                                                                $certDays = $biodata->getDaysUntilCertificateDeadline();
                                                                $isCertOverdue = $biodata->isCertificateOverdue();
                                                            @endphp
                                                            
                                                            @if($isCertOverdue)
                                                                <div class="bg-orange-100 border border-orange-300 rounded p-2 mb-2">
                                                                    <p class="text-xs font-semibold text-orange-900">
                                                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                                                        TERLAMBAT {{ abs($certDays) }} hari!
                                                                    </p>
                                                                </div>
                                                            @else
                                                                <div class="bg-blue-50 border border-blue-200 rounded p-2 mb-2">
                                                                    <p class="text-xs text-blue-800">
                                                                        <i class="fas fa-hourglass-half mr-1"></i>
                                                                        Sisa {{ $certDays }} hari
                                                                    </p>
                                                                </div>
                                                            @endif

                                                            <form method="POST" action="{{ route('admin.reports.mark-certificate-issued', $biodata) }}">
                                                                @csrf
                                                                <button type="submit" 
                                                                        onclick="return confirm('Tandai sertifikat sudah terbit?')"
                                                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold py-2 px-3 rounded transition duration-200">
                                                                    <i class="fas fa-certificate mr-1"></i>Tandai Terbit
                                                                </button>
                                                            </form>
                                                        @else
                                                            <div class="text-center text-gray-500 text-xs py-2">
                                                                <i class="fas fa-lock mr-1"></i>
                                                                Menunggu berkas disetor
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- Contact & Reminders Combined -->
                                                    <div class="space-y-3">
                                                        <!-- Download Kelengkapan Button -->
                                                        <a href="{{ route('admin.reports.download-kelengkapan', $biodata) }}" 
                                                           class="block text-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded transition duration-200">
                                                            <i class="fas fa-file-download mr-1"></i>
                                                            Download Kelengkapan Pendaftaran HKI
                                                        </a>
                                                        <!-- WhatsApp Contact -->
                                                        @if($biodata->user->phone_number)
                                                            <a href="{{ generateWhatsAppUrl($biodata->user->phone_number) }}" 
                                                               target="_blank"
                                                               class="block text-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded transition duration-200">
                                                                <i class="fab fa-whatsapp mr-1"></i>
                                                                Hubungi via WhatsApp
                                                            </a>
                                                        @endif

                                                        <!-- Reminders -->
                                                        @if(!$biodata->document_submitted || $biodata->certificate_issued || ($biodata->document_submitted && !$biodata->certificate_issued))
                                                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-2">
                                                                <p class="text-xs font-semibold text-yellow-900 mb-1.5">
                                                                    <i class="fas fa-bell mr-1"></i>Pengingat:
                                                                </p>
                                                                <ul class="text-xs text-yellow-800 space-y-1">
                                                                    @if(!$biodata->document_submitted)
                                                                        <li class="flex items-start">
                                                                            <i class="fas fa-file-upload mr-1 mt-0.5 flex-shrink-0 text-yellow-600"></i>
                                                                            <span>Ingatkan user <strong>setor berkas</strong> ke kantor HKI</span>
                                                                        </li>
                                                                    @endif
                                                                    @if($biodata->certificate_issued)
                                                                        <li class="flex items-start">
                                                                            <i class="fas fa-certificate mr-1 mt-0.5 flex-shrink-0 text-yellow-600"></i>
                                                                            <span>Hubungi pengaju terkait <strong>sertifikat HKI</strong></span>
                                                                        </li>
                                                                    @endif
                                                                    @if($biodata->document_submitted && !$biodata->certificate_issued)
                                                                        <li class="flex items-start">
                                                                            <i class="fas fa-tasks mr-1 mt-0.5 flex-shrink-0 text-yellow-600"></i>
                                                                            <span>Update & selesaikan <strong>progress sertifikat</strong></span>
                                                                        </li>
                                                                        <li class="flex items-start">
                                                                            <i class="fas fa-money-bill-wave mr-1 mt-0.5 flex-shrink-0 text-yellow-600"></i>
                                                                            <span>Ingatkan pengaju terkait <strong>pembayaran</strong></span>
                                                                        </li>
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Quick Action Button -->
                                                <div class="pt-2">
                                                    <a href="{{ route('admin.biodata-pengaju.show', $biodata) }}" 
                                                       class="block text-center px-4 py-2.5 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded transition duration-200">
                                                        <i class="fas fa-eye mr-2"></i>Lihat Detail
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            <div class="bg-white rounded-lg shadow p-6">
                                {{ $biodatas->links() }}
                            </div>
                        @else
                            <div class="bg-white rounded-lg shadow p-12 text-center">
                                <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                                <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak Ada Data</h3>
                                <p class="text-gray-500">
                                    @if(request('search') || request('tracking_status'))
                                        Tidak ada hasil yang cocok dengan filter Anda.
                                    @else
                                        Belum ada biodata yang disetujui untuk ditampilkan.
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>

    @include('admin.partials.sidebar-script')
</body>
</html>
