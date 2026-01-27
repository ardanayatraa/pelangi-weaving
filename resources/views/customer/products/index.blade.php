@extends('layouts.customer')

@section('title', 'Produk Kami')

@section('content')
<div class="bg-white min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('home') }}" class="hover:text-primary-600">Home</a></li>
                <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
                <li class="text-gray-900 font-medium">Produk</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Produk Kami</h1>
                <p class="text-gray-600">Menampilkan {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} produk</p>
            </div>
            
            <!-- View Toggle -->
            <div class="flex items-center space-x-4 mt-4 md:mt-0">
                <div class="flex bg-gray-100 rounded-lg p-1" x-data="{ view: 'grid' }">
                    <button @click="view = 'grid'" 
                            :class="view === 'grid' ? 'bg-white shadow-sm' : ''"
                            class="px-3 py-2 rounded-md text-sm font-medium transition">
                        Grid View
                    </button>
                    <button @click="view = 'list'" 
                            :class="view === 'list' ? 'bg-white shadow-sm' : ''"
                            class="px-3 py-2 rounded-md text-sm font-medium transition">
                        List View
                    </button>
                </div>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="bg-gray-50 rounded-2xl p-6 mb-8">
            <form method="GET" action="{{ route('products.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Nama produk, motif atau deskripsi..."
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="category" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                    <select name="price_range" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Semua Harga</option>
                        <option value="0-500000" {{ request('price_range') == '0-500000' ? 'selected' : '' }}>< Rp 500.000</option>
                        <option value="500000-1000000" {{ request('price_range') == '500000-1000000' ? 'selected' : '' }}>Rp 500rb - 1jt</option>
                        <option value="1000000-2000000" {{ request('price_range') == '1000000-2000000' ? 'selected' : '' }}>Rp 1jt - 2jt</option>
                        <option value="2000000-999999999" {{ request('price_range') == '2000000-999999999' ? 'selected' : '' }}>> Rp 2jt</option>
                    </select>
                </div>

                <!-- Sort -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Urutkan</label>
                    <select name="sort" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Terbaru</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama A-Z</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Terpopuler</option>
                    </select>
                </div>

                <!-- Filter Button -->
                <div class="md:col-span-5 flex justify-end">
                    <button type="submit" class="bg-primary-600 text-white px-8 py-3 rounded-xl hover:bg-primary-700 transition font-medium">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Category Tabs -->
        <div class="flex flex-wrap gap-2 mb-8">
            <a href="{{ route('products.index') }}" 
               class="px-6 py-3 rounded-full font-medium transition {{ !request('category') ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Semua ({{ \App\Models\Produk::where('status', 'aktif')->count() }})
            </a>
            @foreach(\App\Models\Kategori::withCount(['products' => function($query) { $query->where('status', 'aktif'); }])->get() as $cat)
            <a href="{{ route('products.index', ['category' => $cat->id_kategori]) }}" 
               class="px-6 py-3 rounded-full font-medium transition {{ request('category') == $cat->id_kategori ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                {{ Str::limit($cat->nama_kategori, 15) }} ({{ $cat->products_count }})
            </a>
            @endforeach
        </div>

        <!-- Products Grid -->
        @if($products->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-12">
            @foreach($products as $product)
            <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100">
                <!-- Product Image -->
                <div class="relative overflow-hidden">
                    <a href="{{ route('products.show', $product->slug) }}">
                        @if($product->images->first())
                        <img src="{{ asset('storage/' . $product->images->first()->path) }}" 
                             alt="{{ $product->nama_produk }}"
                             class="w-full h-48 md:h-56 object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                        <div class="w-full h-48 md:h-56 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        @endif
                    </a>
                    
                    <!-- Badges -->
                    <div class="absolute top-3 left-3">
                        @if($product->stok < 10 && $product->stok > 0)
                        <span class="bg-orange-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                            Terbatas
                        </span>
                        @elseif($product->stok == 0)
                        <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                            Habis
                        </span>
                        @else
                        <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                            Tersedia
                        </span>
                        @endif
                    </div>

                    <!-- Quick View Overlay -->
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors duration-300 flex items-center justify-center">
                        <a href="{{ route('products.show', $product->slug) }}" 
                           class="bg-white text-gray-900 px-4 py-2 rounded-lg font-semibold opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                            Lihat Detail
                        </a>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="p-5">
                    <!-- Category -->
                    <span class="inline-block bg-primary-100 text-primary-700 text-xs px-2 py-1 rounded-full mb-3">
                        {{ $product->category->nama_kategori }}
                    </span>
                    
                    <!-- Product Name -->
                    <a href="{{ route('products.show', $product->slug) }}" 
                       class="block font-bold text-gray-900 hover:text-primary-600 mb-3 line-clamp-2 transition-colors">
                        {{ $product->nama_produk }}
                    </a>
                    
                    <!-- Price -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-primary-600 font-bold text-xl">
                            {!! $product->getFormattedPrice() !!}
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <span>4.9</span>
                        </div>
                    </div>
                    
                    <!-- Stock Info -->
                    <div class="text-sm text-gray-600 mb-4">
                        Stok: <span class="font-medium">{{ $product->stok }}</span>
                    </div>
                    
                    <!-- Action Button -->
                    <a href="{{ route('products.show', $product->slug) }}" 
                       class="block w-full text-center bg-primary-600 text-white py-3 rounded-xl hover:bg-primary-700 transition font-medium">
                        Lihat Detail
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $products->appends(request()->query())->links() }}
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <svg class="w-24 h-24 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
            </svg>
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Produk Tidak Ditemukan</h3>
            <p class="text-gray-600 mb-8 max-w-md mx-auto">
                @if(request('search'))
                    Tidak ada produk yang cocok dengan pencarian "{{ request('search') }}"
                @else
                    Belum ada produk tersedia untuk kategori ini
                @endif
            </p>
            <a href="{{ route('products.index') }}" 
               class="inline-block bg-primary-600 text-white px-8 py-3 rounded-xl hover:bg-primary-700 transition font-medium">
                Lihat Semua Produk
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
