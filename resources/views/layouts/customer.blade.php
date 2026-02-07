<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pelangi Traditional Weaving Sidemen')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-14">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center group">
                    <div class="relative">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-600 to-primary-700 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg group-hover:shadow-xl transition-all duration-300 transform group-hover:scale-105">
                            PW
                        </div>
                        <!-- Subtle glow effect -->
                        <div class="absolute inset-0 bg-primary-600 rounded-xl blur opacity-20 group-hover:opacity-30 transition-opacity"></div>
                    </div>
                    <div class="ml-3 hidden sm:block">
                        <div class="font-bold text-gray-900 text-lg">Pelangi</div>
                        <div class="text-xs text-gray-600 -mt-1">Traditional Weaving</div>
                    </div>
                </a>
                
                <!-- Navigation Menu (Center) -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('home') }}" class="relative px-3 py-2 text-gray-700 hover:text-primary-600 font-medium transition-all duration-200 rounded-lg hover:bg-primary-50 group text-sm">
                        Home
                        <span class="absolute bottom-0 left-1/2 w-0 h-0.5 bg-primary-600 transition-all duration-200 group-hover:w-6 transform -translate-x-1/2"></span>
                    </a>
                    <a href="{{ route('products.index') }}" class="relative px-3 py-2 text-gray-700 hover:text-primary-600 font-medium transition-all duration-200 rounded-lg hover:bg-primary-50 group text-sm">
                        Produk
                        <span class="absolute bottom-0 left-1/2 w-0 h-0.5 bg-primary-600 transition-all duration-200 group-hover:w-6 transform -translate-x-1/2"></span>
                    </a>
                    <a href="{{ route('custom-orders.index') }}" class="relative px-3 py-2 text-gray-700 hover:text-primary-600 font-medium transition-all duration-200 rounded-lg hover:bg-primary-50 group text-sm">
                        Custom Order
                        <span class="absolute bottom-0 left-1/2 w-0 h-0.5 bg-primary-600 transition-all duration-200 group-hover:w-6 transform -translate-x-1/2"></span>
                    </a>
                </div>
                
                <!-- Right Side -->
                <div class="hidden md:flex items-center space-x-2">
                    <!-- Search Bar -->
                    <form action="{{ route('products.index') }}" method="GET" class="relative group">
                        <div class="relative">
                            <input type="text" name="search" 
                                   class="w-64 pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-100 transition-all duration-200 bg-gray-50 focus:bg-white group-hover:bg-white"
                                   placeholder="Cari produk tenun...">
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-primary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <button type="submit" class="absolute right-1.5 top-1/2 transform -translate-y-1/2 bg-primary-600 text-white p-1.5 rounded-lg hover:bg-primary-700 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </form>
                    
                    @auth('pelanggan')
                    <!-- Cart Icon -->
                    <a href="{{ route('cart.index') }}" class="relative group">
                        <div class="relative p-2.5 hover:bg-primary-50 rounded-xl transition-all duration-200 group-hover:shadow-md">
                            <i class="bi bi-bag text-lg text-gray-700 group-hover:text-primary-600 transition-colors"></i>
                            @php
                                $cartCount = Auth::guard('pelanggan')->user()->carts()->sum('jumlah');
                            @endphp
                            @if($cartCount > 0)
                            <span class="absolute -top-1 -right-1 bg-primary-600 text-white text-xs min-w-[18px] h-4.5 rounded-full flex items-center justify-center font-semibold shadow-lg animate-pulse">
                                {{ $cartCount > 99 ? '99+' : $cartCount }}
                            </span>
                            @endif
                        </div>
                    </a>
                    
                    <!-- User Menu -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 p-2 hover:bg-primary-50 rounded-xl transition-all duration-200 group">
                            <div class="w-8 h-8 bg-gradient-to-br from-primary-100 to-primary-200 rounded-full flex items-center justify-center group-hover:from-primary-200 group-hover:to-primary-300 transition-all">
                                <i class="bi bi-person-fill text-sm text-primary-600"></i>
                            </div>
                            <div class="hidden xl:block text-left">
                                <div class="text-sm font-semibold text-gray-900">{{ Auth::guard('pelanggan')->user()->nama }}</div>
                                <div class="text-xs text-gray-600">Pelanggan</div>
                            </div>
                            <i class="bi bi-chevron-down text-xs text-gray-500 group-hover:text-primary-600 transition-colors"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-900">{{ Auth::guard('pelanggan')->user()->nama }}</p>
                                <p class="text-xs text-gray-600">{{ Auth::guard('pelanggan')->user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                                <i class="bi bi-person mr-3 text-lg"></i>
                                Profil Saya
                            </a>
                            <a href="{{ route('orders.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                                <i class="bi bi-bag-check mr-3 text-lg"></i>
                                Pesanan Saya
                            </a>
                            <a href="{{ route('cart.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                                <i class="bi bi-bag mr-3 text-lg"></i>
                                Keranjang
                            </a>
                            <hr class="my-2 border-gray-100">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <i class="bi bi-box-arrow-right mr-3 text-lg"></i>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary-600 font-medium px-3 py-2 transition">Masuk</a>
                    <a href="{{ route('register') }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-primary-700 transition">
                        Daftar
                    </a>
                    @endauth
                </div>
                
                <!-- Mobile menu button -->
                <button @click="open = !open" class="md:hidden p-2 hover:bg-gray-100 rounded-lg transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Mobile Menu -->
            <div x-show="open" x-cloak class="md:hidden border-t border-gray-200 py-4">
                <!-- Mobile Search -->
                <form action="{{ route('products.index') }}" method="GET" class="px-4 mb-4">
                    <input type="text" name="search" 
                           class="w-full pl-4 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-primary-600"
                           placeholder="Cari produk tenun...">
                </form>
                
                <div class="space-y-1">
                    <a href="{{ route('home') }}" class="block px-4 py-2 text-gray-700 font-medium hover:bg-gray-100">Home</a>
                    <a href="{{ route('products.index') }}" class="block px-4 py-2 text-gray-700 font-medium hover:bg-gray-100">Produk</a>
                    <a href="{{ route('custom-orders.index') }}" class="block px-4 py-2 text-gray-700 font-medium hover:bg-gray-100">Custom Order</a>
                    
                    @auth('pelanggan')
                    <div class="border-t border-gray-200 pt-2 mt-2">
                        <a href="{{ route('cart.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Keranjang</a>
                        <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Pesanan</a>
                        <a href="{{ route('profile.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Logout</button>
                        </form>
                    </div>
                    @else
                    <div class="border-t border-gray-200 pt-2 mt-2">
                        <a href="{{ route('login') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Masuk</a>
                        <a href="{{ route('register') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Daftar</a>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>


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
                        <li><a href="{{ route('custom-orders.index') }}" class="text-gray-400 hover:text-primary-600 transition flex items-center gap-2">
                            <i class="bi bi-chevron-right text-xs"></i> Custom Order
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
                        <li><a href="{{ route('products.index', ['category' => 1]) }}" class="text-gray-400 hover:text-primary-600 transition flex items-center gap-2">
                            <i class="bi bi-chevron-right text-xs"></i> Selendang Songket
                        </a></li>
                        <li><a href="{{ route('products.index', ['category' => 2]) }}" class="text-gray-400 hover:text-primary-600 transition flex items-center gap-2">
                            <i class="bi bi-chevron-right text-xs"></i> Kain Songket Premium
                        </a></li>
                        <li><a href="{{ route('products.index', ['category' => 3]) }}" class="text-gray-400 hover:text-primary-600 transition flex items-center gap-2">
                            <i class="bi bi-chevron-right text-xs"></i> Kain Endek Katun
                        </a></li>
                        <li><a href="{{ route('products.index', ['category' => 4]) }}" class="text-gray-400 hover:text-primary-600 transition flex items-center gap-2">
                            <i class="bi bi-chevron-right text-xs"></i> Kain Endek Sutra
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
