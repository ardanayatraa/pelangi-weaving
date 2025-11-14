@extends('layouts.customer')

@section('title', $product->nama_produk)

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto px-4 py-3 md:py-6">
        <!-- Breadcrumb -->
        <nav class="mb-3 md:mb-4 text-xs md:text-sm overflow-x-auto">
            <ol class="flex items-center gap-2 text-gray-600 whitespace-nowrap">
                <li><a href="{{ route('home') }}" class="hover:text-primary-600">Home</a></li>
                <li><i class="bi bi-chevron-right text-xs"></i></li>
                <li><a href="{{ route('products.index') }}" class="hover:text-primary-600">Produk</a></li>
                <li><i class="bi bi-chevron-right text-xs"></i></li>
                <li class="text-gray-800 truncate max-w-[150px] md:max-w-none">{{ $product->nama_produk }}</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 md:gap-6">
            <!-- Left: Product Images -->
            <div class="lg:col-span-5">
                <div class="lg:sticky lg:top-20">
                    <!-- Main Image with Zoom -->
                    <div class="bg-gray-50 rounded-lg overflow-hidden mb-3 border border-gray-200 relative group cursor-zoom-in" 
                         id="imageContainer"
                         onmousemove="zoomImage(event)" 
                         onmouseleave="resetZoom()">
                        <img id="mainImage" 
                             src="{{ asset('storage/' . ($product->images->first()->path ?? 'placeholder.png')) }}" 
                             alt="{{ $product->nama_produk }}"
                             class="w-full h-64 md:h-[500px] object-contain transition-transform duration-200"
                             style="transform-origin: center center;">
                    </div>

                    <!-- Thumbnails -->
                    <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-thin scrollbar-thumb-gray-300">
                        <!-- Product Main Images -->
                        @foreach($product->images as $index => $image)
                        <button onclick="changeMainImage('{{ asset('storage/' . $image->path) }}')" 
                                class="thumbnail-btn flex-shrink-0 w-16 h-16 border-2 border-gray-200 rounded-lg overflow-hidden hover:border-primary-600 transition"
                                data-image="{{ asset('storage/' . $image->path) }}">
                            <img src="{{ asset('storage/' . $image->path) }}" 
                                 alt="{{ $product->nama_produk }}"
                                 class="w-full h-full object-cover">
                        </button>
                        @endforeach

                        <!-- Variant Images -->
                        @foreach($product->activeVariants as $variant)
                            @if($variant->gambar_varian)
                            <button onclick="changeMainImage('{{ asset('storage/' . $variant->gambar_varian) }}'); selectVariant({{ $variant->id_varian }});" 
                                    class="thumbnail-btn flex-shrink-0 w-16 h-16 border-2 border-gray-200 rounded-lg overflow-hidden hover:border-primary-600 transition group relative"
                                    data-image="{{ asset('storage/' . $variant->gambar_varian) }}">
                                <img src="{{ asset('storage/' . $variant->gambar_varian) }}" 
                                     alt="{{ $variant->nama_varian }}"
                                     class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition flex items-end justify-center pb-1">
                                    <span class="text-[8px] text-white opacity-0 group-hover:opacity-100 transition font-semibold bg-black bg-opacity-50 px-1 rounded">{{ $variant->warna ?? 'Varian' }}</span>
                                </div>
                            </button>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right: Product Info -->
            <div class="lg:col-span-7">
                <!-- Product Title -->
                <h1 class="text-lg md:text-2xl font-bold text-gray-900 mb-3 md:mb-4">{{ $product->nama_produk }}</h1>
                
                <!-- Category Badge -->
                <div class="mb-3 md:mb-4 flex items-center gap-2 flex-wrap">
                    <span class="inline-block bg-primary-100 text-primary-700 text-sm px-3 py-1 rounded-full">
                        {{ $product->category->nama_kategori }}
                    </span>
                    @if($product->is_made_to_order)
                        <span class="inline-flex items-center bg-purple-100 text-purple-700 text-sm px-3 py-1 rounded-full font-semibold">
                            <i class="bi bi-clock-history mr-1"></i> Made to Order
                        </span>
                    @endif
                </div>
                
                @if($product->is_made_to_order && $product->lead_time_days)
                <div class="mb-4 p-3 bg-purple-50 border border-purple-200 rounded-lg">
                    <p class="text-sm text-purple-800">
                        <i class="bi bi-info-circle mr-1"></i>
                        <strong>Produk ini dibuat sesuai pesanan.</strong> Estimasi waktu pengerjaan: <strong>{{ $product->lead_time_days }} hari kerja</strong> setelah pembayaran dikonfirmasi.
                    </p>
                </div>
                @endif

                <!-- Price Section -->
                <div class="bg-gray-50 rounded-lg p-3 md:p-4 mb-3 md:mb-4">
                    <span id="displayPrice" class="text-xl md:text-3xl font-bold text-primary-600">
                        {!! $product->getFormattedPrice() !!}
                    </span>
                </div>



                <!-- Variants -->
                @if($product->activeVariants->count() > 0)
                <div class="mb-6 pb-6 border-b">
                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                        Pilih Varian <span class="text-red-600">*</span>
                    </label>
                    <p class="text-xs text-gray-600 mb-3">Wajib pilih salah satu varian</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($product->activeVariants as $variant)
                        <button type="button" 
                                onclick="selectVariant({{ $variant->id_varian }})" 
                                id="variant-{{ $variant->id_varian }}"
                                class="variant-btn border-2 border-gray-300 hover:border-primary-600 rounded-lg px-4 py-2 text-sm font-medium transition"
                                {{ $variant->stok == 0 ? 'disabled' : '' }}>
                            {{ $variant->nama_varian }}
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Quantity & Stock -->
                <div class="mb-6 pb-6 border-b">
                    <div class="flex items-center justify-between mb-3">
                        <label class="text-sm font-semibold text-gray-900">Jumlah</label>
                        <span class="text-sm text-gray-600">Stok: <strong id="displayStock" class="text-gray-900">{{ $product->stok }}</strong></span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center border border-gray-300 rounded-lg">
                            <button type="button" onclick="decreaseQty()" 
                                    class="w-10 h-10 hover:bg-gray-50 transition">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="number" id="quantity" value="1" min="1" max="{{ $product->stok }}"
                                   class="w-16 text-center border-x border-gray-300 h-10 focus:outline-none">
                            <button type="button" onclick="increaseQty()" 
                                    class="w-10 h-10 hover:bg-gray-50 transition">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                        <span class="text-sm text-gray-600">Maks. pembelian <span id="maxQty">{{ $product->stok }}</span> pcs</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                @auth('pelanggan')
                <form id="cartForm" action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_produk" value="{{ $product->id_produk }}">
                    <input type="hidden" name="jumlah" id="form_quantity" value="1">
                    <input type="hidden" name="id_varian" id="form_variant" value="">
                    <input type="hidden" name="buy_now" id="form_buy_now" value="">
                    
                    <div class="flex flex-col sm:flex-row gap-2 md:gap-3 mb-4 md:mb-6">
                        <button type="button" onclick="addToCart()" 
                                class="flex-1 bg-primary-50 border-2 border-primary-600 text-primary-600 py-2.5 md:py-3 rounded-lg font-semibold hover:bg-primary-100 transition text-sm md:text-base"
                                {{ $product->stok == 0 ? 'disabled' : '' }}>
                            <i class="bi bi-cart-plus"></i> Keranjang
                        </button>
                        
                        <button type="button" onclick="buyNow()"
                                class="flex-1 bg-primary-600 text-white py-2.5 md:py-3 rounded-lg font-semibold hover:bg-primary-700 transition shadow-lg shadow-primary-200 text-sm md:text-base"
                                {{ $product->stok == 0 ? 'disabled' : '' }}>
                            Beli Sekarang
                        </button>
                    </div>
                </form>
                @else
                <a href="{{ route('login') }}" 
                   class="block text-center bg-primary-600 text-white py-3 rounded-lg font-semibold hover:bg-primary-700 transition shadow-lg shadow-primary-200 mb-6">
                    <i class="bi bi-box-arrow-in-right"></i> Login untuk Membeli
                </a>
                @endauth

                <!-- Additional Info -->
                <div class="bg-gray-50 rounded-lg p-4 space-y-3 text-sm">
                    <div class="flex items-start gap-3">
                        <i class="bi bi-truck text-gray-600 mt-0.5"></i>
                        <div>
                            <p class="font-medium text-gray-900">Pengiriman</p>
                            <p class="text-gray-600">Dikirim dari Sidemen, Karangasem, Bali</p>
                            <p class="text-gray-600">Ongkir mulai dari Rp 10.000</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="bi bi-box-seam text-gray-600 mt-0.5"></i>
                        <div>
                            <p class="font-medium text-gray-900">Berat Produk</p>
                            <p class="text-gray-600">{{ $product->berat }} gram</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="bi bi-shield-check text-gray-600 mt-0.5"></i>
                        <div>
                            <p class="font-medium text-gray-900">Garansi</p>
                            <p class="text-gray-600">Produk original 100% dengan garansi kualitas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Details Tabs -->
        <div class="mt-8 border-t pt-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <div class="lg:col-span-8">
                    <div>
                        <!-- Tabs -->
                        <div class="flex gap-6 border-b mb-6">
                            <button onclick="switchTab('description')" 
                                    id="tab-description"
                                    class="tab-btn pb-3 font-semibold transition border-b-2 border-primary-600 text-primary-600">
                                Deskripsi Produk
                            </button>
                            <button onclick="switchTab('specs')" 
                                    id="tab-specs"
                                    class="tab-btn pb-3 font-semibold transition text-gray-600">
                                Spesifikasi
                            </button>
                        </div>

                        <!-- Description Tab -->
                        <div id="content-description" class="tab-content prose max-w-none">
                            <div class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $product->deskripsi }}</div>
                        </div>

                        <!-- Specs Tab -->
                        <div id="content-specs" class="tab-content hidden">
                            <table class="w-full">
                                <tr class="border-b">
                                    <td class="py-3 text-gray-600 w-1/3">Kategori</td>
                                    <td class="py-3 font-medium">{{ $product->category->nama_kategori }}</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="py-3 text-gray-600">Berat</td>
                                    <td class="py-3 font-medium">{{ $product->berat }} gram</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="py-3 text-gray-600">Stok</td>
                                    <td class="py-3 font-medium">{{ $product->stok }} pcs</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="py-3 text-gray-600">Kondisi</td>
                                    <td class="py-3 font-medium">Baru</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="py-3 text-gray-600">Asal</td>
                                    <td class="py-3 font-medium">Sidemen, Bali</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Seller Info -->
                <div class="lg:col-span-4">
                    <div class="border border-gray-200 rounded-lg p-4 sticky top-20">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
                                <i class="bi bi-shop text-primary-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Pelangi Weaving</p>
                                <p class="text-xs text-gray-600">Sidemen, Bali</p>
                            </div>
                        </div>
                        <div class="space-y-2 text-sm mb-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Sejak</span>
                                <span class="font-medium text-gray-900">1979</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Lokasi</span>
                                <span class="font-medium text-gray-900">Karangasem, Bali</span>
                            </div>
                        </div>
                        <a href="{{ route('products.index') }}" class="block w-full text-center border border-primary-600 text-primary-600 py-2 rounded-lg hover:bg-primary-50 transition text-sm font-medium">
                            <i class="bi bi-shop"></i> Lihat Produk Lain
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
        <div class="mt-12 border-t pt-8">
            <h2 class="text-xl font-bold mb-6">Produk Serupa</h2>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                @foreach($relatedProducts as $related)
                <a href="{{ route('products.show', $related->slug) }}" class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition group">
                    @if($related->images->first())
                    <img src="{{ asset('storage/' . $related->images->first()->path) }}" 
                         alt="{{ $related->nama_produk }}"
                         class="w-full h-40 object-cover group-hover:scale-105 transition">
                    @else
                    <div class="w-full h-40 bg-gray-100 flex items-center justify-center">
                        <i class="bi bi-image text-gray-300 text-3xl"></i>
                    </div>
                    @endif
                    <div class="p-3">
                        <p class="text-sm text-gray-800 line-clamp-2 mb-2 group-hover:text-primary-600">{{ $related->nama_produk }}</p>
                        <p class="text-primary-600 font-bold">{!! $related->getFormattedPrice() !!}</p>
                        <p class="text-xs text-gray-600 mt-1">Stok: {{ $related->stok }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<script>
let selectedVariantId = null;

// Variant data
const variants = {
    @foreach($product->activeVariants as $variant)
    {{ $variant->id_varian }}: {
        harga: {{ $variant->harga ?? $product->harga }},
        stok: {{ $variant->stok }},
        nama: '{{ $variant->nama_varian }}',
        gambar: '{{ $variant->gambar_varian ? asset('storage/' . $variant->gambar_varian) : '' }}'
    },
    @endforeach
};

// Change main image
function changeMainImage(imageSrc) {
    document.getElementById('mainImage').src = imageSrc;
    
    // Update active thumbnail
    document.querySelectorAll('.thumbnail-btn').forEach(btn => {
        btn.classList.remove('border-primary-600', 'ring-2', 'ring-primary-200');
        btn.classList.add('border-gray-200');
    });
    
    // Add active class to clicked thumbnail
    const activeThumb = document.querySelector(`.thumbnail-btn[data-image="${imageSrc}"]`);
    if (activeThumb) {
        activeThumb.classList.remove('border-gray-200');
        activeThumb.classList.add('border-primary-600', 'ring-2', 'ring-primary-200');
    }
}

// Zoom image on hover
function zoomImage(event) {
    const container = event.currentTarget;
    const img = document.getElementById('mainImage');
    const rect = container.getBoundingClientRect();
    
    // Calculate mouse position relative to container
    const x = ((event.clientX - rect.left) / rect.width) * 100;
    const y = ((event.clientY - rect.top) / rect.height) * 100;
    
    // Apply zoom and position
    img.style.transformOrigin = `${x}% ${y}%`;
    img.style.transform = 'scale(2)';
}

// Reset zoom
function resetZoom() {
    const img = document.getElementById('mainImage');
    img.style.transform = 'scale(1)';
    img.style.transformOrigin = 'center center';
}

// Select variant
function selectVariant(variantId) {
    // Remove active class from all variants
    document.querySelectorAll('.variant-btn').forEach(btn => {
        btn.classList.remove('border-primary-600', 'bg-primary-50', 'text-primary-600');
        btn.classList.add('border-gray-300');
    });
    
    // Add active class to selected variant
    const selectedBtn = document.getElementById('variant-' + variantId);
    if (selectedBtn) {
        selectedBtn.classList.remove('border-gray-300');
        selectedBtn.classList.add('border-primary-600', 'bg-primary-50', 'text-primary-600');
    }
    
    // Store selected variant
    selectedVariantId = variantId;
    
    // Update price and stock
    const variant = variants[variantId];
    if (variant) {
        // Update price
        const formattedPrice = 'Rp ' + variant.harga.toLocaleString('id-ID');
        document.getElementById('displayPrice').textContent = formattedPrice;
        
        // Update stock
        document.getElementById('displayStock').textContent = variant.stok;
        document.getElementById('maxQty').textContent = variant.stok;
        
        // Update quantity input max
        const qtyInput = document.getElementById('quantity');
        qtyInput.max = variant.stok;
        
        // Reset quantity to 1 if current exceeds new max
        if (parseInt(qtyInput.value) > variant.stok) {
            qtyInput.value = Math.min(1, variant.stok);
        }
        
        // Change main image if variant has image
        if (variant.gambar) {
            changeMainImage(variant.gambar);
        }
    }
    
    // Update form hidden input
    document.getElementById('form_variant').value = variantId;
}

// Tab switching
function switchTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('border-b-2', 'border-primary-600', 'text-primary-600');
        btn.classList.add('text-gray-600');
    });
    
    // Show selected tab content
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Add active class to selected tab
    const activeTab = document.getElementById('tab-' + tabName);
    activeTab.classList.add('border-b-2', 'border-primary-600', 'text-primary-600');
    activeTab.classList.remove('text-gray-600');
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

function addToCart() {
    // Check if product has variants
    const hasVariants = document.querySelectorAll('.variant-btn').length > 0;
    
    if (hasVariants && !selectedVariantId) {
        alert('Silakan pilih varian terlebih dahulu');
        return;
    }
    
    const quantity = document.getElementById('quantity').value;
    document.getElementById('form_quantity').value = quantity;
    document.getElementById('form_variant').value = selectedVariantId || '';
    document.getElementById('form_buy_now').value = '';
    document.getElementById('cartForm').submit();
}

function buyNow() {
    // Check if product has variants
    const hasVariants = document.querySelectorAll('.variant-btn').length > 0;
    
    if (hasVariants && !selectedVariantId) {
        alert('Silakan pilih varian terlebih dahulu');
        return;
    }
    
    const quantity = document.getElementById('quantity').value;
    document.getElementById('form_quantity').value = quantity;
    document.getElementById('form_variant').value = selectedVariantId || '';
    document.getElementById('form_buy_now').value = '1';
    document.getElementById('cartForm').submit();
}
</script>
@endsection
