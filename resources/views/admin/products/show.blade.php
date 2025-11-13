@extends('layouts.admin')

@section('title', $product->nama_produk)
@section('page-title', 'Detail Produk')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
    <a href="{{ route('admin.products.edit', $product->slug) }}" class="btn btn-primary btn-sm">
        <i class="bi bi-pencil me-1"></i> Edit
    </a>
    <form action="{{ route('admin.products.destroy', $product->slug) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" 
                onclick="return confirm('Yakin hapus produk ini?')"
                class="btn btn-danger btn-sm">
            <i class="bi bi-trash me-1"></i> Hapus
        </button>
    </form>
</div>

<div class="row g-4">
    <!-- Main Content -->
    <div class="col-lg-8">
        <!-- Product Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Informasi Produk</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Nama Produk</label>
                        <p class="fw-bold">{{ $product->nama_produk }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Kategori</label>
                        <p class="fw-bold">{{ $product->category->nama_kategori }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Harga</label>
                        <p class="fw-bold text-primary fs-5">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Berat</label>
                        <p class="fw-bold">{{ $product->berat }} gram</p>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-muted small">Deskripsi</label>
                        <p>{{ $product->deskripsi }}</p>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-muted small">Status</label>
                        <div>
                            @if($product->status == 'aktif')
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i> Aktif
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="bi bi-x-circle me-1"></i> Nonaktif
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Images -->
        @if($product->images->count() > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Gambar Produk</h5>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    @foreach($product->images as $image)
                    <div class="col-3">
                        <div class="position-relative">
                            <img src="{{ asset('storage/' . $image->path) }}" 
                                 alt="{{ $product->nama_produk }}"
                                 class="img-fluid rounded border"
                                 style="cursor: pointer; height: 120px; width: 100%; object-fit: cover;"
                                 onclick="showImageModal('{{ asset('storage/' . $image->path) }}')">
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Product Variants -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Varian Produk ({{ $product->variants->count() }})</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addVariantModal">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Varian
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Gambar</th>
                                <th>Kode</th>
                                <th>Varian</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($product->variants as $variant)
                            <tr>
                                <td>
                                    @if($variant->gambar_varian)
                                        <img src="{{ asset('storage/' . $variant->gambar_varian) }}" 
                                             alt="{{ $variant->nama_varian }}"
                                             class="rounded"
                                             style="width: 50px; height: 50px; object-fit: cover; cursor: pointer;"
                                             onclick="showImageModal('{{ asset('storage/' . $variant->gambar_varian) }}')"
                                             onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'bg-light rounded d-flex align-items-center justify-content-center\' style=\'width: 50px; height: 50px;\'><i class=\'bi bi-image text-muted\'></i></div>';">
                                    @elseif($product->images->first())
                                        <img src="{{ asset('storage/' . $product->images->first()->path) }}" 
                                             alt="{{ $product->nama_produk }}"
                                             class="rounded opacity-50"
                                             style="width: 50px; height: 50px; object-fit: cover;"
                                             onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'bg-light rounded d-flex align-items-center justify-content-center\' style=\'width: 50px; height: 50px;\'><i class=\'bi bi-image text-muted\'></i></div>';">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td><small class="text-muted">{{ $variant->kode_varian }}</small></td>
                                <td>
                                    <div class="fw-bold">{{ $variant->nama_varian }}</div>
                                    <small class="text-muted">
                                        @if($variant->warna) {{ $variant->warna }} @endif
                                        @if($variant->ukuran) • {{ $variant->ukuran }} @endif
                                        @if($variant->jenis_benang) • {{ $variant->jenis_benang }} @endif
                                    </small>
                                </td>
                                <td class="fw-bold">Rp {{ number_format($variant->harga, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge {{ $variant->stok > 10 ? 'bg-success' : ($variant->stok > 0 ? 'bg-warning' : 'bg-danger') }}">
                                        {{ $variant->stok }} unit
                                    </span>
                                </td>
                                <td>
                                    @if($variant->status == 'tersedia')
                                        <span class="badge bg-success">Tersedia</span>
                                    @else
                                        <span class="badge bg-secondary">Habis</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editVariantModal{{ $variant->id_varian }}"
                                            title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('admin.products.variants.destroy', [$product->slug, $variant->id_varian]) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Yakin hapus varian {{ $variant->nama_varian }}?')"
                                                title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    Belum ada varian. 
                                    <button type="button" class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#addVariantModal">
                                        Tambah varian pertama
                                    </button>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Quick Stats -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Statistik</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted small">Total Varian</span>
                    <span class="fw-bold">{{ $product->variants->count() }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted small">Total Stok</span>
                    <span class="fw-bold">{{ $product->variants->sum('stok') }} unit</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted small">Harga Terendah</span>
                    <span class="fw-bold">Rp {{ number_format($product->variants->min('harga') ?? $product->harga, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted small">Harga Tertinggi</span>
                    <span class="fw-bold">Rp {{ number_format($product->variants->max('harga') ?? $product->harga, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Meta Info -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Informasi Lainnya</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label text-muted small">Dibuat</label>
                    <p class="mb-0 small">{{ $product->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small">Terakhir Diupdate</label>
                    <p class="mb-0 small">{{ $product->updated_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <label class="form-label text-muted small">Slug</label>
                    <p class="mb-0 small"><code>{{ $product->slug }}</code></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Variant Modal -->
<div class="modal fade" id="addVariantModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header border-0 bg-light" style="padding: 24px;">
                <div>
                    <h5 class="modal-title fw-bold mb-1">Tambah Varian Baru</h5>
                    <p class="text-muted small mb-0">Isi informasi varian produk</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.products.variants.store', $product->slug) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" style="padding: 24px;">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Varian <span class="text-danger">*</span></label>
                            <input type="text" name="nama_varian" class="form-control" required 
                                   placeholder="Merah Marun - 2x1m">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kode Varian <span class="text-danger">*</span></label>
                            <input type="text" name="kode_varian" class="form-control" required 
                                   placeholder="PW-001-MER">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Warna</label>
                            <input type="text" name="warna" class="form-control" 
                                   placeholder="Merah Marun">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Ukuran</label>
                            <input type="text" name="ukuran" class="form-control" 
                                   placeholder="2 x 1 meter">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jenis Benang</label>
                            <input type="text" name="jenis_benang" class="form-control" 
                                   placeholder="Benang Emas 24K">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="harga" class="form-control" required min="0" 
                                   placeholder="2500000">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Stok <span class="text-danger">*</span></label>
                            <input type="number" name="stok" class="form-control" required min="0" 
                                   placeholder="10">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Berat (gram)</label>
                            <input type="number" name="berat" class="form-control" min="0" 
                                   value="{{ $product->berat }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Gambar Varian (Opsional)</label>
                            <input type="file" name="gambar_varian" class="form-control" accept="image/*">
                            <small class="text-muted">Format: JPG, PNG, JPEG. Maks 2MB</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light" style="padding: 20px 24px;">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal" style="padding: 10px 24px;">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">
                        <i class="bi bi-check-circle me-2"></i>Simpan Varian
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Variant Modals -->
@foreach($product->variants as $variant)
<div class="modal fade" id="editVariantModal{{ $variant->id_varian }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header border-0 bg-light" style="padding: 24px;">
                <div>
                    <h5 class="modal-title fw-bold mb-1">Edit Varian</h5>
                    <p class="text-muted small mb-0">{{ $variant->nama_varian }}</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.products.variants.update', [$product->slug, $variant->id_varian]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body" style="padding: 24px;">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Varian <span class="text-danger">*</span></label>
                            <input type="text" name="nama_varian" class="form-control" required 
                                   value="{{ $variant->nama_varian }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kode Varian <span class="text-danger">*</span></label>
                            <input type="text" name="kode_varian" class="form-control" required 
                                   value="{{ $variant->kode_varian }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Warna</label>
                            <input type="text" name="warna" class="form-control" 
                                   value="{{ $variant->warna }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Ukuran</label>
                            <input type="text" name="ukuran" class="form-control" 
                                   value="{{ $variant->ukuran }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jenis Benang</label>
                            <input type="text" name="jenis_benang" class="form-control" 
                                   value="{{ $variant->jenis_benang }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="harga" class="form-control" required min="0" 
                                   value="{{ $variant->harga }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Stok <span class="text-danger">*</span></label>
                            <input type="number" name="stok" class="form-control" required min="0" 
                                   value="{{ $variant->stok }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Berat (gram)</label>
                            <input type="number" name="berat" class="form-control" min="0" 
                                   value="{{ $variant->berat }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="tersedia" {{ $variant->status == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                <option value="habis" {{ $variant->status == 'habis' ? 'selected' : '' }}>Habis</option>
                            </select>
                        </div>
                        @if($variant->gambar_varian)
                        <div class="col-12">
                            <label class="form-label">Gambar Saat Ini</label>
                            <div>
                                <img src="{{ asset('storage/' . $variant->gambar_varian) }}" 
                                     alt="{{ $variant->nama_varian }}"
                                     class="img-thumbnail"
                                     style="max-width: 200px; cursor: pointer;"
                                     onclick="showImageModal('{{ asset('storage/' . $variant->gambar_varian) }}')">
                            </div>
                        </div>
                        @endif
                        <div class="col-12">
                            <label class="form-label">Upload Gambar Baru (Opsional)</label>
                            <input type="file" name="gambar_varian" class="form-control" accept="image/*">
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light" style="padding: 20px 24px;">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal" style="padding: 10px 24px;">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">
                        <i class="bi bi-check-circle me-2"></i>Update Varian
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-0">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3 bg-white" data-bs-dismiss="modal" style="z-index: 1;"></button>
                <img id="modalImage" src="" alt="" class="img-fluid w-100">
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Modal Animations */
.modal.fade .modal-dialog {
    transform: scale(0.95);
    opacity: 0;
    transition: all 0.2s ease-out;
}

.modal.show .modal-dialog {
    transform: scale(1);
    opacity: 1;
}

/* Modal Backdrop */
.modal-backdrop.show {
    opacity: 0.6;
    backdrop-filter: blur(4px);
}

/* Form Inputs */
.modal .form-control,
.modal .form-select {
    border: 1px solid #e5e5e5;
    border-radius: 8px;
    padding: 10px 14px;
    transition: all 0.2s;
}

.modal .form-control:focus,
.modal .form-select:focus {
    border-color: #DC143C;
    box-shadow: 0 0 0 3px rgba(220, 20, 60, 0.1);
}

.modal .form-label {
    font-weight: 500;
    font-size: 14px;
    color: #333;
    margin-bottom: 6px;
}

/* File Input */
.modal input[type="file"] {
    padding: 8px 12px;
}

/* Buttons */
.modal .btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s;
}

.modal .btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(220, 20, 60, 0.3);
}

.modal .btn-light:hover {
    background: #f0f0f0;
}

/* Image Preview in Modal */
.modal img.img-thumbnail {
    border-radius: 8px;
    border: 2px solid #e5e5e5;
}
</style>
@endpush

@push('scripts')
<script>
function showImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}

// Smooth modal animations
document.addEventListener('DOMContentLoaded', function() {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('show.bs.modal', function() {
            this.querySelector('.modal-dialog').style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.querySelector('.modal-dialog').style.transform = 'scale(1)';
            }, 10);
        });
    });
});
</script>
@endpush
