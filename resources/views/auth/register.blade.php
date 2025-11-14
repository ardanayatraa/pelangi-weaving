<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar - Pelangi Traditional Weaving Sidemen</title>
    
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
            background-color: #F9FAFB;
            min-height: 100vh;
        }
    </style>
</head>
<body>
    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full">
            <!-- Header -->
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold mb-2" style="color: #1F2937;">Pelangi Traditional Weaving</h2>
                <p class="text-sm" style="color: #6B7280;">Buat akun baru untuk mulai belanja</p>
            </div>

            <!-- Register Form -->
            <div class="bg-white rounded-lg p-8" style="box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="nama" class="block text-sm font-medium mb-2" style="color: #1F2937;">
                            Nama Lengkap
                        </label>
                        <input id="nama" 
                               name="nama" 
                               type="text" 
                               autocomplete="name" 
                               required
                               value="{{ old('nama') }}"
                               class="block w-full px-4 py-3 border rounded-lg transition-all @error('nama') border-red-500 @enderror"
                               style="border-color: #E5E7EB;"
                               placeholder="Nama lengkap Anda"
                               onfocus="this.style.borderColor='#DC2626'; this.style.outline='none'; this.style.boxShadow='0 0 0 3px rgba(220, 38, 38, 0.1)';"
                               onblur="this.style.borderColor='#E5E7EB'; this.style.boxShadow='none';">
                        @error('nama')
                        <p class="mt-1 text-sm" style="color: #DC2626;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium mb-2" style="color: #1F2937;">
                            Email
                        </label>
                        <input id="email" 
                               name="email" 
                               type="email" 
                               autocomplete="email" 
                               required
                               value="{{ old('email') }}"
                               class="block w-full px-4 py-3 border rounded-lg transition-all @error('email') border-red-500 @enderror"
                               style="border-color: #E5E7EB;"
                               placeholder="nama@email.com"
                               onfocus="this.style.borderColor='#DC2626'; this.style.outline='none'; this.style.boxShadow='0 0 0 3px rgba(220, 38, 38, 0.1)';"
                               onblur="this.style.borderColor='#E5E7EB'; this.style.boxShadow='none';">
                        @error('email')
                        <p class="mt-1 text-sm" style="color: #DC2626;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="telepon" class="block text-sm font-medium mb-2" style="color: #1F2937;">
                            No. Telepon
                        </label>
                        <input id="telepon" 
                               name="telepon" 
                               type="tel" 
                               autocomplete="tel" 
                               value="{{ old('telepon') }}"
                               class="block w-full px-4 py-3 border rounded-lg transition-all @error('telepon') border-red-500 @enderror"
                               style="border-color: #E5E7EB;"
                               placeholder="08123456789"
                               onfocus="this.style.borderColor='#DC2626'; this.style.outline='none'; this.style.boxShadow='0 0 0 3px rgba(220, 38, 38, 0.1)';"
                               onblur="this.style.borderColor='#E5E7EB'; this.style.boxShadow='none';">
                        @error('telepon')
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
                               autocomplete="new-password" 
                               required
                               class="block w-full px-4 py-3 border rounded-lg transition-all @error('password') border-red-500 @enderror"
                               style="border-color: #E5E7EB;"
                               placeholder="Minimal 8 karakter"
                               onfocus="this.style.borderColor='#DC2626'; this.style.outline='none'; this.style.boxShadow='0 0 0 3px rgba(220, 38, 38, 0.1)';"
                               onblur="this.style.borderColor='#E5E7EB'; this.style.boxShadow='none';">
                        @error('password')
                        <p class="mt-1 text-sm" style="color: #DC2626;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium mb-2" style="color: #1F2937;">
                            Konfirmasi Password
                        </label>
                        <input id="password_confirmation" 
                               name="password_confirmation" 
                               type="password" 
                               autocomplete="new-password" 
                               required
                               class="block w-full px-4 py-3 border rounded-lg transition-all"
                               style="border-color: #E5E7EB;"
                               placeholder="Ulangi password"
                               onfocus="this.style.borderColor='#DC2626'; this.style.outline='none'; this.style.boxShadow='0 0 0 3px rgba(220, 38, 38, 0.1)';"
                               onblur="this.style.borderColor='#E5E7EB'; this.style.boxShadow='none';">
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full py-3 px-4 text-white font-semibold rounded-lg transition-all"
                            style="background: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%);"
                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(220, 38, 38, 0.3)';"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                        Daftar Sekarang
                    </button>
                </form>

                <!-- Login Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm" style="color: #6B7280;">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="font-medium transition-colors" style="color: #DC2626;" onmouseover="this.style.color='#B91C1C'" onmouseout="this.style.color='#DC2626'">
                            Masuk di sini
                        </a>
                    </p>
                </div>
            </div>

            <!-- Back to Home -->
            <div class="mt-6 text-center">
                <a href="{{ route('home') }}" class="text-sm transition-colors" style="color: #6B7280;" onmouseover="this.style.color='#1F2937'" onmouseout="this.style.color='#6B7280'">
                    <i class="bi bi-arrow-left mr-1"></i>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</body>
</html>
