    @extends('layouts.customer')

@section('title', 'Pesanan Saya - Pelangi Weaving')

@section('content')
<!-- Breadcrumb -->
<div class="bg-white py-4 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center text-sm text-gray-600">
            <a href="{{ route('home') }}" class="hover:text-orange-500">Beranda</a>
            <i class="bi bi-chevron-right mx-2 text-xs"></i>
            <span class="text-gray-900 font-medium">Pesanan Saya</span>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-8">
    <!-- Header -->
    <div class="mb-4 md:mb-6">
        <h1 class="text-xl md:text-2xl font-bold text-gray-900">Pesanan Saya</h1>
        <p class="text-sm md:text-base text-gray-600 mt-1">Kelola dan lacak pesanan Anda</p>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-lg border border-gray-200 mb-4 md:mb-6">
        <div class="flex overflow-x-auto scrollbar-thin scrollbar-thumb-gray-300">
            <a href="{{ route('orders.index') }}" 
               class="px-6 py-4 text-sm font-medium border-b-2 whitespace-nowrap {{ !request('status') ? 'text-orange-500 border-orange-500' : 'text-gray-600 border-transparent hover:text-orange-500' }}">
                <i class="bi bi-list-ul mr-2"></i>Semua
            </a>
            <a href="{{ route('orders.index', ['status' => 'baru']) }}" 
               class="px-6 py-4 text-sm font-medium border-b-2 whitespace-nowrap {{ request('status') == 'baru' ? 'text-orange-500 border-orange-500' : 'text-gray-600 border-transparent hover:text-orange-500' }}">
                <i class="bi bi-clock-history mr-2"></i>Baru
            </a>
            <a href="{{ route('orders.index', ['status' => 'diproses']) }}" 
               class="px-6 py-4 text-sm font-medium border-b-2 whitespace-nowrap {{ request('status') == 'diproses' ? 'text-orange-500 border-orange-500' : 'text-gray-600 border-transparent hover:text-orange-500' }}">
                <i class="bi bi-gear mr-2"></i>Diproses
            </a>
            <a href="{{ route('orders.index', ['status' => 'dikirim']) }}" 
               class="px-6 py-4 text-sm font-medium border-b-2 whitespace-nowrap {{ request('status') == 'dikirim' ? 'text-orange-500 border-orange-500' : 'text-gray-600 border-transparent hover:text-orange-500' }}">
                <i class="bi bi-truck mr-2"></i>Dikirim
            </a>
            <a href="{{ route('orders.index', ['status' => 'selesai']) }}" 
               class="px-6 py-4 text-sm font-medium border-b-2 whitespace-nowrap {{ request('status') == 'selesai' ? 'text-orange-500 border-orange-500' : 'text-gray-600 border-transparent hover:text-orange-500' }}">
                <i class="bi bi-check-circle mr-2"></i>Selesai
            </a>
            <a href="{{ route('orders.index', ['status' => 'batal']) }}" 
               class="px-6 py-4 text-sm font-medium border-b-2 whitespace-nowrap {{ request('status') == 'batal' ? 'text-orange-500 border-orange-500' : 'text-gray-600 border-transparent hover:text-orange-500' }}">
                <i class="bi bi-x-circle mr-2"></i>Dibatalkan
            </a>
        </div>
    </div>

    @if($orders->isEmpty())
    <!-- Empty State -->
    <div class="bg-white rounded-lg border border-gray-200 p-12 text-center">
        <div class="max-w-sm mx-auto">
            <i class="bi bi-inbox text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Pesanan</h3>
            <p class="text-gray-600 mb-6">
                @if(request('status'))
                    Tidak ada pesanan dengan status "{{ ucfirst(request('status')) }}"
                @else
                    Anda belum memiliki pesanan. Yuk mulai belanja!
                @endif
            </p>
            <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg transition-colors">
                <i class="bi bi-shop mr-2"></i>
                Mulai Belanja
            </a>
        </div>
    </div>
    @else
    <!-- Orders List -->
    <div class="space-y-4">
        @foreach($orders as $order)
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <!-- Order Header -->
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div>
                            <p class="text-xs text-gray-600">Nomor Invoice</p>
                            <p class="font-bold text-gray-900">{{ $order->nomor_invoice }}</p>
                        </div>
                        <div class="h-8 w-px bg-gray-300"></div>
                        <div>
                            <p class="text-xs text-gray-600">Tanggal Pesanan</p>
                            <p class="font-medium text-gray-900">{{ $order->tanggal_pesanan->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
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
                        
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $status['bg'] }} {{ $status['text'] }}">
                            <i class="bi bi-{{ $status['icon'] }} mr-1.5"></i>
                            {{ $status['label'] }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="px-6 py-4">
                @foreach($order->items as $item)
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
                <div class="flex gap-4 py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                    @if($imageUrl)
                        <img src="{{ asset('storage/' . $imageUrl) }}" 
                             alt="{{ $produk->nama_produk }}"
                             class="w-20 h-20 object-cover rounded border border-gray-200"
                             onerror="this.src='{{ asset('images/placeholder.png') }}'">
                    @else
                        <div class="w-20 h-20 bg-gray-100 rounded border border-gray-200 flex items-center justify-center">
                            <i class="bi bi-image text-gray-400 text-2xl"></i>
                        </div>
                    @endif
                    
                    <div class="flex-1 min-w-0">
                        <h4 class="font-semibold text-gray-900 mb-1">{{ $produk->nama_produk }}</h4>
                        @if($varian)
                        <p class="text-sm text-gray-600 mb-1">
                            {{ $varian->nama_varian }}
                        </p>
                        @endif
                        <p class="text-sm text-gray-600">{{ $item->jumlah }} × Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</p>
                    </div>
                    
                    <div class="text-right">
                        <p class="font-bold text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Order Footer -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <i class="bi bi-box-seam"></i>
                        <span>{{ $order->items->count() }} produk</span>
                        <span class="mx-2">•</span>
                        <span class="font-bold text-gray-900">Total: Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="flex gap-2">
                        @if(in_array($order->status_pesanan, ['baru', 'diproses']))
                        <form action="{{ route('orders.cancel', $order->nomor_invoice) }}" method="POST" 
                              onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                            @csrf
                            <button type="submit" class="px-4 py-2 border border-red-300 text-red-600 hover:bg-red-50 rounded-lg text-sm font-medium transition-colors">
                                <i class="bi bi-x-circle mr-1"></i>
                                Batalkan
                            </button>
                        </form>
                        @endif
                        
                        <a href="{{ route('orders.show', $order->nomor_invoice) }}" 
                           class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg text-sm font-medium transition-colors">
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
    <div class="mt-6">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
