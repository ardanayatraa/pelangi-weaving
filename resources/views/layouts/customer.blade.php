<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pelangi Traditional Weaving Sidemen')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#FEF2F2',
                            100: '#FEE2E2',
                            200: '#FECACA',
                            300: '#FCA5A5',
                            400: '#F87171',
                            500: '#EF4444',
                            600: '#DC2626',
                            700: '#B91C1C',
                            800: '#991B1B',
                            900: '#7F1D1D',
                        },
                        dark: {
                            800: '#1F2937',
                            900: '#111827',
                        }
                    }
                }
            }
        }
    </script>
    
    @vite(['resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        [x-cloak] { display: none !important; }
        
        /* Custom scrollbar */
        .scrollbar-thin::-webkit-scrollbar {
            height: 6px;
            width: 6px;
        }
        .scrollbar-thin::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        /* Hide scrollbar for mobile but keep functionality */
        @media (max-width: 768px) {
            .overflow-x-auto::-webkit-scrollbar {
                display: none;
            }
            .overflow-x-auto {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Top Bar -->
    <div class="bg-dark-900 text-white py-2 hidden md:block">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center text-sm">
                <div><i class="bi bi-telephone text-primary-600"></i> +62 361-123456</div>
                <div>Selamat berbelanja!</div>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-primary-600 to-primary-700 shadow-lg sticky top-0 z-50" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('home') }}" class="text-white font-bold text-xl">
                    <i class="bi bi-shop"></i> Pelangi Weaving
                </a>
                
                <!-- Search Bar (Desktop) -->
                <div class="hidden lg:block flex-1 max-w-md mx-8">
                    <form action="{{ route('products.index') }}" method="GET">
                        <div class="relative">
                            <input type="text" name="search" 
                                   class="w-full pl-4 pr-12 py-2 rounded-full border-2 border-white/20 bg-white/10 text-white placeholder-white/70 focus:bg-white focus:text-gray-800 focus:placeholder-gray-400 transition"
                                   placeholder="Cari produk tenun...">
                            <button type="submit" class="absolute right-0 top-0 h-full px-4 text-white hover:text-gray-200">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
                
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('products.index') }}" class="text-white hover:text-gray-200">Produk</a>
                    
                    @auth('pelanggan')
                    <a href="{{ route('cart.index') }}" class="text-white hover:text-gray-200">
                        <i class="bi bi-cart3"></i> Keranjang
                        @php
                            $cartCount = Auth::guard('pelanggan')->user()->carts()->sum('jumlah');
                        @endphp
                        @if($cartCount > 0)
                        <span class="bg-white text-primary-600 text-xs px-2 py-0.5 rounded-full ml-1">{{ $cartCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('orders.index') }}" class="text-white hover:text-gray-200">
                        <i class="bi bi-box-seam"></i> Pesanan
                    </a>
                    
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="text-white hover:text-gray-200 flex items-center gap-1">
                            <i class="bi bi-person-circle"></i> {{ Auth::guard('pelanggan')->user()->nama }}
                            <i class="bi bi-chevron-down text-xs"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" 
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2">
                            <a href="{{ route('profile.index') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                <i class="bi bi-person-fill"></i> Profile
                            </a>
                            <hr class="my-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <a href="{{ route('login') }}" class="text-white hover:text-gray-200">Masuk</a>
                    <a href="{{ route('register') }}" class="bg-white text-primary-600 px-4 py-2 rounded-full font-semibold hover:bg-gray-100">
                        Daftar
                    </a>
                    @endauth
                </div>
                
                <button @click="open = !open" class="md:hidden text-white">
                    <i class="bi text-2xl" :class="open ? 'bi-x' : 'bi-list'"></i>
                </button>
            </div>
            
            <!-- Mobile Menu -->
            <div x-show="open" class="md:hidden pb-4">
                <!-- Mobile Search -->
                <form action="{{ route('products.index') }}" method="GET" class="mb-4">
                    <div class="relative">
                        <input type="text" name="search" 
                               class="w-full pl-4 pr-12 py-2 rounded-full border-2 border-white/20 bg-white/10 text-white placeholder-white/70"
                               placeholder="Cari produk tenun...">
                        <button type="submit" class="absolute right-0 top-0 h-full px-4 text-white">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
                
                <a href="{{ route('products.index') }}" class="block text-white py-2">Produk</a>
                @auth('pelanggan')
                <a href="{{ route('cart.index') }}" class="block text-white py-2">Keranjang</a>
                <a href="{{ route('orders.index') }}" class="block text-white py-2">Pesanan</a>
                <a href="{{ route('profile.index') }}" class="block text-white py-2">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block text-white py-2">Logout</button>
                </form>
                @else
                <a href="{{ route('login') }}" class="block text-white py-2">Masuk</a>
                <a href="{{ route('register') }}" class="block text-white py-2">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>
    
    <!-- Category Bar -->
    <div class="bg-white border-b shadow-sm py-2 md:py-3">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex gap-3 md:gap-6 overflow-x-auto whitespace-nowrap pb-1">
                <a href="{{ route('products.index') }}" class="text-gray-800 hover:text-primary-600 transition font-medium text-sm md:text-base">
                    <i class="bi bi-grid text-primary-600"></i> Semua Produk
                </a>
                <a href="{{ route('products.index', ['category' => 1]) }}" class="text-gray-800 hover:text-primary-600 transition font-medium text-sm md:text-base">
                    <i class="bi bi-star text-primary-600"></i> Songket Premium
                </a>
                <a href="{{ route('products.index', ['category' => 2]) }}" class="text-gray-800 hover:text-primary-600 transition font-medium text-sm md:text-base">
                    <i class="bi bi-heart text-primary-600"></i> Endek Bali
                </a>
                <a href="{{ route('products.index', ['category' => 3]) }}" class="text-gray-800 hover:text-primary-600 transition font-medium text-sm md:text-base">
                    <i class="bi bi-bag text-primary-600"></i> Selendang
                </a>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
    <div class="max-w-7xl mx-auto px-4 mt-4" x-data="{ show: true }">
        <div x-show="show" class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex justify-between">
            <span><i class="bi bi-check-circle"></i> {{ session('success') }}</span>
            <button @click="show = false" class="text-green-600 hover:text-green-800">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="max-w-7xl mx-auto px-4 mt-4" x-data="{ show: true }">
        <div x-show="show" class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex justify-between">
            <span><i class="bi bi-exclamation-circle"></i> {{ session('error') }}</span>
            <button @click="show = false" class="text-red-600 hover:text-red-800">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    </div>
    @endif

    <!-- Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark-900 text-white mt-auto">
        <!-- Main Footer -->
        <div class="max-w-7xl mx-auto px-4 py-8 md:py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 md:gap-8">
                <!-- Brand Section -->
                <div class="lg:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <i class="bi bi-shop text-primary-600 text-3xl"></i>
                        <h3 class="text-2xl font-bold">Pelangi Traditional Weaving</h3>
                    </div>
                    <p class="text-gray-400 mb-4 leading-relaxed text-sm">
                        UKM tenun tradisional di Desa Sidemen sejak 1979. Menyediakan kain songket berkualitas premium 
                        dengan motif flora dan fauna khas Bali. Benang katun dan sutra dengan pengerjaan teliti dan penuh kesabaran.
                    </p>
                    <div class="flex items-center gap-4 text-sm text-gray-400 mb-6">
                        <div><i class="bi bi-clock-fill text-primary-600 mr-1"></i> Sejak 1979</div>
                        <div><i class="bi bi-star-fill text-yellow-500 mr-1"></i> Rating 4.8</div>
                        <div><i class="bi bi-award-fill text-primary-600 mr-1"></i> 40+ ATBM</div>
                    </div>
                    
                    <!-- Social Media -->
                    <div class="flex items-center gap-3">
                        <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-primary-600 rounded-full flex items-center justify-center transition">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-primary-600 rounded-full flex items-center justify-center transition">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-primary-600 rounded-full flex items-center justify-center transition">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                    </div>
                </div>

                <!-- Menu Cepat -->
                <div>
                    <h4 class="font-bold text-lg mb-4">Menu Cepat</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-primary-600 transition flex items-center gap-2">
                            <i class="bi bi-chevron-right text-xs"></i> Beranda
                        </a></li>
                        <li><a href="{{ route('products.index') }}" class="text-gray-400 hover:text-primary-600 transition flex items-center gap-2">
                            <i class="bi bi-chevron-right text-xs"></i> Produk
                        </a></li>
                        @auth('pelanggan')
                        <li><a href="{{ route('cart.index') }}" class="text-gray-400 hover:text-primary-600 transition flex items-center gap-2">
                            <i class="bi bi-chevron-right text-xs"></i> Keranjang
                        </a></li>
                        <li><a href="{{ route('orders.index') }}" class="text-gray-400 hover:text-primary-600 transition flex items-center gap-2">
                            <i class="bi bi-chevron-right text-xs"></i> Pesanan Saya
                        </a></li>
                        @else
                        <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-primary-600 transition flex items-center gap-2">
                            <i class="bi bi-chevron-right text-xs"></i> Login
                        </a></li>
                        <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-primary-600 transition flex items-center gap-2">
                            <i class="bi bi-chevron-right text-xs"></i> Daftar
                        </a></li>
                        @endauth
                    </ul>
                </div>

                <!-- Kategori Produk -->
                <div>
                    <h4 class="font-bold text-lg mb-4">Kategori Produk</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="{{ route('products.index', ['kategori' => 'kain-tenun']) }}" class="text-gray-400 hover:text-primary-600 transition flex items-center gap-2">
                            <i class="bi bi-chevron-right text-xs"></i> Kain Tenun
                        </a></li>
                        <li><a href="{{ route('products.index', ['kategori' => 'selendang']) }}" class="text-gray-400 hover:text-primary-600 transition flex items-center gap-2">
                            <i class="bi bi-chevron-right text-xs"></i> Selendang
                        </a></li>
                        <li><a href="{{ route('products.index', ['kategori' => 'tas']) }}" class="text-gray-400 hover:text-primary-600 transition flex items-center gap-2">
                            <i class="bi bi-chevron-right text-xs"></i> Tas Tenun
                        </a></li>
                        <li><a href="{{ route('products.index') }}" class="text-gray-400 hover:text-primary-600 transition flex items-center gap-2">
                            <i class="bi bi-chevron-right text-xs"></i> Lihat Semua
                        </a></li>
                    </ul>
                </div>

                <!-- Hubungi Kami -->
                <div>
                    <h4 class="font-bold text-lg mb-4">Hubungi Kami</h4>
                    <ul class="space-y-4 text-sm">
                        <li class="flex items-start gap-3">
                            <i class="bi bi-geo-alt-fill text-primary-600 text-xl mt-1"></i>
                            <div class="text-gray-400">
                                Jl. Sidemen, Kec. Sidemen<br>
                                Kabupaten Karangasem, Bali 80864
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="bi bi-telephone-fill text-primary-600 text-xl"></i>
                            <div class="text-gray-400">
                                +62 361-123456<br>
                                WhatsApp: +62 812-3456-7890
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="bi bi-envelope-fill text-primary-600 text-xl"></i>
                            <div class="text-gray-400">
                                info@pelangiweaving.com
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Payment & Shipping -->
        <div class="border-t border-gray-800">
            <div class="max-w-7xl mx-auto px-4 py-4 md:py-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 text-center">
                    <div>
                        <p class="text-xs text-gray-500 mb-2 md:mb-3">Metode Pembayaran</p>
                        <div class="flex flex-wrap justify-center gap-1.5 md:gap-2">
                            <span class="bg-gray-800 text-white px-2 md:px-3 py-0.5 md:py-1 rounded text-xs">QRIS</span>
                            <span class="bg-gray-800 text-white px-2 md:px-3 py-0.5 md:py-1 rounded text-xs">Virtual Account</span>
                            <span class="bg-gray-800 text-white px-2 md:px-3 py-0.5 md:py-1 rounded text-xs">Credit Card</span>
                            <span class="bg-gray-800 text-white px-2 md:px-3 py-0.5 md:py-1 rounded text-xs">E-Wallet</span>
                        </div>
                        <p class="text-xs text-gray-600 mt-1 md:mt-2">Powered by Midtrans</p>
                    </div>
                    
                    <div>
                        <p class="text-xs text-gray-500 mb-2 md:mb-3">Jasa Pengiriman</p>
                        <div class="flex flex-wrap justify-center gap-1.5 md:gap-2">
                            <span class="bg-gray-800 text-white px-2 md:px-3 py-0.5 md:py-1 rounded text-xs">JNE</span>
                            <span class="bg-gray-800 text-white px-2 md:px-3 py-0.5 md:py-1 rounded text-xs">TIKI</span>
                            <span class="bg-gray-800 text-white px-2 md:px-3 py-0.5 md:py-1 rounded text-xs">POS</span>
                            <span class="bg-gray-800 text-white px-2 md:px-3 py-0.5 md:py-1 rounded text-xs">J&T</span>
                            <span class="bg-gray-800 text-white px-2 md:px-3 py-0.5 md:py-1 rounded text-xs">SiCepat</span>
                        </div>
                        <p class="text-xs text-gray-600 mt-1 md:mt-2">Powered by RajaOngkir</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Footer -->
        <div class="border-t border-gray-800">
            <div class="max-w-7xl mx-auto px-4 py-4 md:py-6">
                <div class="text-center">
                    <p class="text-xs md:text-sm text-gray-400">
                        &copy; {{ date('Y') }} <span class="text-primary-600 font-semibold">Pelangi Traditional Weaving Sidemen</span>. 
                        All rights reserved.
                        <a href="{{ route('admin.login') }}" class="text-gray-600 hover:text-gray-500 transition ml-3 text-xs opacity-50">•</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/6281234567890?text=Halo%20Pelangi%20Weaving,%20saya%20ingin%20bertanya%20tentang%20produk" 
       target="_blank"
       class="fixed bottom-20 md:bottom-20 right-3 md:right-4 z-50 w-12 h-12 md:w-14 md:h-14 bg-green-500 hover:bg-green-600 text-white rounded-full shadow-2xl flex items-center justify-center transition-all transform hover:scale-110 group">
        <i class="bi bi-whatsapp text-xl md:text-2xl"></i>
        <span class="hidden md:block absolute right-16 bg-gray-900 text-white text-xs px-3 py-2 rounded-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
            Chat Customer Service
        </span>
    </a>

    <!-- Back to Top -->
    <div x-data="{ show: false }" @scroll.window="show = window.pageYOffset > 300" x-show="show" x-cloak
         class="fixed bottom-3 md:bottom-4 right-3 md:right-4 z-50">
        <button @click="window.scrollTo({ top: 0, behavior: 'smooth' })" 
                class="w-10 h-10 md:w-12 md:h-12 bg-primary-600 text-white rounded-full shadow-lg hover:bg-primary-700 transition-all transform hover:scale-110">
            <i class="bi bi-arrow-up text-sm md:text-base"></i>
        </button>
    </div>
    
    @stack('scripts')
</body>
</html>
