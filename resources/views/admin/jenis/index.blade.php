@extends('layouts.admin')

@section('title', 'Jenis')
@section('page-title', 'Manajemen Jenis')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.jenis.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
            <i class="bi bi-plus-circle mr-2"></i> Tambah Jenis
        </a>
    </div>
    
    <!-- Search & Filter -->
    <form method="GET" class="flex items-center gap-3">
        <div class="relative">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}"
                   placeholder="Cari jenis..."
                   class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
            <i class="bi bi-search absolute left-3 top-2.5 text-gray-400"></i>
        </div>
        
        <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
            <option value="">Semua Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
        </select>
        
        <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition text-sm font-medium">
            <i class="bi bi-search mr-1"></i> Filter
        </button>
        
        @if(request('search') || request('status'))
            <a href="{{ route('admin.jenis.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition text-sm font-medium">
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Jenis</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Custom Orders</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($jenis as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($item->icon)
                                <i class="{{ $item->icon }} text-lg text-gray-600 mr-3"></i>
                            @endif
                            <span class="font-semibold text-gray-800">{{ $item->nama_jenis }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <code class="text-sm text-gray-600 bg-gray-100 px-2 py-1 rounded">{{ $item->slug }}</code>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900 max-w-xs truncate">
                            {{ $item->deskripsi ?? '-' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($item->status === 'active')
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Aktif
                            </span>
                        @else
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Tidak Aktif
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $item->customOrders->count() ?? 0 }} pesanan
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $item->created_at->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <a href="{{ route('admin.jenis.edit', $item->id_jenis) }}" 
                           class="inline-flex items-center px-3 py-1 border border-blue-600 text-blue-600 hover:bg-blue-50 rounded text-sm font-medium transition"
                           title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        
                        @if($item->customOrders->count() == 0 && $item->products->count() == 0)
                            <form action="{{ route('admin.jenis.destroy', $item->id_jenis) }}" 
                                  method="POST" 
                                  class="inline-block ml-2"
                                  onsubmit="return confirm('Yakin ingin menghapus jenis ini?')">
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
                                  title="Tidak dapat dihapus karena memiliki data terkait">
                                <i class="bi bi-trash"></i>
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">Belum ada jenis yang dibuat</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($jenis->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $jenis->links() }}
    </div>
    @endif
</div>
@endsection