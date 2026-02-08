@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Products -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                            <i class="bi bi-box-seam-fill text-primary-600 text-xl"></i>
                        </div>
                        <span class="text-xs font-semibold text-primary-600 bg-primary-50 px-2 py-1 rounded-full">P</span>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 mb-1">{{ $totalProducts }}</div>
                    <div class="text-sm text-gray-600">Total Produk</div>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="bi bi-cart-check-fill text-blue-600 text-xl"></i>
                        </div>
                        <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">O</span>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 mb-1">{{ $totalOrders }}</div>
                    <div class="text-sm text-gray-600">Pesanan Baru</div>
                </div>
            </div>
        </div>

        <!-- Custom Orders -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                            <i class="bi bi-palette-fill text-purple-600 text-xl"></i>
                        </div>
                        <span class="text-xs font-semibold text-purple-600 bg-purple-50 px-2 py-1 rounded-full">C</span>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 mb-1">23</div>
                    <div class="text-sm text-gray-600">Custom Order</div>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <i class="bi bi-people-fill text-green-600 text-xl"></i>
                        </div>
                        <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">U</span>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 mb-1">{{ $totalCustomers }}</div>
                    <div class="text-sm text-gray-600">Total Pelanggan</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Orders -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between p-6 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-gray-900">Pesanan Terbaru</h2>
                    <a href="{{ route('admin.orders.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                        Lihat Semua
                    </a>
                </div>
                
                <div class="overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Order ID</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Produk</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($recentOrders as $order)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">#ORD-{{ str_pad($order->id_pesanan, 3, '0', STR_PAD_LEFT) }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $order->pelanggan->nama }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-gray-700">{{ $order->items->first()->produk->nama_produk ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($order->status_pesanan === 'baru')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                            Menunggu
                                        </span>
                                    @elseif($order->status_pesanan === 'diproses')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                            Diproses
                                        </span>
                                    @elseif($order->status_pesanan === 'dikirim')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            Selesai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                            Batal
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="text-gray-400">
                                        <i class="bi bi-inbox text-4xl mb-3"></i>
                                        <div class="text-sm">Belum ada pesanan</div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi Cepat</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.products.create') }}" class="flex items-center w-full p-3 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-colors">
                        <i class="bi bi-plus-circle mr-3 text-lg"></i>
                        Tambah Produk
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="flex items-center w-full p-3 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-colors">
                        <i class="bi bi-tags mr-3 text-lg"></i>
                        Kelola Kategori
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center w-full p-3 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-colors">
                        <i class="bi bi-list-check mr-3 text-lg"></i>
                        Lihat Pesanan
                    </a>
                    <a href="{{ route('admin.custom-orders.index') }}" class="flex items-center w-full p-3 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-colors">
                        <i class="bi bi-palette mr-3 text-lg"></i>
                        Custom Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection