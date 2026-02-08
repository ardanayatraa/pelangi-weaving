@extends('layouts.admin')

@section('title', 'Produk')
@section('page-title', 'Manajemen Produk')

@push('styles')
<style>
    @keyframes modalSlideUp {
        from {
            opacity: 0;
            transform: translateY(20px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    .animate-modal-slide-up {
        animation: modalSlideUp 0.3s ease-out;
    }
    
    #modalDelete:not(.hidden) {
        display: flex;
    }
</style>
@endpush

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
                    <td class="px-6 py-4 whitespace-nowrap text-gray-800">
                        {{ $product->variants_count }} varian
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-800">
                        @if($product->variants->count() > 0)
                            @php
                                $minPrice = $product->variants->min('harga');
                                $maxPrice = $product->variants->max('harga');
                            @endphp
                            @if($minPrice == $maxPrice)
                                Rp {{ number_format($minPrice, 0, ',', '.') }}
                            @else
                                Rp {{ number_format($minPrice, 0, ',', '.') }} - {{ number_format($maxPrice, 0, ',', '.') }}
                            @endif
                        @else
                            <span class="text-gray-500 italic">Belum ada varian</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $totalStok = $product->variants->sum('stok');
                        @endphp
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $totalStok > 10 ? 'bg-green-100 text-green-800' : ($totalStok > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ $totalStok }} unit
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
                        <button type="button" 
                                onclick="confirmDelete('{{ route('admin.products.destroy', $product->slug) }}', '{{ $product->nama_produk }}')"
                                class="inline-flex items-center px-3 py-1 border border-red-600 text-red-600 hover:bg-red-50 rounded text-sm font-medium transition ml-2"
                                title="Hapus">
                            <i class="bi bi-trash"></i>
                        </button>
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

<!-- Modal Konfirmasi Hapus -->
<div id="modalDelete" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4" style="backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); background-color: rgba(0, 0, 0, 0.4);">
    <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all animate-modal-slide-up">
        <div class="p-6">
            <div class="flex items-center justify-center w-20 h-20 mx-auto bg-gradient-to-br from-red-100 to-red-50 rounded-full mb-4 shadow-lg">
                <div class="relative">
                    <i class="bi bi-exclamation-triangle text-4xl text-red-600"></i>
                    <div class="absolute inset-0 bg-red-600 opacity-20 blur-xl rounded-full"></div>
                </div>
            </div>
            
            <div class="text-center mb-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Hapus Produk?</h3>
                <p class="text-gray-600 leading-relaxed">
                    Apakah Anda yakin ingin menghapus produk <br>
                    <strong id="productName" class="text-gray-900"></strong>?
                    <br>
                    <span class="text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan.</span>
                </p>
            </div>

            <form id="formDelete" method="POST" action="">
                @csrf
                @method('DELETE')
                
                <div class="flex gap-3">
                    <button type="button" onclick="closeModal()" 
                            class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 font-semibold shadow-sm">
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl transform hover:scale-105">
                        <i class="bi bi-trash mr-2"></i>
                        Ya, Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function confirmDelete(url, name) {
    const modal = document.getElementById('modalDelete');
    const form = document.getElementById('formDelete');
    const nameElement = document.getElementById('productName');
    
    form.action = url;
    nameElement.textContent = name;
    modal.classList.remove('hidden');
}

function closeModal() {
    document.getElementById('modalDelete').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('modalDelete')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection
