<!-- Header -->
<header class="bg-white shadow-sm border-b border-red-200">
    <div class="px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <!-- Mobile Menu Button -->
                <button id="sidebar-toggle-mobile" class="md:hidden p-2 rounded-lg hover:bg-red-50 transition-colors mr-4 border border-red-200 bg-red-50">
                    <i class="fas fa-bars text-red-600"></i>
                </button>
                <!-- Desktop Toggle Button -->
                <button id="sidebar-toggle-desktop" class="hidden md:block p-2 rounded-lg hover:bg-red-50 transition-colors mr-4 border border-red-200 bg-red-50 z-50 relative">
                    <i class="fas fa-bars text-red-600"></i>
                </button>
                <h1 class="text-xl md:text-2xl font-bold text-gray-900">{{ $title ?? 'Dashboard' }}</h1>
            </div>
            <div class="flex items-center space-x-2 md:space-x-4">
                @php
                    $adminId = session('admin_id');
                    $admin = $adminId ? \App\Models\Admin::find($adminId) : null;
                @endphp
                @if($admin)
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-medium text-sm">{{ substr($admin->name, 0, 1) }}</span>
                        </div>
                        <span class="ml-2 text-gray-700 font-medium hidden sm:block">{{ $admin->name }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</header>