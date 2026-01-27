@extends('layouts.admin')

@section('title', 'Edit Jenis')
@section('page-title', 'Edit Jenis')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.jenis.index') }}" class="inline-flex items-center text-gray-700 hover:text-gray-900 font-medium">
        <i class="bi bi-arrow-left mr-2"></i> Kembali
    </a>
</div>

<form action="{{ route('admin.jenis.update', $jenis->id_jenis) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Main Form -->
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-lg shadow p-6">
                <h5 class="text-lg font-semibold text-gray-800 mb-4">Informasi Jenis</h5>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Jenis <span class="text-red-600">*</span></label>
                        <input type="text" name="nama_jenis" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('nama_jenis') border-red-500 @enderror" 
                               value="{{ old('nama_jenis', $jenis->nama_jenis) }}" required>
                        @error('nama_jenis')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                        <input type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-600" 
                               value="{{ $jenis->slug }}" readonly>
                        <p class="mt-1 text-xs text-gray-500">Slug akan diupdate otomatis jika nama jenis diubah</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea name="deskripsi" rows="4" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('deskripsi') border-red-500 @enderror" placeholder="Deskripsi jenis kain ini...">{{ old('deskripsi', $jenis->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Icon (Bootstrap Icons)</label>
                        <div class="flex items-center gap-4">
                            <input type="text" name="icon" class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('icon') border-red-500 @enderror" 
                                   value="{{ old('icon', $jenis->icon) }}" placeholder="Contoh: bi bi-palette">
                            @if($jenis->icon)
                                <div class="flex items-center gap-2 px-4 py-2.5 bg-gray-50 rounded-lg">
                                    <i class="{{ $jenis->icon }} text-2xl text-gray-600"></i>
                                    <span class="text-sm text-gray-600">Preview</span>
                                </div>
                            @endif
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Gunakan class Bootstrap Icons. Contoh: bi bi-palette, bi bi-scissors, bi bi-brush</p>
                        @error('icon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-600">*</span></label>
                        <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('status') border-red-500 @enderror" required>
                            <option value="">Pilih Status</option>
                            <option value="active" {{ old('status', $jenis->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status', $jenis->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-4">
            <div class="bg-white rounded-lg shadow p-6">
                <h6 class="text-base font-semibold text-gray-800 mb-4">Aksi</h6>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg transition hover:shadow-lg mb-2">
                    <i class="bi bi-check-circle mr-2"></i> Update Jenis
                </button>
                <a href="{{ route('admin.jenis.index') }}" class="block w-full text-center bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2.5 px-4 rounded-lg transition">
                    Batal
                </a>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h6 class="text-base font-semibold text-gray-800 mb-4">Statistik</h6>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Custom Orders</span>
                        <span class="font-bold text-gray-800">{{ $jenis->customOrders->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Produk</span>
                        <span class="font-bold text-gray-800">{{ $jenis->products->count() }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h6 class="text-base font-semibold text-gray-800 mb-4">Informasi</h6>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Dibuat</label>
                        <p class="text-sm text-gray-800">{{ $jenis->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Terakhir Diupdate</label>
                        <p class="text-sm text-gray-800">{{ $jenis->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection