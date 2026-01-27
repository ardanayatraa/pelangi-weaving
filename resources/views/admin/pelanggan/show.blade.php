@extends('layouts.admin')

@section('title', 'Detail Pelanggan')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Detail Pelanggan</h1>
            <p class="text-gray-600">{{ $pelanggan->nama }}</p>
        </div>

        <!-- Customer Info -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 mb-8">
            <div class="flex items-start justify-between mb-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-16 w-16">
                        <div class="h-16 w-16 rounded-full bg-red-100 flex items-center justify-center">
                            <span class="text-xl font-medium text-red-600">
                                {{ strtoupper(substr($pelanggan->nama, 0, 2)) }}
                            </span>
                        </div>
                    </div>
                    <div class="ml-6">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $pelanggan->nama }}</h2>
                        <p class="text-gray-600">ID Pelanggan: {{ $pelanggan->id_pelanggan }}</p>
                        <p class="text-sm text-gray-500">Bergabung {{ $pelanggan->created_at->format('d F Y') }}</p>
                    </div>
                </div>
                
                <a href="{{ route('admin.pelanggan.index') }}" 
                   class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                    <i class="bi bi-arrow-left mr-2"></i>Kembali
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Contact Info -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Kontak</h3>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <i class="bi bi-envelope text-gray-400 w-5"></i>
                            <span class="ml-3 text-gray-900">{{ $pelanggan->email }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="bi bi-telephone text-gray-400 w-5"></i>
                            <span class="ml-3 text-gray-900">{{ $pelanggan->telepon ?? '-' }}</span>
                        </div>
                        <div class="flex items-start">
                            <i class="bi bi-geo-alt text-gray-400 w-5 mt-1"></i>
                            <span class="ml-3 text-gray-900">{{ $pelanggan->alamat ?? '-' }}</span>
                        </div>
                        @if($pelanggan->kode_pos)
                            <div class="flex items-center">
                                <i class="bi bi-mailbox text-gray-400 w-5"></i>
                                <span class="ml-3 text-gray-900">{{ $pelanggan->kode_pos }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Statistics -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <div class="text-2xl font-bold text-blue-600">{{ $customerStats['total_pesanan'] }}</div>
                            <div class="text-sm text-blue-600">Total Pesanan</div>
                        </div>
                        <div class="bg-purple-50 rounded-lg p-4">
                            <div class="text-2xl font-bold text-purple-600">{{ $customerStats['total_custom_orders'] }}</div>
                            <div class="text-sm text-purple-600">Custom Orders</div>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4">
                            <div class="text-2xl font-bold text-green-600">Rp {{ number_format($customerStats['total_pembelian'], 0, ',', '.') }}</div>
                            <div class="text-sm text-green-600">Total Pembelian</div>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-4">
                            <div class="text-2xl font-bold text-yellow-600">{{ $customerStats['pesanan_selesai'] }}</div>
                            <div class="text-sm text-yellow-600">Pesanan Selesai</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders History -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Regular Orders -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-6">Riwayat Pesanan Regular</h3>
                
                @if($pelanggan->pesanan->count() > 0)
                    <div class="space-y-4">
                        @foreach($pelanggan->pesanan->take(5) as $order)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $order->nomor_invoice }}</h4>
                                        <p class="text-sm text-gray-600">{{ $order->tanggal_pesanan->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        @if($order->status_pesanan === 'selesai') bg-green-100 text-green-800
                                        @elseif($order->status_pesanan === 'batal') bg-red-100 text-red-800
                                        @elseif($order->status_pesanan === 'dikirim') bg-blue-100 text-blue-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst($order->status_pesanan) }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="text-sm text-gray-600">
                                        {{ $order->items->count() }} item
                                    </div>
                                    <div class="font-semibold text-gray-900">
                                        Rp {{ number_format($order->total_bayar, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        @if($pelanggan->pesanan->count() > 5)
                            <div class="text-center">
                                <p class="text-sm text-gray-500">Dan {{ $pelanggan->pesanan->count() - 5 }} pesanan lainnya</p>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="bi bi-bag text-4xl text-gray-300 mb-2"></i>
                        <p class="text-gray-500">Belum ada pesanan regular</p>
                    </div>
                @endif
            </div>

            <!-- Custom Orders -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-6">Riwayat Custom Orders</h3>
                
                @if($pelanggan->customOrders->count() > 0)
                    <div class="space-y-4">
                        @foreach($pelanggan->customOrders->take(5) as $customOrder)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $customOrder->nomor_custom_order }}</h4>
                                        <p class="text-sm text-gray-600">{{ $customOrder->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    @php
                                        $statusColors = [
                                            'draft' => 'bg-gray-100 text-gray-800',
                                            'pending_approval' => 'bg-yellow-100 text-yellow-800',
                                            'approved' => 'bg-blue-100 text-blue-800',
                                            'in_production' => 'bg-purple-100 text-purple-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                            'rejected' => 'bg-red-100 text-red-800'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$customOrder->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst(str_replace('_', ' ', $customOrder->status)) }}
                                    </span>
                                </div>
                                <div class="mb-2">
                                    <p class="text-sm font-medium text-gray-900">{{ $customOrder->nama_custom }}</p>
                                    <p class="text-sm text-gray-600">{{ $customOrder->jenis->nama_jenis }} • {{ $customOrder->jumlah }} pcs</p>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="text-sm text-gray-600">
                                        @if($customOrder->harga_final > 0)
                                            @if($customOrder->isDpPaid())
                                                <span class="text-green-600">DP Dibayar</span>
                                            @else
                                                <span class="text-red-600">DP Belum Dibayar</span>
                                            @endif
                                        @else
                                            Harga belum ditentukan
                                        @endif
                                    </div>
                                    <div class="font-semibold text-gray-900">
                                        @if($customOrder->harga_final > 0)
                                            Rp {{ number_format($customOrder->harga_final, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        @if($pelanggan->customOrders->count() > 5)
                            <div class="text-center">
                                <p class="text-sm text-gray-500">Dan {{ $pelanggan->customOrders->count() - 5 }} custom order lainnya</p>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="bi bi-palette text-4xl text-gray-300 mb-2"></i>
                        <p class="text-gray-500">Belum ada custom order</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection