@extends('layouts.customer')

@section('title', 'Profile Saya')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-4 md:py-8">
    <!-- Header -->
    <div class="bg-gradient-to-r from-red-600 to-red-700 rounded-xl shadow-lg p-4 md:p-6 mb-4 md:mb-6 text-white">
        <div class="flex items-center gap-3 md:gap-4">
            <div class="w-16 h-16 md:w-20 md:h-20 bg-white/20 rounded-full flex items-center justify-center">
                <i class="bi bi-person-fill text-3xl md:text-4xl"></i>
            </div>
            <div>
                <h1 class="text-lg md:text-2xl font-bold">{{ $pelanggan->nama }}</h1>
                <p class="text-sm md:text-base text-red-100">{{ $pelanggan->email }}</p>
            </div>
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-4 md:gap-6">
        <!-- Main Content -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden" x-data="{ tab: 'profile' }">
                <!-- Tabs -->
                <div class="flex border-b">
                    <button @click="tab = 'profile'" 
                            :class="tab === 'profile' ? 'border-b-2 border-red-600 text-red-600' : 'text-gray-600'"
                            class="flex-1 px-6 py-4 font-medium">
                        <i class="bi bi-person-fill"></i> Profile
                    </button>
                    <button @click="tab = 'password'" 
                            :class="tab === 'password' ? 'border-b-2 border-red-600 text-red-600' : 'text-gray-600'"
                            class="flex-1 px-6 py-4 font-medium">
                        <i class="bi bi-shield-lock-fill"></i> Password
                    </button>
                </div>

                <!-- Profile Tab -->
                <div x-show="tab === 'profile'" class="p-6">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="nama" value="{{ old('nama', $pelanggan->nama) }}" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 @error('nama') border-red-500 @enderror">
                                @error('nama')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" name="email" value="{{ old('email', $pelanggan->email) }}" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 @error('email') border-red-500 @enderror">
                                @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Telepon</label>
                                <input type="text" name="telepon" value="{{ old('telepon', $pelanggan->telepon) }}" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 @error('telepon') border-red-500 @enderror">
                                @error('telepon')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kode Pos</label>
                                <input type="text" name="kode_pos" value="{{ old('kode_pos', $pelanggan->kode_pos) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 @error('kode_pos') border-red-500 @enderror">
                                @error('kode_pos')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                                <textarea name="alamat" rows="3" required
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 @error('alamat') border-red-500 @enderror">{{ old('alamat', $pelanggan->alamat) }}</textarea>
                                @error('alamat')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 text-right">
                            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-red-700">
                                <i class="bi bi-check-circle"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Password Tab -->
                <div x-show="tab === 'password'" class="p-6">
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Password Saat Ini</label>
                                <input type="password" name="current_password" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 @error('current_password') border-red-500 @enderror">
                                @error('current_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                                <input type="password" name="password" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 @error('password') border-red-500 @enderror">
                                @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-600">Minimal 8 karakter</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600">
                            </div>
                        </div>

                        <div class="mt-6 text-right">
                            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-red-700">
                                <i class="bi bi-shield-check"></i> Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="space-y-4">
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <i class="bi bi-box-seam text-4xl text-red-600"></i>
                <h3 class="text-3xl font-bold mt-3">{{ $pelanggan->orders()->count() }}</h3>
                <p class="text-gray-600 text-sm">Total Pesanan</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <i class="bi bi-clock-history text-4xl text-red-600"></i>
                <h3 class="text-3xl font-bold mt-3">{{ $pelanggan->orders()->where('status_pesanan', 'pending')->count() }}</h3>
                <p class="text-gray-600 text-sm">Pending</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <i class="bi bi-check-circle-fill text-4xl text-green-500"></i>
                <h3 class="text-3xl font-bold mt-3">{{ $pelanggan->orders()->where('status_pesanan', 'selesai')->count() }}</h3>
                <p class="text-gray-600 text-sm">Selesai</p>
            </div>
        </div>
    </div>
</div>
@endsection
