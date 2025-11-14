<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pelangi Traditional Weaving Sidemen')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-red: #DC2626;
            --dark-red: #B91C1C;
            --light-red: #EF4444;
            --black: #1F2937;
            --dark-black: #111827;
            --white: #FFFFFF;
            --off-white: #F9FAFB;
            --gray-light: #E5E7EB;
            --gray-medium: #6B7280;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--off-white);
            color: var(--black);
        }
        
        .navbar-custom {
            background: linear-gradient(135deg, var(--primary-red) 0%, var(--dark-red) 100%);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        
        .navbar-custom .navbar-brand {
            color: white !important;
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .navbar-custom .nav-link {
            color: white !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            transition: all 0.3s;
        }
        
        .navbar-custom .nav-link:hover {
            background: rgba(255,255,255,0.2);
            border-radius: 8px;
        }
        
        .btn-primary-custom {
            background: var(--primary-red);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .btn-primary-custom:hover {
            background: var(--dark-red);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }
        
        .card-custom {
            border: none;
            border-radius: 12px;
            background: var(--white);
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s;
        }
        
        .card-custom:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(220, 38, 38, 0.15);
        }
        
        .badge-custom {
            background: var(--primary-red);
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-weight: 600;
        }
        
        .search-box {
            background: white;
            border-radius: 24px;
            padding: 0.5rem 1.5rem;
            border: 2px solid var(--gray-light);
        }
        
        .search-box:focus {
            border-color: var(--primary-red);
            outline: none;
        }
        
        .bg-dark-custom {
            background-color: var(--dark-black) !important;
        }
        
        .text-red-custom {
            color: var(--primary-red) !important;
        }
        
        .btn-outline-red {
            border: 2px solid var(--primary-red);
            color: var(--primary-red);
            background: transparent;
        }
        
        .btn-outline-red:hover {
            background: var(--primary-red);
            color: white;
        }
        
        .hover-red:hover {
            color: var(--primary-red) !important;
        }
        
        .alert-success {
            background: #DEF7EC;
            border-color: #84E1BC;
            color: #03543F;
        }
        
        .alert-danger {
            background: #FDE8E8;
            border-color: #F98080;
            color: #9B1C1C;
        }
        
        .btn-success {
            background: var(--primary-red) !important;
            border-color: var(--primary-red) !important;
        }
        
        .btn-success:hover {
            background: var(--dark-red) !important;
            border-color: var(--dark-red) !important;
        }
        
        .text-primary {
            color: var(--primary-red) !important;
        }
        
        .bg-primary {
            background-color: var(--primary-red) !important;
        }
        
        .border-primary {
            border-color: var(--primary-red) !important;
        }
        
        /* Override Bootstrap btn-primary */
        .btn-primary {
            background-color: var(--primary-red) !important;
            border-color: var(--primary-red) !important;
            color: white !important;
        }
        
        .btn-primary:hover,
        .btn-primary:focus,
        .btn-primary:active {
            background-color: var(--dark-red) !important;
            border-color: var(--dark-red) !important;
            color: white !important;
        }
        
        .btn-info {
            background-color: var(--black) !important;
            border-color: var(--black) !important;
            color: white !important;
        }
        
        .btn-info:hover {
            background-color: var(--dark-black) !important;
            border-color: var(--dark-black) !important;
        }
    </style>
</head>
<body>
    <!-- Top Bar -->
    <div class="bg-dark-custom text-white py-2">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="small">
                    <i class="bi bi-telephone text-red-custom"></i> Hubungi Kami: +62 361-123456
                </div>
                <div class="small">
                     Selamat berbelanja !
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-shop"></i> Pelangi Traditional Weaving
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="border-color: white;">
                <span class="navbar-toggler-icon" style="filter: brightness(0) invert(1);"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Search Bar -->
                <div class="mx-auto d-none d-lg-block" style="width: 400px;">
                    <form action="{{ route('products.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control search-box" placeholder="Cari produk tenun...">
                            <button class="btn btn-light" type="submit" style="border-radius: 0 24px 24px 0;">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
                
                <ul class="navbar-nav ms-auto align-items-center">
                    @auth('pelanggan')
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-2" href="{{ route('cart.index') }}">
                            @php
                                $cartCount = Auth::guard('pelanggan')->user()->carts()->sum('jumlah');
                            @endphp
                            @if($cartCount > 0)
                            <span class="badge rounded-pill" style="background: #FFFFFF; color: #DC2626; font-size: 0.75rem; padding: 0.3rem 0.6rem;">
                                {{ $cartCount }}
                            </span>
                            @endif
                            <span><i class="bi bi-cart3"></i> Keranjang</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('orders.index') }}">
                            <i class="bi bi-box-seam"></i> Pesanan
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ Auth::guard('pelanggan')->user()->nama }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a href="{{ route('profile.index') }}" class="dropdown-item">
                                    <i class="bi bi-person-fill"></i> Profile Saya
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right"></i> Masuk
                        </a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-light btn-sm" href="{{ route('register') }}" style="border-radius: 20px; font-weight: 600;">
                            Daftar
                        </a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Category Bar -->
    <div class="bg-white border-bottom py-2 shadow-sm">
        <div class="container">
            <div class="d-flex gap-3 overflow-auto">
                <a href="{{ route('products.index') }}" class="text-decoration-none text-dark hover-red">
                    <i class="bi bi-grid text-red-custom"></i> Semua Produk
                </a>
                <a href="{{ route('products.index', ['category' => 1]) }}" class="text-decoration-none text-dark hover-red">
                    <i class="bi bi-star text-red-custom"></i> Songket Premium
                </a>
                <a href="{{ route('products.index', ['category' => 2]) }}" class="text-decoration-none text-dark hover-red">
                    <i class="bi bi-heart text-red-custom"></i> Endek Bali
                </a>
                <a href="{{ route('products.index', ['category' => 3]) }}" class="text-decoration-none text-dark hover-red">
                    <i class="bi bi-bag text-red-custom"></i> Selendang
                </a>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
    <div class="container mt-3">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="container mt-3">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    <!-- Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="bg-dark-custom text-white mt-auto">
        <!-- Main Footer -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8">
                <!-- Brand Section -->
                <div class="lg:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <i class="bi bi-shop text-red-500 text-3xl"></i>
                        <h3 class="text-2xl font-bold">Pelangi Traditional Weaving Sidemen</h3>
                    </div>
                    <p class="text-gray-400 mb-4 leading-relaxed">
                        UKM tenun tradisional di Desa Sidemen sejak 1979. Menyediakan kain songket berkualitas premium 
                        dengan motif flora dan fauna khas Bali. Benang katun dan sutra dengan pengerjaan teliti dan penuh kesabaran.
                    </p>
                    <div class="flex items-center gap-4 text-sm text-gray-400 mb-6">
                        <div>
                            <i class="bi bi-clock-fill text-red-500 me-1"></i>
                            Sejak 1979
                        </div>
                        <div>
                            <i class="bi bi-star-fill text-yellow-500 me-1"></i>
                            Rating 4.8
                        </div>
                        <div>
                            <i class="bi bi-award-fill text-red-500 me-1"></i>
                            40+ ATBM
                        </div>
                    </div>
                    
                    <!-- Social Media -->
                    <div class="flex items-center gap-3">
                        <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-red-600 rounded-full flex items-center justify-center transition-colors">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-red-600 rounded-full flex items-center justify-center transition-colors">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-red-600 rounded-full flex items-center justify-center transition-colors">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                    </div>
                </div>

                <!-- Menu Cepat -->
                <div>
                    <h4 class="font-bold text-lg mb-4">Menu Cepat</h4>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ route('home') }}" class="text-gray-400 hover:text-red-500 transition-colors flex items-center gap-2">
                                <i class="bi bi-chevron-right text-xs"></i>
                                Beranda
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('products.index') }}" class="text-gray-400 hover:text-red-500 transition-colors flex items-center gap-2">
                                <i class="bi bi-chevron-right text-xs"></i>
                                Produk
                            </a>
                        </li>
                        @auth('pelanggan')
                        <li>
                            <a href="{{ route('cart.index') }}" class="text-gray-400 hover:text-red-500 transition-colors flex items-center gap-2">
                                <i class="bi bi-chevron-right text-xs"></i>
                                Keranjang
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('orders.index') }}" class="text-gray-400 hover:text-red-500 transition-colors flex items-center gap-2">
                                <i class="bi bi-chevron-right text-xs"></i>
                                Pesanan Saya
                            </a>
                        </li>
                        @else
                        <li>
                            <a href="{{ route('login') }}" class="text-gray-400 hover:text-red-500 transition-colors flex items-center gap-2">
                                <i class="bi bi-chevron-right text-xs"></i>
                                Login
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('register') }}" class="text-gray-400 hover:text-red-500 transition-colors flex items-center gap-2">
                                <i class="bi bi-chevron-right text-xs"></i>
                                Daftar
                            </a>
                        </li>
                        @endauth
                    </ul>
                </div>

                <!-- Kategori Produk -->
                <div>
                    <h4 class="font-bold text-lg mb-4">Kategori Produk</h4>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ route('products.index', ['kategori' => 'kain-tenun']) }}" class="text-gray-400 hover:text-red-500 transition-colors flex items-center gap-2">
                                <i class="bi bi-chevron-right text-xs"></i>
                                Kain Tenun
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('products.index', ['kategori' => 'selendang']) }}" class="text-gray-400 hover:text-red-500 transition-colors flex items-center gap-2">
                                <i class="bi bi-chevron-right text-xs"></i>
                                Selendang
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('products.index', ['kategori' => 'tas']) }}" class="text-gray-400 hover:text-red-500 transition-colors flex items-center gap-2">
                                <i class="bi bi-chevron-right text-xs"></i>
                                Tas Tenun
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('products.index', ['kategori' => 'aksesoris']) }}" class="text-gray-400 hover:text-red-500 transition-colors flex items-center gap-2">
                                <i class="bi bi-chevron-right text-xs"></i>
                                Aksesoris
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('products.index') }}" class="text-gray-400 hover:text-red-500 transition-colors flex items-center gap-2">
                                <i class="bi bi-chevron-right text-xs"></i>
                                Lihat Semua
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Hubungi Kami -->
                <div>
                    <h4 class="font-bold text-lg mb-4">Hubungi Kami</h4>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <i class="bi bi-geo-alt-fill text-red-500 text-xl mt-1"></i>
                            <div>
                                <p class="text-gray-400 text-sm">
                                    Jl. Sidemen, Kec. Sidemen<br>
                                    Kabupaten Karangasem, Bali 80864<br>
                                    Indonesia
                                </p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="bi bi-telephone-fill text-red-500 text-xl"></i>
                            <div>
                                <p class="text-gray-400 text-sm">+62 361-123456</p>
                                <p class="text-gray-400 text-sm">WhatsApp: +62 812-3456-7890</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="bi bi-envelope-fill text-red-500 text-xl"></i>
                            <div>
                                <p class="text-gray-400 text-sm">info@pelangiweaving.com</p>
                                <p class="text-gray-400 text-sm">support@pelangiweaving.com</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="bi bi-clock-fill text-red-500 text-xl"></i>
                            <div>
                                <p class="text-gray-400 text-sm">Senin - Minggu</p>
                                <p class="text-gray-400 text-sm">08:00 - 18:00 WITA</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Payment & Shipping -->
        <div class="border-t border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-center">
                    <!-- Payment -->
                    <div>
                        <p class="text-xs text-gray-500 mb-3">Metode Pembayaran</p>
                        <div class="flex flex-wrap justify-center gap-2">
                            <span class="bg-gray-800 text-white px-3 py-1 rounded text-xs">QRIS</span>
                            <span class="bg-gray-800 text-white px-3 py-1 rounded text-xs">Virtual Account</span>
                            <span class="bg-gray-800 text-white px-3 py-1 rounded text-xs">Credit Card</span>
                            <span class="bg-gray-800 text-white px-3 py-1 rounded text-xs">E-Wallet</span>
                        </div>
                        <p class="text-xs text-gray-600 mt-2">Powered by Midtrans</p>
                    </div>
                    
                    <!-- Shipping -->
                    <div>
                        <p class="text-xs text-gray-500 mb-3">Jasa Pengiriman</p>
                        <div class="flex flex-wrap justify-center gap-2">
                            <span class="bg-gray-800 text-white px-3 py-1 rounded text-xs">JNE</span>
                            <span class="bg-gray-800 text-white px-3 py-1 rounded text-xs">TIKI</span>
                            <span class="bg-gray-800 text-white px-3 py-1 rounded text-xs">POS</span>
                            <span class="bg-gray-800 text-white px-3 py-1 rounded text-xs">J&T</span>
                            <span class="bg-gray-800 text-white px-3 py-1 rounded text-xs">SiCepat</span>
                        </div>
                        <p class="text-xs text-gray-600 mt-2">Powered by RajaOngkir</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Footer -->
        <div class="border-t border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <p class="text-sm text-gray-400 text-center md:text-left">
                        &copy; {{ date('Y') }} <span class="text-red-500 font-semibold">Pelangi Traditional Weaving Sidemen</span>. 
                        All rights reserved.
                        <a href="{{ route('admin.login') }}" class="text-gray-600 hover:text-gray-500 transition-colors ms-3" style="font-size: 0.7rem; opacity: 0.5;">•</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button id="backToTop" class="btn position-fixed bottom-0 end-0 m-4 rounded-circle shadow-lg" 
            style="width: 50px; height: 50px; background: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%); border: none; display: none; z-index: 1000; transition: all 0.3s;">
        <i class="bi bi-arrow-up text-white" style="font-size: 1.5rem;"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Back to Top functionality
        const backToTopBtn = document.getElementById('backToTop');
        
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopBtn.style.display = 'block';
            } else {
                backToTopBtn.style.display = 'none';
            }
        });
        
        backToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
        
        // Hover effect
        backToTopBtn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 8px 20px rgba(220, 38, 38, 0.4)';
        });
        
        backToTopBtn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
        });
    </script>
    
    @stack('scripts')
</body>
</html>
