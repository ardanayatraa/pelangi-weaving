@extends('layouts.customer')

@section('title', 'Pesanan Saya - Pelangi Weaving')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Pesanan Saya</h1>
                <p class="text-gray-600">Kelola dan lacak semua pesanan Anda</p>
            </div>
            <div class="flex items-center space-x-3 mt-4 md:mt-0">
                <button class="px-4 py-2 border border-primary-300 text-primary-600 rounded-lg hover:bg-primary-50 transition-colors">
                    <i class="bi bi-download mr-2"></i>
                    Download Invoice
                </button>
                <button class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    <i class="bi bi-plus mr-2"></i>
                    Belanja Lagi
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            @php
            $stats = [
                'total' => $orders->total(),
                'baru' => \App\Models\Pesanan::where('id_pelanggan', Auth::guard('pelanggan')->id())->where('status_pesanan', 'baru')->count(),
                'diproses' => \App\Models\Pesanan::where('id_pelanggan', Auth::guard('pelanggan')->id())->where('status_pesanan', 'diproses')->count(),
                'selesai' => \App\Models\Pesanan::where('id_pelanggan', Auth::guard('pelanggan')->id())->where('status_pesanan', 'selesai')->count(),
                'total_spent' => \App\Models\Pesanan::where('id_pelanggan', Auth::guard('pelanggan')->id())->where('status_pesanan', 'selesai')->sum('total_bayar')
            ];
            @endphp

            <!-- Total Pesanan -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mr-4">
                        <i class="bi bi-receipt text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                        <p class="text-sm text-gray-600">Total Pesanan</p>
                    </div>
                </div>
            </div>

            <!-- Pesanan Selesai -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                        <i class="bi bi-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['selesai'] }}</p>
                        <p class="text-sm text-gray-600">Pesanan Selesai</p>
                    </div>
                </div>
            </div>

            <!-- Dalam Proses -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center mr-4">
                        <i class="bi bi-clock text-orange-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['diproses'] }}</p>
                        <p class="text-sm text-gray-600">Dalam Proses</p>
                    </div>
                </div>
            </div>

            <!-- Total Belanja -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                        <i class="bi bi-currency-dollar text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-primary-600">Rp {{ number_format($stats['total_spent']/1000000, 1) }}jt</p>
                        <p class="text-sm text-gray-600">Total Belanja</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter and Search -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                <!-- Status Filter Tabs -->
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('orders.index') }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ !request('status') ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        <i class="bi bi-list-ul mr-1"></i>
                        Semua Pesanan
                    </a>
                    <a href="{{ route('orders.index', ['status' => 'baru']) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('status') == 'baru' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        Menunggu Bayar
                    </a>
                    <a href="{{ route('orders.index', ['status' => 'diproses']) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('status') == 'diproses' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        Diproses
                    </a>
                    <a href="{{ route('orders.index', ['status' => 'dikirim']) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('status') == 'dikirim' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        Dikirim
                    </a>
                    <a href="{{ route('orders.index', ['status' => 'selesai']) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('status') == 'selesai' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        Selesai
                    </a>
                </div>

                <!-- Search and Filter -->
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <input type="text" 
                               placeholder="Cari berdasarkan nomor invoice, nama produk..."
                               class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option>Semua Waktu</option>
                        <option>7 Hari Terakhir</option>
                        <option>30 Hari Terakhir</option>
                        <option>3 Bulan Terakhir</option>
                    </select>
                    <button class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        <i class="bi bi-funnel"></i>
                        Cari
                    </button>
                </div>
            </div>
        </div>

        @if($orders->isEmpty())
        <!-- Empty State -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="max-w-sm mx-auto">
                <i class="bi bi-inbox text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Pesanan</h3>
                <p class="text-gray-600 mb-6">
                    @if(request('status'))
                        Tidak ada pesanan dengan status "{{ ucfirst(request('status')) }}"
                    @else
                        Anda belum memiliki pesanan. Yuk mulai belanja produk tenun terbaik!
                    @endif
                </p>
                <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl transition-colors">
                    <i class="bi bi-shop mr-2"></i>
                    Mulai Belanja
                </a>
            </div>
        </div>
        @else
        <!-- Orders List -->
        <div class="space-y-6">
            @foreach($orders as $order)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                <!-- Order Header -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex items-center space-x-6">
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Invoice</p>
                                <p class="font-bold text-gray-900 font-mono">{{ $order->nomor_invoice }}</p>
                                @if($order->status_pesanan == 'baru')
                                <span class="inline-block bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1 rounded-full mt-1">
                                    Menunggu Pembayaran
                                </span>
                                @endif
                            </div>
                            <div class="hidden md:block w-px h-12 bg-gray-300"></div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Tanggal</p>
                                <p class="font-semibold text-gray-900">{{ $order->tanggal_pesanan->format('d M Y') }}</p>
                                <p class="text-sm text-gray-600">{{ $order->tanggal_pesanan->format('H:i') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between md:justify-end space-x-4">
                            @php
                            $statusConfig = [
                                'baru' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'clock-history', 'label' => 'Baru'],
                                'diproses' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'gear', 'label' => 'Diproses'],
                                'dikirim' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'icon' => 'truck', 'label' => 'Dikirim'],
                                'selesai' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'check-circle', 'label' => 'Selesai'],
                                'batal' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'x-circle', 'label' => 'Dibatalkan'],
                            ];
                            $status = $statusConfig[$order->status_pesanan] ?? $statusConfig['baru'];
                            @endphp
                            
                            <span class="inline-flex items-center px-3 py-2 rounded-xl text-sm font-semibold {{ $status['bg'] }} {{ $status['text'] }}">
                                <i class="bi bi-{{ $status['icon'] }} mr-2"></i>
                                {{ $status['label'] }}
                            </span>
                            
                            <div class="text-right">
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Total</p>
                                <p class="text-xl font-bold text-primary-600">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="px-6 py-4">
                    <div class="space-y-4">
                        @foreach($order->items->take(2) as $item)
                        @php
                            $produk = $item->produk;
                            $varian = $item->varian;
                            
                            // Get image URL
                            $imageUrl = null;
                            if ($varian && $varian->gambar_varian) {
                                $imageUrl = $varian->gambar_varian;
                            } elseif ($produk && $produk->images->first()) {
                                $imageUrl = $produk->images->first()->path;
                            }
                        @endphp
                        <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-xl">
                            @if($imageUrl)
                                <img src="{{ asset('storage/' . $imageUrl) }}" 
                                     alt="{{ $produk->nama_produk }}"
                                     class="w-16 h-16 object-cover rounded-lg border border-gray-200"
                                     onerror="this.src='{{ asset('images/placeholder.png') }}'">
                            @else
                                <div class="w-16 h-16 bg-gray-200 rounded-lg border border-gray-200 flex items-center justify-center">
                                    <i class="bi bi-image text-gray-400 text-xl"></i>
                                </div>
                            @endif
                            
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-gray-900 truncate">{{ $produk->nama_produk }}</h4>
                                @if($varian)
                                <p class="text-sm text-gray-600 mt-1">Varian: {{ $varian->nama_varian }}</p>
                                @endif
                                <div class="flex items-center justify-between mt-2">
                                    <p class="text-sm text-gray-600">Qty: {{ $item->jumlah }} × Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</p>
                                    <p class="font-bold text-primary-600">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                        @if($order->items->count() > 2)
                        <div class="text-center py-2">
                            <p class="text-sm text-gray-600">
                                +{{ $order->items->count() - 2 }} produk lainnya
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Order Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                            <div class="flex items-center">
                                <i class="bi bi-box-seam mr-2"></i>
                                <span>{{ $order->items->count() }} produk</span>
                            </div>
                            @if($order->pengiriman)
                            <div class="flex items-center">
                                <i class="bi bi-truck mr-2"></i>
                                <span>{{ $order->pengiriman->kurir }} {{ $order->pengiriman->layanan }}</span>
                            </div>
                            @endif
                            @if($order->payment && $order->payment->status_bayar == 'paid')
                            <div class="flex items-center text-green-600">
                                <i class="bi bi-check-circle mr-2"></i>
                                <span>Sudah Bayar</span>
                            </div>
                            @endif
                        </div>
                        
                        <div class="flex items-center space-x-3">
                            @if($order->status_pesanan == 'baru' && $order->payment && $order->payment->status_bayar != 'paid')
                            <a href="{{ route('payment.show', $order->nomor_invoice) }}" 
                               class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-sm font-medium transition-colors">
                                <i class="bi bi-credit-card mr-1"></i>
                                Bayar Sekarang
                            </a>
                            @endif
                            
                            @if(in_array($order->status_pesanan, ['baru', 'diproses']))
                            <form action="{{ route('orders.cancel', $order->nomor_invoice) }}" method="POST" 
                                  onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 border border-red-300 text-red-600 hover:bg-red-50 rounded-lg text-sm font-medium transition-colors">
                                    <i class="bi bi-x-circle mr-1"></i>
                                    Batalkan
                                </button>
                            </form>
                            @endif
                            
                            @if($order->status_pesanan == 'dikirim')
                            <form action="{{ route('orders.complete', $order->nomor_invoice) }}" method="POST" 
                                  onsubmit="return confirm('Konfirmasi bahwa pesanan sudah diterima?')" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg text-sm font-medium transition-colors">
                                    <i class="bi bi-check-circle mr-1"></i>
                                    Pesanan Diterima
                                </button>
                            </form>
                            @endif
                            
                            <a href="{{ route('orders.show', $order->nomor_invoice) }}" 
                               class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-sm font-medium transition-colors">
                                <i class="bi bi-eye mr-1"></i>
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="mt-8 flex justify-center">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-2">
                {{ $orders->links() }}
            </div>
        </div>
        @endif
        @endif
    </div>
</div>
@endsection
