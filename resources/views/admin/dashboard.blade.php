@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm mb-1">Total Pesanan</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $totalOrders }}</h3>
            </div>
            <div class="bg-blue-100 p-3 rounded-lg">
                <i class="bi bi-cart-check-fill text-blue-600 text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm mb-1">Total Produk</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $totalProducts }}</h3>
            </div>
            <div class="bg-green-100 p-3 rounded-lg">
                <i class="bi bi-box-seam-fill text-green-600 text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm mb-1">Total Pelanggan</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $totalCustomers }}</h3>
            </div>
            <div class="bg-cyan-100 p-3 rounded-lg">
                <i class="bi bi-people-fill text-cyan-600 text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm mb-1">Total Pendapatan</p>
                <h3 class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            </div>
            <div class="bg-yellow-100 p-3 rounded-lg">
                <i class="bi bi-cash-stack text-yellow-600 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h5 class="text-lg font-semibold text-gray-800">Pesanan Terbaru</h5>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Invoice</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($recentOrders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('admin.orders.show', $order->id_pesanan) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                            {{ $order->nomor_invoice }}
                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-800">{{ $order->pelanggan->nama }}</td>
                    <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-800">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($order->status_pesanan === 'baru')
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Baru</span>
                        @elseif($order->status_pesanan === 'diproses')
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Diproses</span>
                        @elseif($order->status_pesanan === 'dikirim')
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-cyan-100 text-cyan-800">Dikirim</span>
                        @elseif($order->status_pesanan === 'selesai')
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>
                        @else
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Batal</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-800">{{ $order->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada pesanan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
