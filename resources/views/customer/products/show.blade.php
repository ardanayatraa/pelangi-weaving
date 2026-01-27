@extends('layouts.customer')

@section('title', $product->nama_produk)

@section('content')
<div class="bg-white min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('home') }}" class="hover:text-primary-600">Home</a></li>
                <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
                <li><a href="{{ route('products.index') }}" class="hover:text-primary-600">Produk</a></li>
                <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
                <li><a href="{{ route('products.index', ['category' => $product->category->id_kategori]) }}" class="hover:text-primary-600">{{ $product->category->nama_kategori }}</a></li>
                <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
                <li class="text-gray-900 font-medium">{{ Str::limit($product->nama_produk, 30) }}</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12" x-data="productData()">
            <!-- Left: Product Images -->
            <div>
                <!-- Main Image -->
                <div class="mb-6">
                    <div class="relative bg-gray-50 rounded-2xl overflow-hidden aspect-square">
                        <img :src="currentImage" 
                             :alt="product.nama_produk"
                             class="w-full h-full object-cover">
                        
                        <!-- Badge -->
                        <div class="absolute top-4 left-4">
                            <span class="bg-primary-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                BARU
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Thumbnails -->
                <div class="grid grid-cols-4 gap-3">
                    <template x-for="(image, index) in productImages" :key="index">
                        <button @click="changeMainImage(image)" 
                                class="aspect-square rounded-xl overflow-hidden border-2 transition-all duration-200"
                                :class="currentImage === image ? 'border-primary-600 ring-2 ring-primary-200' : 'border-gray-200 hover:border-gray-300'">
                            <img :src="image" 
                                 :alt="product.nama_produk"
                                 class="w-full h-full object-cover">
                        </button>
                    </template>
                </div>
            </div>

            <!-- Right: Product Info -->
            <div>
                <!-- Product Title -->
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->nama_produk }}</h1>
                
                <!-- SKU -->
                <p class="text-gray-600 mb-4">SKU: {{ $product->sku ?? 'KTB-001' }}</p>
                
                <!-- Rating -->
                <div class="flex items-center mb-6">
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        @endfor
                    </div>
                    <span class="ml-2 text-gray-600">4.2 (24 ulasan)</span>
                </div>

                <!-- Price -->
                <div class="mb-8">
                    <div class="flex items-baseline space-x-3">
                        <span class="text-4xl font-bold text-primary-600" x-text="formattedPrice">
                            {!! $product->getFormattedPrice() !!}
                        </span>
                        @if($product->activeVariants->count() > 0 && $product->activeVariants->min('harga') < $product->activeVariants->max('harga'))
                        <span class="text-xl text-gray-400 line-through">
                            Rp {{ number_format($product->activeVariants->max('harga')) }}
                        </span>
                        @endif
                    </div>
                    <p class="text-green-600 text-sm mt-1">Hemat Rp 50.000 (10% OFF)</p>
                </div>

                <!-- Variants -->
                @if($product->activeVariants->count() > 0)
                <div class="mb-8">
                    <label class="block text-lg font-semibold text-gray-900 mb-4">
                        Pilih Varian:
                    </label>
                    <div class="flex flex-wrap gap-3">
                        <template x-for="variant in variants" :key="variant.id">
                            <button type="button" 
                                    @click="selectVariant(variant.id)"
                                    class="px-6 py-3 border-2 rounded-xl font-medium transition-all duration-200"
                                    :class="selectedVariantId === variant.id ? 'border-primary-600 bg-primary-600 text-white' : 'border-gray-300 text-gray-700 hover:border-primary-600'"
                                    :disabled="variant.stok === 0"
                                    x-text="variant.nama.split(' - ')[0]">
                            </button>
                        </template>
                    </div>
                </div>
                @endif

                <!-- Quantity -->
                <div class="mb-8">
                    <label class="block text-lg font-semibold text-gray-900 mb-4">
                        Jumlah:
                    </label>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center border-2 border-gray-300 rounded-xl">
                            <button type="button" @click="decreaseQty()" 
                                    class="w-12 h-12 flex items-center justify-center hover:bg-gray-50 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </button>
                            <input type="number" x-model="quantity" :min="1" :max="currentStock"
                                   class="w-16 text-center border-0 focus:outline-none text-lg font-semibold">
                            <button type="button" @click="increaseQty()" 
                                    class="w-12 h-12 flex items-center justify-center hover:bg-gray-50 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </button>
                        </div>
                        <span class="text-gray-600">Stok: <span class="font-semibold" x-text="currentStock">{{ $product->stok }}</span> pcs</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                @auth('pelanggan')
                <form action="{{ route('cart.add') }}" method="POST" id="addToCartForm">
                    @csrf
                    <input type="hidden" name="id_produk" value="{{ $product->id_produk }}">
                    <input type="hidden" name="jumlah" :value="quantity">
                    <input type="hidden" name="id_varian" :value="selectedVariantId">
                    <input type="hidden" name="buy_now" x-ref="buyNowInput" value="">
                    
                    <div class="flex space-x-4 mb-8">
                        <button type="button" @click="addToCart()" 
                                class="flex-1 bg-primary-600 text-white py-4 rounded-xl font-semibold text-lg hover:bg-primary-700 transition-colors"
                                :disabled="currentStock === 0">
                            Tambah ke Keranjang
                        </button>
                        
                        <button type="button" 
                                class="w-16 h-16 border-2 border-gray-300 rounded-xl flex items-center justify-center hover:border-primary-600 hover:text-primary-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                    </div>
                </form>
                @else
                <div class="mb-8">
                    <a href="{{ route('login') }}" 
                       class="block text-center bg-primary-600 text-white py-4 rounded-xl font-semibold text-lg hover:bg-primary-700 transition-colors">
                        Login untuk Membeli
                    </a>
                </div>
                @endauth

                <!-- Product Details -->
                <div class="space-y-4">
                    <div>
                        <span class="font-semibold text-gray-900">Kategori:</span>
                        <span class="text-gray-600 ml-2">{{ $product->category->nama_kategori }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-900">Bahan:</span>
                        <span class="text-gray-600 ml-2">
                            @if($product->category->nama_kategori == 'Kain Endek Sutra (Premium)')
                                Sutra Premium
                            @elseif($product->category->nama_kategori == 'Kain Endek Katun')
                                Katun Premium
                            @else
                                Katun Premium
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Description & Details -->
        <div class="mt-16 border-t pt-16">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Description -->
                <div class="lg:col-span-2">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Deskripsi Produk</h2>
                    <div class="prose max-w-none text-gray-700 leading-relaxed">
                        <p>{{ $product->deskripsi }}</p>
                    </div>
                    
                    <!-- Specifications -->
                    <div class="mt-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Spesifikasi</h3>
                        <div class="bg-gray-50 rounded-2xl p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <span class="font-semibold text-gray-900">Berat:</span>
                                    <span class="text-gray-600 ml-2">{{ $product->berat }} gram</span>
                                </div>
                                <div>
                                    <span class="font-semibold text-gray-900">Kondisi:</span>
                                    <span class="text-gray-600 ml-2">Baru</span>
                                </div>
                                <div>
                                    <span class="font-semibold text-gray-900">Asal:</span>
                                    <span class="text-gray-600 ml-2">Sidemen, Bali</span>
                                </div>
                                <div>
                                    <span class="font-semibold text-gray-900">Garansi:</span>
                                    <span class="text-gray-600 ml-2">Kualitas Original</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Seller Info -->
                <div>
                    <div class="bg-gray-50 rounded-2xl p-6 sticky top-6">
                        <div class="flex items-center mb-6">
                            <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-8 h-8 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm3 6V7h6v3H7z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Pelangi Weaving</h3>
                                <p class="text-gray-600 text-sm">Sidemen, Bali</p>
                            </div>
                        </div>
                        
                        <div class="space-y-3 text-sm mb-6">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Sejak</span>
                                <span class="font-semibold text-gray-900">1979</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Produk</span>
                                <span class="font-semibold text-gray-900">50+ Motif</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Rating</span>
                                <span class="font-semibold text-gray-900">4.9/5</span>
                            </div>
                        </div>
                        
                        <a href="{{ route('products.index') }}" 
                           class="block w-full text-center border-2 border-primary-600 text-primary-600 py-3 rounded-xl hover:bg-primary-50 transition font-semibold">
                            Lihat Produk Lain
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
        <div class="mt-16 border-t pt-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">Produk Serupa</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($relatedProducts as $related)
                <a href="{{ route('products.show', $related->slug) }}" class="group">
                    <div class="bg-white rounded-2xl overflow-hidden border border-gray-200 hover:shadow-lg transition-all duration-300">
                        @if($related->images->first())
                        <img src="{{ asset('storage/' . $related->images->first()->path) }}" 
                             alt="{{ $related->nama_produk }}"
                             class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                        <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        @endif
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-primary-600 transition-colors">
                                {{ $related->nama_produk }}
                            </h3>
                            <p class="text-primary-600 font-bold text-lg">{!! $related->getFormattedPrice() !!}</p>
                            <p class="text-gray-600 text-sm mt-1">Stok: {{ $related->stok }}</p>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<script>
function productData() {
    return {
        // Product data
        product: {
            nama_produk: '{{ $product->nama_produk }}',
            stok: {{ $product->stok }}
        },
        
        // Images
        productImages: [
            @foreach($product->images as $image)
            '{{ asset('storage/' . $image->path) }}'{{ !$loop->last ? ',' : '' }}
            @endforeach
        ],
        currentImage: '{{ asset('storage/' . ($product->images->first()->path ?? 'placeholder.png')) }}',
        
        // Variants
        variants: [
            @foreach($product->activeVariants as $variant)
            {
                id: {{ $variant->id_varian }},
                nama: "{{ addslashes($variant->nama_varian) }}",
                harga: {{ $variant->harga ?? 0 }},
                stok: {{ $variant->stok ?? 0 }},
                gambar: "{{ $variant->gambar_varian ? asset('storage/' . $variant->gambar_varian) : '' }}",
                imageIndex: {{ $loop->index }} // Map variant to product image by index
            }{{ !$loop->last ? ',' : '' }}
            @endforeach
        ],
        
        // State
        selectedVariantId: null,
        quantity: 1,
        
        // Computed properties
        get currentStock() {
            if (this.selectedVariantId) {
                const variant = this.variants.find(v => v.id === this.selectedVariantId);
                return variant ? variant.stok : 0;
            }
            return this.product.stok;
        },
        
        get formattedPrice() {
            if (this.selectedVariantId) {
                const variant = this.variants.find(v => v.id === this.selectedVariantId);
                if (variant) {
                    return 'Rp ' + variant.harga.toLocaleString('id-ID');
                }
            }
            return '{!! $product->getFormattedPrice() !!}';
        },
        
        // Methods
        changeMainImage(imageSrc) {
            this.currentImage = imageSrc;
        },
        
        selectVariant(variantId) {
            console.log('Selecting variant:', variantId);
            this.selectedVariantId = variantId;
            
            const variant = this.variants.find(v => v.id === variantId);
            if (variant) {
                // Use variant's specific image if available, otherwise use product image by index
                if (variant.gambar) {
                    this.changeMainImage(variant.gambar);
                } else if (this.productImages[variant.imageIndex]) {
                    this.changeMainImage(this.productImages[variant.imageIndex]);
                }
            }
            
            // Reset quantity if exceeds new stock
            if (this.quantity > this.currentStock) {
                this.quantity = Math.min(1, this.currentStock);
            }
        },
        
        increaseQty() {
            if (this.quantity < this.currentStock) {
                this.quantity++;
            }
        },
        
        decreaseQty() {
            if (this.quantity > 1) {
                this.quantity--;
            }
        },
        
        addToCart() {
            if (this.variants.length > 0 && !this.selectedVariantId) {
                alert('Silakan pilih varian terlebih dahulu');
                return;
            }
            
            if (this.currentStock === 0) {
                alert('Stok tidak tersedia');
                return;
            }
            
            this.$refs.buyNowInput.value = '';
            document.getElementById('addToCartForm').submit();
        },
        
        buyNow() {
            if (this.variants.length > 0 && !this.selectedVariantId) {
                alert('Silakan pilih varian terlebih dahulu');
                return;
            }
            
            if (this.currentStock === 0) {
                alert('Stok tidak tersedia');
                return;
            }
            
            this.$refs.buyNowInput.value = '1';
            document.getElementById('addToCartForm').submit();
        }
    }
}
</script>
@endsection