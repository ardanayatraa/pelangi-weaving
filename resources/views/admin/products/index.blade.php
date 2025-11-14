@extends('layouts.admin')

@section('title', 'Produk')
@section('page-title', 'Manajemen Produk')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
        <i class="bi bi-plus-circle mr-2"></i> Tambah Produk
    </a>
</div>

<div class="bg-white rounded-lg shadow">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Produk</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-800">{{ $product->nama_produk }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-800">{{ $product->category->nama_kategori }}</td>
                    <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-800">Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->stok > 10 ? 'bg-green-100 text-green-800' : ($product->stok > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ $product->stok }} unit
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($product->status === 'aktif')
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                        @else
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <a href="{{ route('admin.products.show', $product->slug) }}" 
                           class="inline-flex items-center px-3 py-1 border border-blue-600 text-blue-600 hover:bg-blue-50 rounded text-sm font-medium transition"
                           title="Detail">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('admin.products.edit', $product->slug) }}" 
                           class="inline-flex items-center px-3 py-1 border border-gray-600 text-gray-600 hover:bg-gray-50 rounded text-sm font-medium transition ml-2"
                           title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada produk</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection
