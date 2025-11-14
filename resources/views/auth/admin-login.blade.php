<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - Pelangi Weaving</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-red: #DC2626;
            --dark-red: #B91C1C;
            --black: #1F2937;
            --dark-black: #111827;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #111827 0%, #1F2937 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body>
    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full">
            <!-- Header -->
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-white mb-2">Admin Panel</h2>
                <p class="text-sm" style="color: #9CA3AF;">Masuk ke dashboard administrator</p>
            </div>

            <!-- Login Form -->
            <div class="bg-white rounded-lg p-8" style="box-shadow: 0 4px 16px rgba(0,0,0,0.2);">
                @if (session('error'))
                <div class="mb-4 p-3 bg-red-50 border-l-4 rounded" style="border-color: #DC2626;">
                    <p class="text-sm" style="color: #DC2626;">{{ session('error') }}</p>
                </div>
                @endif

                @if (session('success'))
                <div class="mb-4 p-3 bg-green-50 border-l-4 border-green-500 rounded">
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
                @endif

                <form method="POST" action="{{ route('admin.login') }}" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium mb-2" style="color: #1F2937;">
                            Email Administrator
                        </label>
                        <input id="email" 
                               name="email" 
                               type="email" 
                               autocomplete="email" 
                               required
                               value="{{ old('email') }}"
                               class="block w-full px-4 py-3 border rounded-lg transition-all @error('email') border-red-500 @enderror"
                               style="border-color: #E5E7EB;"
                               placeholder="admin@pelangiweaving.com"
                               onfocus="this.style.borderColor='#DC2626'; this.style.outline='none'; this.style.boxShadow='0 0 0 3px rgba(220, 38, 38, 0.1)';"
                               onblur="this.style.borderColor='#E5E7EB'; this.style.boxShadow='none';">
                        @error('email')
                        <p class="mt-1 text-sm" style="color: #DC2626;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium mb-2" style="color: #1F2937;">
                            Password
                        </label>
                        <input id="password" 
                               name="password" 
                               type="password" 
                               autocomplete="current-password" 
                               required
                               class="block w-full px-4 py-3 border rounded-lg transition-all @error('password') border-red-500 @enderror"
                               style="border-color: #E5E7EB;"
                               placeholder="••••••••"
                               onfocus="this.style.borderColor='#DC2626'; this.style.outline='none'; this.style.boxShadow='0 0 0 3px rgba(220, 38, 38, 0.1)';"
                               onblur="this.style.borderColor='#E5E7EB'; this.style.boxShadow='none';">
                        @error('password')
                        <p class="mt-1 text-sm" style="color: #DC2626;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input id="remember_me" 
                               name="remember" 
                               type="checkbox"
                               class="h-4 w-4 rounded"
                               style="color: #DC2626; border-color: #E5E7EB;">
                        <label for="remember_me" class="ml-2 block text-sm" style="color: #6B7280;">
                            Ingat saya
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full py-3 px-4 text-white font-semibold rounded-lg transition-all"
                            style="background: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%);"
                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(220, 38, 38, 0.3)';"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                        Masuk ke Dashboard
                    </button>
                </form>
            </div>

            <!-- Back to Home -->
            <div class="mt-6 text-center">
                <a href="{{ route('home') }}" class="text-sm transition-colors" style="color: #9CA3AF;" onmouseover="this.style.color='#FFFFFF'" onmouseout="this.style.color='#9CA3AF'">
                    <i class="bi bi-arrow-left mr-1"></i>
                    Kembali ke Beranda
                </a>
            </div>

            <!-- Info -->
            <div class="mt-6 text-center">
                <p class="text-xs" style="color: #6B7280;">
                    Area terbatas untuk administrator
                </p>
            </div>
        </div>
    </div>
</body>
</html>
