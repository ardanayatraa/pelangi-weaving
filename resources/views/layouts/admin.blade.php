<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Pelangi Traditional Weaving Sidemen</title>
    
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
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        [x-cloak] { display: none !important; }
        
        /* Custom scrollbar */
        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
        }
        .scrollbar-thin::-webkit-scrollbar-track {
            background: #374151;
            border-radius: 10px;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #6B7280;
            border-radius: 10px;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #9CA3AF;
        }
    </style>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans">
    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false, showLogoutModal: false }">
        <!-- Sidebar -->
        <aside class="fixed left-0 top-0 h-screen w-64 bg-gray-800 flex flex-col z-50 transform transition-transform duration-300 ease-in-out lg:translate-x-0" 
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
            <!-- Sidebar Header -->
            <div class="p-6 border-b border-gray-700">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center group">
                    <div class="relative">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-600 to-primary-700 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg group-hover:shadow-xl transition-all duration-300 transform group-hover:scale-105">
                            PW
                        </div>
                        <!-- Subtle glow effect -->
                        <div class="absolute inset-0 bg-primary-600 rounded-xl blur opacity-20 group-hover:opacity-30 transition-opacity"></div>
                    </div>
                    <div class="ml-3">
                        <div class="font-bold text-white text-lg">Admin Panel</div>
                        <div class="text-xs text-gray-400 -mt-1">Pelangi Weaving</div>
                    </div>
                </a>
            </div>
            
            <!-- Sidebar Navigation -->
            <nav class="flex-1 p-4 overflow-y-auto scrollbar-thin">
                <!-- MAIN Section -->
                <div class="mb-6">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 px-3">MAIN</h3>
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3 px-4 py-3 mb-1 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-primary-600 text-white shadow-lg' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <i class="bi bi-grid-fill text-lg"></i>
                        Dashboard
                    </a>
                </div>
                
                <!-- CATALOG Section -->
                <div class="mb-6">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 px-3">CATALOG</h3>
                    <a href="{{ route('admin.categories.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 mb-1 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.categories.*') ? 'bg-primary-600 text-white shadow-lg' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <i class="bi bi-tags-fill text-lg"></i>
                        Categories
                    </a>
                    
                    <a href="{{ route('admin.jenis.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 mb-1 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.jenis.*') ? 'bg-primary-600 text-white shadow-lg' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <i class="bi bi-scissors text-lg"></i>
                        Jenis
                    </a>
                    
                    <a href="{{ route('admin.products.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 mb-1 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.products.*') ? 'bg-primary-600 text-white shadow-lg' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <i class="bi bi-box-seam-fill text-lg"></i>
                        Products
                    </a>
                </div>
                
                <!-- ORDERS Section -->
                <div class="mb-6">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 px-3">ORDERS</h3>
                    <a href="{{ route('admin.orders.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 mb-1 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.orders.*') ? 'bg-primary-600 text-white shadow-lg' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <i class="bi bi-cart-check-fill text-lg"></i>
                        Orders
                    </a>
                    
                    <a href="{{ route('admin.custom-orders.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 mb-1 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.custom-orders.*') ? 'bg-primary-600 text-white shadow-lg' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <i class="bi bi-palette-fill text-lg"></i>
                        Custom Orders
                    </a>
                    
                    <a href="{{ route('admin.pelanggan.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 mb-1 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.pelanggan.*') ? 'bg-primary-600 text-white shadow-lg' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <i class="bi bi-people-fill text-lg"></i>
                        Pelanggan
                    </a>
                </div>
                
                <!-- REPORTS Section -->
                <div class="mb-6">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 px-3">REPORTS</h3>
                    <a href="{{ route('admin.reports.sales') }}" 
                       class="flex items-center gap-3 px-4 py-3 mb-1 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.reports.*') ? 'bg-primary-600 text-white shadow-lg' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <i class="bi bi-graph-up text-lg"></i>
                        Reports
                    </a>
                </div>
            </nav>
            
            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-gray-700">
                <a href="{{ route('home') }}" target="_blank"
                   class="flex items-center gap-3 px-4 py-2.5 mb-3 rounded-xl text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition-all duration-200">
                    <i class="bi bi-globe text-lg"></i>
                    Lihat Website
                </a>
                
                <button @click="showLogoutModal = true" 
                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-all duration-200 font-medium text-sm shadow-lg">
                    <i class="bi bi-box-arrow-right"></i>
                    Logout
                </button>
            </div>
        </aside>
        
        <!-- Logout Confirmation Modal - MOVED OUTSIDE SIDEBAR -->
        <div x-show="showLogoutModal" x-cloak @click.self="showLogoutModal = false" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="backdrop-filter: blur(8px); background-color: rgba(0, 0, 0, 0.6);"
             x-transition:enter="transition-opacity ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div @click.away="showLogoutModal = false" 
                     x-show="showLogoutModal" 
                     x-transition:enter="transition-all ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition-all ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden">
                    
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-red-50 to-red-100 px-6 py-4 border-b border-red-200">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                <i class="bi bi-box-arrow-right text-red-600 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-red-900">Konfirmasi Logout</h3>
                                <p class="text-sm text-red-700">Anda akan keluar dari admin panel</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Modal Body -->
                    <div class="px-6 py-5">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="bi bi-exclamation-triangle text-yellow-600 text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">Yakin ingin logout?</h4>
                                <p class="text-gray-600 text-sm leading-relaxed">
                                    Anda akan keluar dari admin panel dan perlu login kembali untuk mengakses halaman admin.
                                </p>
                                
                                <!-- Admin Info -->
                                <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-gradient-to-br from-primary-100 to-primary-200 rounded-full flex items-center justify-center">
                                            <i class="bi bi-person-fill text-primary-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">{{ Auth::guard('admin')->user()->nama }}</p>
                                            <p class="text-xs text-gray-500">{{ Auth::guard('admin')->user()->email }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                        <button @click="showLogoutModal = false" 
                                class="px-5 py-2.5 bg-white hover:bg-gray-100 text-gray-700 font-semibold rounded-lg border border-gray-300 transition-colors">
                            <i class="bi bi-x-circle mr-2"></i>Batal
                        </button>
                        <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors shadow-lg">
                                <i class="bi bi-box-arrow-right mr-2"></i>Ya, Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" 
             class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"></div>
        
        <!-- Main Content -->
        <main class="flex-1 ml-0 lg:ml-64 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white h-16 flex items-center px-4 lg:px-8 border-b border-gray-200 sticky top-0 z-30">
                <!-- Mobile Menu Button -->
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100 mr-4">
                    <i class="bi bi-list text-xl"></i>
                </button>
                
                <!-- Page Title -->
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                    @hasSection('breadcrumb')
                        <nav class="ml-4 flex items-center text-sm text-gray-600">
                            <i class="bi bi-chevron-right mx-2"></i>
                            @yield('breadcrumb')
                        </nav>
                    @endif
                </div>
                
                <!-- Top Bar Actions -->
                <div class="ml-auto flex items-center gap-4">
                    <!-- User Menu -->
                    <div class="flex items-center gap-3 text-gray-700">
                        <div class="w-8 h-8 bg-gradient-to-br from-primary-100 to-primary-200 rounded-full flex items-center justify-center">
                            <i class="bi bi-person-fill text-primary-600"></i>
                        </div>
                        <div class="hidden sm:block">
                            <div class="text-sm font-semibold">Admin</div>
                            <div class="text-xs text-gray-500">{{ Auth::guard('admin')->user()->nama }}</div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Content Area -->
            <div class="flex-1 p-4 lg:p-8 overflow-y-auto bg-gray-50">
                <!-- Success Alert -->
                @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 p-4 rounded-xl mb-6 flex items-start gap-3 shadow-sm">
                    <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="bi bi-check text-green-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold mb-1">Berhasil!</div>
                        <div class="text-sm">{{ session('success') }}</div>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800 p-1">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>
                @endif
                
                <!-- Error Alert -->
                @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-xl mb-6 flex items-start gap-3 shadow-sm">
                    <div class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="bi bi-exclamation text-red-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold mb-1">Error!</div>
                        <div class="text-sm">{{ session('error') }}</div>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800 p-1">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>
                @endif
                
                <!-- Validation Errors -->
                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-xl mb-6 shadow-sm">
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="bi bi-exclamation text-red-600 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold mb-2">Terdapat kesalahan pada form:</div>
                            <ul class="text-sm space-y-1">
                                @foreach($errors->all() as $error)
                                <li class="flex items-start gap-2">
                                    <i class="bi bi-dot text-red-600 mt-1"></i>
                                    {{ $error }}
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-red-600 hover:text-red-800 p-1">
                            <i class="bi bi-x-lg text-sm"></i>
                        </button>
                    </div>
                </div>
                @endif
                
                @yield('content')
            </div>
        </main>
    </div>
    
    @stack('scripts')
</body>
</html>