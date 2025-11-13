@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.orders.index') }}" class="text-gray-700 hover:text-gray-900 font-medium inline-flex items-center">
        <i class="bi bi-arrow-left mr-1"></i> Kembali ke Daftar Pesanan
    </a>
</div>

<div class="flex items-center justify-between mb-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">
            Detail Pesanan #{{ $order->order_number }}
        </h1>
        <p class="text-sm text-gray-600 mt-1">{{ $order->created_at->format('d F Y, H:i') }} WIB</p>
    </div>
    <div>
        @if($order->status == 'pending')
            <span class="badge-status bg-yellow-100 text-yellow-800">
                <i class="bi bi-clock mr-1"></i> Pending
            </span>
        @elseif($order->status == 'confirmed')
            <span class="badge-status bg-gray-100 text-gray-800">
                <i class="bi bi-check-circle mr-1"></i> Dikonfirmasi
            </span>
        @elseif($order->status == 'processing')
            <span class="badge-status bg-purple-100 text-purple-800">
                <i class="bi bi-arrow-repeat mr-1"></i> Diproses
            </span>
        @elseif($order->status == 'shipped')
            <span class="badge-status bg-indigo-100 text-indigo-800">
                <i class="bi bi-truck mr-1"></i> Dikirim
            </span>
        @elseif($order->status == 'delivered')
            <span class="badge-status bg-green-100 text-green-800">
                <i class="bi bi-check-all mr-1"></i> Selesai
            </span>
        @elseif($order->status == 'cancelled')
            <span class="badge-status bg-red-100 text-red-800">
                <i class="bi bi-x-circle mr-1"></i> Dibatalkan
            </span>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-4">
        <!-- Order Items -->
        <div class="bg-white border border-gray-200">
            <div class="px-4 py-3 border-b border-gray-200" style="background: #f9f9f9;">
                <h2 class="text-sm font-bold text-gray-900 uppercase">Produk yang Dipesan</h2>
            </div>
            <div class="p-4">
                <div class="space-y-3">
                    @foreach($order->items as $item)
                    <div class="flex items-start space-x-3 pb-3 border-b last:border-b-0">
                        <div class="w-16 h-16 bg-gray-100 border border-gray-200 flex items-center justify-center">
                            <i class="bi bi-box-seam text-gray-400 text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-sm text-gray-900">{{ $item->product_name }}</h3>
                            <p class="text-xs text-gray-600 mt-1">Varian: {{ $item->variant_name }}</p>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-xs text-gray-600">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                <span class="font-bold text-sm text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Order Summary -->
                <div class="mt-4 pt-4 border-t space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal Produk</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Ongkos Kirim ({{ $order->courier_service ?? 'Kurir' }})</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-base font-bold pt-2 border-t">
                        <span>Total</span>
                        <span class="text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Info -->
        <div class="bg-white border border-gray-200">
            <div class="px-4 py-3 border-b border-gray-200" style="background: #f9f9f9;">
                <h2 class="text-sm font-bold text-gray-900 uppercase">Informasi Pengiriman</h2>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div>
                        <p class="text-xs text-gray-600 mb-1">Kurir</p>
                        <p class="font-semibold text-sm text-gray-900">{{ strtoupper($order->courier_service ?? 'N/A') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 mb-1">Layanan</p>
                        <p class="font-semibold text-sm text-gray-900">{{ $order->courier_type ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 mb-1">Estimasi</p>
                        <p class="font-semibold text-sm text-gray-900">{{ $order->estimated_delivery ?? 'N/A' }}</p>
                    </div>
                </div>
                
                @if($order->tracking_number)
                <div class="mb-4 p-3 bg-gray-50 border border-gray-200">
                    <p class="text-xs text-gray-600 mb-1">Nomor Resi</p>
                    <p class="font-bold text-gray-700 text-base">{{ $order->tracking_number }}</p>
                </div>
                @endif

                <div>
                    <p class="text-xs text-gray-600 mb-2">Alamat Pengiriman</p>
                    <div class="p-3 bg-gray-50 border border-gray-200">
                        <p class="font-semibold text-sm text-gray-900">{{ $order->shipping_address }}</p>
                        <p class="text-xs text-gray-600 mt-1">{{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-4">
        <!-- Customer Info -->
        <div class="bg-white border border-gray-200">
            <div class="px-4 py-3 border-b border-gray-200" style="background: #f9f9f9;">
                <h2 class="text-sm font-bold text-gray-900 uppercase">Informasi Pelanggan</h2>
            </div>
            <div class="p-4 space-y-3">
                <div>
                    <p class="text-xs text-gray-600 mb-1">Nama</p>
                    <p class="font-semibold text-sm text-gray-900">{{ $order->customer_name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-600 mb-1">Email</p>
                    <p class="font-semibold text-sm text-gray-900">{{ $order->customer_email }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-600 mb-1">Telepon</p>
                    <p class="font-semibold text-sm text-gray-900">{{ $order->customer_phone }}</p>
                </div>
            </div>
        </div>

        <!-- Payment Info -->
        @if($order->payment)
        <div class="bg-white border border-gray-200">
            <div class="px-4 py-3 border-b border-gray-200" style="background: #f9f9f9;">
                <h2 class="text-sm font-bold text-gray-900 uppercase">Informasi Pembayaran</h2>
            </div>
            <div class="p-4 space-y-3">
                <div>
                    <p class="text-xs text-gray-600 mb-1">Metode</p>
                    <p class="font-semibold text-sm text-gray-900">{{ $order->payment->payment_method }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-600 mb-1">Status</p>
                    <p class="font-semibold text-sm {{ $order->payment->status == 'paid' ? 'text-green-700' : 'text-yellow-700' }}">
                        {{ ucfirst($order->payment->status) }}
                    </p>
                </div>
                @if($order->payment->paid_at)
                <div>
                    <p class="text-xs text-gray-600 mb-1">Dibayar Pada</p>
                    <p class="font-semibold text-sm text-gray-900">{{ $order->payment->paid_at->format('d M Y, H:i') }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Update Status -->
        <div class="bg-white border border-gray-200">
            <div class="px-4 py-3 border-b border-gray-200" style="background: #f9f9f9;">
                <h2 class="text-sm font-bold text-gray-900 uppercase">Update Status</h2>
            </div>
            <div class="p-4">
                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Status Pesanan</label>
                        <select name="status" class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Diproses</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Dikirim</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Selesai</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nomor Resi (Opsional)</label>
                        <input type="text" 
                               name="tracking_number" 
                               value="{{ $order->tracking_number }}"
                               class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400"
                               placeholder="Masukkan nomor resi">
                    </div>

                    <button type="submit" 
                            class="w-full btn-primary font-semibold py-2 px-4 text-sm">
                        <i class="bi bi-check-circle mr-2"></i>
                        Update Status
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
