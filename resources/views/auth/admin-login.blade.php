<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - Pelangi Weaving</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#FEF2F2',
                            100: '#FEE2E2',
                            200: '#FECACA',
                            300: '#FCA5A5',
                            400: '#F87171',
                            500: '#EF4444',
                            600: '#DC2626',
                            700: '#B91C1C',
                            800: '#991B1B',
                            900: '#7F1D1D',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation Bar -->
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center group">
                    <div class="relative">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-600 to-primary-700 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg group-hover:shadow-xl transition-all duration-300 transform group-hover:scale-105">
                            PW
                        </div>
                        <!-- Subtle glow effect -->
                        <div class="absolute inset-0 bg-primary-600 rounded-xl blur opacity-20 group-hover:opacity-30 transition-opacity"></div>
                    </div>
                    <div class="ml-3">
                        <div class="font-bold text-gray-900 text-lg">Pelangi</div>
                        <div class="text-xs text-gray-600 -mt-1">Traditional Weaving</div>
                    </div>
                </a>
                
                <!-- Navigation Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-primary-600 font-medium transition">Home</a>
                    <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-primary-600 font-medium transition">Produk</a>
                    <a href="{{ route('custom-orders.index') }}" class="text-gray-700 hover:text-primary-600 font-medium transition">Custom Order</a>
                    <a href="#" class="text-gray-700 hover:text-primary-600 font-medium transition">Tentang</a>
                    <a href="#" class="text-gray-700 hover:text-primary-600 font-medium transition">Kontak</a>
                </div>
                
                <!-- Right Side -->
                <div class="flex items-center space-x-4">
                    <i class="bi bi-bell text-gray-600 text-lg"></i>
                    <div class="flex items-center space-x-2">
                        <i class="bi bi-person-circle text-primary-600 text-2xl"></i>
                        <span class="text-gray-700 font-medium">Admin</span>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="flex items-center justify-center min-h-[calc(100vh-4rem)] py-12 px-4">
        <div class="max-w-md w-full">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Admin Login</h1>
                <p class="text-gray-600">Masuk ke panel admin Pelangi Weaving</p>
            </div>

            <!-- Login Form -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
                @if (session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="bi bi-exclamation text-red-600 text-xs"></i>
                        </div>
                        <p class="text-sm text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
                @endif

                @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="bi bi-check text-green-600 text-xs"></i>
                        </div>
                        <p class="text-sm text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                <form method="POST" action="{{ route('admin.login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Field -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', 'admin@pelangiweaving.com') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('email') border-red-500 @enderror"
                               placeholder="admin@pelangiweaving.com">
                        @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Password</label>
                        <input type="password" name="password" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('password') border-red-500 @enderror"
                               placeholder="••••••••">
                        @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                            <span class="ml-2 text-sm text-gray-700">Ingat saya</span>
                        </label>
                        <a href="#" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                            Lupa password?
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-primary-600 text-white py-3 rounded-xl font-semibold hover:bg-primary-700 transition-colors shadow-lg hover:shadow-xl">
                        Masuk ke Admin Panel
                    </button>
                </form>
            </div>

            <!-- Footer Link -->
            <div class="mt-8 text-center">
                <a href="{{ route('home') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-primary-600 transition-colors">
                    <i class="bi bi-arrow-left mr-2"></i>
                    Kembali ke Halaman Utama
                </a>
            </div>
        </div>
    </div>
</body>
</html>