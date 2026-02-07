@extends('layouts.customer')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="bg-gray-50 min-h-screen py-4 md:py-8">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-xl md:text-2xl font-bold text-gray-900 mb-4 md:mb-6">
            <i class="bi bi-cart3"></i> Keranjang Belanja
        </h1>
        
        @if($cartItems->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
            <!-- Cart Items -->
            <div class="lg:col-span-2 space-y-3 md:space-y-4">
                <!-- Select All -->
                <div class="bg-white rounded-lg shadow-sm p-4 flex items-center justify-between">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)" checked
                               class="w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-600">
                        <span class="font-semibold text-gray-900">Pilih Semua ({{ $cartItems->count() }} Produk)</span>
                    </label>
                    <button type="button" onclick="deleteSelected()" 
                            class="text-red-600 hover:text-red-700 font-medium text-sm">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </div>

                <!-- Cart Items -->
                @foreach($cartItems as $item)
                <div class="bg-white rounded-lg shadow-sm p-3 md:p-4 hover:shadow-md transition">
                    <div class="flex gap-2 md:gap-4">
                        <!-- Checkbox -->
                        <div class="flex items-start pt-1 md:pt-2">
                            <input type="checkbox" value="{{ $item->id_keranjang }}" onchange="updateSummary()" checked
                                   class="item-checkbox w-4 h-4 md:w-5 md:h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-600">
                        </div>

                        <!-- Product Image -->
                        <div class="flex-shrink-0">
                            <a href="{{ route('products.show', $item->product->slug) }}">
                                @if($item->product->primary_image_path)
                                <img src="{{ asset('storage/' . $item->product->primary_image_path) }}" 
                                     alt="{{ $item->product->nama_produk }}"
                                     class="w-16 h-16 md:w-24 md:h-24 object-cover rounded-lg">
                                @else
                                <div class="w-16 h-16 md:w-24 md:h-24 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <i class="bi bi-image text-gray-400 text-xl md:text-2xl"></i>
                                </div>
                                @endif
                            </a>
                        </div>

                        <!-- Product Info -->
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('products.show', $item->product->slug) }}" 
                               class="font-semibold text-sm md:text-base text-gray-900 hover:text-primary-600 line-clamp-2 mb-2">
                                {{ $item->product->nama_produk }}
                            </a>
                            
                            @if($item->productVariant)
                            <div class="mb-2">
                                <span class="inline-block bg-primary-100 text-primary-700 text-xs font-semibold px-2 md:px-3 py-0.5 md:py-1 rounded-full">
                                    <i class="bi bi-palette"></i> {{ $item->productVariant->nama_varian }}
                                </span>
                            </div>
                            @endif

                            <div class="flex flex-col md:flex-row md:items-center md:justify-between mt-2 md:mt-3 gap-2">
                                <!-- Price -->
                                <div>
                                    @php
                                        $harga = $item->productVariant ? $item->productVariant->harga : $item->product->harga;
                                    @endphp
                                    <p class="text-base md:text-xl font-bold text-primary-600">
                                        Rp {{ number_format($harga, 0, ',', '.') }}
                                    </p>
                                </div>

                                <!-- Quantity Controls -->
                                <div class="flex items-center gap-1 md:gap-2">
                                    <form action="{{ route('cart.update', $item->id_keranjang) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="jumlah" value="{{ max(1, $item->jumlah - 1) }}">
                                        <button type="submit" 
                                                class="w-8 h-8 border border-gray-300 rounded-lg hover:bg-gray-50 transition"
                                                {{ $item->jumlah <= 1 ? 'disabled' : '' }}>
                                            <i class="bi bi-dash"></i>
                                        </button>
                                    </form>

                                    <span class="w-12 text-center font-semibold">{{ $item->jumlah }}</span>

                                    <form action="{{ route('cart.update', $item->id_keranjang) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="jumlah" value="{{ $item->jumlah + 1 }}">
                                        <button type="submit" 
                                                class="w-8 h-8 border border-gray-300 rounded-lg hover:bg-gray-50 transition"
                                                {{ $item->jumlah >= ($item->productVariant ? $item->productVariant->stok : $item->product->stok) ? 'disabled' : '' }}>
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('cart.remove', $item->id_keranjang) }}" method="POST" class="inline ml-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Hapus item ini?')"
                                                class="w-8 h-8 text-red-600 hover:bg-red-50 rounded-lg transition">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Subtotal -->
                            <div class="mt-2 text-right">
                                <p class="text-sm text-gray-600">Subtotal:</p>
                                <p class="text-lg font-bold text-gray-900">
                                    Rp {{ number_format($harga * $item->jumlah, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-20">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Ringkasan Belanja</h3>
                    
                    <div class="space-y-3 mb-4 pb-4 border-b">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Total Harga (<span id="selectedCount">0</span> barang)</span>
                            <span class="font-semibold" id="totalPrice">Rp 0</span>
                        </div>
                    </div>

                    <div class="flex justify-between text-lg font-bold mb-6">
                        <span>Total</span>
                        <span class="text-primary-600" id="grandTotal">Rp 0</span>
                    </div>

                    <button type="button" onclick="checkout()" id="checkoutBtn"
                            class="w-full bg-primary-600 text-white py-3 rounded-lg font-semibold hover:bg-primary-700 transition shadow-lg shadow-primary-200 disabled:bg-gray-300 disabled:cursor-not-allowed"
                            disabled>
                        Lanjut ke Pembayaran
                    </button>

                    <a href="{{ route('products.index') }}" 
                       class="block text-center text-primary-600 hover:text-primary-700 mt-4 text-sm font-medium">
                        <i class="bi bi-arrow-left"></i> Lanjut Belanja
                    </a>
                </div>
            </div>
        </div>
        @else
        <!-- Empty Cart -->
        <div class="bg-white rounded-xl shadow-sm p-12 text-center">
            <i class="bi bi-cart-x text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Keranjang Belanja Kosong</h3>
            <p class="text-gray-600 mb-6">Yuk, isi keranjangmu dengan produk tenun pilihan!</p>
            <a href="{{ route('products.index') }}" 
               class="inline-block bg-primary-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary-700 transition">
                <i class="bi bi-shop"></i> Mulai Belanja
            </a>
        </div>
        @endif
    </div>
</div>

<script>
function toggleSelectAll(checkbox) {
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    itemCheckboxes.forEach(cb => {
        cb.checked = checkbox.checked;
    });
    updateSummary();
}

function updateSummary() {
    const checkboxes = document.querySelectorAll('.item-checkbox:checked');
    const selectAllCheckbox = document.getElementById('selectAll');
    const allCheckboxes = document.querySelectorAll('.item-checkbox');
    
    // Update select all checkbox
    selectAllCheckbox.checked = checkboxes.length === allCheckboxes.length && allCheckboxes.length > 0;
    
    let total = 0;
    let count = 0;
    
    checkboxes.forEach(checkbox => {
        const cartItem = checkbox.closest('.bg-white.rounded-lg');
        const subtotalElement = cartItem.querySelector('.text-lg.font-bold.text-gray-900');
        if (subtotalElement) {
            const priceText = subtotalElement.textContent;
            const price = parseInt(priceText.replace(/[^0-9]/g, ''));
            total += price;
            count++;
        }
    });
    
    document.getElementById('selectedCount').textContent = count;
    document.getElementById('totalPrice').textContent = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('grandTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
    
    const checkoutBtn = document.getElementById('checkoutBtn');
    checkoutBtn.disabled = count === 0;
}

function deleteSelected() {
    const checkboxes = document.querySelectorAll('.item-checkbox:checked');
    if (checkboxes.length === 0) {
        alert('Pilih item yang ingin dihapus');
        return;
    }
    
    if (confirm(`Hapus ${checkboxes.length} item dari keranjang?`)) {
        // Delete each item one by one
        const ids = Array.from(checkboxes).map(cb => cb.value);
        let completed = 0;
        
        ids.forEach(id => {
            fetch(`/cart/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            }).then(() => {
                completed++;
                if (completed === ids.length) {
                    window.location.reload();
                }
            });
        });
    }
}

function checkout() {
    const checkboxes = document.querySelectorAll('.item-checkbox:checked');
    if (checkboxes.length === 0) {
        alert('Pilih item yang ingin dibeli');
        return;
    }
    
    const ids = Array.from(checkboxes).map(cb => cb.value);
    window.location.href = '{{ route("checkout.index") }}?items=' + ids.join(',');
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateSummary();
});
</script>
@endsection
