@extends('layouts.admin')

@section('title', 'Tambah Produk')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.products.index') }}" class="text-gray-700 hover:text-gray-900 font-medium inline-flex items-center">
        <i class="bi bi-arrow-left mr-1"></i> Kembali ke Daftar Produk
    </a>
</div>

<div class="mb-4">
    <h1 class="text-2xl font-bold text-gray-900">Tambah Produk Baru</h1>
    <p class="text-sm text-gray-600 mt-1">Tambahkan produk kain tenun tradisional baru</p>
</div>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Main Form -->
        <div class="lg:col-span-2 space-y-4">
            <!-- Basic Info -->
            <div class="bg-white border border-gray-200 p-4">
                <h2 class="text-sm font-bold text-gray-900 uppercase mb-4">Informasi Dasar</h2>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Produk *</label>
                        <input type="text" 
                               name="nama_produk" 
                               value="{{ old('nama_produk') }}"
                               class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400 @error('nama_produk') border-red-500 @enderror"
                               placeholder="Contoh: Songket Bali Motif Pucuk Rebung"
                               required>
                        @error('nama_produk')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Kategori *</label>
                        <select name="id_kategori" 
                                class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400 @error('id_kategori') border-red-500 @enderror"
                                required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id_kategori }}" {{ old('id_kategori') == $category->id_kategori ? 'selected' : '' }}>
                                    {{ $category->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_kategori')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="deskripsi" 
                                  rows="4"
                                  class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400 @error('deskripsi') border-red-500 @enderror"
                                  placeholder="Jelaskan detail produk, bahan, motif, dan keunikannya...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Berat (gram) *</label>
                            <input type="number" 
                                   name="berat" 
                                   value="{{ old('berat', 500) }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400 @error('berat') border-red-500 @enderror"
                                   placeholder="500"
                                   min="0"
                                   required>
                            @error('berat')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4 bg-yellow-50 border border-yellow-200 p-3 rounded">
                        <div class="flex">
                            <i class="bi bi-info-circle text-yellow-600 mr-2"></i>
                            <p class="text-xs text-yellow-700">
                                <strong>Catatan:</strong> Harga dan Stok diatur melalui <strong>Varian Produk</strong> setelah produk berhasil dibuat.
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Product Images -->
            <div class="bg-white border border-gray-200 p-4">
                <h2 class="text-sm font-bold text-gray-900 uppercase mb-4">Gambar Produk</h2>
                
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Upload Gambar (Max 5 gambar)</label>
                    <input type="file" 
                           id="productImages"
                           name="images[]" 
                           multiple
                           accept="image/*"
                           class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400 @error('images') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, JPEG. Maksimal 2MB per gambar.</p>
                    @error('images')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    
                    <!-- Preview Container -->
                    <div id="imagePreview" class="grid grid-cols-4 gap-2 mt-3" style="display: none;"></div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-4">
            <!-- Status -->
            <div class="bg-white border border-gray-200 p-4">
                <h2 class="text-sm font-bold text-gray-900 uppercase mb-4">Status & Pengaturan</h2>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b border-gray-200">
                        <label class="text-xs font-semibold text-gray-700">Status Aktif</label>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="hidden" name="status" value="nonaktif">
                            <input type="checkbox" name="status" value="aktif" class="sr-only peer" {{ old('status', 'aktif') == 'aktif' ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between py-2 border-b border-gray-200" x-data="{ madeToOrder: {{ old('is_made_to_order') ? 'true' : 'false' }} }">
                        <label class="text-xs font-semibold text-gray-700">Made to Order</label>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_made_to_order" value="1" class="sr-only peer" {{ old('is_made_to_order') ? 'checked' : '' }} x-model="madeToOrder">
                            <div class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                        </label>
                    </div>

                    <div x-show="madeToOrder" x-cloak x-transition>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Waktu Pengerjaan (Hari)</label>
                        <input type="number" 
                               name="lead_time_days" 
                               value="{{ old('lead_time_days', 7) }}"
                               class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400"
                               min="1"
                               placeholder="7">
                        <p class="mt-1 text-xs text-gray-500">Estimasi waktu pengerjaan pesanan</p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="bg-white border border-gray-200 p-4">
                <button type="submit" 
                        class="w-full btn-primary font-semibold py-3 px-4 text-sm">
                    <i class="bi bi-check-circle mr-2"></i>
                    Simpan Produk
                </button>
                <a href="{{ route('admin.products.index') }}" 
                   class="block w-full text-center mt-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-4 text-sm">
                    Batal
                </a>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
// Image Preview for Product
document.getElementById('productImages').addEventListener('change', function(e) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    preview.style.display = 'grid';
    
    const files = Array.from(e.target.files);
    
    files.forEach((file, index) => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative';
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-20 object-cover border border-gray-200">
                    <span class="absolute top-1 left-1 px-2 py-0.5 bg-black bg-opacity-70 text-white text-xs font-bold">
                        ${index + 1}
                    </span>
                `;
                preview.appendChild(div);
            };
            
            reader.readAsDataURL(file);
        }
    });
    
    if (files.length === 0) {
        preview.style.display = 'none';
    }
});
</script>
@endpush
