@extends('layouts.customer')

@section('title', 'Keranjang Belanja')

@section('content')
<style>
    .cart-item-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #E0E0E0;
        margin-bottom: 16px;
        transition: all 0.3s;
    }
    
    .cart-item-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    
    .cart-checkbox {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }
    
    .cart-image {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
    }
    
    .quantity-control {
        display: flex;
        align-items: center;
        border: 1px solid #E0E0E0;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .quantity-btn {
        width: 32px;
        height: 32px;
        border: none;
        background: white;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .quantity-btn:hover {
        background: #F5F5F5;
        color: #FF6600;
    }
    
    .quantity-input {
        width: 50px;
        height: 32px;
        border: none;
        text-align: center;
        font-weight: 600;
    }
    
    .summary-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #E0E0E0;
        position: sticky;
        top: 20px;
    }
    
    .btn-checkout {
        background: #FF6600;
        color: white;
        border: none;
        padding: 14px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1.1rem;
        width: 100%;
        transition: all 0.3s;
    }
    
    .btn-checkout:hover {
        background: #FF8533;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255,102,0,0.3);
    }
    
    .empty-cart {
        text-align: center;
        padding: 60px 20px;
    }
    
    .empty-cart-icon {
        font-size: 5rem;
        color: #E0E0E0;
        margin-bottom: 20px;
    }
</style>

<div class="container py-4">
    <h2 class="fw-bold mb-4">
        <i class="bi bi-cart3"></i> Keranjang Belanja
    </h2>
    
    @if($cartItems->count() > 0)
    <div class="row">
        <!-- Cart Items -->
        <div class="col-lg-8">
            <!-- Select All -->
            <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
                <div class="card-body py-3">
                    <div class="form-check">
                        <input class="form-check-input cart-checkbox" type="checkbox" id="selectAll" onchange="toggleSelectAll(this)">
                        <label class="form-check-label fw-semibold" for="selectAll">
                            Pilih Semua ({{ $cartItems->count() }} Produk)
                        </label>
                        <button type="button" class="btn btn-link text-danger float-end" onclick="deleteSelected()">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>

            <!-- Cart Items -->
            @foreach($cartItems as $item)
            <div class="cart-item-card">
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        <!-- Checkbox -->
                        <div class="col-auto">
                            <input class="form-check-input cart-checkbox item-checkbox" type="checkbox" value="{{ $item->id_keranjang }}" onchange="updateSummary()">
                        </div>

                        <!-- Product Image -->
                        <div class="col-auto">
                            <a href="{{ route('products.show', $item->product->slug) }}">
                                @if($item->product->images->first())
                                <img src="{{ Storage::url($item->product->images->first()->path) }}" class="cart-image" alt="{{ $item->product->nama_produk }}">
                                @else
                                <div class="cart-image bg-light d-flex align-items-center justify-content-center">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                                @endif
                            </a>
                        </div>

                        <!-- Product Info -->
                        <div class="col">
                            <a href="{{ route('products.show', $item->product->slug) }}" class="text-decoration-none text-dark">
                                <h6 class="mb-1 fw-semibold">{{ $item->product->nama_produk }}</h6>
                            </a>
                            @if($item->productVariant)
                            <div class="text-muted small mb-2">
                                <span class="badge bg-light text-dark">{{ $item->productVariant->warna }}</span>
                                <span class="badge bg-light text-dark">{{ $item->productVariant->ukuran }}</span>
                                @if($item->productVariant->jenis_benang)
                                <span class="badge bg-light text-dark">{{ $item->productVariant->jenis_benang }}</span>
                                @endif
                            </div>
                            @endif
                            
                            <!-- Price & Quantity -->
                            <div class="d-flex align-items-center justify-content-between mt-3">
                                <div>
                                    <div class="fw-bold" style="color: #FF6600; font-size: 1.1rem;">
                                        Rp {{ number_format($item->productVariant ? $item->productVariant->harga : $item->product->harga, 0, ',', '.') }}
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-center gap-3">
                                    <!-- Quantity Control -->
                                    <div class="quantity-control">
                                        <button type="button" class="quantity-btn" onclick="updateQuantity({{ $item->id_keranjang }}, 'decrease')">-</button>
                                        <input type="number" class="quantity-input" id="qty-{{ $item->id_keranjang }}" value="{{ $item->jumlah }}" min="1" max="{{ $item->productVariant ? $item->productVariant->stok : $item->product->stok }}" readonly>
                                        <button type="button" class="quantity-btn" onclick="updateQuantity({{ $item->id_keranjang }}, 'increase')">+</button>
                                    </div>
                                    
                                    <!-- Delete Button -->
                                    <form action="{{ route('cart.remove', $item->id_keranjang) }}" method="POST" class="d-inline" id="delete-form-{{ $item->id_keranjang }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-link text-danger p-0" onclick="confirmDelete({{ $item->id_keranjang }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Summary -->
        <div class="col-lg-4">
            <div class="summary-card">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Ringkasan Belanja</h5>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Total Harga (<span id="selectedCount">0</span> Produk)</span>
                        <span class="fw-semibold" id="subtotalDisplay">Rp 0</span>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold">Total</span>
                        <span class="fw-bold" style="color: #FF6600; font-size: 1.5rem;" id="totalDisplay">Rp 0</span>
                    </div>
                    
                    <button type="button" class="btn-checkout" onclick="checkout()" id="checkoutBtn" disabled>
                        Beli (<span id="checkoutCount">0</span>)
                    </button>
                    
                    <div class="mt-3 text-center">
                        <small class="text-muted">
                            <i class="bi bi-shield-check"></i> Transaksi Aman & Terpercaya
                        </small>
                    </div>
                </div>
            </div>


        </div>
    </div>
    @else
    <!-- Empty Cart -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-body">
            <div class="empty-cart">
                <div class="empty-cart-icon">
                    <i class="bi bi-cart-x"></i>
                </div>
                <h4 class="fw-bold mb-3">Keranjang Belanja Kosong</h4>
                <p class="text-muted mb-4">Yuk, isi keranjangmu dengan produk tenun pilihan!</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary-custom btn-lg px-5" style="border-radius: 24px;">
                    <i class="bi bi-shop"></i> Mulai Belanja
                </a>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
// Cart items data
@php
$cartData = $cartItems->map(function($item) {
    return [
        'id' => $item->id_keranjang,
        'jumlah' => $item->jumlah,
        'harga' => $item->productVariant ? $item->productVariant->harga : $item->product->harga,
        'stok' => $item->productVariant ? $item->productVariant->stok : $item->product->stok
    ];
});
@endphp
const cartItems = @json($cartData);

function toggleSelectAll(checkbox) {
    document.querySelectorAll('.item-checkbox').forEach(cb => {
        cb.checked = checkbox.checked;
    });
    updateSummary();
}

function updateSummary() {
    const checkboxes = document.querySelectorAll('.item-checkbox:checked');
    const count = checkboxes.length;
    let total = 0;
    
    checkboxes.forEach(cb => {
        const itemId = parseInt(cb.value);
        const item = cartItems.find(i => i.id === itemId);
        if (item) {
            const qty = parseInt(document.getElementById('qty-' + itemId).value);
            total += item.harga * qty;
        }
    });
    
    document.getElementById('selectedCount').textContent = count;
    document.getElementById('checkoutCount').textContent = count;
    document.getElementById('subtotalDisplay').textContent = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('totalDisplay').textContent = 'Rp ' + total.toLocaleString('id-ID');
    
    document.getElementById('checkoutBtn').disabled = count === 0;
}

function updateQuantity(cartId, action) {
    const input = document.getElementById('qty-' + cartId);
    const item = cartItems.find(i => i.id === cartId);
    let currentQty = parseInt(input.value);
    
    if (action === 'increase' && currentQty < item.stok) {
        currentQty++;
    } else if (action === 'decrease' && currentQty > 1) {
        currentQty--;
    }
    
    input.value = currentQty;
    
    // Update ke server
    fetch(`/cart/${cartId}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ jumlah: currentQty })
    })
    .then(response => response.json())
    .then(data => {
        // Update item in array
        const itemIndex = cartItems.findIndex(i => i.id === cartId);
        if (itemIndex !== -1) {
            cartItems[itemIndex].jumlah = currentQty;
        }
        updateSummary();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Gagal update jumlah');
    });
}

function confirmDelete(cartId) {
    if (confirm('Hapus produk dari keranjang?')) {
        document.getElementById('delete-form-' + cartId).submit();
    }
}

function deleteSelected() {
    const checkboxes = document.querySelectorAll('.item-checkbox:checked');
    if (checkboxes.length === 0) {
        alert('Pilih produk yang ingin dihapus');
        return;
    }
    
    if (confirm(`Hapus ${checkboxes.length} produk dari keranjang?`)) {
        checkboxes.forEach(cb => {
            document.getElementById('delete-form-' + cb.value).submit();
        });
    }
}

function checkout() {
    const checkboxes = document.querySelectorAll('.item-checkbox:checked');
    if (checkboxes.length === 0) {
        alert('Pilih produk yang ingin dibeli');
        return;
    }
    
    // Redirect ke checkout dengan selected items
    const selectedIds = Array.from(checkboxes).map(cb => cb.value);
    window.location.href = '{{ route("checkout.index") }}?items=' + selectedIds.join(',');
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Check all by default
    document.getElementById('selectAll').checked = true;
    document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = true);
    updateSummary();
});
</script>
@endsection
