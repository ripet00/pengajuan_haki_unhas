<!-- Mobile Overlay -->
<div id="mobile-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden md:hidden"></div>

<!-- Sidebar -->
<div id="sidebar" class="sidebar-transition sidebar-expanded bg-white shadow-lg fixed md:relative z-30 h-screen md:flex-shrink-0 border-r-2 border-red-100 flex flex-col">
    <!-- Logo & Institution Info -->
    <div class="h-20 border-b border-red-100 px-4 py-3 bg-gradient-to-r from-red-600 to-red-700 flex-shrink-0">
        <div class="flex items-center h-full sidebar-header-content">
            <div class="sidebar-logo-container">
                <img src="{{ asset('images/logo-unhas-kecil.png') }}" alt="Logo Unhas" class="w-10 h-10 sidebar-logo">
            </div>
            <div class="flex-1 ml-3">
                <div id="logo-text" class="transition-opacity duration-300">
                    <h1 class="text-sm font-bold text-white leading-tight">Direktorat Inovasi dan</h1>
                    <h2 class="text-sm font-bold text-red-100 leading-tight">Kekayaan Intelektual</h2>
                    <p class="text-xs text-red-200 font-medium">Universitas Hasanuddin</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation - Scrollable Area -->
    <nav class="flex-1 overflow-y-auto py-4">
        @php
            $admin = Auth::guard('admin')->user();
        @endphp
        <ul class="space-y-2 px-4 pb-4">
            <!-- Dashboard - Show for all except Pendamping Paten -->
            @if($admin && !$admin->isPendampingPaten())
            <li>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-red-50 group {{ Request::routeIs('admin.dashboard') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-tachometer-alt mr-3 {{ Request::routeIs('admin.dashboard') ? 'text-red-600' : 'text-gray-500' }}"></i>
                    <span class="sidebar-text transition-opacity duration-300">Dashboard</span>
                </a>
            </li>
            @endif

            <!-- Pendamping Paten Menu - Only for Pendamping Paten -->
            @if($admin && $admin->isPendampingPaten())
            <li>
                <a href="{{ route('admin.pendamping-paten.dashboard') }}" class="relative flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-purple-50 group {{ Request::routeIs('admin.pendamping-paten.dashboard') ? 'sidebar-active-pendamping' : '' }}">
                    <i class="fas fa-tachometer-alt mr-3 {{ Request::routeIs('admin.pendamping-paten.dashboard') ? 'text-purple-600' : 'text-gray-500' }}"></i>
                    <span class="sidebar-text transition-opacity duration-300">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.pendamping-paten.index') }}" class="relative flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-purple-50 group {{ Request::routeIs('admin.pendamping-paten.index') || Request::routeIs('admin.pendamping-paten.show') ? 'sidebar-active-pendamping' : '' }}">
                    <i class="fas fa-microscope mr-3 {{ Request::routeIs('admin.pendamping-paten.index') || Request::routeIs('admin.pendamping-paten.show') ? 'text-purple-600' : 'text-gray-500' }}"></i>
                    <span class="sidebar-text transition-opacity duration-300">Review Substansi Paten</span>
                    @php
                        $pendingSubstanceCount = \App\Models\SubmissionPaten::where('pendamping_paten_id', $admin->id)
                            ->where('status', \App\Models\SubmissionPaten::STATUS_PENDING_SUBSTANCE_REVIEW)
                            ->count();
                    @endphp
                    @if($pendingSubstanceCount > 0)
                        <span class="sidebar-badge ml-auto bg-purple-500 text-white text-xs font-bold px-2 py-1 rounded-full transition-all duration-300">
                            {{ $pendingSubstanceCount }}
                        </span>
                    @endif
                </a>
            </li>
            @endif

            <!-- Show other menus only for non-Pendamping Paten roles -->
            @if($admin && !$admin->isPendampingPaten())
            <li>
                <a href="{{ route('admin.users.index') }}" class="relative flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-red-50 group {{ Request::routeIs('admin.users.*') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-users mr-3 {{ Request::routeIs('admin.users.*') ? 'text-red-600' : 'text-gray-500' }}"></i>
                    <span class="sidebar-text transition-opacity duration-300">Kelola User</span>
                    @if(isset($pendingUsers) && $pendingUsers > 0)
                        <span class="sidebar-badge ml-auto bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded-full transition-all duration-300">
                            {{ $pendingUsers }}
                        </span>
                    @endif
                </a>
            </li>
            @if($admin->canManageAdmins())
            <li>
                <a href="{{ route('admin.admins.index') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-red-50 group {{ Request::routeIs('admin.admins.*') || Request::routeIs('admin.create') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-user-shield mr-3 {{ Request::routeIs('admin.admins.*') || Request::routeIs('admin.create') ? 'text-red-600' : 'text-gray-500' }}"></i>
                    <span class="sidebar-text transition-opacity duration-300">Kelola Admin</span>
                </a>
            </li>
            @endif
            <li>
                <a href="{{ route('admin.password-reset.index') }}" class="relative flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-red-50 group {{ Request::routeIs('admin.password-reset.*') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-key mr-3 {{ Request::routeIs('admin.password-reset.*') ? 'text-red-600' : 'text-gray-500' }}"></i>
                    <span class="sidebar-text transition-opacity duration-300">Reset Password</span>
                    @php
                        $pendingResets = \App\Models\PasswordResetRequest::where('status', 'pending')->count();
                    @endphp
                    @if($pendingResets > 0)
                        <span class="sidebar-badge ml-auto bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full transition-all duration-300">
                            {{ $pendingResets }}
                        </span>
                    @endif
                </a>
            </li>
            @endif
            
            <!-- Divider -->
            <li class="my-2">
                <hr class="border-gray-300">
            </li>
            
            <!-- Show Jenis Karya and Hak Cipta menus only for non-Pendamping Paten roles -->
            @if($admin && !$admin->isPendampingPaten())
            @if($admin->canAccessJenisKarya())
            <li>
                <a href="{{ route('admin.jenis-karyas.index') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-red-50 group {{ Request::routeIs('admin.jenis-karyas.*') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-list mr-3 {{ Request::routeIs('admin.jenis-karyas.*') ? 'text-red-600' : 'text-gray-500' }}"></i>
                    <span class="sidebar-text transition-opacity duration-300">Jenis Karya</span>
                </a>
            </li>
            @endif
            @if($admin->canAccessHakCipta())
            <li>
                <a href="{{ route('admin.submissions.index') }}" class="relative flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-red-50 group {{ Request::routeIs('admin.submissions.*') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-file-upload mr-3 {{ Request::routeIs('admin.submissions.*') ? 'text-red-600' : 'text-gray-500' }}"></i>
                    <span class="sidebar-text transition-opacity duration-300">Pengajuan Hak Cipta</span>
                    @if(isset($pendingSubmissions) && $pendingSubmissions > 0)
                        <span class="sidebar-badge ml-auto bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded-full transition-all duration-300">
                            {{ $pendingSubmissions }}
                        </span>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('admin.biodata.index') }}" class="relative flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-red-50 group {{ Request::routeIs('admin.biodata.*') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-user-friends mr-3 {{ Request::routeIs('admin.biodata.*') ? 'text-red-600' : 'text-gray-500' }}"></i>
                    <span class="sidebar-text transition-opacity duration-300">Biodata Pencipta</span>
                    @if(isset($pendingBiodatas) && $pendingBiodatas > 0)
                        <span class="sidebar-badge ml-auto bg-purple-500 text-white text-xs font-bold px-2 py-1 rounded-full transition-all duration-300">
                            {{ $pendingBiodatas }}
                        </span>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('admin.reports.index') }}" class="relative flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-red-50 group {{ Request::routeIs('admin.reports.*') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-chart-bar mr-3 {{ Request::routeIs('admin.reports.*') ? 'text-red-600' : 'text-gray-500' }}"></i>
                    <span class="sidebar-text transition-opacity duration-300">Laporan Hak Cipta</span>
                    @if(isset($pendingCertificates) && $pendingCertificates > 0)
                        <span class="sidebar-badge ml-auto bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded-full transition-all duration-300">
                            {{ $pendingCertificates }}
                        </span>
                    @endif
                </a>
            </li>
            @endif
            
            <!-- Divider -->
            @if($admin && !$admin->isPendampingPaten() && ($admin->canAccessHakCipta() || $admin->canAccessPaten()))
            <li class="my-2">
                <div class="border-t border-gray-200"></div>
            </li>
            @endif
            
            @if($admin && $admin->canAccessPaten())
            <li>
                <a href="{{ route('admin.submissions-paten.index') }}" class="relative flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-green-50 group {{ Request::routeIs('admin.submissions-paten.*') && !Request::routeIs('admin.biodata-paten.*') ? 'sidebar-active-paten' : '' }}">
                    <i class="fas fa-lightbulb mr-3 {{ Request::routeIs('admin.submissions-paten.*') && !Request::routeIs('admin.biodata-paten.*') ? 'text-green-600' : 'text-gray-500' }}"></i>
                    <span class="sidebar-text transition-opacity duration-300">Pengajuan Paten</span>
                    @if(isset($pendingPatenSubmissions) && $pendingPatenSubmissions > 0)
                        <span class="sidebar-badge ml-auto bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full transition-all duration-300">
                            {{ $pendingPatenSubmissions }}
                        </span>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('admin.biodata-paten.index') }}" class="relative flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-green-50 group {{ Request::routeIs('admin.biodata-paten.*') && !Request::routeIs('admin.reports-paten.*') ? 'sidebar-active-paten' : '' }}">
                    <i class="fas fa-user-tie mr-3 {{ Request::routeIs('admin.biodata-paten.*') && !Request::routeIs('admin.reports-paten.*') ? 'text-green-600' : 'text-gray-500' }}"></i>
                    <span class="sidebar-text transition-opacity duration-300">Biodata Inventor</span>
                    @if(isset($pendingBiodataPatens) && $pendingBiodataPatens > 0)
                        <span class="sidebar-badge ml-auto bg-purple-500 text-white text-xs font-bold px-2 py-1 rounded-full transition-all duration-300">
                            {{ $pendingBiodataPatens }}
                        </span>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('admin.reports-paten.index') }}" class="relative flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-green-50 group {{ Request::routeIs('admin.reports-paten.*') ? 'sidebar-active-paten' : '' }}">
                    <i class="fas fa-chart-line mr-3 {{ Request::routeIs('admin.reports-paten.*') ? 'text-green-600' : 'text-gray-500' }}"></i>
                    <span class="sidebar-text transition-opacity duration-300">Laporan Paten</span>
                    @if(isset($pendingSigning) && $pendingSigning > 0)
                        <span class="sidebar-badge ml-auto bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded-full transition-all duration-300">
                            {{ $pendingSigning }}
                        </span>
                    @endif
                </a>
            </li>
            @endif

            @endif
        </ul>
    </nav>

    <!-- Logout Button - Fixed at bottom -->
    <div class="p-4 border-t border-gray-200 flex-shrink-0">
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center px-4 py-3 text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-200">
                <i class="fas fa-sign-out-alt w-6"></i>
                <span class="sidebar-text transition-opacity duration-300">Logout</span>
            </button>
        </form>
    </div>
</div>

<style>
.sidebar-active {
    background-color: rgba(220, 38, 38, 0.1);
    border-left: 4px solid #dc2626;
    border-radius: 0.5rem 0.5rem 0.5rem 0;
    color: #dc2626;
    font-weight: 600;
}

.sidebar-active .sidebar-text {
    color: #dc2626;
}

.sidebar-active-paten {
    background-color: rgba(5, 150, 105, 0.1);
    border-left: 4px solid #059669;
    border-radius: 0.5rem 0.5rem 0.5rem 0;
    color: #059669;
    font-weight: 600;
}

.sidebar-active-paten .sidebar-text {
    color: #059669;
}

.sidebar-active-pendamping {
    background-color: rgba(147, 51, 234, 0.1);
    border-left: 4px solid #9333ea;
    border-radius: 0.5rem 0.5rem 0.5rem 0;
    color: #9333ea;
    font-weight: 600;
}

.sidebar-active-pendamping .sidebar-text {
    color: #9333ea;
}

.sidebar-transition {
    transition: transform 0.3s ease-in-out, width 0.3s ease-in-out;
}

.sidebar-collapsed {
    width: 4rem;
}

.sidebar-expanded {
    width: 16rem;
}

.sidebar-collapsed .sidebar-text,
.sidebar-collapsed #logo-text {
    opacity: 0;
    visibility: hidden;
}

.sidebar-collapsed .sidebar-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    margin-left: 0 !important;
    min-width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 6px;
}

.sidebar-badge {
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 24px;
    height: 24px;
    line-height: 1;
}

.sidebar-collapsed .sidebar-header-content {
    justify-content: center;
}

.sidebar-collapsed .sidebar-logo-container {
    margin: 0 auto;
}

.sidebar-logo-container {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    transition: all 0.3s ease;
}

.sidebar-toggle-fixed {
    position: fixed;
    top: 20px;
    left: 20px;
    z-index: 1000;
    transition: all 0.3s ease;
}

.sidebar-toggle-fixed.collapsed {
    left: 20px;
}

.sidebar-toggle-fixed.expanded {
    left: 280px;
}

@media (max-width: 768px) {
    .sidebar-mobile-hidden {
        transform: translateX(-100%);
    }
    .sidebar-mobile-visible {
        transform: translateX(0);
    }
}
</style>