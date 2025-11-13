<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - Pelangi Weaving</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-red: #DC2626;
            --dark-red: #B91C1C;
            --black: #1F2937;
            --dark-black: #111827;
            --white: #FFFFFF;
            --off-white: #F9FAFB;
        }
        
        body {
            background-color: var(--off-white) !important;
        }
        
        .navbar-admin {
            background: linear-gradient(135deg, var(--dark-black) 0%, var(--black) 100%) !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        
        .navbar-admin .navbar-brand {
            color: white !important;
            font-weight: 700;
        }
        
        .navbar-admin .nav-link {
            color: rgba(255,255,255,0.85) !important;
            transition: all 0.3s;
        }
        
        .navbar-admin .nav-link:hover {
            color: var(--primary-red) !important;
            background: rgba(220, 38, 38, 0.1);
            border-radius: 6px;
        }
        
        .btn-primary {
            background: var(--primary-red) !important;
            border-color: var(--primary-red) !important;
        }
        
        .btn-primary:hover {
            background: var(--dark-red) !important;
            border-color: var(--dark-red) !important;
        }
        
        .btn-danger {
            background: var(--dark-black) !important;
            border-color: var(--dark-black) !important;
        }
        
        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-radius: 12px;
        }
        
        .table thead {
            background: var(--dark-black);
            color: white;
        }
        
        .badge-primary {
            background: var(--primary-red) !important;
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
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-admin">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-shield-check"></i> Admin Panel
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.categories.index') }}">
                            <i class="bi bi-tags"></i> Kategori
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.products.index') }}">
                            <i class="bi bi-box-seam"></i> Produk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.orders.index') }}">
                            <i class="bi bi-cart3"></i> Pesanan
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ Auth::guard('admin')->user()->nama }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="py-4">
        @if(session('success'))
        <div class="container-fluid">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="container-fluid">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
