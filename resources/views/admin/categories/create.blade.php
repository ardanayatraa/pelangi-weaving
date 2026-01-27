@extends('layouts.admin')

@section('title', 'Tambah Kategori')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.categories.index') }}" class="text-gray-700 hover:text-gray-900 font-medium inline-flex items-center">
        <i class="bi bi-arrow-left mr-1"></i> Kembali ke Daftar Kategori
    </a>
</div>

<div class="mb-4">
    <h1 class="text-2xl font-bold text-gray-900">Tambah Kategori Baru</h1>
    <p class="text-sm text-gray-600 mt-1">Tambahkan kategori produk baru</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <!-- Main Form -->
    <div class="lg:col-span-2">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            
            <div class="bg-white border border-gray-200 p-4 space-y-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Kategori *</label>
                    <input type="text" 
                           name="nama_kategori" 
                           value="{{ old('nama_kategori') }}"
                           class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400 @error('nama_kategori') border-red-500 @enderror"
                           placeholder="Contoh: Kain Songket Premium"
                           required 
                           autofocus>
                    @error('nama_kategori')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Slug</label>
                    <input type="text" 
                           name="slug" 
                           value="{{ old('slug') }}"
                           class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400 @error('slug') border-red-500 @enderror"
                           placeholder="kain-songket-premium">
                    <p class="mt-1 text-xs text-gray-500">Kosongkan untuk auto-generate dari nama</p>
                    @error('slug')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" 
                              rows="4"
                              class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400 @error('deskripsi') border-red-500 @enderror"
                              placeholder="Deskripsi singkat tentang kategori ini...">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center space-x-2 pt-3">
                    <button type="submit" 
                            class="btn-primary font-semibold py-2 px-4 text-sm">
                        <i class="bi bi-check-circle mr-2"></i>
                        Simpan Kategori
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
    <div>
        <div class="bg-white border border-gray-200 p-4">
            <h2 class="text-sm font-bold text-gray-900 uppercase mb-3">Panduan</h2>
            <div class="space-y-3 text-xs text-gray-600">
                <div>
                    <p class="font-semibold text-gray-900 mb-1">Nama Kategori</p>
                    <p>Nama kategori yang akan ditampilkan di website</p>
                </div>
                <div>
                    <p class="font-semibold text-gray-900 mb-1">Slug</p>
                    <p>URL-friendly name untuk kategori. Jika dikosongkan, akan dibuat otomatis dari nama</p>
                </div>
                <div>
                    <p class="font-semibold text-gray-900 mb-1">Urutan</p>
                    <p>Menentukan urutan tampilan kategori. Semakin kecil angka, semakin atas posisinya</p>
                </div>
                <div>
                    <p class="font-semibold text-gray-900 mb-1">Status</p>
                    <p>Kategori yang tidak aktif tidak akan ditampilkan di website</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
