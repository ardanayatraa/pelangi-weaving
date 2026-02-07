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
                                <strong>Catatan:</strong> Harga dan stok diatur per varian. Tambah varian di bagian bawah atau nanti di halaman detail produk.
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Product Images (disimpan ke varian: gambar 1 → varian 1, dst) -->
            <div class="bg-white border border-gray-200 p-4">
                <h2 class="text-sm font-bold text-gray-900 uppercase mb-4">Gambar Produk</h2>
                <p class="text-xs text-gray-500 mb-3">Gambar disimpan per varian. Gambar pertama ke varian pertama, dst. Jika belum ada varian, varian default akan dibuat.</p>
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

            <!-- Varian Produk (opsional, bisa ditambah di sini atau nanti di detail produk) -->
            <div class="bg-white border border-gray-200 p-4">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-bold text-gray-900 uppercase">Varian Produk</h2>
                    <button type="button" id="btnTambahVarian" class="inline-flex items-center px-3 py-1.5 bg-gray-800 hover:bg-gray-900 text-white text-xs font-semibold rounded transition">
                        <i class="bi bi-plus-lg mr-1"></i> Tambah Varian
                    </button>
                </div>
                <p class="text-xs text-gray-500 mb-3">Tambahkan varian (warna/ukuran), harga, stok, dan <strong>gambar per varian</strong> di tiap baris. Gambar varian bisa di-upload langsung di sini atau lewat section "Gambar Produk" di atas (urut: gambar 1 → varian 1, dst).</p>
                <div id="varianList" class="space-y-3">
                    <!-- Template row (hidden, jangan submit) -->
                    <div id="varianRowTemplate" class="varian-row border border-gray-200 rounded-lg p-4 bg-gray-50 hidden" data-template="1">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xs font-semibold text-gray-600">Varian #<span class="varian-num">1</span></span>
                            <button type="button" class="varian-remove text-red-600 hover:text-red-800 text-sm font-medium">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Varian *</label>
                                <input type="text" name="variants[__INDEX__][nama_varian]" class="varian-field w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:border-gray-400" placeholder="Merah - 2x1m" required disabled>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Kode Varian *</label>
                                <input type="text" name="variants[__INDEX__][kode_varian]" class="varian-field w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:border-gray-400" placeholder="PW-001-MER" required disabled>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Harga (Rp) *</label>
                                <input type="number" name="variants[__INDEX__][harga]" class="varian-field w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:border-gray-400" min="0" placeholder="250000" required disabled>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Stok *</label>
                                <input type="number" name="variants[__INDEX__][stok]" class="varian-field w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:border-gray-400" min="0" placeholder="10" required disabled>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Warna</label>
                                <input type="text" name="variants[__INDEX__][warna]" class="varian-field w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:border-gray-400" placeholder="Merah Marun">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Ukuran</label>
                                <input type="text" name="variants[__INDEX__][ukuran]" class="varian-field w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:border-gray-400" placeholder="2x1 m">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Jenis Benang</label>
                                <input type="text" name="variants[__INDEX__][jenis_benang]" class="varian-field w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:border-gray-400" placeholder="Opsional">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Berat (gram)</label>
                                <input type="number" name="variants[__INDEX__][berat]" class="varian-field w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:border-gray-400" min="0" placeholder="500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Gambar varian (opsional)</label>
                                <input type="file" name="variants[__INDEX__][gambar_varian]" class="varian-field w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:border-gray-400 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-gray-100 file:text-gray-700" accept="image/jpeg,image/png,image/jpg">
                                <p class="text-xs text-gray-400 mt-0.5">JPG, PNG. Maks 2MB. Gambar ini tampil untuk varian ini.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <p id="varianEmptyHint" class="text-xs text-gray-500 mt-2">Belum ada varian. Klik "Tambah Varian" untuk menambah.</p>
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

// Varian: tambah baris
let varianIndex = 0;
const template = document.getElementById('varianRowTemplate');
const list = document.getElementById('varianList');
const emptyHint = document.getElementById('varianEmptyHint');

document.getElementById('btnTambahVarian').addEventListener('click', function() {
    const clone = template.cloneNode(true);
    clone.id = '';
    clone.removeAttribute('data-template');
    clone.classList.remove('hidden');
    clone.querySelector('.varian-num').textContent = varianIndex + 1;
    clone.querySelectorAll('input').forEach(function(input) {
        input.name = input.name.replace(/__INDEX__/g, varianIndex);
        input.removeAttribute('disabled');
    });
    clone.querySelector('.varian-remove').addEventListener('click', function() {
        clone.remove();
        renumberVariants();
    });
    list.appendChild(clone);
    varianIndex++;
    emptyHint.style.display = 'none';
});

function renumberVariants() {
    const rows = list.querySelectorAll('.varian-row:not([data-template])');
    emptyHint.style.display = rows.length === 0 ? 'block' : 'none';
    rows.forEach(function(row, i) {
        row.querySelector('.varian-num').textContent = i + 1;
        row.querySelectorAll('input').forEach(function(input) {
            input.name = input.name.replace(/variants\[\d+\]/g, 'variants[' + i + ']');
        });
    });
    varianIndex = rows.length;
}

// Jangan required jika tidak ada varian (form bisa submit tanpa varian)
document.querySelector('form').addEventListener('submit', function() {
    const rows = list.querySelectorAll('.varian-row:not(#varianRowTemplate)');
    rows.forEach(function(row) {
        row.querySelectorAll('input[name*="nama_varian"], input[name*="kode_varian"], input[name*="harga"], input[name*="stok"]').forEach(function(input) {
            input.setAttribute('required', 'required');
        });
    });
});
</script>
@endpush
