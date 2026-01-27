@extends('layouts.customer')

@section('title', 'Profil Saya - Pelangi Weaving')

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
                        <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $pelanggan->nama }}</h3>
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
                            Alamat Pengiriman
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
                                    <input type="text" name="nama" value="{{ $pelanggan->nama }}" readonly
                                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('nama') border-red-500 @enderror">
                                    @error('nama')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input type="email" name="email" value="{{ $pelanggan->email }}" readonly
                                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('email') border-red-500 @enderror">
                                    @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nomor Telepon -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                                    <input type="tel" name="telepon" value="{{ $pelanggan->telepon }}" readonly
                                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('telepon') border-red-500 @enderror">
                                    @error('telepon')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Kode Pos -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Pos</label>
                                    <input type="text" name="kode_pos" value="{{ $pelanggan->kode_pos }}" readonly
                                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('kode_pos') border-red-500 @enderror">
                                    @error('kode_pos')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Alamat -->
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</label>
                                <textarea name="alamat" rows="3" readonly
                                          class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('alamat') border-red-500 @enderror">{{ $pelanggan->alamat }}</textarea>
                                @error('alamat')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
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

                <!-- Alamat Pengiriman Tab -->
                <div id="content-alamat" class="tab-content" style="display: none;">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold text-gray-900">Alamat Pengiriman</h2>
                            <button class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                                <i class="bi bi-plus mr-2"></i>
                                Kelola Alamat
                            </button>
                        </div>

                        <!-- Default Address -->
                        <div class="border-2 border-primary-200 bg-primary-50 rounded-xl p-6 mb-4">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-3">
                                        <span class="bg-primary-600 text-white px-3 py-1 rounded-full text-xs font-semibold mr-3">
                                            <i class="bi bi-house-door mr-1"></i>
                                            Alamat Utama
                                        </span>
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-semibold">
                                            Default
                                        </span>
                                    </div>
                                    <h4 class="font-semibold text-gray-900 mb-2">{{ $pelanggan->nama }}</h4>
                                    <p class="text-gray-700 mb-1">{{ $pelanggan->telepon }}</p>
                                    <p class="text-gray-600 text-sm">
                                        {{ $pelanggan->alamat ?? 'Jl. Merdeka No. 123, Jakarta Selatan, DKI Jakarta 12345' }}
                                    </p>
                                </div>
                                <div class="flex space-x-2">
                                    <button class="p-2 text-primary-600 hover:bg-primary-100 rounded-lg transition-colors">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Add New Address Form -->
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center">
                            <i class="bi bi-plus-circle text-4xl text-gray-400 mb-3"></i>
                            <h4 class="font-semibold text-gray-900 mb-2">Tambah Alamat Baru</h4>
                            <p class="text-gray-600 text-sm mb-4">Tambahkan alamat pengiriman untuk kemudahan berbelanja</p>
                            <button class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                                <i class="bi bi-plus mr-2"></i>
                                Tambah Alamat
                            </button>
                        </div>
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

// Edit Mode Management
function toggleEdit(section) {
    const form = document.getElementById('form-' + section);
    const inputs = form.querySelectorAll('input, select, textarea');
    const editBtn = document.getElementById('edit-btn-' + section);
    const saveButtons = document.getElementById('save-buttons-' + section);
    
    const isReadonly = inputs[0].hasAttribute('readonly') || inputs[0].hasAttribute('disabled');
    
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
    `;
    document.head.appendChild(style);
});
</script>
@endsection