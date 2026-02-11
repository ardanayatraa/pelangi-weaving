@extends('layouts.customer')

@section('title', 'Profil Saya - Pelangi Weaving')

@push('styles')
<style>
    @keyframes modalSlideUp {
        from {
            opacity: 0;
            transform: translateY(20px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    .animate-modal-slide-up {
        animation: modalSlideUp 0.3s ease-out;
    }
    
    #modalDeleteAlamat:not(.hidden),
    #modalAlamat:not(.hidden) {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
</style>
@endpush

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Profil Saya</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Sidebar Profile -->
            <div class="lg:col-span-1">
                <!-- Profile Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
                    <div class="text-center">
                        <!-- Profile Photo -->
                        <div class="w-24 h-24 bg-gray-200 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <i class="bi bi-person-fill text-4xl text-gray-400"></i>
                        </div>
                        
                        <!-- Profile Info -->
                        <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $pelanggan->nama ?? 'User' }}</h3>
                        <p class="text-sm text-gray-600 mb-2">{{ $pelanggan->email }}</p>
                        <p class="text-xs text-gray-500 mb-4">Member sejak {{ $pelanggan->created_at->format('M Y') }}</p>
                        
                        <!-- Upload Photo Button -->
                        <button class="w-full bg-primary-600 text-white py-2 px-4 rounded-lg text-sm font-medium hover:bg-primary-700 transition-colors">
                            <i class="bi bi-camera mr-2"></i>
                            Ubah Foto
                        </button>
                    </div>
                </div>

                <!-- Menu Profile -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 bg-gray-50 border-b border-gray-200">
                        <h4 class="font-semibold text-gray-900">Menu Profil</h4>
                    </div>
                    <nav class="p-2">
                        <a href="#" onclick="showTab('informasi')" id="tab-informasi" 
                           class="profile-tab active flex items-center w-full px-4 py-3 text-left text-sm font-medium rounded-lg transition-colors">
                            <i class="bi bi-person mr-3 text-primary-600"></i>
                            Informasi Pribadi
                        </a>
                        <a href="#" onclick="showTab('alamat')" id="tab-alamat"
                           class="profile-tab flex items-center w-full px-4 py-3 text-left text-sm font-medium rounded-lg transition-colors">
                            <i class="bi bi-geo-alt mr-3 text-gray-400"></i>
                            Daftar Alamat
                        </a>
                        <a href="#" onclick="showTab('password')" id="tab-password"
                           class="profile-tab flex items-center w-full px-4 py-3 text-left text-sm font-medium rounded-lg transition-colors">
                            <i class="bi bi-shield-lock mr-3 text-gray-400"></i>
                            Ubah Password
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3">
                <!-- Informasi Pribadi Tab -->
                <div id="content-informasi" class="tab-content">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold text-gray-900">Informasi Pribadi</h2>
                            <button onclick="toggleEdit('informasi')" id="edit-btn-informasi" 
                                    class="px-4 py-2 border border-primary-300 text-primary-600 rounded-lg hover:bg-primary-50 transition-colors">
                                <i class="bi bi-pencil mr-2"></i>
                                Edit
                            </button>
                        </div>

                        <form id="form-informasi" action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nama Lengkap -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                    <input type="text" name="nama" value="{{ $pelanggan->nama ?? '' }}" readonly
                                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('nama') border-red-500 @enderror">
                                    @error('nama')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input type="email" name="email" value="{{ $pelanggan->email ?? '' }}" readonly
                                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('email') border-red-500 @enderror">
                                    @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nomor Telepon -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                                    <input type="tel" name="telepon" value="{{ $pelanggan->telepon ?? '' }}" readonly
                                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('telepon') border-red-500 @enderror">
                                    @error('telepon')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- WhatsApp -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">WhatsApp</label>
                                    <input type="tel" name="whatsapp" value="{{ $pelanggan->whatsapp ?? '' }}" readonly
                                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('whatsapp') border-red-500 @enderror">
                                    @error('whatsapp')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Kode Pos -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Pos</label>
                                    <input type="text" name="kode_pos" value="{{ $pelanggan->kode_pos ?? '' }}" readonly
                                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('kode_pos') border-red-500 @enderror">
                                    @error('kode_pos')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-start">
                                    <i class="bi bi-info-circle text-blue-600 mr-3 mt-0.5"></i>
                                    <div>
                                        <p class="text-sm text-blue-800 font-medium">Kelola Alamat Pengiriman</p>
                                        <p class="text-sm text-blue-700 mt-1">Untuk menambah atau mengubah alamat pengiriman, silakan ke tab <strong>"Daftar Alamat"</strong></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Save Buttons (Hidden by default) -->
                            <div id="save-buttons-informasi" class="mt-6 flex justify-end space-x-3" style="display: none;">
                                <button type="button" onclick="cancelEdit('informasi')" 
                                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                    Batal
                                </button>
                                <button type="submit" 
                                        class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                                    <i class="bi bi-check-circle mr-2"></i>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Daftar Alamat Tab -->
                <div id="content-alamat" class="tab-content" style="display: none;">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 mb-1">Daftar Alamat</h2>
                                <p class="text-sm text-gray-600">Kelola alamat pengiriman Anda</p>
                            </div>
                            <button onclick="openModalAlamat()" 
                                    class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                                <i class="bi bi-plus-circle mr-2"></i>
                                Tambah Alamat
                            </button>
                        </div>

                        @php
                            $alamatList = $pelanggan->getAlamatList();
                            $defaultIndex = $pelanggan->alamat_default_index ?? 0;
                        @endphp

                        @if(empty($alamatList))
                            <!-- Empty State -->
                            <div class="text-center py-12">
                                <div class="w-24 h-24 bg-gray-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                                    <i class="bi bi-geo-alt text-4xl text-gray-400"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Alamat</h3>
                                <p class="text-gray-600 mb-6">Tambahkan alamat pengiriman untuk memudahkan checkout</p>
                                <button onclick="openModalAlamat()" 
                                        class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                                    <i class="bi bi-plus-circle mr-2"></i>
                                    Tambah Alamat Pertama
                                </button>
                            </div>
                        @else
                            <!-- Alamat List -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($alamatList as $index => $alamat)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-primary-300 transition-colors {{ $index === $defaultIndex ? 'ring-2 ring-primary-500 border-primary-500' : '' }}">
                                    <!-- Label & Default Badge -->
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center space-x-2">
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                                {{ $alamat['label'] === 'rumah' ? 'bg-blue-100 text-blue-700' : '' }}
                                                {{ $alamat['label'] === 'kantor' ? 'bg-purple-100 text-purple-700' : '' }}
                                                {{ $alamat['label'] === 'lainnya' ? 'bg-gray-100 text-gray-700' : '' }}">
                                                <i class="bi bi-{{ $alamat['label'] === 'rumah' ? 'house' : ($alamat['label'] === 'kantor' ? 'building' : 'geo-alt') }} mr-1"></i>
                                                {{ ucfirst($alamat['label']) }}
                                            </span>
                                            @if($index === $defaultIndex)
                                            <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full">
                                                <i class="bi bi-check-circle mr-1"></i>
                                                Utama
                                            </span>
                                            @endif
                                        </div>
                                        
                                        <!-- Actions Dropdown -->
                                        <div class="relative">
                                            <button onclick="toggleDropdown({{ $index }})" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                                <i class="bi bi-three-dots-vertical text-gray-600"></i>
                                            </button>
                                            <div id="dropdown-{{ $index }}" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                                                @if($index !== $defaultIndex)
                                                <form action="{{ route('alamat.set-default', $index) }}" method="POST" class="block">
                                                    @csrf
                                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center">
                                                        <i class="bi bi-check-circle mr-2 text-green-600"></i>
                                                        Jadikan Utama
                                                    </button>
                                                </form>
                                                @endif
                                                <button onclick="editAlamat({{ $index }}, '{{ $alamat['label'] }}', '{{ $alamat['nama_penerima'] }}', '{{ $alamat['telepon'] }}', '{{ $alamat['alamat_lengkap'] }}', '{{ $alamat['kota'] }}', '{{ $alamat['provinsi'] }}', '{{ $alamat['kode_pos'] }}')" 
                                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center">
                                                    <i class="bi bi-pencil mr-2 text-blue-600"></i>
                                                    Edit
                                                </button>
                                                <button onclick="confirmDeleteAlamat({{ $index }})" 
                                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center">
                                                    <i class="bi bi-trash mr-2"></i>
                                                    Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Alamat Details -->
                                    <div class="space-y-2">
                                        <div class="flex items-start">
                                            <i class="bi bi-person text-gray-400 mr-2 mt-1"></i>
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $alamat['nama_penerima'] }}</p>
                                                <p class="text-sm text-gray-600">{{ $alamat['telepon'] }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <i class="bi bi-geo-alt text-gray-400 mr-2 mt-1"></i>
                                            <p class="text-sm text-gray-700">
                                                {{ $alamat['alamat_lengkap'] }}<br>
                                                {{ $alamat['kota'] }}, {{ $alamat['provinsi'] }} {{ $alamat['kode_pos'] }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Ubah Password Tab -->
                <div id="content-password" class="tab-content" style="display: none;">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <div class="mb-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-2">Ubah Password</h2>
                            <p class="text-gray-600">Pastikan akun Anda menggunakan password yang panjang dan acak untuk tetap aman</p>
                        </div>

                        <form action="{{ route('profile.password') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="space-y-6">
                                <!-- Current Password -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Password Saat Ini</label>
                                    <div class="relative">
                                        <input type="password" name="current_password" id="current_password" 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('current_password') border-red-500 @enderror"
                                               placeholder="Masukkan password saat ini">
                                        <button type="button" onclick="togglePassword('current_password')" 
                                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                            <i class="bi bi-eye" id="current_password-icon"></i>
                                        </button>
                                    </div>
                                    @error('current_password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- New Password -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                                    <div class="relative">
                                        <input type="password" name="password" id="password" 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('password') border-red-500 @enderror"
                                               placeholder="Masukkan password baru">
                                        <button type="button" onclick="togglePassword('password')" 
                                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                            <i class="bi bi-eye" id="password-icon"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-sm text-gray-600">Password minimal 8 karakter</p>
                                </div>

                                <!-- Confirm Password -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                                    <div class="relative">
                                        <input type="password" name="password_confirmation" id="password_confirmation" 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                               placeholder="Konfirmasi password baru">
                                        <button type="button" onclick="togglePassword('password_confirmation')" 
                                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                            <i class="bi bi-eye" id="password_confirmation-icon"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Password Strength Indicator -->
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="text-sm font-medium text-gray-900 mb-3">Keamanan Password:</h4>
                                    <div class="space-y-2">
                                        <div class="flex items-center text-sm">
                                            <i class="bi bi-check-circle text-green-500 mr-2"></i>
                                            <span class="text-gray-600">Minimal 8 karakter</span>
                                        </div>
                                        <div class="flex items-center text-sm">
                                            <i class="bi bi-circle text-gray-300 mr-2"></i>
                                            <span class="text-gray-600">Mengandung huruf besar dan kecil</span>
                                        </div>
                                        <div class="flex items-center text-sm">
                                            <i class="bi bi-circle text-gray-300 mr-2"></i>
                                            <span class="text-gray-600">Mengandung angka</span>
                                        </div>
                                        <div class="flex items-center text-sm">
                                            <i class="bi bi-circle text-gray-300 mr-2"></i>
                                            <span class="text-gray-600">Mengandung karakter khusus</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Save Button -->
                            <div class="mt-8 flex justify-end space-x-3">
                                <button type="button" 
                                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                    Batal
                                </button>
                                <button type="submit" 
                                        class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                                    <i class="bi bi-shield-check mr-2"></i>
                                    Ubah Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Alamat -->
<div id="modalAlamat" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4" style="backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); background-color: rgba(0, 0, 0, 0.4);">
    <!-- Modal Content -->
    <div class="relative bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden transform transition-all animate-modal-slide-up">
        <!-- Header -->
        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-primary-50 to-white">
            <div class="flex items-center justify-between">
                <h3 id="modalTitle" class="text-2xl font-bold text-gray-900">Tambah Alamat Baru</h3>
                <button onclick="closeModalAlamat()" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full p-2 transition-all">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
        </div>
        
        <!-- Form Content -->
        <div class="overflow-y-auto max-h-[calc(90vh-140px)]">
            <form id="formAlamat" method="POST" class="p-6">
                    @csrf
            <input type="hidden" id="methodField" name="_method" value="POST">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Label Alamat -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Label Alamat</label>
                    <div class="flex space-x-3">
                        <label class="flex-1 group cursor-pointer">
                            <input type="radio" name="label" value="rumah" class="sr-only" checked>
                            <div class="label-alamat-option border-2 border-gray-300 rounded-lg p-3 text-center transition-colors hover:border-primary-400 group-has-[:checked]:border-primary-600 group-has-[:checked]:bg-primary-50">
                                <i class="bi bi-house-door text-xl"></i>
                                <p class="text-sm font-medium mt-1">Rumah</p>
                            </div>
                        </label>
                        <label class="flex-1 group cursor-pointer">
                            <input type="radio" name="label" value="kantor" class="sr-only">
                            <div class="label-alamat-option border-2 border-gray-300 rounded-lg p-3 text-center transition-colors hover:border-primary-400 group-has-[:checked]:border-primary-600 group-has-[:checked]:bg-primary-50">
                                <i class="bi bi-building text-xl"></i>
                                <p class="text-sm font-medium mt-1">Kantor</p>
                            </div>
                        </label>
                        <label class="flex-1 group cursor-pointer">
                            <input type="radio" name="label" value="lainnya" class="sr-only">
                            <div class="label-alamat-option border-2 border-gray-300 rounded-lg p-3 text-center transition-colors hover:border-primary-400 group-has-[:checked]:border-primary-600 group-has-[:checked]:bg-primary-50">
                                <i class="bi bi-geo-alt text-xl"></i>
                                <p class="text-sm font-medium mt-1">Lainnya</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Nama Penerima -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Penerima</label>
                    <input type="text" name="nama_penerima" id="nama_penerima" required
                           value="{{ $pelanggan->nama ?? '' }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                           placeholder="Nama lengkap penerima">
                </div>

                <!-- Nomor Telepon -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                    <input type="tel" name="telepon" id="telepon" required
                           value="{{ $pelanggan->telepon ?? '' }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                           placeholder="08xxxxxxxxxx">
                </div>

                <!-- Alamat Lengkap -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</label>
                    <textarea name="alamat_lengkap" id="alamat_lengkap" rows="3" required
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                              placeholder="Jalan, nomor rumah, RT/RW, kelurahan, kecamatan"></textarea>
                </div>

                <!-- Kota -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kota/Kabupaten</label>
                    <input type="text" name="kota" id="kota" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                           placeholder="Contoh: Denpasar">
                </div>

                <!-- Provinsi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
                    <input type="text" name="provinsi" id="provinsi" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                           placeholder="Contoh: Bali">
                </div>

                <!-- Kode Pos -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Pos</label>
                    <input type="text" name="kode_pos" id="kode_pos" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                           placeholder="Contoh: 80361">
                </div>

                <!-- Set Default -->
                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_default" id="is_default" value="1" class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <span class="ml-2 text-sm text-gray-700">Jadikan alamat utama</span>
                    </label>
                </div>
            </div>

            <!-- Footer dengan tombol -->
            <div class="mt-6 flex gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeModalAlamat()" 
                        class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 font-semibold shadow-sm">
                    Batal
                </button>
                <button type="submit" 
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-xl hover:from-primary-700 hover:to-primary-800 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl transform hover:scale-105">
                    <i class="bi bi-check-circle mr-2"></i>
                    Simpan Alamat
                </button>
            </div>
        </form>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus Alamat -->
<div id="modalDeleteAlamat" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4" style="backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); background-color: rgba(0, 0, 0, 0.4);">
    <!-- Modal Content -->
    <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all animate-modal-slide-up">
        <div class="p-6">
            <!-- Icon -->
            <div class="flex items-center justify-center w-20 h-20 mx-auto bg-gradient-to-br from-red-100 to-red-50 rounded-full mb-4 shadow-lg">
                <div class="relative">
                    <i class="bi bi-exclamation-triangle text-4xl text-red-600"></i>
                    <div class="absolute inset-0 bg-red-600 opacity-20 blur-xl rounded-full"></div>
                </div>
            </div>
            
            <!-- Title & Description -->
            <div class="text-center mb-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Hapus Alamat?</h3>
                <p class="text-gray-600 leading-relaxed">
                    Apakah Anda yakin ingin menghapus alamat ini? <br>
                    <span class="text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan.</span>
                </p>
            </div>

            <!-- Form -->
            <form id="formDeleteAlamat" method="POST" action="">
                @csrf
                @method('DELETE')
                
                <div class="flex gap-3">
                    <button type="button" onclick="closeModalDeleteAlamat()" 
                            class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 font-semibold shadow-sm">
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl transform hover:scale-105">
                        <i class="bi bi-trash mr-2"></i>
                        Ya, Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Tab Management
function showTab(tabName) {
    // Hide all tab contents
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => {
        content.style.display = 'none';
    });
    
    // Remove active class from all tabs
    const tabs = document.querySelectorAll('.profile-tab');
    tabs.forEach(tab => {
        tab.classList.remove('active');
        const icon = tab.querySelector('i');
        icon.classList.remove('text-primary-600');
        icon.classList.add('text-gray-400');
    });
    
    // Show selected tab content
    document.getElementById('content-' + tabName).style.display = 'block';
    
    // Add active class to selected tab
    const activeTab = document.getElementById('tab-' + tabName);
    activeTab.classList.add('active');
    const activeIcon = activeTab.querySelector('i');
    activeIcon.classList.remove('text-gray-400');
    activeIcon.classList.add('text-primary-600');
}

// Dropdown Management for Alamat
function toggleDropdown(index) {
    const dropdown = document.getElementById('dropdown-' + index);
    const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');
    
    // Close all other dropdowns
    allDropdowns.forEach(d => {
        if (d.id !== 'dropdown-' + index) {
            d.classList.add('hidden');
        }
    });
    
    // Toggle current dropdown
    dropdown.classList.toggle('hidden');
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('[onclick^="toggleDropdown"]') && !e.target.closest('[id^="dropdown-"]')) {
        document.querySelectorAll('[id^="dropdown-"]').forEach(d => d.classList.add('hidden'));
    }
});

// Edit Mode Management
function toggleEdit(section) {
    const form = document.getElementById('form-' + section);
    const inputs = form.querySelectorAll('input:not([type="hidden"]), select, textarea');
    const editBtn = document.getElementById('edit-btn-' + section);
    const saveButtons = document.getElementById('save-buttons-' + section);
    
    // Cek readonly dari input pertama yang visible (bukan hidden)
    const firstEditable = form.querySelector('input[type="text"], input[type="email"], input[type="tel"], textarea');
    const isReadonly = !firstEditable || firstEditable.hasAttribute('readonly') || firstEditable.hasAttribute('disabled');
    
    if (isReadonly) {
        // Enable editing
        inputs.forEach(input => {
            input.removeAttribute('readonly');
            input.removeAttribute('disabled');
            input.classList.add('bg-white');
        });
        editBtn.innerHTML = '<i class="bi bi-x mr-2"></i>Batal';
        editBtn.classList.remove('border-primary-300', 'text-primary-600', 'hover:bg-primary-50');
        editBtn.classList.add('border-red-300', 'text-red-600', 'hover:bg-red-50');
        saveButtons.style.display = 'flex';
    } else {
        // Cancel editing
        cancelEdit(section);
    }
}

function cancelEdit(section) {
    const form = document.getElementById('form-' + section);
    const inputs = form.querySelectorAll('input, select, textarea');
    const editBtn = document.getElementById('edit-btn-' + section);
    const saveButtons = document.getElementById('save-buttons-' + section);
    
    // Disable editing
    inputs.forEach(input => {
        if (input.type !== 'hidden') {
            input.setAttribute('readonly', true);
            if (input.tagName === 'SELECT') {
                input.setAttribute('disabled', true);
            }
            input.classList.remove('bg-white');
        }
    });
    
    editBtn.innerHTML = '<i class="bi bi-pencil mr-2"></i>Edit';
    editBtn.classList.remove('border-red-300', 'text-red-600', 'hover:bg-red-50');
    editBtn.classList.add('border-primary-300', 'text-primary-600', 'hover:bg-primary-50');
    saveButtons.style.display = 'none';
    
    // Reset form to original values
    form.reset();
}

// Password Toggle
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}

// Label Alamat: update tampilan active
function updateLabelAlamatActive() {
    document.querySelectorAll('#modalAlamat .label-alamat-option').forEach(function(div) {
        div.classList.remove('label-alamat-active');
    });
    const checked = document.querySelector('#modalAlamat input[name="label"]:checked');
    if (checked) {
        const option = checked.closest('label').querySelector('.label-alamat-option');
        if (option) option.classList.add('label-alamat-active');
    }
}

// Modal Alamat Management
function openModalAlamat() {
    document.getElementById('modalAlamat').classList.remove('hidden');
    document.getElementById('modalTitle').textContent = 'Tambah Alamat Baru';
    document.getElementById('formAlamat').action = '{{ route("alamat.store") }}';
    document.getElementById('methodField').value = 'POST';
    document.getElementById('formAlamat').reset();
    setTimeout(updateLabelAlamatActive, 0);
}

function closeModalAlamat() {
    document.getElementById('modalAlamat').classList.add('hidden');
    document.getElementById('formAlamat').reset();
}

// Modal Delete Alamat Management
function confirmDeleteAlamat(index) {
    const modal = document.getElementById('modalDeleteAlamat');
    const form = document.getElementById('formDeleteAlamat');
    
    // Set form action dengan index alamat
    form.action = '/alamat/' + index;
    
    // Show modal
    modal.classList.remove('hidden');
    
    // Close dropdown
    document.querySelectorAll('[id^="dropdown-"]').forEach(d => d.classList.add('hidden'));
}

function closeModalDeleteAlamat() {
    document.getElementById('modalDeleteAlamat').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('modalDeleteAlamat')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeModalDeleteAlamat();
    }
});


function editAlamat(id, label, nama, telepon, alamat, kota, provinsi, kodePos) {
    document.getElementById('modalAlamat').classList.remove('hidden');
    document.getElementById('modalTitle').textContent = 'Edit Alamat';
    document.getElementById('formAlamat').action = '/alamat/' + id;
    document.getElementById('methodField').value = 'PUT';
    
    // Set form values
    document.querySelector(`input[name="label"][value="${label}"]`).checked = true;
    document.getElementById('nama_penerima').value = nama;
    document.getElementById('telepon').value = telepon;
    document.getElementById('alamat_lengkap').value = alamat;
    document.getElementById('kota').value = kota;
    document.getElementById('provinsi').value = provinsi;
    document.getElementById('kode_pos').value = kodePos;
    updateLabelAlamatActive();
}

// Close modal when clicking outside
document.getElementById('modalAlamat')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeModalAlamat();
    }
});

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Set default tab
    showTab('informasi');
    
    // Add active styles
    const style = document.createElement('style');
    style.textContent = `
        .profile-tab.active {
            background-color: #fef2f2;
            color: #dc2626;
        }
        .profile-tab:hover {
            background-color: #f9fafb;
        }
        .form-input[readonly] {
            background-color: #f9fafb;
            cursor: not-allowed;
        }
        .form-input[disabled] {
            background-color: #f9fafb;
            cursor: not-allowed;
        }
        .label-alamat-option.label-alamat-active {
            border-color: #dc2626 !important;
            background-color: #fef2f2 !important;
        }
    `;
    document.head.appendChild(style);

    // Label alamat: tampilkan state active saat radio berubah
    document.querySelectorAll('#modalAlamat input[name="label"]').forEach(function(radio) {
        radio.addEventListener('change', updateLabelAlamatActive);
    });
});
</script>
@endsection