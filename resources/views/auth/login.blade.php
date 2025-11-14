<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk - Pelangi Traditional Weaving</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Pelangi Traditional Weaving</h1>
            <p class="text-gray-600">Masuk ke akun Anda</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8">
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600 @error('email') border-red-500 @enderror"
                           placeholder="nama@email.com">
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

                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-red-600 focus:ring-red-600">
                        <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                    </label>
                </div>

                <button type="submit" 
                        class="w-full bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700 transition">
                    Masuk
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-red-600 font-medium hover:text-red-700">
                        Daftar di sini
                    </a>
                </p>
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('home') }}" class="text-sm text-gray-600 hover:text-gray-800">
                <i class="bi bi-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>
