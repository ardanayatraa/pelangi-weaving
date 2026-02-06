@extends('layouts.admin')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Laporan Penjualan</h1>
            <p class="text-gray-600">Analisis penjualan dan performa bisnis</p>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
            <form method="GET" class="flex flex-wrap gap-4">
                <div class="min-w-40">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                    <input type="date" 
                           name="date_from" 
                           value="{{ $dateFrom }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600">
                </div>
                
                <div class="min-w-40">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                    <input type="date" 
                           name="date_to" 
                           value="{{ $dateTo }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600">
                </div>
                
                <div class="min-w-40">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Laporan</label>
                    <select name="report_type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600">
                        <option value="all" {{ $reportType == 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="regular" {{ $reportType == 'regular' ? 'selected' : '' }}>Pesanan Regular</option>
                        <option value="custom" {{ $reportType == 'custom' ? 'selected' : '' }}>Custom Orders</option>
                    </select>
                </div>
                
                <div class="flex items-end gap-2">
                    <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                        <i class="bi bi-search mr-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.reports.export-sales') }}?{{ http_build_query(request()->all()) }}" 
                       target="_blank"
                       class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                        <i class="bi bi-printer mr-1"></i> Print Laporan
                    </a>
                </div>
            </form>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Regular Orders Stats -->
            <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i class="bi bi-bag text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-600 mb-1">Pesanan Regular</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['regular_orders']['count'] }}</p>
                        <p class="text-xs text-gray-500 truncate">Rp {{ number_format($stats['regular_orders']['total_revenue'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Custom Orders Stats -->
            <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <i class="bi bi-palette text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-600 mb-1">Custom Orders</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['custom_orders']['count'] }}</p>
                        <p class="text-xs text-gray-500 truncate">Rp {{ number_format($stats['custom_orders']['total_revenue'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Total Revenue -->
            <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <i class="bi bi-currency-dollar text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-600 mb-1">Total Revenue</p>
                        <p class="text-xl font-bold text-gray-900 truncate">Rp {{ number_format($stats['regular_orders']['total_revenue'] + $stats['custom_orders']['total_revenue'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Average Order Value -->
            <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <i class="bi bi-graph-up text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-600 mb-1">Rata-rata Order</p>
                        <p class="text-xl font-bold text-gray-900 truncate">
                            Rp {{ number_format(($stats['regular_orders']['avg_order_value'] + $stats['custom_orders']['avg_order_value']) / 2, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Trend Chart -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Tren Penjualan Bulanan</h3>
            
            @if(count($monthlyTrend) > 0)
                <div class="h-80">
                    <div class="flex items-end justify-between gap-3 h-full pb-12">
                        @foreach($monthlyTrend as $month)
                            <div class="flex-1 flex flex-col items-center h-full justify-end">
                                @php
                                    $maxRevenue = collect($monthlyTrend)->max('total_revenue');
                                    $heightPercent = $maxRevenue > 0 ? ($month['total_revenue'] / $maxRevenue) * 100 : 0;
                                @endphp
                                
                                <!-- Bar -->
                                <div class="w-full relative group cursor-pointer" style="height: {{ $heightPercent }}%; min-height: 20px;">
                                    <div class="w-full h-full bg-gradient-to-t from-red-600 to-red-400 rounded-t-lg hover:from-red-700 hover:to-red-500 transition-all duration-200 shadow-md"></div>
                                    
                                    <!-- Tooltip -->
                                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">
                                        <div class="bg-gray-900 text-white text-xs rounded-lg py-2 px-3 whitespace-nowrap shadow-xl">
                                            <div class="font-semibold">{{ $month['month'] }}</div>
                                            <div class="text-gray-300">Rp {{ number_format($month['total_revenue'], 0, ',', '.') }}</div>
                                        </div>
                                        <div class="absolute top-full left-1/2 transform -translate-x-1/2 -mt-1">
                                            <div class="border-4 border-transparent border-t-gray-900"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Label -->
                                <div class="text-xs text-gray-600 mt-3 text-center w-full">
                                    <div class="font-semibold truncate">{{ $month['month'] }}</div>
                                    <div class="text-gray-500 mt-1">
                                        @if($month['total_revenue'] >= 1000000)
                                            Rp {{ number_format($month['total_revenue'] / 1000000, 1) }}M
                                        @elseif($month['total_revenue'] >= 1000)
                                            Rp {{ number_format($month['total_revenue'] / 1000, 0) }}K
                                        @else
                                            Rp {{ number_format($month['total_revenue'], 0) }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="h-64 flex items-center justify-center text-gray-500">
                    <div class="text-center">
                        <i class="bi bi-graph-up text-4xl mb-2"></i>
                        <p>Tidak ada data penjualan untuk periode ini</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Top Products -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Produk Terlaris</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty Terjual</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($topProducts as $product)
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $product->nama_produk }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $product->total_qty }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-gray-500">Tidak ada data produk</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Detailed Orders -->
        @if($reportType === 'all' || $reportType === 'regular')
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-6">Detail Pesanan Regular</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($regularOrders->take(10) as $order)
                                <tr>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $order->nomor_invoice }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $order->pelanggan->nama }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $order->tanggal_pesanan->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                            @if($order->status_pesanan === 'selesai') bg-green-100 text-green-800
                                            @elseif($order->status_pesanan === 'batal') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ ucfirst($order->status_pesanan) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">Tidak ada pesanan regular</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($regularOrders->count() > 10)
                    <div class="mt-4 text-center">
                        <p class="text-sm text-gray-500">Menampilkan 10 dari {{ $regularOrders->count() }} pesanan</p>
                    </div>
                @endif
            </div>
        @endif

        @if($reportType === 'all' || $reportType === 'custom')
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-6">Detail Custom Orders</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($customOrders->take(10) as $order)
                                <tr>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $order->nomor_custom_order }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $order->pelanggan->nama }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $order->jenis->nama_jenis }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $order->created_at->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3">
                                        @php
                                            $statusColors = [
                                                'completed' => 'bg-green-100 text-green-800',
                                                'in_production' => 'bg-purple-100 text-purple-800',
                                                'approved' => 'bg-blue-100 text-blue-800',
                                                'cancelled' => 'bg-red-100 text-red-800',
                                                'rejected' => 'bg-red-100 text-red-800'
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                        @if($order->harga_final > 0)
                                            Rp {{ number_format($order->harga_final, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">Tidak ada custom order</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($customOrders->count() > 10)
                    <div class="mt-4 text-center">
                        <p class="text-sm text-gray-500">Menampilkan 10 dari {{ $customOrders->count() }} custom order</p>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection