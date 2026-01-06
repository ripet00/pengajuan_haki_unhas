<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Reset Password - Pengajuan HKI</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('admin.partials.header', ['title' => 'Detail Reset Password'])

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="px-4 sm:px-6 lg:px-8 py-6">
                    <div class="mb-6">
                        <a href="{{ route('admin.password-reset.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-200 shadow-sm">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg shadow-sm">
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

                    @if(session('reset_url'))
                        <!-- Important Warning -->
                        <div class="mb-4 bg-yellow-50 border-l-4 border-yellow-500 p-5 rounded-lg shadow-md">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-yellow-500 text-2xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-bold text-yellow-800 mb-2">
                                        ⚠️ PENTING - Link Hanya Ditampilkan Sekali!
                                    </h4>
                                    <div class="text-sm text-yellow-700 space-y-1">
                                        <p class="font-semibold">Link reset password di bawah ini TIDAK DISIMPAN di database untuk alasan keamanan.</p>
                                        <p>Setelah Anda meninggalkan halaman ini, link tidak akan bisa dilihat lagi.</p>
                                        <p class="font-bold text-yellow-900 mt-2">📱 Silakan SEGERA kirim link ke user melalui WhatsApp atau copy link sebelum meninggalkan halaman ini!</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6 bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold text-red-800 mb-4 flex items-center">
                                <i class="fas fa-link mr-2"></i>Link Reset Password Berhasil Dibuat!
                            </h3>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-red-700 mb-2">Link Reset Password:</label>
                                <div class="flex gap-2">
                                    <input type="text" id="resetUrl" value="{{ session('reset_url') }}" readonly 
                                           class="flex-1 px-4 py-2.5 border border-red-200 rounded-md bg-white text-sm font-mono focus:outline-none focus:ring-2 focus:ring-red-500">
                                    <button onclick="copyToClipboard('resetUrl')" 
                                            class="px-5 py-2.5 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-md transition duration-200 shadow-md">
                                        <i class="fas fa-copy mr-1"></i> Copy
                                    </button>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <a href="https://wa.me/{{ str_replace(['+', ' '], '', $request->country_code . $request->phone_number) }}?text={{ urlencode('Halo, berikut link reset password Anda: ' . session('reset_url') . "\n\nLink berlaku selama 60 menit. Silakan klik link tersebut untuk mengubah password Anda.") }}"
                                   target="_blank"
                                   class="inline-flex items-center px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-md transition duration-200 shadow-md">
                                    <i class="fab fa-whatsapp mr-2"></i>Kirim via WhatsApp
                                </a>
                                <button onclick="copyWhatsAppMessage()"
                                        class="inline-flex items-center px-5 py-2.5 bg-gray-600 hover:bg-gray-700 text-white rounded-md transition duration-200 shadow-md">
                                    <i class="fas fa-clipboard mr-2"></i>Copy Pesan WA
                                </button>
                            </div>
                        </div>
                    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Request Details -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Detail Permintaan</h2>
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            @if($request->status === 'pending')
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-2"></i> Pending
                                </span>
                            @elseif($request->status === 'sent')
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <i class="fas fa-paper-plane mr-2"></i> Link Terkirim
                                </span>
                            @elseif($request->status === 'used')
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-2"></i> Sudah Digunakan
                                </span>
                            @elseif($request->status === 'rejected')
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-times mr-2"></i> Ditolak
                                </span>
                            @elseif($request->status === 'expired')
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                                    <i class="fas fa-hourglass-end mr-2"></i> Kadaluarsa
                                </span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tipe Akun</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($request->user_type) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nomor WhatsApp</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $request->country_code }} {{ $request->phone_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Waktu Request</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $request->requested_at->format('d F Y, H:i') }} WIB</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">IP Address Requester</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $request->request_ip ?? '-' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Account Info -->
            @if($account)
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Akun</h2>
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nama</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $account->name }}</dd>
                    </div>
                    @if($request->user_type === 'user')
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Fakultas</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $account->faculty ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status Akun</dt>
                            <dd class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $account->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($account->status) }}
                                </span>
                            </dd>
                        </div>
                    @else
                        <div>
                            <dt class="text-sm font-medium text-gray-500">NIP/NIDN/NIDK/NIM</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $account->nip_nidn_nidk_nim ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Role</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $account->role_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $account->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $account->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>
            @endif

            <!-- Approval/Rejection Info -->
            @if($request->status !== 'pending')
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Proses</h2>
                <dl class="grid grid-cols-1 gap-4">
                    @if($request->approved_by_admin_id)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Disetujui Oleh</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $request->approvedBy->name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Waktu Approval</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $request->approved_at?->format('d F Y, H:i') ?? '-' }} WIB</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Metode Verifikasi</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ strtoupper($request->verification_method ?? '-') }}</dd>
                        </div>
                        @if($request->verification_notes)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Catatan Verifikasi</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $request->verification_notes }}</dd>
                        </div>
                        @endif
                        @if($request->token_expires_at)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Link Berlaku Hingga</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $request->token_expires_at->format('d F Y, H:i') }} WIB</dd>
                        </div>
                        @endif
                    @endif

                    @if($request->rejected_by_admin_id)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Ditolak Oleh</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $request->rejectedBy->name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Waktu Penolakan</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $request->rejected_at?->format('d F Y, H:i') ?? '-' }} WIB</dd>
                        </div>
                        @if($request->rejection_reason)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Alasan Penolakan</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $request->rejection_reason }}</dd>
                        </div>
                        @endif
                    @endif

                    @if($request->used_at)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Password Diubah Pada</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $request->used_at->format('d F Y, H:i') }} WIB</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">IP Address Saat Reset</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $request->used_ip ?? '-' }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
            @endif
        </div>

        <!-- Actions Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            @if($request->status === 'pending')
            <!-- Contact User via WhatsApp -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg p-5 shadow-sm">
                <h3 class="text-md font-semibold text-gray-800 mb-3 flex items-center">
                    <i class="fab fa-whatsapp text-green-600 mr-2 text-lg"></i>
                    Hubungi User untuk Verifikasi
                </h3>
                <p class="text-sm text-gray-600 mb-4">
                    Sebelum approve, verifikasi identitas user terlebih dahulu via WhatsApp atau telepon.
                </p>
                <a href="https://wa.me/{{ str_replace(['+', ' '], '', $request->country_code . $request->phone_number) }}?text={{ urlencode('Halo, saya Admin dari Sistem Pengajuan HKI Universitas Hasanuddin.' . "\n\n" . 'Saya menerima permintaan reset password untuk akun dengan nomor WhatsApp ini.' . "\n\n" . 'Untuk memastikan keamanan akun Anda, mohon konfirmasi:' . "\n" . '1. Nama lengkap Anda' . "\n" . '2. Apakah benar Anda yang mengajukan reset password?' . "\n\n" . 'Terima kasih.') }}"
                   target="_blank"
                   class="w-full inline-flex items-center justify-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-md transition duration-200 shadow-md font-medium">
                    <i class="fab fa-whatsapp mr-2 text-xl"></i>
                    Buka WhatsApp Chat dengan User
                </a>
                <div class="mt-3 text-xs text-gray-500 space-y-1">
                    <p><i class="fas fa-info-circle mr-1"></i>Nomor: <span class="font-medium">{{ $request->country_code }} {{ $request->phone_number }}</span></p>
                    @if($request->user_type === 'user' && $request->user)
                        <p><i class="fas fa-user mr-1"></i>Nama: <span class="font-medium">{{ $request->user->name }}</span></p>
                    @elseif($request->user_type === 'admin' && $request->admin)
                        <p><i class="fas fa-user-shield mr-1"></i>Nama: <span class="font-medium">{{ $request->admin->name }}</span></p>
                    @endif
                </div>
            </div>

            <!-- Approve Form -->
            <div class="bg-white shadow-md rounded-lg p-6 border-t-4 border-green-500">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-2"></i>Approve Request
                </h3>
                <form action="{{ route('admin.password-reset.approve', $request->id) }}" method="POST" 
                      onsubmit="return confirm('⚠️ PERHATIAN!\n\nSetelah generate link, link reset password akan ditampilkan SATU KALI SAJA dan tidak akan disimpan di database.\n\nPastikan Anda siap untuk langsung mengirim link ke user.\n\nLanjutkan approve request ini?')">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-phone-alt text-gray-400 mr-1"></i>Metode Verifikasi *
                            </label>
                            <select name="verification_method" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 transition duration-200">
                                <option value="">Pilih metode...</option>
                                <option value="call"><i class="fas fa-phone"></i> Telepon</option>
                                <option value="wa"><i class="fab fa-whatsapp"></i> WhatsApp</option>
                                <option value="other"><i class="fas fa-ellipsis-h"></i> Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-sticky-note text-gray-400 mr-1"></i>Catatan Verifikasi
                            </label>
                            <textarea name="verification_notes" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 transition duration-200"
                                placeholder="Contoh: Sudah dikonfirmasi via WA, user menyebutkan data yang benar"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-4 py-3 rounded-md transition duration-200 shadow-md font-medium">
                            <i class="fas fa-check mr-2"></i>Approve & Generate Link
                        </button>
                    </div>
                </form>
            </div>

            <!-- Reject Form -->
            <div class="bg-white shadow-md rounded-lg p-6 border-t-4 border-red-500">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-times-circle text-red-500 mr-2"></i>Tolak Request
                </h3>
                <form action="{{ route('admin.password-reset.reject', $request->id) }}" method="POST" 
                      onsubmit="return confirm('⚠️ Konfirmasi Penolakan\n\nApakah Anda yakin ingin MENOLAK request reset password ini?\n\nUser harus mengajukan request baru jika ditolak.\n\nLanjutkan penolakan?')">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-comment-dots text-gray-400 mr-1"></i>Alasan Penolakan *
                            </label>
                            <textarea name="rejection_reason" required rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 transition duration-200"
                                placeholder="Contoh: Tidak bisa menghubungi user, user tidak bisa memverifikasi identitas"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-4 py-3 rounded-md transition duration-200 shadow-md font-medium">
                            <i class="fas fa-times mr-2"></i>Tolak Request
                        </button>
                    </div>
                </form>
            </div>
            @endif

            <!-- History -->
            @if($history->count() > 0)
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Riwayat Request</h3>
                <div class="space-y-3">
                    @foreach($history as $h)
                    <div class="text-sm border-l-2 border-gray-300 pl-3">
                        <div class="font-medium text-gray-900">{{ $h->requested_at->format('d/m/Y H:i') }}</div>
                        <div class="text-gray-600">Status: {{ $h->status }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
                </div>
            </main>
        </div>
    </div>

    @include('admin.partials.sidebar-script')

    <script>
    function copyToClipboard(elementId) {
        const copyText = document.getElementById(elementId);
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
        
        // Show success feedback
        const btn = event.target.closest('button');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check mr-1"></i> Copied!';
        btn.classList.add('bg-green-600', 'hover:bg-green-700');
        btn.classList.remove('from-red-500', 'to-red-600', 'hover:from-red-600', 'hover:to-red-700');
        
        setTimeout(() => {
            btn.innerHTML = originalHTML;
            btn.classList.remove('bg-green-600', 'hover:bg-green-700');
            btn.classList.add('from-red-500', 'to-red-600', 'hover:from-red-600', 'hover:to-red-700');
        }, 2000);
    }

    function copyWhatsAppMessage() {
        const message = `Halo, berikut link reset password Anda: {{ session('reset_url') }}\n\nLink berlaku selama 60 menit. Silakan klik link tersebut untuk mengubah password Anda.`;
        navigator.clipboard.writeText(message);
        
        // Show success feedback
        const btn = event.target;
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check mr-2"></i>Copied!';
        btn.classList.add('bg-green-600', 'hover:bg-green-700');
        btn.classList.remove('bg-gray-600', 'hover:bg-gray-700');
        
        setTimeout(() => {
            btn.innerHTML = originalHTML;
            btn.classList.remove('bg-green-600', 'hover:bg-green-700');
            btn.classList.add('bg-gray-600', 'hover:bg-gray-700');
        }, 2000);
    }
    </script>
</body>
</html>
