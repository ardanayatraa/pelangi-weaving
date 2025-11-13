@extends('layouts.admin')

@section('title', 'Edit Produk')
@section('page-title', 'Edit Produk')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.products.show', $product->slug) }}" class="btn btn-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<form action="{{ route('admin.products.update', $product->slug) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="row g-4">
        <!-- Main Form -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Produk</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" name="nama_produk" class="form-control @error('nama_produk') is-invalid @enderror" 
                                   value="{{ old('nama_produk', $product->nama_produk) }}" required>
                            @error('nama_produk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="id_kategori" class="form-select @error('id_kategori') is-invalid @enderror" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id_kategori }}" {{ old('id_kategori', $product->id_kategori) == $category->id_kategori ? 'selected' : '' }}>
                                        {{ $category->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Slug</label>
                            <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" 
                                   value="{{ old('slug', $product->slug) }}" 
                                   placeholder="Kosongkan untuk auto-generate">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Kosongkan untuk generate otomatis dari nama produk</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="aktif" {{ old('status', $product->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status', $product->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror" 
                                   value="{{ old('harga', $product->harga) }}" required min="0">
                            @error('harga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Stok <span class="text-danger">*</span></label>
                            <input type="number" name="stok" class="form-control @error('stok') is-invalid @enderror" 
                                   value="{{ old('stok', $product->stok) }}" required min="0">
                            @error('stok')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Berat (gram) <span class="text-danger">*</span></label>
                            <input type="number" name="berat" class="form-control @error('berat') is-invalid @enderror" 
                                   value="{{ old('berat', $product->berat) }}" required min="0">
                            @error('berat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" rows="4" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $product->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Images -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Gambar Produk</h5>
                </div>
                <div class="card-body">
                    @if($product->images->count() > 0)
                    <div class="mb-3">
                        <label class="form-label">Gambar Saat Ini</label>
                        <div class="row g-2">
                            @foreach($product->images as $image)
                            <div class="col-3">
                                <img src="{{ asset('storage/' . $image->path) }}" 
                                     alt="{{ $product->nama_produk }}"
                                     class="img-fluid rounded border"
                                     style="height: 100px; width: 100%; object-fit: cover;">
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div>
                        <label class="form-label">Upload Gambar Baru (Opsional)</label>
                        <input type="file" name="images[]" multiple accept="image/*" class="form-control">
                        <small class="text-muted">Upload gambar baru akan ditambahkan ke gambar yang sudah ada. Format: JPG, PNG, JPEG. Maks 2MB per file.</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Aksi</h6>
                </div>
                <div class="card-body">
                    <button type="submit" class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-check-circle me-1"></i> Update Produk
                    </button>
                    <a href="{{ route('admin.products.show', $product->slug) }}" class="btn btn-light border w-100">
                        Batal
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Informasi</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small">Dibuat</label>
                        <p class="mb-0 small">{{ $product->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <label class="form-label text-muted small">Terakhir Diupdate</label>
                        <p class="mb-0 small">{{ $product->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
