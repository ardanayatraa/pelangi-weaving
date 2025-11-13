@extends('layouts.customer')

@section('title', $product->nama_produk)

@section('content')
<style>
    .product-image-main {
        width: 100%;
        height: 500px;
        object-fit: cover;
        border-radius: 12px;
    }
    
    .product-image-thumb {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        cursor: pointer;
        border: 2px solid #E0E0E0;
        transition: all 0.3s;
    }
    
    .product-image-thumb:hover,
    .product-image-thumb.active {
        border-color: #FF6600;
    }
    
    .variant-option {
        border: 2px solid #E0E0E0;
        padding: 10px 20px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
        background: white;
    }
    
    .variant-option:hover {
        border-color: #FF6600;
        background: #FFF5F0;
    }
    
    .variant-option.active {
        border-color: #FF6600;
        background: #FF6600;
        color: white;
        font-weight: 600;
    }
    
    .variant-option.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background: #F5F5F5;
    }
    
    .price-section {
        background: #FFF5F0;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
    }
    
    .original-price {
        text-decoration: line-through;
        color: #999;
        font-size: 1rem;
    }
    
    .current-price {
        color: #FF6600;
        font-size: 2rem;
        font-weight: 700;
    }
    
    .stock-badge {
        background: #E8F5E9;
        color: #2E7D32;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
    }
    
    .quantity-selector {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .quantity-btn {
        width: 40px;
        height: 40px;
        border: 2px solid #E0E0E0;
        background: white;
        border-radius: 8px;
        font-size: 1.25rem;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .quantity-btn:hover {
        border-color: #FF6600;
        color: #FF6600;
    }
    
    .quantity-input {
        width: 60px;
        height: 40px;
        text-align: center;
        border: 2px solid #E0E0E0;
        border-radius: 8px;
        font-weight: 600;
    }
</style>

<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none">Produk</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index', ['category' => $product->id_kategori]) }}" class="text-decoration-none">{{ $product->category->nama_kategori }}</a></li>
            <li class="breadcrumb-item active">{{ $product->nama_produk }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Images -->
        <div class="col-lg-5 mb-4">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-3">
                    @if($product->images->first())
                    <img src="{{ asset('storage/' . $product->images->first()->path) }}" 
                         class="product-image-main mb-3" 
                         id="mainImage" 
                         alt="{{ $product->nama_produk }}">
                    @else
                    <div class="product-image-main bg-light d-flex align-items-center justify-content-center mb-3" id="mainImage">
                        <i class="bi bi-image text-muted" style="font-size: 5rem;"></i>
                    </div>
                    @endif
                    
                    <!-- Thumbnails -->
                    <div class="d-flex gap-2 overflow-auto" id="thumbnailContainer">
                        @foreach($product->images as $image)
                        <img src="{{ asset('storage/' . $image->path) }}" 
                             class="product-image-thumb {{ $loop->first ? 'active' : '' }}" 
                             onclick="changeImage(this)" 
                             alt="{{ $product->nama_produk }}">
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-4">
                    <h2 class="fw-bold mb-3">{{ $product->nama_produk }}</h2>
                    
                    <!-- Product Info -->
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-box-seam text-primary me-1"></i>
                            <span class="text-muted">Stok: <span class="fw-bold text-dark">{{ $product->stok }}</span></span>
                        </div>
                        <div class="text-muted">|</div>
                        <div class="text-muted">
                            <i class="bi bi-tag me-1"></i>
                            {{ $product->kategori->nama_kategori ?? 'Umum' }}
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="price-section">
                        <div class="current-price" id="displayPrice">Rp {{ number_format($product->harga, 0, ',', '.') }}</div>
                        <div class="d-flex align-items-center gap-2 mt-2">
                            <span class="stock-badge" id="stockBadge">Stok: {{ $product->stok }}</span>
                        </div>
                    </div>

                    <form action="{{ route('cart.add') }}" method="POST" id="addToCartForm">
                        @csrf
                        <input type="hidden" name="id_produk" value="{{ $product->id_produk }}">
                        <input type="hidden" name="id_varian" id="selectedVariantId" value="">

                        <!-- Variants -->
                        @if($product->variants->count() > 0)
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Pilih Varian:</h6>
                            
                            <!-- Warna -->
                            @php
                                $colors = $product->variants->pluck('warna')->unique()->filter();
                            @endphp
                            @if($colors->count() > 0)
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Warna:</label>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($colors as $color)
                                    <div class="variant-option" data-type="warna" data-value="{{ $color }}" onclick="selectVariant(this, 'warna')">
                                        {{ $color }}
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Ukuran -->
                            @php
                                $sizes = $product->variants->pluck('ukuran')->unique()->filter();
                            @endphp
                            @if($sizes->count() > 0)
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Ukuran:</label>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($sizes as $size)
                                    <div class="variant-option" data-type="ukuran" data-value="{{ $size }}" onclick="selectVariant(this, 'ukuran')">
                                        {{ $size }}
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Jenis Benang -->
                            @php
                                $threads = $product->variants->pluck('jenis_benang')->unique()->filter();
                            @endphp
                            @if($threads->count() > 0)
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Jenis Benang:</label>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($threads as $thread)
                                    <div class="variant-option" data-type="jenis_benang" data-value="{{ $thread }}" onclick="selectVariant(this, 'jenis_benang')">
                                        {{ $thread }}
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <div id="variantError" class="text-danger small mt-2" style="display: none;">
                                Silakan pilih semua varian terlebih dahulu
                            </div>
                        </div>
                        @endif

                        <!-- Quantity -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Jumlah:</label>
                            <div class="quantity-selector">
                                <button type="button" class="quantity-btn" onclick="decreaseQty()">-</button>
                                <input type="number" name="jumlah" id="quantity" class="quantity-input" value="1" min="1" max="{{ $product->stok }}" readonly>
                                <button type="button" class="quantity-btn" onclick="increaseQty()">+</button>
                                <span class="text-muted ms-2">Tersedia: <span id="availableStock">{{ $product->stok }}</span></span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        @auth('pelanggan')
                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-outline-primary flex-fill" style="border-radius: 8px; font-weight: 600; height: 50px;">
                                <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
                            </button>
                            <button type="submit" name="buy_now" value="1" class="btn btn-primary-custom flex-fill" style="border-radius: 8px; font-weight: 600; height: 50px;">
                                <i class="bi bi-lightning-fill"></i> Beli Sekarang
                            </button>
                        </div>
                        @else
                        <a href="{{ route('login') }}" class="btn btn-primary-custom w-100" style="border-radius: 8px; font-weight: 600; height: 50px;">
                            <i class="bi bi-box-arrow-in-right"></i> Login untuk Membeli
                        </a>
                        @endauth
                    </form>
                </div>
            </div>

            <!-- Product Details -->
            <div class="card border-0 shadow-sm mt-4" style="border-radius: 12px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Detail Produk</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted" style="width: 150px;">Kategori</td>
                            <td class="fw-semibold">{{ $product->category->nama_kategori }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Berat</td>
                            <td class="fw-semibold">{{ $product->berat }} gram</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status</td>
                            <td>
                                @if($product->status === 'aktif')
                                <span class="badge bg-success">Aktif</span>
                                @else
                                <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                    
                    <h6 class="fw-bold mt-4 mb-2">Deskripsi:</h6>
                    <p class="text-muted">{{ $product->deskripsi }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="mt-5">
        <h4 class="fw-bold mb-4">Produk Terkait</h4>
        <div class="row g-4">
            @foreach($relatedProducts as $related)
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <a href="{{ route('products.show', $related->slug) }}">
                        @if($related->images->first())
                        <img src="{{ Storage::url($related->images->first()->path) }}" class="card-img-top" style="height: 200px; object-fit: cover; border-radius: 12px 12px 0 0;" alt="{{ $related->nama_produk }}">
                        @else
                        <div class="bg-light" style="height: 200px; border-radius: 12px 12px 0 0;"></div>
                        @endif
                    </a>
                    <div class="card-body">
                        <h6 class="mb-2" style="height: 40px; overflow: hidden;">{{ $related->nama_produk }}</h6>
                        <div class="text-primary fw-bold">Rp {{ number_format($related->harga, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
// Variants data from backend
const variants = @json($product->variants);
let selectedVariants = {
    warna: null,
    ukuran: null,
    jenis_benang: null
};

function changeImage(img) {
    document.getElementById('mainImage').src = img.src;
    document.querySelectorAll('.product-image-thumb').forEach(thumb => thumb.classList.remove('active'));
    img.classList.add('active');
}

function selectVariant(element, type) {
    // Remove active from siblings
    document.querySelectorAll(`[data-type="${type}"]`).forEach(el => el.classList.remove('active'));
    element.classList.add('active');
    
    selectedVariants[type] = element.dataset.value;
    updateVariantInfo();
}

function updateVariantInfo() {
    // Find matching variant
    const matchingVariant = variants.find(v => {
        return (!selectedVariants.warna || v.warna === selectedVariants.warna) &&
               (!selectedVariants.ukuran || v.ukuran === selectedVariants.ukuran) &&
               (!selectedVariants.jenis_benang || v.jenis_benang === selectedVariants.jenis_benang);
    });
    
    if (matchingVariant) {
        document.getElementById('selectedVariantId').value = matchingVariant.id_varian;
        document.getElementById('displayPrice').textContent = 'Rp ' + parseInt(matchingVariant.harga).toLocaleString('id-ID');
        document.getElementById('stockBadge').textContent = 'Stok: ' + matchingVariant.stok;
        
        const availableStockEl = document.getElementById('availableStock');
        if (availableStockEl) availableStockEl.textContent = matchingVariant.stok;
        
        const quantityInput = document.getElementById('quantity');
        if (quantityInput) {
            quantityInput.max = matchingVariant.stok;
            if (parseInt(quantityInput.value) > matchingVariant.stok) {
                quantityInput.value = matchingVariant.stok;
            }
        }
        
        document.getElementById('variantError').style.display = 'none';
        
        // Update gambar jika varian punya gambar
        if (matchingVariant.gambar_varian) {
            const mainImage = document.getElementById('mainImage');
            const thumbnailContainer = document.getElementById('thumbnailContainer');
            
            if (mainImage) {
                // Ganti gambar utama dengan gambar varian
                mainImage.src = '/storage/' + matchingVariant.gambar_varian;
                
                // Update thumbnails - tampilkan gambar varian
                if (thumbnailContainer) {
                    thumbnailContainer.innerHTML = `
                        <img src="/storage/${matchingVariant.gambar_varian}" 
                             class="product-image-thumb active" 
                             onclick="changeImage(this)" 
                             alt="${matchingVariant.nama_varian}">
                    `;
                }
            }
        } else {
            // Jika varian tidak punya gambar, kembalikan ke gambar produk default
            resetToDefaultImages();
        }
    }
}

function resetToDefaultImages() {
    const productImages = @json($product->images->map(function($img) {
        return asset('storage/' . $img->path);
    }));
    
    const mainImage = document.getElementById('mainImage');
    const thumbnailContainer = document.getElementById('thumbnailContainer');
    
    if (productImages.length > 0 && mainImage) {
        mainImage.src = productImages[0];
        
        if (thumbnailContainer) {
            thumbnailContainer.innerHTML = '';
            productImages.forEach((imgSrc, index) => {
                const thumb = document.createElement('img');
                thumb.src = imgSrc;
                thumb.className = 'product-image-thumb' + (index === 0 ? ' active' : '');
                thumb.onclick = function() { changeImage(this); };
                thumb.alt = '{{ $product->nama_produk }}';
                thumbnailContainer.appendChild(thumb);
            });
        }
    }
}

function increaseQty() {
    const input = document.getElementById('quantity');
    const max = parseInt(input.max);
    const current = parseInt(input.value);
    if (current < max) {
        input.value = current + 1;
    }
}

function decreaseQty() {
    const input = document.getElementById('quantity');
    const current = parseInt(input.value);
    if (current > 1) {
        input.value = current - 1;
    }
}

// Validate form before submit
document.getElementById('addToCartForm').addEventListener('submit', function(e) {
    @if($product->variants->count() > 0)
    if (!document.getElementById('selectedVariantId').value) {
        e.preventDefault();
        document.getElementById('variantError').style.display = 'block';
        return false;
    }
    @endif
});
</script>
@endsection
