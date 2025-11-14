@extends('layouts.customer')

@section('title', 'Beranda - Pelangi Traditional Weaving Sidemen')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-primary-50 to-white py-8 md:py-20">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-6 md:gap-12 items-center">
            <div>
                <span class="bg-primary-100 text-primary-700 px-3 md:px-4 py-1.5 md:py-2 rounded-full text-xs md:text-sm font-semibold">
                    ✨ Koleksi Terbaru
                </span>
                <h1 class="text-3xl md:text-5xl font-bold mt-4 md:mt-6 mb-3 md:mb-4">
                    Kain Tenun Bali<br>
                    <span class="text-primary-600">Berkualitas Premium</span>
                </h1>
                <p class="text-gray-600 text-sm md:text-lg mb-6 md:mb-8">
                    Kain songket dengan motif flora & fauna khas Bali sejak 1979.
                    Benang katun dan sutra berkualitas premium.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 md:gap-4">
                    <a href="{{ route('products.index') }}" 
                       class="bg-primary-600 text-white px-6 md:px-8 py-2.5 md:py-3 rounded-lg font-semibold hover:bg-primary-700 transition text-center text-sm md:text-base">
                        <i class="bi bi-bag"></i> Belanja Sekarang
                    </a>
                    <a href="{{ route('products.index') }}" 
                       class="border-2 border-gray-300 text-gray-700 px-6 md:px-8 py-2.5 md:py-3 rounded-lg font-semibold hover:border-primary-600 hover:text-primary-600 transition text-center text-sm md:text-base">
                        <i class="bi bi-grid"></i> Lihat Katalog
                    </a>
                </div>
            </div>
            <div class="hidden md:block">
                <div class="bg-gradient-to-br from-primary-200 to-primary-100 rounded-3xl h-96 flex items-center justify-center">
                    <i class="bi bi-shop text-primary-600 text-9xl"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold">Kategori Pilihan</h2>
            <a href="{{ route('products.index') }}" class="text-primary-600 hover:text-primary-700 font-semibold">
                Lihat Semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        
        <!-- Auto-scroll carousel -->
        <div class="relative overflow-hidden" x-data="{ paused: false }">
            <div class="flex gap-6 animate-scroll" 
                 @mouseenter="paused = true" 
                 @mouseleave="paused = false"
                 :style="paused ? 'animation-play-state: paused' : 'animation-play-state: running'">
                @foreach($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->id]) }}" 
                   class="bg-white border-2 border-gray-200 rounded-xl p-6 text-center hover:border-primary-600 hover:shadow-lg transition group flex-shrink-0 w-64">
                    <div class="text-5xl mb-4 group-hover:scale-110 transition">
                        @if($category->id == 1)
                            <i class="bi bi-star text-primary-600"></i>
                        @elseif($category->id == 2)
                            <i class="bi bi-heart text-primary-600"></i>
                        @elseif($category->id == 3)
                            <i class="bi bi-bag text-primary-600"></i>
                        @else
                            <i class="bi bi-grid text-primary-600"></i>
                        @endif
                    </div>
                    <h3 class="font-bold text-lg">{{ $category->nama_kategori }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $category->products_count }} produk</p>
                </a>
                @endforeach
                
                <!-- Duplicate for seamless loop -->
                @foreach($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->id]) }}" 
                   class="bg-white border-2 border-gray-200 rounded-xl p-6 text-center hover:border-primary-600 hover:shadow-lg transition group flex-shrink-0 w-64">
                    <div class="text-5xl mb-4 group-hover:scale-110 transition">
                        @if($category->id == 1)
                            <i class="bi bi-star text-primary-600"></i>
                        @elseif($category->id == 2)
                            <i class="bi bi-heart text-primary-600"></i>
                        @elseif($category->id == 3)
                            <i class="bi bi-bag text-primary-600"></i>
                        @else
                            <i class="bi bi-grid text-primary-600"></i>
                        @endif
                    </div>
                    <h3 class="font-bold text-lg">{{ $category->nama_kategori }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $category->products_count }} produk</p>
                </a>
                @endforeach
            </div>
        </div>
        
        <style>
            @keyframes scroll {
                0% {
                    transform: translateX(0);
                }
                100% {
                    transform: translateX(-50%);
                }
            }
            
            .animate-scroll {
                animation: scroll 20s linear infinite;
            }
        </style>
    </div>
</section>

<!-- Featured Products -->
<section class="py-8 md:py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center mb-6 md:mb-8">
            <h2 class="text-xl md:text-3xl font-bold">Produk Unggulan</h2>
            <a href="{{ route('products.index') }}" class="text-primary-600 hover:text-primary-700 font-semibold text-sm md:text-base">
                Lihat Semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        
        <!-- Grid layout for products -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-6">
            @foreach($featuredProducts as $product)
            <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition">
                <a href="{{ route('products.show', $product->slug) }}">
                    @if($product->images->first())
                    <img src="{{ asset('storage/' . $product->images->first()->path) }}" 
                         alt="{{ $product->nama_produk }}"
                         class="w-full h-32 md:h-48 object-cover">
                    @else
                    <div class="w-full h-32 md:h-48 bg-gray-200 flex items-center justify-center">
                        <i class="bi bi-image text-gray-400 text-2xl md:text-4xl"></i>
                    </div>
                    @endif
                </a>
                
                <div class="p-3 md:p-4">
                    <a href="{{ route('products.show', $product->slug) }}" 
                       class="font-bold text-sm md:text-base text-gray-800 hover:text-primary-600 line-clamp-2">
                        {{ $product->nama_produk }}
                    </a>
                    <p class="text-primary-600 font-bold text-base md:text-xl mt-1 md:mt-2">
                        {!! $product->getFormattedPrice() !!}
                    </p>
                    <p class="text-xs md:text-sm text-gray-600 mt-1">Stok: {{ $product->stok }}</p>
                    <a href="{{ route('products.show', $product->slug) }}" 
                       class="block mt-2 md:mt-3 bg-primary-600 text-white text-center py-1.5 md:py-2 rounded-lg hover:bg-primary-700 transition text-xs md:text-sm">
                        <i class="bi bi-cart-plus"></i> Beli
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Features -->
<section class="py-8 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-xl md:text-3xl font-bold text-center mb-8 md:mb-12">Mengapa Memilih Kami?</h2>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8">
            <div class="text-center">
                <div class="text-3xl md:text-5xl text-primary-600 mb-2 md:mb-4">
                    <i class="bi bi-award"></i>
                </div>
                <h3 class="font-bold text-sm md:text-base mb-1 md:mb-2">Kualitas Premium</h3>
                <p class="text-xs md:text-sm text-gray-600">Benang katun & sutra berkualitas tinggi</p>
            </div>
            
            <div class="text-center">
                <div class="text-3xl md:text-5xl text-primary-600 mb-2 md:mb-4">
                    <i class="bi bi-hand-thumbs-up"></i>
                </div>
                <h3 class="font-bold text-sm md:text-base mb-1 md:mb-2">Handmade</h3>
                <p class="text-xs md:text-sm text-gray-600">Dikerjakan dengan tangan oleh pengrajin ahli</p>
            </div>
            
            <div class="text-center">
                <div class="text-3xl md:text-5xl text-primary-600 mb-2 md:mb-4">
                    <i class="bi bi-truck"></i>
                </div>
                <h3 class="font-bold text-sm md:text-base mb-1 md:mb-2">Pengiriman Cepat</h3>
                <p class="text-xs md:text-sm text-gray-600">Ke seluruh Indonesia</p>
            </div>
            
            <div class="text-center">
                <div class="text-3xl md:text-5xl text-primary-600 mb-2 md:mb-4">
                    <i class="bi bi-shield-check"></i>
                </div>
                <h3 class="font-bold text-sm md:text-base mb-1 md:mb-2">Terpercaya</h3>
                <p class="text-xs md:text-sm text-gray-600">Sejak 1979</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-8 md:py-16 bg-gradient-to-r from-primary-600 to-primary-700 text-white">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-2xl md:text-4xl font-bold mb-3 md:mb-4">Siap Berbelanja?</h2>
        <p class="text-base md:text-xl mb-6 md:mb-8 text-primary-100">
            Temukan koleksi kain tenun terbaik kami dan dapatkan penawaran spesial
        </p>
        <a href="{{ route('products.index') }}" 
           class="inline-block bg-white text-primary-600 px-6 md:px-8 py-3 md:py-4 rounded-lg font-bold text-base md:text-lg hover:bg-gray-100 transition">
            Mulai Belanja <i class="bi bi-arrow-right"></i>
        </a>
    </div>
</section>
@endsection
