<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Pengajuan HAKI</title>
    @filamentStyles
    <style>
        body {
            background: #f9fafb;
            min-height: 100vh;
        }
        .header {
            background: white;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }
        .users-table {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .status-pending { background: #fef3c7; color: #d97706; }
        .status-active { background: #d1fae5; color: #059669; }
        .status-denied { background: #fee2e2; color: #dc2626; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="container">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                        Create New Admin
                    </a>
                    <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mt-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Total Users</h3>
                <p class="text-3xl font-bold text-blue-600">{{ $totalUsers }}</p>
            </div>
            <div class="stat-card">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Active Users</h3>
                <p class="text-3xl font-bold text-green-600">{{ $activeUsers }}</p>
            </div>
            <div class="stat-card">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Pending Approval</h3>
                <p class="text-3xl font-bold text-yellow-600">{{ $pendingUsers->count() }}</p>
            </div>
            <div class="stat-card">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Denied Users</h3>
                <p class="text-3xl font-bold text-red-600">{{ $deniedUsers }}</p>
            </div>
        </div>

        <!-- Pending Users Table -->
        <div class="users-table">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Pending User Approvals</h2>
            </div>
            
            @if($pendingUsers->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Faculty</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pendingUsers as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->phone_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->faculty }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="status-badge status-{{ $user->status }}">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                        <form method="POST" action="{{ route('admin.users.update-status', $user) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="active">
                                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs font-medium">
                                                Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.users.update-status', $user) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="denied">
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs font-medium">
                                                Deny
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-8 text-center text-gray-500">
                    No pending users to approve.
                </div>
            @endif
        </div>
    </div>

    @filamentScripts
</body>
</html>