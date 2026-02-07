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
                    $admin = Auth::guard('admin')->user();
                @endphp
                @if($admin)
                    <div class="flex items-center">
                        <div class="w-8 h-8 md:w-10 md:h-10 bg-red-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-medium text-sm md:text-base">{{ substr($admin->name, 0, 1) }}</span>
                        </div>
                        <div class="ml-2 md:ml-3 hidden sm:block">
                            <div class="text-gray-900 font-semibold text-sm md:text-base">{{ $admin->name }}</div>
                            <div class="text-xs text-gray-500">
                                <i class="fas fa-shield-alt mr-1"></i>{{ $admin->role_name }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</header>