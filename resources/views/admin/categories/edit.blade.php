@extends('layouts.admin')

@section('title', 'Edit Kategori')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.categories.index') }}" class="text-gray-700 hover:text-gray-900 font-medium inline-flex items-center">
        <i class="bi bi-arrow-left mr-1"></i> Kembali ke Daftar Kategori
    </a>
</div>

<div class="mb-4">
    <h1 class="text-2xl font-bold text-gray-900">Edit Kategori</h1>
    <p class="text-sm text-gray-600 mt-1">Update informasi kategori {{ $category->name }}</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <!-- Main Form -->
    <div class="lg:col-span-2">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="bg-white border border-gray-200 p-4 space-y-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Kategori *</label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name', $category->name) }}"
                           class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400 @error('name') border-red-500 @enderror"
                           required 
                           autofocus>
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Slug</label>
                    <input type="text" 
                           name="slug" 
                           value="{{ old('slug', $category->slug) }}"
                           class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400 @error('slug') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">URL-friendly name</p>
                    @error('slug')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" 
                              rows="4"
                              class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400 @error('description') border-red-500 @enderror">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Urutan Tampilan</label>
                    <input type="number" 
                           name="sort_order" 
                           value="{{ old('sort_order', $category->sort_order) }}"
                           class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400"
                           min="0">
                </div>

                <div class="flex items-center justify-between py-2 border-t border-gray-200">
                    <label class="text-xs font-semibold text-gray-700">Status Aktif</label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                    </label>
                </div>

                <div class="flex items-center space-x-2 pt-3">
                    <button type="submit" 
                            class="btn-primary font-semibold py-2 px-4 text-sm">
                        <i class="bi bi-check-circle mr-2"></i>
                        Update Kategori
                    </button>
                    <a href="{{ route('admin.categories.index') }}" 
                       class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 text-sm">
                        Batal
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Sidebar -->
    <div class="space-y-4">
        <div class="bg-white border border-gray-200 p-4">
            <h2 class="text-sm font-bold text-gray-900 uppercase mb-3">Informasi</h2>
            <div class="space-y-3 text-xs">
                <div>
                    <p class="text-gray-600 mb-1">Dibuat</p>
                    <p class="font-medium text-gray-900">{{ $category->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-gray-600 mb-1">Terakhir Update</p>
                    <p class="font-medium text-gray-900">{{ $category->updated_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-gray-600 mb-1">Jumlah Produk</p>
                    <p class="font-bold text-blue-600 text-base">{{ $category->products()->count() }} produk</p>
                </div>
            </div>
        </div>

        @if($category->products()->count() > 0)
        <div class="bg-yellow-50 border border-yellow-200 p-4">
            <div class="flex items-start">
                <i class="bi bi-exclamation-triangle text-yellow-600 text-lg mr-2 mt-0.5"></i>
                <div>
                    <h3 class="font-semibold text-yellow-900 text-xs mb-1">Peringatan</h3>
                    <p class="text-xs text-yellow-800">
                        Kategori ini memiliki {{ $category->products()->count() }} produk. 
                        Jika kategori dihapus, semua produk akan ikut terhapus.
                    </p>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
