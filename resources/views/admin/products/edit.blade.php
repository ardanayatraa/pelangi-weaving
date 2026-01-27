@extends('layouts.admin')

@section('title', 'Edit Produk')
@section('page-title', 'Edit Produk')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.products.show', $product->slug) }}" class="inline-flex items-center text-gray-700 hover:text-gray-900 font-medium">
        <i class="bi bi-arrow-left mr-2"></i> Kembali
    </a>
</div>

<form action="{{ route('admin.products.update', $product->slug) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Main Form -->
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-lg shadow p-6">
                <h5 class="text-lg font-semibold text-gray-800 mb-4">Informasi Produk</h5>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Produk <span class="text-red-600">*</span></label>
                            <input type="text" name="nama_produk" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('nama_produk') border-red-500 @enderror" 
                                   value="{{ old('nama_produk', $product->nama_produk) }}" required>
                            @error('nama_produk')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kategori <span class="text-red-600">*</span></label>
                            <select name="id_kategori" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('id_kategori') border-red-500 @enderror" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id_kategori }}" {{ old('id_kategori', $product->id_kategori) == $category->id_kategori ? 'selected' : '' }}>
                                        {{ $category->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_kategori')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                            <input type="text" name="slug" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('slug') border-red-500 @enderror" 
                                   value="{{ old('slug', $product->slug) }}" 
                                   placeholder="Kosongkan untuk auto-generate">
                            @error('slug')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Kosongkan untuk generate otomatis dari nama produk</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                <option value="aktif" {{ old('status', $product->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status', $product->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Berat (gram) <span class="text-red-600">*</span></label>
                            <input type="number" name="berat" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('berat') border-red-500 @enderror" 
                                   value="{{ old('berat', $product->berat) }}" required min="0" placeholder="600">
                            @error('berat')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 p-3 rounded mt-2">
                        <div class="flex">
                            <i class="bi bi-info-circle text-blue-600 mr-2"></i>
                            <p class="text-sm text-blue-700">
                                <strong>Info:</strong> Harga dan Stok dikelola melalui tab <strong>Varian Produk</strong> di halaman detail produk.
                            </p>
                        </div>
                    </div>


                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea name="deskripsi" rows="4" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('deskripsi') border-red-500 @enderror" placeholder="Jelaskan detail produk, bahan, motif, dan keunikannya...">{{ old('deskripsi', $product->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Images -->
            <div class="bg-white rounded-lg shadow p-6">
                <h5 class="text-lg font-semibold text-gray-800 mb-4">Gambar Produk</h5>
                @if($product->images->count() > 0)
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Saat Ini</label>
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($product->images as $image)
                        <div>
                            <img src="{{ asset('storage/' . $image->path) }}" 
                                 alt="{{ $product->nama_produk }}"
                                 class="w-full h-24 object-cover rounded-lg border border-gray-200">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Gambar Baru (Opsional)</label>
                    <input type="file" name="images[]" multiple accept="image/*" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="mt-2 text-xs text-gray-500">Upload gambar baru akan ditambahkan ke gambar yang sudah ada. Format: JPG, PNG, JPEG. Maks 2MB per file.</p>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-4">
            <div class="bg-white rounded-lg shadow p-6">
                <h6 class="text-base font-semibold text-gray-800 mb-4">Aksi</h6>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg transition hover:shadow-lg mb-2">
                    <i class="bi bi-check-circle mr-2"></i> Update Produk
                </button>
                <a href="{{ route('admin.products.show', $product->slug) }}" class="block w-full text-center bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2.5 px-4 rounded-lg transition">
                    Batal
                </a>
            </div>

            <div class="bg-white rounded-lg shadow p-6" x-data="{ madeToOrder: {{ old('is_made_to_order', $product->is_made_to_order) ? 'true' : 'false' }} }">
                <h6 class="text-base font-semibold text-gray-800 mb-4">Pengaturan</h6>
                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b border-gray-200">
                        <label class="text-sm font-medium text-gray-700">Made to Order</label>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_made_to_order" value="1" class="sr-only peer" {{ old('is_made_to_order', $product->is_made_to_order) ? 'checked' : '' }} x-model="madeToOrder">
                            <div class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                        </label>
                    </div>

                    <div x-show="madeToOrder" x-cloak x-transition>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Waktu Pengerjaan (Hari)</label>
                        <input type="number" 
                               name="lead_time_days" 
                               value="{{ old('lead_time_days', $product->lead_time_days ?? 7) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               min="1"
                               placeholder="7">
                        <p class="mt-1 text-xs text-gray-500">Estimasi waktu pengerjaan pesanan</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h6 class="text-base font-semibold text-gray-800 mb-4">Informasi</h6>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Dibuat</label>
                        <p class="text-sm text-gray-800">{{ $product->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Terakhir Diupdate</label>
                        <p class="text-sm text-gray-800">{{ $product->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
