@extends('layouts.customer')

@section('title', 'Beranda - Pelangi Weaving')

@section('content')
<style>
    .hero-banner {
        margin-bottom: 2rem;
    }
    
    .hero-slide {
        border-radius: 0 0 24px 24px;
        overflow: hidden;
    }
    
    .category-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s;
        border: 2px solid #F0F0F0;
    }
    
    .category-card:hover {
        border-color: #8B0000;
        box-shadow: 0 4px 16px rgba(139,0,0,0.15);
    }
    
    .category-icon {
        font-size: 3rem;
        color: #8B0000;
        margin-bottom: 1rem;
    }
    
    .product-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s;
        border: 1px solid #E0E0E0;
    }
    
    .product-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        border-color: #8B0000;
    }
    
    .product-image {
        width: 100%;
        height: 220px;
        object-fit: cover;
        background: #F8F8F8;
    }
    
    .price-tag {
        color: #DC143C;
        font-size: 1.25rem;
        font-weight: 700;
    }
    

</style>

<!-- Hero Banner Blibli Style -->
<section class="hero-banner">
    <div class="container-fluid px-0">
        <div class="row g-0">
            <div class="col-12">
                <div class="hero-slide" style="background: #8B0000; min-height: 400px; display: flex; align-items: center;">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-6 text-white">
                                <span class="badge bg-light text-dark mb-3 px-3 py-2 fw-bold">✨ Koleksi Terbaru</span>
                                <h1 class="display-3 fw-bold mb-3">Kain Tenun Bali Berkualitas</h1>
                                <p class="fs-5 mb-4 opacity-90">Temukan koleksi kain tenun tradisional dengan motif autentik. Pengiriman ke seluruh Indonesia!</p>
                                <div class="d-flex gap-3">
                                    <a href="{{ route('products.index') }}" class="btn btn-light btn-lg px-5 fw-bold text-dark" style="border-radius: 8px;">
                                        Belanja Sekarang
                                    </a>
                                    <a href="{{ route('products.index') }}" class="btn btn-outline-light btn-lg px-5 fw-bold" style="border-radius: 8px;">
                                        Lihat Katalog
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-6 text-center d-none d-lg-block">
                                <img src="https://images.unsplash.com/photo-1558769132-cb1aea1f5db8?w=600&h=400&fit=crop" 
                                     alt="Kain Tenun" 
                                     class="img-fluid rounded-4 shadow-lg"
                                     style="max-height: 350px; object-fit: cover;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Info Banner -->
<div class="container my-4">
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card border-0 text-white" style="border-radius: 12px; background: #8B0000;">
                <div class="card-body text-center py-4">
                    <i class="bi bi-truck fs-1 mb-2"></i>
                    <h6 class="mb-0">Pengiriman Cepat</h6>
                    <small>Ke Seluruh Indonesia</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 bg-dark text-white" style="border-radius: 12px;">
                <div class="card-body text-center py-4">
                    <i class="bi bi-shield-check fs-1 mb-2"></i>
                    <h6 class="mb-0">100% Original</h6>
                    <small>Produk Asli Bali</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 bg-light text-dark" style="border-radius: 12px; border: 2px solid #E0E0E0;">
                <div class="card-body text-center py-4">
                    <i class="bi bi-arrow-repeat fs-1 mb-2"></i>
                    <h6 class="mb-0">Mudah Dikembalikan</h6>
                    <small>Garansi 7 Hari</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Categories -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">Kategori Pilihan</h3>
            <a href="{{ route('products.index') }}" class="text-decoration-none" style="color: #FF6600; font-weight: 600;">
                Lihat Semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        
        <!-- Auto-scroll Categories -->
        <div class="categories-slider-wrapper">
            <div class="categories-slider" id="categoriesSlider">
                @foreach($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->id_kategori]) }}" class="text-decoration-none">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="bi bi-{{ $loop->index % 4 == 0 ? 'star' : ($loop->index % 4 == 1 ? 'heart' : ($loop->index % 4 == 2 ? 'bag' : 'gem')) }}"></i>
                        </div>
                        <h6 class="fw-bold text-dark">{{ $category->nama_kategori }}</h6>
                        <p class="text-muted small mb-0">{{ $category->products_count }} produk</p>
                    </div>
                </a>
                @endforeach
                
                <!-- Duplicate for seamless loop -->
                @foreach($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->id_kategori]) }}" class="text-decoration-none">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="bi bi-{{ $loop->index % 4 == 0 ? 'star' : ($loop->index % 4 == 1 ? 'heart' : ($loop->index % 4 == 2 ? 'bag' : 'gem')) }}"></i>
                        </div>
                        <h6 class="fw-bold text-dark">{{ $category->nama_kategori }}</h6>
                        <p class="text-muted small mb-0">{{ $category->products_count }} produk</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
    
    <style>
    .categories-slider-wrapper {
        overflow: hidden;
        position: relative;
    }
    
    .categories-slider {
        display: flex;
        gap: 1.5rem;
        animation: scroll 30s linear infinite;
        width: fit-content;
    }
    
    .categories-slider:hover {
        animation-play-state: paused;
    }
    
    .categories-slider .category-card {
        min-width: 200px;
    }
    
    @keyframes scroll {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(-50%);
        }
    }
    
    @media (max-width: 768px) {
        .categories-slider .category-card {
            min-width: 150px;
        }
    }
    </style>
</section>

<!-- Featured Products -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">Produk Unggulan</h3>
            <a href="{{ route('products.index') }}" class="text-decoration-none" style="color: #FF6600; font-weight: 600;">
                Lihat Semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        
        <div class="row g-4">
            @foreach($featuredProducts as $product)
            <div class="col-6 col-md-3">
                <div class="product-card">
                    <a href="{{ route('products.show', $product->slug) }}">
                        @if($product->images->first())
                        <img src="{{ Storage::url($product->images->first()->path) }}" class="product-image" alt="{{ $product->nama_produk }}">
                        @else
                        <div class="product-image bg-light d-flex align-items-center justify-content-center">
                            <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                        </div>
                        @endif
                    </a>
                    
                    <div class="p-3">
                        <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">
                            <h6 class="mb-2" style="height: 40px; overflow: hidden;">{{ $product->nama_produk }}</h6>
                        </a>
                        <div class="price-tag mb-2">{!! $product->getFormattedPrice() !!}</div>
                        <div class="text-muted small mb-3">
                            <i class="bi bi-box-seam me-1"></i>
                            <span>Stok: {{ $product->stok }}</span>
                        </div>
                        <a href="{{ route('products.show', $product->slug) }}" class="btn btn-primary-custom btn-sm w-100">
                            <i class="bi bi-cart-plus"></i> Beli
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="card border-0" style="background: linear-gradient(135deg, #0095DA 0%, #00B4DB 100%); border-radius: 16px;">
            <div class="card-body text-center text-white py-5">
                <h2 class="fw-bold mb-3">Bergabunglah Bersama Kami!</h2>
                <p class="lead mb-4">Daftar sekarang dan nikmati kemudahan berbelanja kain tenun berkualitas</p>
                <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5" style="border-radius: 24px; font-weight: 600;">
                    Daftar Sekarang
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
