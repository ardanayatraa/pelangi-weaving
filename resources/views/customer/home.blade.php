@extends('layouts.customer')

@section('title', 'Beranda - Pelangi Traditional Weaving Sidemen')

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

<!-- Hero Banner Modern Minimalist -->
<section class="hero-banner">
    <div class="container">
        <div class="row align-items-center" style="min-height: 450px;">
            <div class="col-lg-6">
                <div class="mb-3">
                    <span class="badge" style="background: #FFF5F0; color: #DC143C; padding: 8px 16px; border-radius: 20px; font-weight: 600; font-size: 14px;">
                        ✨ Koleksi Terbaru
                    </span>
                </div>
                <h1 class="display-4 fw-bold mb-3" style="color: #1a1a1a; line-height: 1.2;">
                    Kain Tenun Bali<br>
                    <span style="color: #DC143C;">Berkualitas Premium</span>
                </h1>
                <p class="fs-5 mb-4" style="color: #666; line-height: 1.6;">
                    Kain songket dengan motif flora & fauna khas Bali sejak 1979.<br>
                    Benang katun dan sutra berkualitas premium. Pengiriman ke seluruh Indonesia.
                </p>
                <div class="d-flex gap-3">
                    <a href="{{ route('products.index') }}" class="btn btn-lg px-4" style="background: #DC143C; color: white; border-radius: 12px; font-weight: 600; border: none;">
                        <i class="bi bi-bag me-2"></i>Belanja Sekarang
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-lg px-4" style="background: white; color: #1a1a1a; border-radius: 12px; font-weight: 600; border: 2px solid #e5e5e5;">
                        <i class="bi bi-grid me-2"></i>Lihat Katalog
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

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

<!-- Keunggulan Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h3 class="fw-bold mb-3">Mengapa Memilih Kami?</h3>
            <p class="text-muted">Keunggulan Pelangi Traditional Weaving Sidemen</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-3 col-6">
                <div class="text-center p-4">
                    <div class="mb-3" style="font-size: 3rem; color: #DC143C;">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Sejak 1979</h5>
                    <p class="text-muted small">UKM pertama di Desa Sidemen dengan pengalaman 46+ tahun</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="text-center p-4">
                    <div class="mb-3" style="font-size: 3rem; color: #DC143C;">
                        <i class="bi bi-hand-thumbs-up-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Kualitas Premium</h5>
                    <p class="text-muted small">Tenun rapat, halus, dibuat dengan kesabaran dan ketelitian</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="text-center p-4">
                    <div class="mb-3" style="font-size: 3rem; color: #DC143C;">
                        <i class="bi bi-palette-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Motif Autentik</h5>
                    <p class="text-muted small">Motif flora & fauna khas Bali dengan beragam pilihan warna</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="text-center p-4">
                    <div class="mb-3" style="font-size: 3rem; color: #DC143C;">
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Rating 4.8</h5>
                    <p class="text-muted small">Kepuasan pelanggan terbukti di Google Maps</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tentang Singkat -->
<section class="py-5" style="background: linear-gradient(135deg, #FFF5F0 0%, #FFE8E0 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <span class="badge bg-danger mb-3">Tentang Kami</span>
                <h2 class="fw-bold mb-4">Melestarikan Warisan Budaya Bali</h2>
                <p class="mb-3">
                    Tenun Trisna Tradisional Weaving adalah UKM pertama di Desa Sidemen yang berdiri sejak 1979. 
                    Kami memiliki 40 ATBM dan alat pemintal benang (jantra).
                </p>
                <p class="mb-4">
                    Setiap kain songket kami dibuat dengan teknik tradisional, menggunakan motif flora dan fauna khas Bali. 
                    Tersedia dalam benang katun dan sutra dengan berbagai varian motif.
                </p>
                <a href="{{ route('products.index') }}" class="btn btn-danger btn-lg">
                    <i class="bi bi-bag me-2"></i>Lihat Produk
                </a>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="card border-0 shadow-sm text-center p-4">
                            <h3 class="fw-bold text-danger mb-2">40+</h3>
                            <p class="text-muted small mb-0">ATBM & Alat Tenun</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-0 shadow-sm text-center p-4">
                            <h3 class="fw-bold text-danger mb-2">46+</h3>
                            <p class="text-muted small mb-0">Tahun Pengalaman</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-0 shadow-sm text-center p-4">
                            <h3 class="fw-bold text-danger mb-2">35-50</h3>
                            <p class="text-muted small mb-0">Kain per Minggu</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-0 shadow-sm text-center p-4">
                            <h3 class="fw-bold text-danger mb-2">4.8★</h3>
                            <p class="text-muted small mb-0">Rating Google</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="card border-0" style="background: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%); border-radius: 16px;">
            <div class="card-body text-center text-white py-5">
                <h2 class="fw-bold mb-3">Bergabunglah Bersama Kami!</h2>
                <p class="lead mb-4">Daftar sekarang dan nikmati kemudahan berbelanja kain tenun berkualitas premium</p>
                <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5" style="border-radius: 24px; font-weight: 600;">
                    <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
