<!DOCTYPE html>
<html>
<head>
    <title>Test Authentication Status</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Authentication Status Test</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- User Authentication Status -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4">User Authentication</h2>
                
                @auth
                    <div class="text-green-600 mb-4">
                        <i class="fas fa-check-circle"></i> User is authenticated
                    </div>
                    <div class="space-y-2">
                        <p><strong>ID:</strong> {{ auth()->id() }}</p>
                        <p><strong>Name:</strong> {{ auth()->user()->name }}</p>
                        <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                    </div>
                @else
                    <div class="text-red-600">
                        <i class="fas fa-times-circle"></i> User is NOT authenticated
                    </div>
                @endauth
                
                <div class="mt-4 space-y-2">
                    <a href="/login" class="inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">User Login</a>
                    <a href="/dashboard" class="inline-block bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">User Dashboard</a>
                    
                    @auth
                        <form method="POST" action="/logout" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">User Logout</button>
                        </form>
                    @endauth
                </div>
            </div>
            
            <!-- Admin Authentication Status -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4">Admin Authentication</h2>
                
                @if(session('admin_id'))
                    <div class="text-green-600 mb-4">
                        <i class="fas fa-check-circle"></i> Admin is authenticated
                    </div>
                    <div class="space-y-2">
                        <p><strong>Admin ID:</strong> {{ session('admin_id') }}</p>
                        <p><strong>Admin Name:</strong> {{ session('admin_name') }}</p>
                    </div>
                @else
                    <div class="text-red-600">
                        <i class="fas fa-times-circle"></i> Admin is NOT authenticated
                    </div>
                @endif
                
                <div class="mt-4 space-y-2">
                    <a href="/admin/login" class="inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Admin Login</a>
                    <a href="/admin" class="inline-block bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Admin Dashboard</a>
                    
                    @if(session('admin_id'))
                        <form method="POST" action="/admin/logout" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Admin Logout</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="mt-8 bg-yellow-50 border border-yellow-200 p-4 rounded">
            <h3 class="font-semibold text-yellow-800 mb-2">Test Instructions:</h3>
            <ol class="list-decimal list-inside space-y-1 text-yellow-700">
                <li>Try accessing User Dashboard without login - should redirect to login</li>
                <li>Login as user, access dashboard, then logout and try dashboard again</li>
                <li>Try accessing Admin Dashboard without login - should redirect to admin login</li>
                <li>Login as admin, access dashboard, then logout and try dashboard again</li>
            </ol>
        </div>
    </div>
</body>
</html>