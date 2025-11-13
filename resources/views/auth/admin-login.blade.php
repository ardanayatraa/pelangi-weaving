<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - Pelangi Weaving</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <!-- Logo -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center space-x-2">
                    <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-pink-500 rounded-lg flex items-center justify-center">
                        <i class="bi bi-shield-lock text-white text-2xl"></i>
                    </div>
                    <span class="text-2xl font-bold text-white">Admin Panel</span>
                </div>
                <h2 class="mt-6 text-3xl font-bold text-white">Login Administrator</h2>
                <p class="mt-2 text-sm text-gray-400">Masuk ke dashboard admin</p>
            </div>

            <!-- Login Form -->
            <div class="bg-gray-800 rounded-2xl shadow-2xl p-8 border border-gray-700">
                @if (session('error'))
                <div class="mb-4 p-4 bg-red-900/50 border border-red-700 rounded-lg">
                    <p class="text-sm text-red-200">{{ session('error') }}</p>
                </div>
                @endif

                @if (session('success'))
                <div class="mb-4 p-4 bg-green-900/50 border border-green-700 rounded-lg">
                    <p class="text-sm text-green-200">{{ session('success') }}</p>
                </div>
                @endif

                <form method="POST" action="{{ route('admin.login') }}" class="space-y-6">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                            Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-envelope text-gray-500"></i>
                            </div>
                            <input id="email" 
                                   name="email" 
                                   type="email" 
                                   autocomplete="email" 
                                   required
                                   value="{{ old('email') }}"
                                   class="block w-full pl-10 pr-3 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('email') border-red-500 @enderror"
                                   placeholder="admin@pelangiweaving.com">
                        </div>
                        @error('email')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-lock text-gray-500"></i>
                            </div>
                            <input id="password" 
                                   name="password" 
                                   type="password" 
                                   autocomplete="current-password" 
                                   required
                                   class="block w-full pl-10 pr-3 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('password') border-red-500 @enderror"
                                   placeholder="••••••••">
                        </div>
                        @error('password')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input id="remember_me" 
                               name="remember" 
                               type="checkbox"
                               class="h-4 w-4 text-orange-500 focus:ring-orange-500 border-gray-600 rounded bg-gray-700">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-300">
                            Ingat saya
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-lg text-sm font-bold text-white bg-gradient-to-r from-orange-500 to-pink-500 hover:from-orange-600 hover:to-pink-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all">
                        <i class="bi bi-box-arrow-in-right mr-2"></i>
                        Masuk ke Dashboard
                    </button>
                </form>
            </div>

            <!-- Back to Home -->
            <div class="mt-6 text-center">
                <a href="{{ route('home') }}" class="text-sm text-gray-400 hover:text-white">
                    <i class="bi bi-arrow-left mr-1"></i>
                    Kembali ke Beranda
                </a>
            </div>

            <!-- Info -->
            <div class="mt-8 text-center">
                <p class="text-xs text-gray-500">
                    <i class="bi bi-shield-check mr-1"></i>
                    Area terbatas untuk administrator
                </p>
            </div>
        </div>
    </div>
</body>
</html>
