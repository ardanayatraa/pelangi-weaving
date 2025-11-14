<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Pelangi Traditional Weaving Sidemen</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Alpine Modal Styles */
        [x-cloak] {
            display: none !important;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="fixed left-0 top-0 h-screen w-64 bg-gray-900 flex flex-col z-50">
            <!-- Sidebar Header -->
            <div class="p-6 border-b border-gray-800">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 text-white hover:text-gray-200 transition">
                    <i class="bi bi-shop-window text-primary-600 text-2xl"></i>
                    <div>
                        <div class="font-bold text-lg">Admin Panel</div>
                        <div class="text-xs text-gray-400">Pelangi Traditional Weaving</div>
                    </div>
                </a>
            </div>
            
            <!-- Sidebar Navigation -->
            <nav class="flex-1 p-3 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-3 mb-1 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <i class="bi bi-grid-fill text-lg"></i>
                    Dashboard
                </a>
                
                <a href="{{ route('admin.categories.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 mb-1 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.categories.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <i class="bi bi-tags-fill text-lg"></i>
                    Kategori
                </a>
                
                <a href="{{ route('admin.products.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 mb-1 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.products.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <i class="bi bi-box-seam-fill text-lg"></i>
                    Produk
                </a>
                
                <a href="{{ route('admin.orders.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 mb-1 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.orders.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <i class="bi bi-cart-check-fill text-lg"></i>
                    Pesanan
                </a>
                
                <div class="h-px bg-gray-800 my-4"></div>
                
                <a href="{{ route('home') }}" target="_blank"
                   class="flex items-center gap-3 px-4 py-3 mb-1 rounded-lg text-sm font-medium text-gray-300 hover:bg-gray-800 hover:text-white transition">
                    <i class="bi bi-globe text-lg"></i>
                    Lihat Website
                </a>
            </nav>
            
            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-gray-800">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 border border-gray-700 text-gray-300 rounded-lg hover:bg-gray-800 hover:text-white hover:border-gray-600 transition font-medium text-sm">
                        <i class="bi bi-box-arrow-right"></i>
                        Logout
                    </button>
                </form>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="flex-1 ml-64 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <div class="bg-white h-16 flex items-center px-8 border-b border-gray-200 sticky top-0 z-40">
                <h1 class="text-xl font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                
                <div class="ml-auto flex items-center gap-3 text-gray-600 text-sm">
                    <i class="bi bi-person-circle text-primary-600 text-xl"></i>
                    <span class="font-medium">{{ Auth::guard('admin')->user()->nama }}</span>
                </div>
            </div>
            
            <!-- Content Area -->
            <div class="flex-1 p-8 overflow-y-auto">
                @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded-lg mb-6 flex items-start gap-3">
                    <i class="bi bi-check-circle-fill text-lg"></i>
                    <div class="flex-1">{{ session('success') }}</div>
                    <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                @endif
                
                @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-lg mb-6 flex items-start gap-3">
                    <i class="bi bi-exclamation-circle-fill text-lg"></i>
                    <div class="flex-1">{{ session('error') }}</div>
                    <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                @endif
                
                @yield('content')
            </div>
        </main>
    </div>
    
    <!-- Alpine.js for Modal -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @stack('scripts')
</body>
</html>
