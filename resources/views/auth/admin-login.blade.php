<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - Pelangi Weaving</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-gradient-to-br from-gray-900 to-gray-800 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">Admin Panel</h1>
            <p class="text-gray-400">Masuk ke dashboard administrator</p>
        </div>

        <div class="bg-white rounded-xl shadow-2xl p-8">
            @if (session('error'))
            <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-600 rounded">
                <p class="text-sm text-red-600">{{ session('error') }}</p>
            </div>
            @endif

            @if (session('success'))
            <div class="mb-4 p-3 bg-green-50 border-l-4 border-green-500 rounded">
                <p class="text-sm text-green-800">{{ session('success') }}</p>
            </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Administrator</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600 @error('email') border-red-500 @enderror"
                           placeholder="admin@pelangiweaving.com">
                    @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600 @error('password') border-red-500 @enderror"
                           placeholder="••••••••">
                    @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-red-600 focus:ring-red-600">
                    <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                </div>

                <button type="submit" 
                        class="w-full bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700 transition">
                    Masuk ke Dashboard
                </button>
            </form>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('home') }}" class="text-sm text-gray-400 hover:text-white">
                <i class="bi bi-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>

        <div class="mt-4 text-center">
            <p class="text-xs text-gray-500">Area terbatas untuk administrator</p>
        </div>
    </div>
</body>
</html>
