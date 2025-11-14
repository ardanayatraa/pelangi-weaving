@extends('layouts.customer')

@section('title', 'Produk')

@section('content')
<div class="bg-gray-50 min-h-screen py-4 md:py-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-4 md:mb-8">
            <h1 class="text-xl md:text-3xl font-bold text-gray-800 mb-2">
                @if(request('search'))
                    Hasil Pencarian: "{{ request('search') }}"
                @elseif(request('category'))
                    Kategori: {{ $products->first()->category->nama_kategori ?? 'Produk' }}
                @else
                    Semua Produk
                @endif
            </h1>
            <p class="text-sm md:text-base text-gray-600">Ditemukan {{ $products->total() }} produk</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6" x-data="{ showFilter: false }">
            <!-- Mobile Filter Toggle -->
            <div class="lg:hidden mb-4">
                <button @click="showFilter = !showFilter" 
                        class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 flex items-center justify-between font-medium text-gray-700">
                    <span><i class="bi bi-funnel mr-2"></i>Filter Produk</span>
                    <i class="bi" :class="showFilter ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                </button>
            </div>

            <!-- Sidebar Filter -->
            <div class="lg:col-span-1" x-show="showFilter" x-cloak class="lg:!block">
                <div class="bg-white rounded-lg shadow-sm p-4 md:p-6 lg:sticky lg:top-20">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2 mb-6">
                        <i class="bi bi-funnel"></i> Filter Produk
                    </h3>

                    <form method="GET" action="{{ route('products.index') }}" class="space-y-6">
                        <!-- Keyword Search -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="bi bi-search"></i> Kata Kunci
                            </label>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Cari produk..."
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600 text-sm">
                        </div>

                        <!-- Category Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="bi bi-tag"></i> Kategori
                            </label>
                            <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600 text-sm">
                                <option value="">Semua Kategori</option>
                                @foreach(\App\Models\Kategori::all() as $cat)
                                <option value="{{ $cat->id_kategori }}" {{ request('category') == $cat->id_kategori ? 'selected' : '' }}>
                                    {{ $cat->nama_kategori }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="bi bi-cash-stack"></i> Range Harga
                            </label>
                            <div class="space-y-2">
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-gray-500 text-sm">Rp</span>
                                    <input type="number" name="min_price" value="{{ request('min_price') }}" 
                                           placeholder="Min"
                                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600 text-sm">
                                </div>
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-gray-500 text-sm">Rp</span>
                                    <input type="number" name="max_price" value="{{ request('max_price') }}" 
                                           placeholder="Max"
                                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600 text-sm">
                                </div>
                            </div>
                            
                            <!-- Quick Price -->
                            <div class="mt-3 space-y-1">
                                <a href="{{ route('products.index', ['min_price' => 0, 'max_price' => 500000] + request()->except(['min_price', 'max_price'])) }}" 
                                   class="block text-xs text-gray-600 hover:text-primary-600 py-1">
                                    &lt; Rp 500.000
                                </a>
                                <a href="{{ route('products.index', ['min_price' => 500000, 'max_price' => 1000000] + request()->except(['min_price', 'max_price'])) }}" 
                                   class="block text-xs text-gray-600 hover:text-primary-600 py-1">
                                    Rp 500rb - 1jt
                                </a>
                                <a href="{{ route('products.index', ['min_price' => 1000000, 'max_price' => 2000000] + request()->except(['min_price', 'max_price'])) }}" 
                                   class="block text-xs text-gray-600 hover:text-primary-600 py-1">
                                    Rp 1jt - 2jt
                                </a>
                                <a href="{{ route('products.index', ['min_price' => 2000000] + request()->except(['min_price', 'max_price'])) }}" 
                                   class="block text-xs text-gray-600 hover:text-primary-600 py-1">
                                    &gt; Rp 2jt
                                </a>
                            </div>
                        </div>

                        <!-- Sort -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="bi bi-sort-down"></i> Urutkan
                            </label>
                            <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600 text-sm">
                                <option value="">Default</option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama A-Z</option>
                            </select>
                        </div>

                        <!-- Stock Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="bi bi-box-seam"></i> Ketersediaan
                            </label>
                            <select name="stock" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-primary-600 text-sm">
                                <option value="">Semua</option>
                                <option value="available" {{ request('stock') == 'available' ? 'selected' : '' }}>Tersedia</option>
                                <option value="limited" {{ request('stock') == 'limited' ? 'selected' : '' }}>Terbatas</option>
                            </select>
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-2 pt-4 border-t">
                            <button type="submit" class="flex-1 bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition text-sm font-medium">
                                <i class="bi bi-search"></i> Filter
                            </button>
                            @if(request()->hasAny(['search', 'category', 'sort', 'stock', 'min_price', 'max_price']))
                            <a href="{{ route('products.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-sm">
                                <i class="bi bi-x-circle"></i>
                            </a>
                            @endif
                        </div>
                    </form>

                    <!-- Active Filters -->
                    @if(request()->hasAny(['search', 'category', 'min_price', 'max_price']))
                    <div class="mt-6 pt-6 border-t">
                        <p class="text-xs font-medium text-gray-700 mb-2">Filter Aktif:</p>
                        <div class="space-y-2">
                            @if(request('search'))
                            <div class="flex items-center justify-between text-xs bg-primary-50 text-primary-700 px-2 py-1 rounded">
                                <span>"{{ request('search') }}"</span>
                                <a href="{{ route('products.index', request()->except('search')) }}" class="hover:text-primary-900">×</a>
                            </div>
                            @endif
                            @if(request('category'))
                            <div class="flex items-center justify-between text-xs bg-primary-50 text-primary-700 px-2 py-1 rounded">
                                <span>{{ \App\Models\Kategori::find(request('category'))->nama_kategori ?? '' }}</span>
                                <a href="{{ route('products.index', request()->except('category')) }}" class="hover:text-primary-900">×</a>
                            </div>
                            @endif
                            @if(request('min_price') || request('max_price'))
                            <div class="flex items-center justify-between text-xs bg-primary-50 text-primary-700 px-2 py-1 rounded">
                                <span>Rp {{ number_format(request('min_price', 0)) }} - {{ request('max_price') ? number_format(request('max_price')) : '∞' }}</span>
                                <a href="{{ route('products.index', request()->except(['min_price', 'max_price'])) }}" class="hover:text-primary-900">×</a>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Products Grid -->

            <!-- Products Grid -->
            <div class="lg:col-span-3">
                @if($products->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-6">
            @foreach($products as $product)
            <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition group">
                <a href="{{ route('products.show', $product->slug) }}" class="block">
                    @if($product->images->first())
                    <img src="{{ asset('storage/' . $product->images->first()->path) }}" 
                         alt="{{ $product->nama_produk }}"
                         class="w-full h-32 md:h-48 object-cover group-hover:scale-105 transition duration-300">
                    @else
                    <div class="w-full h-32 md:h-48 bg-gray-200 flex items-center justify-center">
                        <i class="bi bi-image text-gray-400 text-2xl md:text-4xl"></i>
                    </div>
                    @endif
                </a>
                
                <div class="p-3 md:p-4">
                    <span class="inline-block bg-primary-100 text-primary-700 text-xs px-2 py-1 rounded-full mb-2">
                        {{ $product->category->nama_kategori }}
                    </span>
                    
                    <a href="{{ route('products.show', $product->slug) }}" 
                       class="block font-bold text-sm md:text-base text-gray-800 hover:text-primary-600 mb-2 line-clamp-2">
                        {{ $product->nama_produk }}
                    </a>
                    
                    <p class="text-primary-600 font-bold text-base md:text-xl mb-2">
                        {!! $product->getFormattedPrice() !!}
                    </p>
                    
                    <div class="flex items-center justify-between text-xs md:text-sm text-gray-600 mb-3">
                        <span><i class="bi bi-box-seam"></i> Stok: {{ $product->stok }}</span>
                        @if($product->stok < 10 && $product->stok > 0)
                        <span class="text-orange-600"><i class="bi bi-exclamation-circle"></i> Terbatas</span>
                        @elseif($product->stok == 0)
                        <span class="text-red-600"><i class="bi bi-x-circle"></i> Habis</span>
                        @endif
                    </div>
                    
                    <a href="{{ route('products.show', $product->slug) }}" 
                       class="block text-center bg-primary-600 text-white py-1.5 md:py-2 rounded-lg hover:bg-primary-700 transition text-xs md:text-sm">
                        <i class="bi bi-eye"></i> Lihat Detail
                    </a>
                </div>
            </div>
            @endforeach
        </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
                @else
                <!-- Empty State -->
                <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                    <i class="bi bi-inbox text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Produk Tidak Ditemukan</h3>
                    <p class="text-gray-600 mb-6">
                        @if(request('search'))
                            Tidak ada produk yang cocok dengan pencarian "{{ request('search') }}"
                        @else
                            Belum ada produk tersedia saat ini
                        @endif
                    </p>
                    <a href="{{ route('products.index') }}" class="inline-block bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition">
                        <i class="bi bi-arrow-left"></i> Lihat Semua Produk
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
