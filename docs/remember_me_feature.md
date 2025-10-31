# Remember Me Feature

## 📋 Overview
Fitur "Remember Me" untuk login user dan admin yang memungkinkan user untuk tetap login dalam jangka waktu tertentu tanpa perlu memasukkan credentials berulang kali.

## 🎯 Features

### User Remember Me
- **Laravel Built-in**: Menggunakan sistem remember me bawaan Laravel
- **Auto-login**: User otomatis login jika remember me aktif
- **Secure**: Token disimpan dengan enkripsi Laravel
- **Duration**: Configurable melalui config/auth.php

### Admin Remember Me 
- **Custom Implementation**: Implementasi khusus untuk admin session
- **Cookie-based**: Menggunakan secure cookies untuk remember token
- **30-day Duration**: Token valid selama 30 hari
- **Auto-cleanup**: Token dibersihkan saat logout

## 🔧 Technical Implementation

### User Login (UserAuthController.php)
```php
public function login(Request $request)
{
    // Validate remember checkbox
    $request->validate([
        'phone_number' => 'required|string',
        'password' => 'required|string',
        'remember' => 'boolean',
    ]);
    
    // Login with remember functionality
    $remember = $request->boolean('remember');
    Auth::login($user, $remember);
}
```

### Admin Login (AdminAuthController.php)
```php
public function login(Request $request)
{
    // Handle Remember Me for admin
    if ($request->boolean('remember')) {
        $rememberToken = \Str::random(60);
        $admin->update(['remember_token' => $rememberToken]);
        
        // Set long-lived cookies (30 days)
        cookie()->queue('admin_remember_token', $rememberToken, 60 * 24 * 30);
        cookie()->queue('admin_phone_number', $admin->phone_number, 60 * 24 * 30);
    }
}
```

### Auto-Login Check
```php
public function showLoginForm(Request $request)
{
    // Check admin remember me cookies
    $rememberToken = $request->cookie('admin_remember_token');
    $phoneNumber = $request->cookie('admin_phone_number');
    
    if ($rememberToken && $phoneNumber) {
        $admin = Admin::where('phone_number', $phoneNumber)
                     ->where('remember_token', $rememberToken)
                     ->first();
        
        if ($admin) {
            session(['admin_id' => $admin->id]);
            return redirect('/admin');
        }
    }
}
```

## 🎨 UI/UX Features

### Checkbox Design
- **Consistent styling** dengan design system
- **Icon integration** untuk visual appeal
- **Clear labeling** untuk user understanding
- **Auto-check** untuk remembered users (admin)

### User Login Form
```html
<div class="flex items-center">
    <input type="checkbox" id="remember" name="remember" value="1"
           class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
    <label for="remember" class="ml-2 block text-sm text-gray-700">
        <i class="fas fa-heart mr-1 text-red-400"></i>Ingat saya
    </label>
</div>
```

### Admin Login Form
```html
<div class="flex items-center">
    <input type="checkbox" id="remember" name="remember" value="1"
           {{ isset($remembered_phone) ? 'checked' : '' }}
           class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
    <label for="remember" class="ml-2 block text-sm text-gray-700">
        <i class="fas fa-shield-alt mr-1 text-red-400"></i>Ingat saya
    </label>
</div>
```

## 🔐 Security Features

### User Security
- **Laravel encryption** untuk remember tokens
- **Secure cookies** dengan proper flags
- **Token rotation** pada setiap login
- **Auto-expiration** berdasarkan config

### Admin Security
- **Random token generation** menggunakan Str::random(60)
- **Database token verification** untuk setiap request
- **Cookie security flags** (httpOnly, secure)
- **Token cleanup** pada logout

### Token Management
```php
// Admin logout - clear all remember data
public function logout(Request $request)
{
    $admin = Admin::find(session('admin_id'));
    if ($admin) {
        $admin->update(['remember_token' => null]); // Clear DB token
    }
    
    // Clear cookies
    cookie()->queue(cookie()->forget('admin_remember_token'));
    cookie()->queue(cookie()->forget('admin_phone_number'));
}
```

## 📊 Database Changes

### Migration: Add remember_token to admins
```php
Schema::table('admins', function (Blueprint $table) {
    $table->string('remember_token', 100)->nullable()->after('password');
});
```

### Admin Model Update
```php
protected $fillable = [
    'name',
    'nip_nidn_nidk_nim', 
    'phone_number',
    'password',
    'remember_token', // Added
];
```

## 🎯 User Experience

### Login Flow with Remember Me
1. **User checks "Ingat saya"** pada login form
2. **System saves remember token** (user: Laravel, admin: cookie)
3. **User closes browser** dan return later
4. **Auto-login** jika token masih valid
5. **Redirect to dashboard** tanpa perlu login ulang

### Visual Indicators
- ✅ **Checkbox checked** untuk remembered users (admin)
- ✅ **Phone number pre-filled** untuk admin
- ✅ **Icon differentiation**: heart untuk user, shield untuk admin
- ✅ **Smooth transitions** dan hover effects

## ⚙️ Configuration

### Laravel Remember Me Duration
```php
// config/auth.php
'remember' => [
    'duration' => 2628000, // 30 days in seconds
],
```

### Admin Cookie Duration
```php
// 30 days = 60 minutes * 24 hours * 30 days
$cookieDuration = 60 * 24 * 30;
```

## 🧪 Testing Scenarios

### Test Case 1: User Remember Me
1. Login dengan checkbox checked
2. Close browser completely
3. Return dan access protected route
4. Should auto-login via Laravel remember token

### Test Case 2: Admin Remember Me  
1. Login dengan checkbox checked
2. Close browser
3. Return to admin login page
4. Should auto-redirect to admin dashboard

### Test Case 3: Token Expiration
1. Wait beyond token expiration
2. Try to access protected routes
3. Should redirect to login (expired tokens cleared)

### Test Case 4: Logout Cleanup
1. Login dengan remember me
2. Logout explicitly
3. Return to login page
4. Should not auto-login (tokens cleared)

## 🚀 Benefits

### For Users
- **Convenience** - tidak perlu login berulang kali
- **Time saving** - akses langsung ke dashboard
- **Better UX** - smooth login experience
- **Secure** - menggunakan Laravel encryption

### For Admins
- **Efficiency** - faster access untuk admin tasks
- **Security** - token-based dengan expiration
- **Flexibility** - manual logout tetap tersedia
- **Control** - checkbox untuk opt-in/out

### For System
- **Performance** - reduced login requests
- **Security** - proper token management
- **Scalability** - efficient session handling
- **Maintainability** - clean implementation

## 🔄 Future Enhancements

### Potential Improvements
- **Device tracking** untuk security monitoring
- **Multiple device** remember me support
- **Admin panel** untuk manage remember tokens
- **Security notifications** untuk new device logins
- **Token refresh** mechanism untuk extended sessions