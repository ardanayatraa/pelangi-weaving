@extends('layouts.admin')

@section('title', 'Pelanggan')
@section('page-title', 'Manajemen Pelanggan')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-lg">
                <i class="bi bi-people text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Pelanggan</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_pelanggan']) }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-lg">
                <i class="bi bi-person-plus text-green-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Bulan Ini</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['pelanggan_bulan_ini']) }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-purple-100 rounded-lg">
                <i class="bi bi-person-check text-purple-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Pelanggan Aktif</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['pelanggan_aktif']) }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filter -->
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <h1 class="text-2xl font-bold text-gray-900">Daftar Pelanggan</h1>
    </div>
    
    <form method="GET" class="flex items-center gap-3">
        <div class="relative">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}"
                   placeholder="Cari pelanggan..."
                   class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
            <i class="bi bi-search absolute left-3 top-2.5 text-gray-400"></i>
        </div>
        
        <input type="date" 
               name="date_from" 
               value="{{ request('date_from') }}"
               class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
               placeholder="Dari tanggal">
        
        <input type="date" 
               name="date_to" 
               value="{{ request('date_to') }}"
               class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
               placeholder="Sampai tanggal">
        
        <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition text-sm font-medium">
            <i class="bi bi-search mr-1"></i> Filter
        </button>
        
        @if(request('search') || request('date_from') || request('date_to'))
            <a href="{{ route('admin.pelanggan.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition text-sm font-medium">
                <i class="bi bi-arrow-clockwise mr-1"></i> Reset
            </a>
        @endif
    </form>
</div>

<div class="bg-white rounded-lg shadow">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pesanan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Custom Orders</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bergabung</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pelanggan as $customer)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <span class="text-sm font-medium text-blue-600">
                                        {{ strtoupper(substr($customer->nama, 0, 2)) }}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="font-semibold text-gray-800">{{ $customer->nama }}</div>
                                <div class="text-sm text-gray-500">ID: {{ $customer->id_pelanggan }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $customer->email }}</div>
                        <div class="text-sm text-gray-500">{{ $customer->telepon ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900 max-w-xs truncate">
                            {{ $customer->alamat ?? '-' }}
                        </div>
                        @if($customer->kode_pos)
                            <div class="text-sm text-gray-500">{{ $customer->kode_pos }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $customer->pesanan->count() }} pesanan
                        </span>
                        <div class="text-sm text-gray-500 mt-1">
                            Rp {{ number_format($customer->pesanan->sum('total_bayar'), 0, ',', '.') }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                            {{ $customer->customOrders->count() }} custom
                        </span>
                        <div class="text-sm text-gray-500 mt-1">
                            Rp {{ number_format($customer->customOrders->where('status', 'completed')->sum('harga_final'), 0, ',', '.') }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $customer->created_at->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <a href="{{ route('admin.pelanggan.show', $customer->id_pelanggan) }}" 
                           class="inline-flex items-center px-3 py-1 border border-blue-600 text-blue-600 hover:bg-blue-50 rounded text-sm font-medium transition"
                           title="Detail">
                            <i class="bi bi-eye"></i>
                        </a>
                        
                        @if($customer->pesanan->count() == 0 && $customer->customOrders->count() == 0)
                            <form action="{{ route('admin.pelanggan.destroy', $customer->id_pelanggan) }}" 
                                  method="POST" 
                                  class="inline-block ml-2"
                                  onsubmit="return confirm('Yakin ingin menghapus pelanggan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center px-3 py-1 border border-red-600 text-red-600 hover:bg-red-50 rounded text-sm font-medium transition"
                                        title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        @else
                            <span class="inline-flex items-center px-3 py-1 border border-gray-300 text-gray-400 rounded text-sm font-medium ml-2" 
                                  title="Tidak dapat dihapus karena memiliki riwayat pesanan">
                                <i class="bi bi-trash"></i>
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">Belum ada pelanggan terdaftar</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($pelanggan->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $pelanggan->links() }}
    </div>
    @endif
</div>
@endsection