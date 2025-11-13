@extends('layouts.admin')

@section('title', 'Edit Produk')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.products.show', $product) }}" class="text-gray-700 hover:text-gray-900 font-medium inline-flex items-center">
        <i class="bi bi-arrow-left mr-1"></i> Kembali ke Detail Produk
    </a>
</div>

<div class="mb-4">
    <h1 class="text-2xl font-bold text-gray-900">Edit Produk</h1>
    <p class="text-sm text-gray-600 mt-1">Update informasi produk {{ $product->name }}</p>
</div>

<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
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
                               name="name" 
                               value="{{ old('name', $product->name) }}"
                               class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400 @error('name') border-red-500 @enderror"
                               required>
                        @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Kategori *</label>
                        <select name="category_id" 
                                class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400 @error('category_id') border-red-500 @enderror"
                                required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi *</label>
                        <textarea name="description" 
                                  rows="4"
                                  class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400 @error('description') border-red-500 @enderror"
                                  required>{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Harga Dasar (Rp) *</label>
                            <input type="number" 
                                   name="base_price" 
                                   value="{{ old('base_price', $product->base_price) }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400 @error('base_price') border-red-500 @enderror"
                                   min="0"
                                   required>
                            @error('base_price')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Berat (gram) *</label>
                            <input type="number" 
                                   name="weight" 
                                   value="{{ old('weight', $product->weight) }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400 @error('weight') border-red-500 @enderror"
                                   min="0"
                                   required>
                            @error('weight')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Images -->
            <div class="bg-white border border-gray-200 p-4">
                <h2 class="text-sm font-bold text-gray-900 uppercase mb-4">Gambar Produk</h2>
                
                @if($product->images->count() > 0)
                <div class="mb-4">
                    <p class="text-xs text-gray-600 mb-2">Gambar saat ini:</p>
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($product->images as $image)
                        <div class="relative">
                            <img src="{{ asset('storage/' . $image->image_path) }}" 
                                 alt="{{ $product->name }}"
                                 class="w-full h-20 object-cover border border-gray-200">
                            @if($image->is_primary)
                            <span class="absolute top-1 left-1 px-2 py-0.5 bg-red-600 text-white text-xs font-bold">
                                UTAMA
                            </span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Upload Gambar Baru (Opsional)</label>
                    <input type="file" 
                           id="productImagesEdit"
                           name="images[]" 
                           multiple
                           accept="image/*"
                           class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400">
                    <p class="mt-1 text-xs text-gray-500">Upload gambar baru akan menambahkan ke gambar yang sudah ada.</p>
                    
                    <!-- Preview Container -->
                    <div id="imagePreviewEdit" class="grid grid-cols-4 gap-2 mt-3" style="display: none;"></div>
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
                            <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between py-2 border-b border-gray-200">
                        <label class="text-xs font-semibold text-gray-700">Produk Unggulan</label>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1" class="sr-only peer" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-yellow-500"></div>
                        </label>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Urutan Tampilan</label>
                        <input type="number" 
                               name="sort_order" 
                               value="{{ old('sort_order', $product->sort_order) }}"
                               class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400"
                               min="0">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="bg-white border border-gray-200 p-4">
                <button type="submit" 
                        class="w-full btn-primary font-semibold py-3 px-4 text-sm">
                    <i class="bi bi-check-circle mr-2"></i>
                    Update Produk
                </button>
                <a href="{{ route('admin.products.show', $product) }}" 
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
// Image Preview for Product Edit
document.getElementById('productImagesEdit').addEventListener('change', function(e) {
    const preview = document.getElementById('imagePreviewEdit');
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
                        NEW ${index + 1}
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
