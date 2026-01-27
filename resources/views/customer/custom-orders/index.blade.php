@extends('layouts.customer')

@section('title', 'Custom Order Saya - Pelangi Weaving')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Custom Order Saya</h1>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('custom-orders.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-semibold">
                    <i class="bi bi-plus mr-2"></i>
                    Buat Custom Order Baru
                </a>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                <!-- Status Filter Tabs -->
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('custom-orders.index') }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ !request('status') ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        <i class="bi bi-list-ul mr-1"></i>
                        Semua
                    </a>
                    <a href="{{ route('custom-orders.index', ['status' => 'pending_approval']) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('status') == 'pending_approval' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        Menunggu Persetujuan
                    </a>
                    <a href="{{ route('custom-orders.index', ['status' => 'in_production']) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('status') == 'in_production' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        Dalam Produksi
                    </a>
                    <a href="{{ route('custom-orders.index', ['status' => 'approved']) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('status') == 'approved' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        Siap Bayar
                    </a>
                    <a href="{{ route('custom-orders.index', ['status' => 'completed']) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('status') == 'completed' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        Selesai
                    </a>
                    <a href="{{ route('custom-orders.index', ['status' => 'cancelled']) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('status') == 'cancelled' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        Dibatalkan
                    </a>
                </div>

                <!-- Search and Filter -->
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <input type="text" 
                               placeholder="Cari custom order berdasarkan nomor atau nama produk..."
                               class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option>6 Bulan Terakhir</option>
                        <option>3 Bulan Terakhir</option>
                        <option>1 Bulan Terakhir</option>
                        <option>Semua Waktu</option>
                    </select>
                    <button class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        <i class="bi bi-funnel"></i>
                        Cari
                    </button>
                </div>
            </div>
        </div>

        <!-- Custom Orders List -->
        @if($customOrders->count() > 0)
            <div class="space-y-6">
                @foreach($customOrders as $order)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                        <!-- Order Header -->
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                <div class="flex items-center space-x-6">
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wide">Custom Order ID</p>
                                        <p class="font-bold text-gray-900 font-mono">{{ $order->nomor_custom_order }}</p>
                                        <p class="text-sm text-gray-600">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between md:justify-end space-x-4">
                                    @php
                                    $statusConfig = [
                                        'draft' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'Draft'],
                                        'pending_approval' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Menunggu Persetujuan'],
                                        'approved' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Siap Bayar'],
                                        'in_production' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'label' => 'Dalam Produksi'],
                                        'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Selesai'],
                                        'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Dibatalkan'],
                                        'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Ditolak']
                                    ];
                                    $status = $statusConfig[$order->status] ?? $statusConfig['draft'];
                                    @endphp
                                    
                                    <span class="inline-flex items-center px-3 py-2 rounded-xl text-sm font-semibold {{ $status['bg'] }} {{ $status['text'] }}">
                                        {{ $status['label'] }}
                                    </span>
                                    
                                    <div class="text-right">
                                        @if($order->harga_final > 0)
                                            <p class="text-xs text-gray-500 uppercase tracking-wide">Harga Estimasi</p>
                                            <p class="text-xl font-bold text-primary-600">Rp {{ number_format($order->harga_final, 0, ',', '.') }}</p>
                                        @else
                                            <p class="text-sm text-gray-500">Harga belum ditentukan</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Content -->
                        <div class="px-6 py-6">
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <!-- Product Info -->
                                <div class="lg:col-span-2">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $order->nama_custom }}</h3>
                                    <div class="flex items-center space-x-4 text-sm text-gray-600 mb-3">
                                        <span class="flex items-center">
                                            <i class="bi bi-tag mr-1"></i>
                                            Kategori: {{ $order->jenis->nama_jenis }}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="bi bi-box mr-1"></i>
                                            Ukuran: {{ $order->ukuran ?? '200x115cm' }}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="bi bi-hash mr-1"></i>
                                            Qty: {{ $order->jumlah }} pcs
                                        </span>
                                    </div>
                                    <p class="text-gray-700 text-sm leading-relaxed">{{ Str::limit($order->deskripsi_custom, 200) }}</p>
                                    
                                    @if($order->estimasi_selesai)
                                    <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                                        <p class="text-sm text-blue-800">
                                            <i class="bi bi-clock mr-2"></i>
                                            Estimasi: {{ $order->estimasi_selesai }} hari kerja
                                        </p>
                                    </div>
                                    @endif
                                </div>

                                <!-- Status Info -->
                                <div class="lg:col-span-1">
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <h4 class="font-semibold text-gray-900 mb-3">Status Progress</h4>
                                        
                                        @if($order->status === 'pending_approval')
                                        <p class="text-sm text-yellow-700 mb-2">Menunggu persetujuan admin</p>
                                        @elseif($order->status === 'approved')
                                        <p class="text-sm text-blue-700 mb-2">Disetujui - Siap untuk pembayaran DP</p>
                                        @elseif($order->status === 'in_production')
                                        <p class="text-sm text-purple-700 mb-2">Sedang dalam proses produksi</p>
                                        @elseif($order->status === 'completed')
                                        <p class="text-sm text-green-700 mb-2">Produksi selesai</p>
                                        @endif

                                        @if($order->harga_final > 0 && $order->dp_amount > 0)
                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Subtotal (1 produk)</span>
                                                <span class="font-semibold">Rp {{ number_format($order->harga_final, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">DP (50%)</span>
                                                <span class="font-semibold">Rp {{ number_format($order->dp_amount, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Biaya Admin</span>
                                                <span class="font-semibold">Rp 2.500</span>
                                            </div>
                                            <div class="border-t pt-2 flex justify-between">
                                                <span class="font-semibold">Total DP</span>
                                                <span class="font-bold text-primary-600">Rp {{ number_format($order->dp_amount + 2500, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Footer -->
                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    @if($order->dp_amount > 0)
                                    <div class="flex items-center">
                                        @if($order->isDpPaid())
                                        <i class="bi bi-check-circle text-green-500 mr-2"></i>
                                        <span class="text-green-600">DP Sudah Dibayar</span>
                                        @else
                                        <i class="bi bi-exclamation-circle text-yellow-500 mr-2"></i>
                                        <span class="text-yellow-600">DP Belum Dibayar</span>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                                
                                <div class="flex items-center space-x-3">
                                    @if($order->status === 'draft')
                                    <a href="{{ route('custom-orders.edit', $order->nomor_custom_order) }}" 
                                       class="px-4 py-2 border border-blue-300 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors text-sm font-medium">
                                        <i class="bi bi-pencil mr-1"></i>
                                        Edit
                                    </a>
                                    @endif
                                    
                                    @if($order->status === 'approved' && !$order->isDpPaid())
                                    <a href="{{ route('custom-orders.payment', $order->nomor_custom_order) }}" 
                                       class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-sm font-medium transition-colors">
                                        <i class="bi bi-credit-card mr-1"></i>
                                        Bayar DP
                                    </a>
                                    @endif
                                    
                                    @if(in_array($order->status, ['draft', 'pending_approval', 'approved']))
                                    <form action="{{ route('custom-orders.cancel', $order->nomor_custom_order) }}" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirm('Yakin ingin membatalkan custom order ini?')">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 border border-red-300 text-red-600 hover:bg-red-50 rounded-lg text-sm font-medium transition-colors">
                                            <i class="bi bi-x-circle mr-1"></i>
                                            Batalkan
                                        </button>
                                    </form>
                                    @endif
                                    
                                    <a href="{{ route('custom-orders.show', $order->nomor_custom_order) }}" 
                                       class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-sm font-medium transition-colors">
                                        <i class="bi bi-eye mr-1"></i>
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($customOrders->hasPages())
                <div class="mt-8 flex justify-center">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-2">
                        {{ $customOrders->links() }}
                    </div>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
                <div class="max-w-sm mx-auto">
                    <i class="bi bi-inbox text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Custom Order</h3>
                    <p class="text-gray-600 mb-6">
                        @if(request('status'))
                            Tidak ada custom order dengan status "{{ ucfirst(request('status')) }}"
                        @else
                            Mulai buat custom order pertama Anda untuk mendapatkan produk tenun sesuai keinginan!
                        @endif
                    </p>
                    <a href="{{ route('custom-orders.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl transition-colors">
                        <i class="bi bi-plus mr-2"></i>
                        Buat Custom Order
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection