@extends('layouts.admin')

@section('title', 'Tambah Jenis')
@section('page-title', 'Tambah Jenis')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.jenis.index') }}" class="inline-flex items-center text-gray-700 hover:text-gray-900 font-medium">
        <i class="bi bi-arrow-left mr-2"></i> Kembali ke Daftar Jenis
    </a>
</div>

<div class="mb-4">
    <h1 class="text-2xl font-bold text-gray-900">Tambah Jenis Baru</h1>
    <p class="text-sm text-gray-600 mt-1">Tambahkan jenis kain baru untuk custom orders</p>
</div>

<form action="{{ route('admin.jenis.store') }}" method="POST">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Main Form -->
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white border border-gray-200 p-4">
                <h2 class="text-sm font-bold text-gray-900 uppercase mb-4">Informasi Dasar</h2>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Jenis *</label>
                        <input type="text" 
                               name="nama_jenis" 
                               value="{{ old('nama_jenis') }}"
                               class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400 @error('nama_jenis') border-red-500 @enderror"
                               placeholder="Contoh: Songket Palembang"
                               required>
                        @error('nama_jenis')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="deskripsi" 
                                  rows="4"
                                  class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400 @error('deskripsi') border-red-500 @enderror"
                                  placeholder="Deskripsi jenis kain ini...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Icon (Bootstrap Icons)</label>
                        <input type="text" 
                               name="icon" 
                               value="{{ old('icon') }}"
                               class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400 @error('icon') border-red-500 @enderror"
                               placeholder="Contoh: bi bi-palette">
                        <p class="mt-1 text-xs text-gray-500">Gunakan class Bootstrap Icons. Contoh: bi bi-palette, bi bi-scissors, bi bi-brush</p>
                        @error('icon')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-4">
            <!-- Status -->
            <div class="bg-white border border-gray-200 p-4">
                <h2 class="text-sm font-bold text-gray-900 uppercase mb-4">Status</h2>
                
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Status *</label>
                    <select name="status" 
                            class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400 @error('status') border-red-500 @enderror"
                            required>
                        <option value="">Pilih Status</option>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="bg-white border border-gray-200 p-4">
                <button type="submit" 
                        class="w-full btn-primary font-semibold py-3 px-4 text-sm">
                    <i class="bi bi-check-circle mr-2"></i>
                    Simpan Jenis
                </button>
                <a href="{{ route('admin.jenis.index') }}" 
                   class="block w-full text-center mt-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-4 text-sm">
                    Batal
                </a>
            </div>
        </div>
    </div>
</form>
@endsection