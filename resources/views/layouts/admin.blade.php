<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Pelangi Traditional Weaving Sidemen</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f5f5f5;
            overflow-x: hidden;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 260px;
            background: #1a1a1a;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar-header {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-brand {
            color: white;
            font-size: 20px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        
        .sidebar-brand i {
            color: #DC143C;
            font-size: 24px;
        }
        
        .sidebar-subtitle {
            color: rgba(255,255,255,0.5);
            font-size: 12px;
            margin-top: 4px;
            font-weight: 400;
        }
        
        .sidebar-nav {
            flex: 1;
            padding: 20px 12px;
            overflow-y: auto;
        }
        
        .nav-item {
            margin-bottom: 4px;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-size: 14px;
            font-weight: 500;
        }
        
        .nav-link:hover {
            background: rgba(255,255,255,0.05);
            color: white;
        }
        
        .nav-link.active {
            background: #DC143C;
            color: white;
        }
        
        .nav-link i {
            width: 20px;
            margin-right: 12px;
            font-size: 18px;
        }
        
        .nav-divider {
            height: 1px;
            background: rgba(255,255,255,0.1);
            margin: 16px 0;
        }
        
        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        
        /* Main Content */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Top Bar */
        .topbar {
            background: white;
            height: 64px;
            display: flex;
            align-items: center;
            padding: 0 32px;
            border-bottom: 1px solid #e5e5e5;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        .topbar-title {
            font-size: 20px;
            font-weight: 600;
            color: #1a1a1a;
        }
        
        .topbar-user {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #666;
            font-size: 14px;
        }
        
        .topbar-user i {
            font-size: 20px;
            color: #DC143C;
        }
        
        /* Content Area */
        .content-area {
            flex: 1;
            padding: 32px;
        }
        
        /* Cards */
        .card {
            background: white;
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid #e5e5e5;
            background: transparent;
        }
        
        .card-body {
            padding: 24px;
        }
        
        /* Buttons */
        .btn-primary {
            background: #DC143C !important;
            border: none !important;
            color: white !important;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .btn-primary:hover {
            background: #B01030 !important;
            transform: translateY(-1px);
        }
        
        .btn-secondary {
            background: #1a1a1a !important;
            border: none !important;
            color: white !important;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
        }
        
        .btn-secondary:hover {
            background: #2a2a2a !important;
        }
        
        .btn-outline-light {
            background: transparent;
            border: 1px solid rgba(255,255,255,0.3) !important;
            color: rgba(255,255,255,0.9) !important;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .btn-outline-light:hover {
            background: rgba(255,255,255,0.1) !important;
            border-color: white !important;
            color: white !important;
        }
        
        /* Alerts */
        .alert {
            border: none;
            border-radius: 8px;
            padding: 16px 20px;
        }
        
        .alert-success {
            background: #f0fdf4;
            color: #166534;
            border-left: 4px solid #22c55e;
        }
        
        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }
        
        /* Table */
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background: #f9fafb;
            border-bottom: 2px solid #e5e5e5;
            color: #666;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 16px;
        }
        
        .table tbody td {
            padding: 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .table tbody tr:hover {
            background: #fafafa;
        }
        
        /* Badges */
        .badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 12px;
        }
        
        /* Scrollbar */
        .sidebar-nav::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar-nav::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 3px;
        }
        
        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.3);
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
                <i class="bi bi-shop-window"></i>
                <div>
                    <div>Admin Panel</div>
                    <div class="sidebar-subtitle">Pelangi Traditional Weaving</div>
                </div>
            </a>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-fill"></i>
                    Dashboard
                </a>
            </div>
            
            <div class="nav-item">
                <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="bi bi-tags-fill"></i>
                    Kategori
                </a>
            </div>
            
            <div class="nav-item">
                <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam-fill"></i>
                    Produk
                </a>
            </div>
            
            <div class="nav-item">
                <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <i class="bi bi-cart-check-fill"></i>
                    Pesanan
                </a>
            </div>
            
            <div class="nav-divider"></div>
            
            <div class="nav-item">
                <a href="{{ route('home') }}" class="nav-link" target="_blank">
                    <i class="bi bi-globe"></i>
                    Lihat Website
                </a>
            </div>
        </nav>
        
        <div class="sidebar-footer">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-light w-100">
                    <i class="bi bi-box-arrow-right me-2"></i>
                    Logout
                </button>
            </form>
        </div>
    </aside>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Bar -->
        <div class="topbar">
            <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>
            
            <div class="topbar-user">
                <i class="bi bi-person-circle"></i>
                <span>{{ Auth::guard('admin')->user()->nama }}</span>
            </div>
        </div>
        
        <!-- Content Area -->
        <div class="content-area">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            
            @yield('content')
        </div>
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
