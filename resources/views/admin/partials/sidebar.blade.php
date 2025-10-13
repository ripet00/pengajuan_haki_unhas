<!-- Mobile Overlay -->
<div id="mobile-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden md:hidden"></div>

<!-- Sidebar -->
<div id="sidebar" class="sidebar-transition sidebar-expanded bg-white shadow-lg fixed md:relative z-30 h-full md:flex-shrink-0">
    <!-- Logo -->
    <div class="flex items-center justify-between h-16 border-b border-gray-200 px-4">
        <div class="flex items-center">
            <i class="fas fa-shield-alt text-2xl text-indigo-600 mr-2"></i>
            <span id="logo-text" class="text-xl font-bold text-gray-800 transition-opacity duration-300">HAKI Admin</span>
        </div>
        <!-- Desktop Toggle Button -->
        <button id="sidebar-toggle-desktop" class="hidden md:block p-2 rounded-lg hover:bg-indigo-50 transition-colors border border-indigo-200 bg-indigo-50">
            <i class="fas fa-bars text-indigo-600"></i>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="mt-8 flex-1 overflow-y-auto">
        <ul class="space-y-2 px-4">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 group {{ Request::routeIs('admin.dashboard') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-tachometer-alt mr-3 {{ Request::routeIs('admin.dashboard') ? 'text-indigo-600' : '' }}"></i>
                    <span class="sidebar-text transition-opacity duration-300">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 group {{ Request::routeIs('admin.users.*') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-users mr-3 {{ Request::routeIs('admin.users.*') ? 'text-indigo-600' : '' }}"></i>
                    <span class="sidebar-text transition-opacity duration-300">Kelola User</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.admins.index') }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 group {{ Request::routeIs('admin.admins.*') || Request::routeIs('admin.create') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-user-shield mr-3 {{ Request::routeIs('admin.admins.*') || Request::routeIs('admin.create') ? 'text-indigo-600' : '' }}"></i>
                    <span class="sidebar-text transition-opacity duration-300">Kelola Admin</span>
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 group">
                    <i class="fas fa-file-alt mr-3"></i>
                    <span class="sidebar-text transition-opacity duration-300">Pengajuan HAKI</span>
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 group">
                    <i class="fas fa-chart-bar mr-3"></i>
                    <span class="sidebar-text transition-opacity duration-300">Laporan</span>
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 group">
                    <i class="fas fa-cog mr-3"></i>
                    <span class="sidebar-text transition-opacity duration-300">Pengaturan</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Logout Button -->
    <div class="p-4 border-t border-gray-200">
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center px-4 py-3 text-red-600 rounded-lg hover:bg-red-50 transition duration-200 group">
                <i class="fas fa-sign-out-alt mr-3"></i>
                <span class="sidebar-text transition-opacity duration-300">Logout</span>
            </button>
        </form>
    </div>
</div>

<style>
    .sidebar-active {
        background-color: rgba(99, 102, 241, 0.1);
        border-right: 3px solid #6366f1;
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
    @media (max-width: 768px) {
        .sidebar-mobile-hidden {
            transform: translateX(-100%);
        }
        .sidebar-mobile-visible {
            transform: translateX(0);
        }
    }
</style>