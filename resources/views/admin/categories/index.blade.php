@extends('layouts.admin')

@section('title', 'Kategori')
@section('page-title', 'Manajemen Kategori')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
        <i class="bi bi-plus-circle mr-2"></i> Tambah Kategori
    </a>
</div>

<div class="bg-white rounded-lg shadow">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Produk</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($categories as $category)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-800">{{ $category->nama_kategori }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <code class="text-sm text-gray-600 bg-gray-100 px-2 py-1 rounded">{{ $category->slug }}</code>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $category->products_count }} produk
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <a href="{{ route('admin.categories.edit', $category->id_kategori) }}" 
                           class="inline-flex items-center px-3 py-1 border border-blue-600 text-blue-600 hover:bg-blue-50 rounded text-sm font-medium transition"
                           title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.categories.destroy', $category->id_kategori) }}" method="POST" class="inline-block ml-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-3 py-1 border border-red-600 text-red-600 hover:bg-red-50 rounded text-sm font-medium transition"
                                    onclick="return confirm('Yakin hapus kategori ini?')" 
                                    title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada kategori</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($categories->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $categories->links() }}
    </div>
    @endif
</div>
@endsection
