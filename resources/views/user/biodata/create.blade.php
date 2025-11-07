<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isEdit ? 'Edit' : 'Buat' }} Biodata - HKI Unhas</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        }
        
        /* Ensure text is always visible with high contrast */
        .header-text {
            color: #ffffff !important;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }
        
        .user-avatar {
            background: rgba(255, 255, 255, 0.25) !important;
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }
        
        .logout-btn {
            background: rgba(255, 255, 255, 0.2) !important;
            border: 1px solid rgba(255, 255, 255, 0.4) !important;
            backdrop-filter: blur(10px);
            color: #ffffff !important;
        }
        
        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.35) !important;
            border-color: rgba(255, 255, 255, 0.6) !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .logout-btn i, .logout-btn span {
            color: #ffffff !important;
            filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.3));
        }
        
        /* Additional visibility fixes */
        .header-icon {
            color: #ffffff !important;
            filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.3));
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <header class="gradient-bg shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-center py-4 sm:py-6 space-y-3 sm:space-y-0 w-full">
                <div class="flex items-center">
                    <img src="{{ asset('images/logo-unhas-kecil.png') }}" alt="Logo Unhas" class="w-10 h-10 sm:w-12 sm:h-12 mr-3">
                    <div>
                        <h1 class="text-sm sm:text-lg font-bold header-text leading-tight">Direktorat Inovasi dan Kekayaan Intelektual</h1>
                        <p class="text-red-100 text-xs sm:text-sm">Universitas Hasanuddin</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <div class="flex items-center header-text min-w-0 flex-1 sm:flex-initial">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 user-avatar rounded-full flex items-center justify-center mr-2 flex-shrink-0">
                            <span class="header-text font-medium text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <div class="min-w-0 flex-1 sm:flex-initial">
                            <span class="font-medium text-sm sm:text-base header-text hidden sm:block">{{ Auth::user()->name }}</span>
                            <span class="font-medium text-sm header-text block sm:hidden truncate">{{ explode(' ', Auth::user()->name)[0] }}</span>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('user.logout') }}">
                        @csrf
                        <button type="submit" class="logout-btn px-3 sm:px-4 py-2 rounded-lg font-medium transition duration-200 text-sm sm:text-base">
                            <i class="fas fa-sign-out-alt mr-1 sm:mr-2 header-icon"></i><span class="hidden sm:inline header-text">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('user.submissions.show', $submission) }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Detail Submission
            </a>
        </div>

        <!-- Header Section -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-2xl font-semibold text-gray-900">
                    <i class="fas fa-user-friends mr-3 text-blue-600"></i>
                    {{ $isEdit ? 'Edit' : 'Buat' }} Biodata Karya Cipta
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Submission: <strong>{{ $submission->title }}</strong> (ID: #{{ $submission->id }})
                </p>
            </div>

            <!-- Progress Info -->
            <div class="px-6 py-4">
                @if($isEdit)
                    @if($biodata && $biodata->status == 'denied')
                        <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                            <h4 class="text-sm font-semibold text-red-800 mb-2">
                                <i class="fas fa-exclamation-triangle mr-1"></i>Biodata Ditolak
                            </h4>
                            <p class="text-sm text-red-700">{{ $biodata ? $biodata->rejection_reason : '' }}</p>
                            <p class="text-sm text-red-600 mt-2">Silakan perbaiki biodata sesuai dengan catatan admin.</p>
                        </div>
                    @else
                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <h4 class="text-sm font-semibold text-blue-800 mb-2">
                                <i class="fas fa-edit mr-1"></i>Mode Edit Biodata
                            </h4>
                            <p class="text-sm text-blue-700">Anda sedang mengedit biodata yang telah dibuat sebelumnya.</p>
                        </div>
                    @endif
                @else
                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                        <h4 class="text-sm font-semibold text-green-800 mb-2">
                            <i class="fas fa-check-circle mr-1"></i>Submission Disetujui
                        </h4>
                        <p class="text-sm text-green-700">Lengkapi biodata untuk melanjutkan proses pengajuan HKI Anda.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Form Section -->
        <form method="POST" action="{{ route('user.biodata.store', $submission) }}" class="space-y-6">
            @csrf
            
            <!-- Biodata Information -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Karya Cipta</h3>
                </div>
                
                <div class="p-6 space-y-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Karya Cipta</label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               value="{{ $submission->title }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed"
                               readonly
                               disabled>
                        <p class="mt-1 text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Judul ini diambil otomatis dari submission yang telah disetujui
                        </p>
                    </div>                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="tempat_ciptaan" class="block text-sm font-medium text-gray-700 mb-1">Tempat Ciptaan *</label>
                            <input type="text" 
                                   id="tempat_ciptaan" 
                                   name="tempat_ciptaan" 
                                   value="{{ old('tempat_ciptaan', $biodata ? $biodata->tempat_ciptaan : '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                            @error('tempat_ciptaan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tanggal_ciptaan" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Ciptaan *</label>
                            <input type="date" 
                                   id="tanggal_ciptaan" 
                                   name="tanggal_ciptaan" 
                                   value="{{ old('tanggal_ciptaan', $biodata && $biodata->tanggal_ciptaan ? $biodata->tanggal_ciptaan->format('Y-m-d') : '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                            @error('tanggal_ciptaan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="uraian_singkat" class="block text-sm font-medium text-gray-700 mb-1">Uraian Singkat Karya Cipta *</label>
                        <textarea id="uraian_singkat" 
                                  name="uraian_singkat" 
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  required>{{ old('uraian_singkat', $biodata ? $biodata->uraian_singkat : '') }}</textarea>
                        @error('uraian_singkat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Members Section -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Data Pencipta</h3>
                        <button type="button" 
                                id="add-member-btn" 
                                class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200">
                            <i class="fas fa-plus mr-1"></i>Tambah Anggota
                        </button>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">Maksimal 10 orang pencipta (termasuk ketua)</p>
                </div>
                
                <div id="members-container" class="divide-y divide-gray-200">
                    <!-- Members will be added here by JavaScript -->
                </div>
            </div>

            <!-- Submit Section -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900">Review & Submit</h4>
                            <p class="text-sm text-gray-600">Pastikan semua data telah diisi dengan benar.</p>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('user.submissions.show', $submission) }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 text-sm font-medium rounded-lg transition duration-200">
                                <i class="fas fa-times mr-2"></i>Batal
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-200">
                                <i class="fas fa-save mr-2"></i>
                                {{ $isEdit ? 'Update' : 'Simpan' }} Biodata
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>

    <script>
        let memberCount = 0;
        const maxMembers = 10;
        
        // Existing members data from server
        const existingMembers = @json($members ? $members->toArray() : []);
        
        function createMemberForm(index, memberData = {}) {
            const isLeader = index === 0;
            const member = memberData || {};
            
            return `
                <div class="member-form p-6" data-member-index="${index}">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-md font-semibold text-gray-900">
                            <i class="fas fa-user mr-2"></i>
                            Pencipta ke-${index + 1} ${isLeader ? '(Ketua)' : ''}
                        </h4>
                        ${!isLeader ? `
                            <button type="button" 
                                    class="remove-member-btn text-red-600 hover:text-red-800 transition duration-200"
                                    onclick="removeMember(${index})">
                                <i class="fas fa-trash"></i>
                            </button>
                        ` : ''}
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                            <input type="text" 
                                   name="members[${index}][name]" 
                                   value="${member.name || ''}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                            <input type="text" 
                                   name="members[${index}][nik]" 
                                   value="${member.nik || ''}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan</label>
                            <input type="text" 
                                   name="members[${index}][pekerjaan]" 
                                   value="${member.pekerjaan || ''}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Universitas</label>
                            <input type="text" 
                                   name="members[${index}][universitas]" 
                                   value="${member.universitas || ''}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fakultas</label>
                            <input type="text" 
                                   name="members[${index}][fakultas]" 
                                   value="${member.fakultas || ''}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Program Studi</label>
                            <input type="text" 
                                   name="members[${index}][program_studi]" 
                                   value="${member.program_studi || ''}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                            <textarea name="members[${index}][alamat]" 
                                      rows="2"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">${member.alamat || ''}</textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kelurahan</label>
                            <input type="text" 
                                   name="members[${index}][kelurahan]" 
                                   value="${member.kelurahan || ''}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan</label>
                            <input type="text" 
                                   name="members[${index}][kecamatan]" 
                                   value="${member.kecamatan || ''}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kota/Kabupaten</label>
                            <input type="text" 
                                   name="members[${index}][kota_kabupaten]" 
                                   value="${member.kota_kabupaten || ''}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                            <input type="text" 
                                   name="members[${index}][provinsi]" 
                                   value="${member.provinsi || ''}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Pos</label>
                            <input type="text" 
                                   name="members[${index}][kode_pos]" 
                                   value="${member.kode_pos || ''}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" 
                                   name="members[${index}][email]" 
                                   value="${member.email || ''}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
                            <input type="text" 
                                   name="members[${index}][nomor_hp]" 
                                   value="${member.nomor_hp || ''}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kewarganegaraan</label>
                            <input type="text" 
                                   name="members[${index}][kewarganegaraan]" 
                                   value="${member.kewarganegaraan || 'Indonesia'}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                </div>
            `;
        }
        
        function addMember() {
            if (memberCount >= maxMembers) {
                alert('Maksimal 10 anggota diperbolehkan.');
                return;
            }
            
            const container = document.getElementById('members-container');
            const memberHtml = createMemberForm(memberCount);
            container.insertAdjacentHTML('beforeend', memberHtml);
            memberCount++;
            updateAddButton();
        }
        
        function removeMember(index) {
            const memberForm = document.querySelector(`[data-member-index="${index}"]`);
            if (memberForm) {
                memberForm.remove();
                updateMemberIndexes();
                updateAddButton();
            }
        }
        
        function updateMemberIndexes() {
            const memberForms = document.querySelectorAll('.member-form');
            memberCount = 0;
            
            memberForms.forEach((form, newIndex) => {
                form.setAttribute('data-member-index', newIndex);
                
                // Update form title
                const title = form.querySelector('h4');
                const isLeader = newIndex === 0;
                title.innerHTML = `
                    <i class="fas fa-user mr-2"></i>
                    Pencipta ke-${newIndex + 1} ${isLeader ? '(Ketua)' : ''}
                `;
                
                // Update remove button
                const removeBtn = form.querySelector('.remove-member-btn');
                if (removeBtn) {
                    if (isLeader) {
                        removeBtn.style.display = 'none';
                    } else {
                        removeBtn.style.display = 'block';
                        removeBtn.setAttribute('onclick', `removeMember(${newIndex})`);
                    }
                }
                
                // Update all input names
                const inputs = form.querySelectorAll('input, textarea');
                inputs.forEach(input => {
                    const name = input.getAttribute('name');
                    if (name && name.includes('members[')) {
                        const newName = name.replace(/members\[\d+\]/, `members[${newIndex}]`);
                        input.setAttribute('name', newName);
                    }
                });
                
                memberCount++;
            });
        }
        
        function updateAddButton() {
            const addButton = document.getElementById('add-member-btn');
            if (memberCount >= maxMembers) {
                addButton.style.display = 'none';
            } else {
                addButton.style.display = 'inline-flex';
            }
        }
        
        // Initialize form
        document.addEventListener('DOMContentLoaded', function() {
            // Add existing members or create first member
            if (existingMembers.length > 0) {
                existingMembers.forEach((member, index) => {
                    const container = document.getElementById('members-container');
                    const memberHtml = createMemberForm(index, member);
                    container.insertAdjacentHTML('beforeend', memberHtml);
                    memberCount++;
                });
            } else {
                // Create first member (leader) with user data
                addMember();
            }
            
            updateAddButton();
            
            // Add event listener for add member button
            document.getElementById('add-member-btn').addEventListener('click', addMember);
        });
    </script>
</body>
</html>