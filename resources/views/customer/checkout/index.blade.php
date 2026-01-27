@extends('layouts.customer')

@section('title', 'Checkout - Pelangi Weaving')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors">Beranda</a></li>
                <li><i class="bi bi-chevron-right text-xs"></i></li>
                <li><a href="{{ route('products.index') }}" class="hover:text-primary-600 transition-colors">Produk</a></li>
                <li><i class="bi bi-chevron-right text-xs"></i></li>
                <li><a href="{{ route('cart.index') }}" class="hover:text-primary-600 transition-colors">Keranjang</a></li>
                <li><i class="bi bi-chevron-right text-xs"></i></li>
                <li class="text-gray-900 font-medium">Checkout</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Checkout</h1>
            <p class="text-gray-600">Lengkapi informasi untuk menyelesaikan pesanan Anda</p>
        </div>

        <!-- Progress Steps -->
        <div class="flex items-center justify-center mb-12">
            <div class="flex items-center space-x-4 md:space-x-8">
                <!-- Step 1: Keranjang -->
                <div class="flex items-center">
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-green-500 text-white rounded-full flex items-center justify-center font-semibold text-sm">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    <span class="ml-2 md:ml-3 font-medium text-green-600 text-sm md:text-base">Keranjang</span>
                </div>
                
                <div class="w-8 md:w-16 h-1 bg-green-500"></div>
                
                <!-- Step 2: Checkout -->
                <div class="flex items-center">
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-primary-600 text-white rounded-full flex items-center justify-center font-semibold text-sm">
                        2
                    </div>
                    <span class="ml-2 md:ml-3 font-medium text-primary-600 text-sm md:text-base">Checkout</span>
                </div>
                
                <div class="w-8 md:w-16 h-1 bg-gray-300"></div>
                
                <!-- Step 3: Pembayaran -->
                <div class="flex items-center">
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-semibold text-sm">
                        3
                    </div>
                    <span class="ml-2 md:ml-3 font-medium text-gray-600 text-sm md:text-base">Pembayaran</span>
                </div>
            </div>
        </div>

        <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Left: Form -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Alamat Pengiriman -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center mb-6">
                            <div class="w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center font-semibold text-sm mr-3">
                                1
                            </div>
                            <h2 class="text-xl font-bold text-gray-900">Alamat Pengiriman</h2>
                        </div>
                        
                        <!-- Selected Address Card -->
                        <div class="bg-gradient-to-r from-primary-50 to-blue-50 border-2 border-primary-200 rounded-xl p-4 mb-4">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <span class="bg-primary-600 text-white px-3 py-1 rounded-full text-xs font-semibold mr-3">
                                            <i class="bi bi-house-door"></i> Rumah
                                        </span>
                                        <span class="font-semibold text-gray-900">{{ $pelanggan->nama }}</span>
                                    </div>
                                    <p class="text-gray-700 mb-1 flex items-center">
                                        <i class="bi bi-telephone mr-2 text-primary-600"></i>
                                        {{ $pelanggan->telepon }}
                                    </p>
                                    <p class="text-gray-600 text-sm flex items-start">
                                        <i class="bi bi-geo-alt mr-2 text-primary-600 mt-0.5"></i>
                                        {{ $pelanggan->alamat ?? 'Jl. Merdeka No. 123, Jakarta Selatan, DKI Jakarta 12345' }}
                                    </p>
                                </div>
                                <button type="button" class="text-primary-600 hover:text-primary-700 font-medium text-sm px-3 py-1 rounded-lg hover:bg-primary-50 transition-colors">
                                    <i class="bi bi-pencil"></i> Ubah
                                </button>
                            </div>
                        </div>
                        
                        <button type="button" class="text-primary-600 hover:text-primary-700 font-medium text-sm flex items-center hover:bg-primary-50 px-3 py-2 rounded-lg transition-colors">
                            <i class="bi bi-plus-circle mr-2"></i>
                            Tambah Alamat Baru
                        </button>

                        <!-- Hidden fields for address -->
                        <input type="hidden" name="shipping_address" value="{{ $pelanggan->alamat ?? 'Jl. Merdeka No. 123, Jakarta Selatan, DKI Jakarta 12345' }}">
                        <input type="hidden" name="shipping_city" value="Jakarta Selatan">
                        <input type="hidden" name="shipping_province" value="DKI Jakarta">
                        <input type="hidden" name="shipping_postal_code" value="12345">
                    </div>

                    <!-- Metode Pengiriman -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center mb-6">
                            <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-semibold text-sm mr-3">
                                2
                            </div>
                            <h2 class="text-xl font-bold text-gray-900">Metode Pengiriman</h2>
                        </div>
                        
                        <div class="space-y-3">
                            @php
                            $shippingOptions = [
                                ['name' => 'JNE', 'service' => 'REG', 'description' => 'Layanan Reguler', 'cost' => 25000, 'etd' => '2-3 hari', 'icon' => 'bi-truck'],
                                ['name' => 'JNE', 'service' => 'YES', 'description' => 'Yakin Esok Sampai', 'cost' => 45000, 'etd' => '1 hari', 'icon' => 'bi-lightning'],
                                ['name' => 'TIKI', 'service' => 'REG', 'description' => 'Regular Service', 'cost' => 23000, 'etd' => '3-4 hari', 'icon' => 'bi-truck'],
                                ['name' => 'POS', 'service' => 'Paket Kilat', 'description' => 'Layanan Kilat', 'cost' => 20000, 'etd' => '2-4 hari', 'icon' => 'bi-send'],
                            ];
                            @endphp

                            @foreach($shippingOptions as $index => $option)
                            <label class="shipping-option flex items-center justify-between p-4 border-2 border-gray-200 rounded-xl cursor-pointer transition-all hover:border-primary-300 hover:shadow-sm {{ $index === 0 ? 'border-primary-600 bg-primary-50' : '' }}">
                                <div class="flex items-center flex-1">
                                    <input type="radio" 
                                           name="shipping_radio" 
                                           value="{{ $index }}"
                                           data-code="{{ strtolower($option['name']) }}"
                                           data-service="{{ $option['service'] }}"
                                           data-cost="{{ $option['cost'] }}"
                                           onchange="selectShipping(this)"
                                           class="text-primary-600 focus:ring-primary-500 w-5 h-5"
                                           {{ $index === 0 ? 'checked' : '' }}
                                           required>
                                    <div class="ml-4 flex items-center">
                                        <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center mr-4">
                                            <i class="{{ $option['icon'] }} text-primary-600"></i>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">{{ $option['name'] }} - {{ $option['service'] }}</div>
                                            <div class="text-sm text-gray-600">{{ $option['description'] }}</div>
                                            <div class="text-sm text-primary-600 mt-1 flex items-center">
                                                <i class="bi bi-clock mr-1"></i>
                                                Estimasi: {{ $option['etd'] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold text-primary-600">Rp {{ number_format($option['cost'], 0, ',', '.') }}</div>
                                </div>
                            </label>
                            @endforeach
                        </div>

                        <!-- Hidden Fields -->
                        <input type="hidden" name="courier_service" id="courier_service" value="jne" required>
                        <input type="hidden" name="courier_type" id="courier_type" value="REG" required>
                        <input type="hidden" name="shipping_cost" id="shipping_cost" value="25000" required>
                        <input type="hidden" name="selected_items" value="{{ $cartItems->pluck('id_keranjang')->implode(',') }}" required>
                        <input type="hidden" name="notes" value="">
                    </div>
                </div>

                <!-- Right: Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sticky top-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                            <i class="bi bi-receipt mr-2 text-primary-600"></i>
                            Ringkasan Pesanan
                        </h2>

                        <!-- Products -->
                        <div class="space-y-4 mb-6 max-h-64 overflow-y-auto">
                            @foreach($cartItems as $item)
                            @php
                                $product = $item->product;
                                $variant = $item->productVariant;
                                $harga = $variant ? $variant->harga : $product->harga;
                                $imageUrl = $product->images->first()->path ?? 'placeholder.png';
                            @endphp
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                <img src="{{ asset('storage/' . $imageUrl) }}" 
                                     alt="{{ $product->nama_produk }}"
                                     class="w-12 h-12 object-cover rounded-lg border border-gray-200">
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-900 text-sm truncate">{{ $product->nama_produk }}</h4>
                                    @if($variant)
                                    <p class="text-xs text-gray-600 mt-1">{{ $variant->nama_varian }}</p>
                                    @endif
                                    <div class="flex justify-between items-center mt-1">
                                        <p class="text-xs text-gray-600">Qty: {{ $item->jumlah }}</p>
                                        <p class="font-bold text-primary-600 text-sm">Rp {{ number_format($harga, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Price Breakdown -->
                        <div class="border-t border-gray-200 pt-4 space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal ({{ $cartItems->count() }} item)</span>
                                <span class="font-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Diskon</span>
                                <span class="font-semibold text-green-600">-Rp 50.000</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Ongkos Kirim</span>
                                <span class="font-semibold" id="display-shipping">Rp 25.000</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Biaya Admin</span>
                                <span class="font-semibold">Rp 2.500</span>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="border-t-2 border-gray-300 pt-4 mt-4">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-lg font-bold text-gray-900">Total Pembayaran</span>
                                <span class="text-2xl font-bold text-primary-600" id="display-total">
                                    Rp {{ number_format($subtotal + 25000 - 50000 + 2500, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <!-- Security Info -->
                        <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4">
                            <div class="flex items-center text-green-700 text-sm">
                                <i class="bi bi-shield-check mr-2"></i>
                                <span>Transaksi aman dengan enkripsi SSL</span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                id="submit-btn"
                                class="w-full bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-bold py-4 rounded-xl transition-all duration-200 text-lg shadow-lg shadow-primary-200 hover:shadow-xl hover:shadow-primary-300 transform hover:-translate-y-0.5">
                            <i class="bi bi-credit-card mr-2"></i>
                            Lanjut ke Pembayaran
                        </button>

                        <!-- Back to Cart -->
                        <a href="{{ route('cart.index') }}" 
                           class="block text-center text-gray-600 hover:text-primary-600 mt-4 text-sm font-medium transition-colors">
                            <i class="bi bi-arrow-left mr-1"></i>
                            Kembali ke Keranjang
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Midtrans Snap -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
// Variables
const subtotal = {{ $subtotal }};
let shippingCost = 25000; // Default JNE REG
const discount = 50000;
const adminFee = 2500;

// Select Shipping
function selectShipping(radio) {
    const code = radio.dataset.code;
    const service = radio.dataset.service;
    const cost = parseInt(radio.dataset.cost);
    
    document.getElementById('courier_service').value = code;
    document.getElementById('courier_type').value = service;
    document.getElementById('shipping_cost').value = cost;
    
    shippingCost = cost;
    updateTotal();
    
    // Update visual
    document.querySelectorAll('.shipping-option').forEach(opt => {
        opt.classList.remove('border-primary-600', 'bg-primary-50');
        opt.classList.add('border-gray-200');
    });
    radio.closest('.shipping-option').classList.remove('border-gray-200');
    radio.closest('.shipping-option').classList.add('border-primary-600', 'bg-primary-50');
    
    // Update step indicator
    const step2 = document.querySelector('.shipping-option input:checked').closest('.bg-white').querySelector('.w-8.h-8.bg-gray-300');
    if (step2) {
        step2.classList.remove('bg-gray-300', 'text-gray-600');
        step2.classList.add('bg-green-500', 'text-white');
        step2.innerHTML = '<i class="bi bi-check-lg"></i>';
    }
}

function updateTotal() {
    const total = subtotal + shippingCost - discount + adminFee;
    document.getElementById('display-shipping').textContent = 'Rp ' + shippingCost.toLocaleString('id-ID');
    document.getElementById('display-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

// Handle form submission with AJAX for Snap payment
document.getElementById('checkout-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submit-btn');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<div class="flex items-center justify-center"><svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Memproses...</div>';
    
    try {
        const formData = new FormData(this);
        
        const response = await fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success && data.snap_token) {
            snap.pay(data.snap_token, {
                onSuccess: function(result) {
                    console.log('Payment success:', result);
                    const finishUrl = '{{ route("payment.finish") }}?order_id=' + result.order_id;
                    window.location.href = finishUrl;
                },
                onPending: function(result) {
                    console.log('Payment pending:', result);
                    const finishUrl = '{{ route("payment.finish") }}?order_id=' + result.order_id;
                    window.location.href = finishUrl;
                },
                onError: function(result) {
                    console.log('Payment error:', result);
                    alert('Pembayaran gagal: ' + (result.status_message || 'Silakan coba lagi'));
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                },
                onClose: function() {
                    console.log('Payment popup closed');
                    window.location.href = `/orders/${data.order_number}`;
                }
            });
        } else {
            throw new Error(data.message || 'Terjadi kesalahan');
        }
    } catch (error) {
        alert('Terjadi kesalahan: ' + error.message);
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});

// Initialize
updateTotal();
</script>
@endsection
