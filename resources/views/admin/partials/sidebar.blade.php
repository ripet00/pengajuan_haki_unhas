<!-- Mobile Overlay -->
<div id="mobile-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden md:hidden"></div>

<!-- Sidebar -->
<div id="sidebar" class="sidebar-transition sidebar-expanded bg-white shadow-lg fixed md:relative z-30 h-full md:flex-shrink-0 border-r-2 border-red-100">
    <!-- Logo & Institution Info -->
    <div class="h-20 border-b border-red-100 px-4 py-3 bg-gradient-to-r from-red-600 to-red-700">
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

    <!-- Navigation -->
    <nav class="mt-8 flex-1 overflow-y-auto">
        <ul class="space-y-2 px-4">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-red-50 group {{ Request::routeIs('admin.dashboard') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-tachometer-alt mr-3 {{ Request::routeIs('admin.dashboard') ? 'text-red-600' : 'text-gray-500' }}"></i>
                    <span class="sidebar-text transition-opacity duration-300">Dashboard</span>
                </a>
            </li>
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
            <li>
                <a href="{{ route('admin.admins.index') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-red-50 group {{ Request::routeIs('admin.admins.*') || Request::routeIs('admin.create') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-user-shield mr-3 {{ Request::routeIs('admin.admins.*') || Request::routeIs('admin.create') ? 'text-red-600' : 'text-gray-500' }}"></i>
                    <span class="sidebar-text transition-opacity duration-300">Kelola Admin</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.jenis-karyas.index') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-red-50 group {{ Request::routeIs('admin.jenis-karyas.*') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-list mr-3 {{ Request::routeIs('admin.jenis-karyas.*') ? 'text-red-600' : 'text-gray-500' }}"></i>
                    <span class="sidebar-text transition-opacity duration-300">Jenis Karya</span>
                </a>
            </li>
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
                <a href="{{ route('admin.biodata-pengaju.index') }}" class="relative flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-red-50 group {{ Request::routeIs('admin.biodata-pengaju.*') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-user-friends mr-3 {{ Request::routeIs('admin.biodata-pengaju.*') ? 'text-red-600' : 'text-gray-500' }}"></i>
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
            
            <!-- Divider -->
            <li class="my-4">
                <div class="border-t border-gray-200"></div>
            </li>
            
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
                <a href="{{ route('admin.biodata-paten.index') }}" class="relative flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-green-50 group {{ Request::routeIs('admin.biodata-paten.*') ? 'sidebar-active-paten' : '' }}">
                    <i class="fas fa-user-tie mr-3 {{ Request::routeIs('admin.biodata-paten.*') ? 'text-green-600' : 'text-gray-500' }}"></i>
                    <span class="sidebar-text transition-opacity duration-300">Biodata Inventor</span>
                    @if(isset($pendingBiodataPatens) && $pendingBiodataPatens > 0)
                        <span class="sidebar-badge ml-auto bg-purple-500 text-white text-xs font-bold px-2 py-1 rounded-full transition-all duration-300">
                            {{ $pendingBiodataPatens }}
                        </span>
                    @endif
                </a>
            </li>
        </ul>
    </nav>

    <!-- Logout Button -->
    <div class="p-4 border-t border-gray-200">
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