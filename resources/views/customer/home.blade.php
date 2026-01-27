@extends('layouts.customer')

@section('title', 'Beranda - Pelangi Traditional Weaving Sidemen')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-gray-50 via-white to-primary-50 py-20 md:py-28 overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-10 left-10 w-32 h-32 bg-primary-600 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-20 w-40 h-40 bg-primary-400 rounded-full blur-3xl"></div>
    </div>
    
    <div class="max-w-6xl mx-auto px-4 relative">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="text-center md:text-left">
                <!-- Badge -->
                <div class="inline-flex items-center bg-primary-100 text-primary-700 px-4 py-2 rounded-full text-sm font-semibold mb-6">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    Sejak 1979 - Terpercaya
                </div>
                
                <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                    Pelangi Traditional<br>
                    <span class="text-primary-600 relative">
                        Weaving
                        <svg class="absolute -bottom-2 left-0 w-full h-3 text-primary-200" viewBox="0 0 200 12" fill="currentColor">
                            <path d="M0,8 Q50,2 100,8 T200,8 L200,12 L0,12 Z"></path>
                        </svg>
                    </span>
                </h1>
                
                <p class="text-lg md:text-xl text-gray-600 mb-8 max-w-lg leading-relaxed">
                    Tenun tradisional berkualitas tinggi dengan motif khas Indonesia. 
                    Dibuat dengan penuh cinta oleh pengrajin ahli di Sidemen, Bali.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 mb-8">
                    <a href="{{ route('products.index') }}" 
                       class="group bg-primary-600 text-white px-8 py-4 rounded-xl font-semibold hover:bg-primary-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Lihat Produk
                    </a>
                    <a href="{{ route('custom-orders.index') }}" 
                       class="group border-2 border-primary-600 text-primary-600 px-8 py-4 rounded-xl font-semibold hover:bg-primary-50 transition-all duration-300 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"></path>
                        </svg>
                        Custom Order
                    </a>
                </div>
                
                <!-- Stats -->
                <div class="flex items-center justify-center md:justify-start space-x-8 text-sm text-gray-600">
                    <div class="text-center">
                        <div class="font-bold text-2xl text-gray-900">45+</div>
                        <div>Tahun Pengalaman</div>
                    </div>
                    <div class="text-center">
                        <div class="font-bold text-2xl text-gray-900">1000+</div>
                        <div>Pelanggan Puas</div>
                    </div>
                    <div class="text-center">
                        <div class="font-bold text-2xl text-gray-900">50+</div>
                        <div>Motif Unik</div>
                    </div>
                </div>
            </div>
            
            <!-- Hero Image -->
            <div class="relative">
                <div class="relative bg-gradient-to-br from-primary-100 to-primary-200 rounded-3xl p-8 shadow-2xl">
                    <!-- Decorative elements -->
                    <div class="absolute top-4 right-4 w-20 h-20 bg-white/20 rounded-full blur-xl"></div>
                    <div class="absolute bottom-4 left-4 w-16 h-16 bg-white/30 rounded-full blur-lg"></div>
                    
                    @if($featuredProducts->first() && $featuredProducts->first()->images->first())
                    <img src="{{ asset('storage/' . $featuredProducts->first()->images->first()->path) }}" 
                         alt="Produk Unggulan"
                         class="w-full h-80 object-cover rounded-2xl shadow-lg">
                    @else
                    <div class="w-full h-80 bg-gradient-to-br from-primary-300 to-primary-400 rounded-2xl flex items-center justify-center">
                        <svg class="w-24 h-24 text-white/50" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    @endif
                    
                    <!-- Floating badge -->
                    <div class="absolute -bottom-4 -right-4 bg-white rounded-2xl p-4 shadow-xl">
                        <div class="flex items-center space-x-2">
                            <div class="flex -space-x-2">
                                <div class="w-8 h-8 bg-primary-600 rounded-full border-2 border-white"></div>
                                <div class="w-8 h-8 bg-primary-500 rounded-full border-2 border-white"></div>
                                <div class="w-8 h-8 bg-primary-400 rounded-full border-2 border-white"></div>
                            </div>
                            <div class="text-sm">
                                <div class="font-semibold text-gray-900">4.9/5</div>
                                <div class="text-gray-600">Rating</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Kategori Produk -->
<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Kategori Produk</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Temukan berbagai jenis kain tenun tradisional dengan kualitas terbaik dan motif yang memukau
            </p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($categories as $category)
            <a href="{{ route('products.index', ['category' => $category->id]) }}" 
               class="group relative bg-white rounded-2xl p-6 text-center hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-primary-200 transform hover:-translate-y-2">
                
                <!-- Product Slideshow -->
                <div class="relative mb-6 overflow-hidden rounded-2xl" x-data="{ currentSlide: 0, slides: {{ $category->products->count() }} }" x-init="setInterval(() => { currentSlide = (currentSlide + 1) % slides }, 3000)">
                    <div class="w-20 h-20 md:w-24 md:h-24 mx-auto relative">
                        @if($category->products->count() > 0)
                            @foreach($category->products as $index => $product)
                            <div x-show="currentSlide === {{ $index }}" 
                                 x-transition:enter="transition ease-in-out duration-500"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 x-transition:leave="transition ease-in-out duration-500"
                                 x-transition:leave-start="opacity-100 transform scale-100"
                                 x-transition:leave-end="opacity-0 transform scale-95"
                                 class="absolute inset-0">
                                @if($product->images->first())
                                <img src="{{ asset('storage/' . $product->images->first()->path) }}" 
                                     alt="{{ $product->nama_produk }}"
                                     class="w-full h-full object-cover rounded-2xl shadow-md group-hover:scale-110 transition-transform duration-500">
                                @else
                                <div class="w-full h-full bg-gradient-to-br 
                                    @if($category->id == 1) from-pink-100 to-pink-200 
                                    @elseif($category->id == 2) from-purple-100 to-purple-200
                                    @elseif($category->id == 3) from-blue-100 to-blue-200
                                    @else from-green-100 to-green-200 @endif
                                    rounded-2xl flex items-center justify-center">
                                    <div class="text-center">
                                        <div class="text-xs font-medium text-gray-600 opacity-75">
                                            {{ Str::limit($product->nama_produk, 15) }}
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        @else
                            <!-- Simple placeholder tanpa icon -->
                            <div class="w-full h-full bg-gradient-to-br 
                                @if($category->id == 1) from-pink-100 to-pink-200 
                                @elseif($category->id == 2) from-purple-100 to-purple-200
                                @elseif($category->id == 3) from-blue-100 to-blue-200
                                @else from-green-100 to-green-200 @endif
                                rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <div class="text-center">
                                    <div class="text-sm font-semibold 
                                        @if($category->id == 1) text-pink-600 
                                        @elseif($category->id == 2) text-purple-600
                                        @elseif($category->id == 3) text-blue-600
                                        @else text-green-600 @endif">
                                        @if($category->id == 1)
                                            Selendang
                                        @elseif($category->id == 2)
                                            Songket
                                        @elseif($category->id == 3)
                                            Endek Katun
                                        @else
                                            Endek Sutra
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">Coming Soon</div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Slide indicators -->
                    @if($category->products->count() > 1)
                    <div class="flex justify-center mt-2 space-x-1">
                        @foreach($category->products as $index => $product)
                        <div class="w-1.5 h-1.5 rounded-full transition-colors duration-200"
                             :class="currentSlide === {{ $index }} ? 'bg-primary-600' : 'bg-gray-300'"></div>
                        @endforeach
                    </div>
                    @endif
                    
                    <!-- Floating dot with count -->
                    <div class="absolute -top-2 -right-2 w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center">
                        <span class="text-white text-xs font-bold">{{ $category->products_count }}</span>
                    </div>
                </div>
                
                <h3 class="font-bold text-lg text-gray-900 mb-2 group-hover:text-primary-600 transition-colors">
                    @if($category->id == 1)
                        Selendang
                    @elseif($category->id == 2)
                        Kain Songket
                    @elseif($category->id == 3)
                        Endek Katun
                    @else
                        Endek Sutra
                    @endif
                </h3>
                <p class="text-sm text-gray-600 mb-4">{{ $category->products_count }} Produk Tersedia</p>
                
                <!-- Arrow -->
                <div class="flex justify-center">
                    <svg class="w-5 h-5 text-primary-600 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Produk Unggulan -->
<section class="py-20 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Produk Unggulan</h2>
                <p class="text-lg text-gray-600">Koleksi terbaik pilihan pelanggan</p>
            </div>
            <a href="{{ route('products.index') }}" class="group flex items-center text-primary-600 hover:text-primary-700 font-semibold">
                Lihat Semua
                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
            <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="relative overflow-hidden">
                    <a href="{{ route('products.show', $product->slug) }}">
                        @if($product->images->first())
                        <img src="{{ asset('storage/' . $product->images->first()->path) }}" 
                             alt="{{ $product->nama_produk }}"
                             class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                        <div class="w-full h-48 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        @endif
                    </a>
                    
                    <!-- Badge -->
                    <div class="absolute top-3 left-3 bg-primary-600 text-white px-2 py-1 rounded-full text-xs font-semibold">
                        Populer
                    </div>
                    
                    <!-- Quick view button -->
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors duration-300 flex items-center justify-center">
                        <a href="{{ route('products.show', $product->slug) }}" 
                           class="bg-white text-gray-900 px-4 py-2 rounded-lg font-semibold opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                            Lihat Detail
                        </a>
                    </div>
                </div>
                
                <div class="p-5">
                    <a href="{{ route('products.show', $product->slug) }}" 
                       class="font-bold text-gray-900 hover:text-primary-600 line-clamp-2 mb-2 transition-colors">
                        {{ $product->nama_produk }}
                    </a>
                    <p class="text-primary-600 font-bold text-xl mb-2">
                        {!! $product->getFormattedPrice() !!}
                    </p>
                    <div class="flex items-center justify-between text-sm text-gray-600">
                        <span>Stok: {{ $product->stok }}</span>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <span>4.9</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="py-16 bg-primary-600">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
            Dapatkan Update Terbaru
        </h2>
        <p class="text-xl text-primary-100 mb-8">
            Berlangganan newsletter kami untuk mendapatkan info produk terbaru dan penawaran spesial
        </p>
        <form class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
            <input type="email" 
                   class="flex-1 px-6 py-3 rounded-xl border-0 focus:outline-none focus:ring-2 focus:ring-white/50" 
                   placeholder="Masukkan email Anda">
            <button type="submit" 
                    class="bg-white text-primary-600 px-8 py-3 rounded-xl font-semibold hover:bg-gray-100 transition-colors">
                Berlangganan
            </button>
        </form>
    </div>
</section>
@endsection
