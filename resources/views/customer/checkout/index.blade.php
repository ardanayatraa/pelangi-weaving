@extends('layouts.customer')

@section('title', 'Checkout - Pelangi Weaving')

@section('content')
<!-- Breadcrumb -->
<div class="bg-white py-4 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center text-sm text-gray-600">
            <a href="{{ route('home') }}" class="hover:text-red-600">Beranda</a>
            <i class="bi bi-chevron-right mx-2 text-xs"></i>
            <a href="{{ route('cart.index') }}" class="hover:text-red-600">Keranjang</a>
            <i class="bi bi-chevron-right mx-2 text-xs"></i>
            <span class="text-gray-900 font-medium">Checkout</span>
        </div>
    </div>
</div>

<form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
    @csrf
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-4 md:space-y-6">
                <!-- Customer Info -->
                <div class="bg-white rounded-lg border border-gray-200 p-4 md:p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <i class="bi bi-person-circle text-red-600 mr-2"></i>
                        Informasi Pembeli
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap
                            </label>
                            <input type="text" 
                                   value="{{ $pelanggan->nama }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50" 
                                   readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                No. Telepon
                            </label>
                            <input type="tel" 
                                   value="{{ $pelanggan->telepon }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50" 
                                   readonly>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email
                            </label>
                            <input type="email" 
                                   value="{{ $pelanggan->email }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50" 
                                   readonly>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <i class="bi bi-geo-alt-fill text-orange-500 mr-2"></i>
                        Alamat Pengiriman
                    </h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Alamat Lengkap <span class="text-red-500">*</span>
                            </label>
                            <textarea name="shipping_address" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                      placeholder="Jalan, nomor rumah, RT/RW, kelurahan..."
                                      required>{{ old('shipping_address', $pelanggan->alamat) }}</textarea>
                        </div>

                        <!-- Destination Search -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Cari Kota/Kecamatan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       id="destination-search"
                                       placeholder="Ketik minimal 1 kata daerah... (contoh: Jakarta, Denpasar)"
                                       class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                       autocomplete="off">
                                
                                <!-- Loading -->
                                <div id="search-loading" class="hidden absolute right-3 top-3">
                                    <svg class="animate-spin h-5 w-5 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>

                                <!-- Results Dropdown -->
                                <div id="search-results" class="hidden absolute z-50 w-full mt-2 bg-white border border-gray-200 rounded-lg shadow-xl max-h-80 overflow-y-auto scrollbar-thin scrollbar-thumb-orange-400 scrollbar-track-gray-100"></div>
                            </div>

                            <!-- Selected Destination -->
                            <div id="selected-destination" class="hidden mt-3 bg-green-50 border-2 border-green-300 rounded-lg p-4">
                                <div class="flex items-start">
                                    <i class="bi bi-check-circle-fill text-green-600 text-xl mr-3 mt-0.5"></i>
                                    <div class="flex-1">
                                        <p class="font-bold text-gray-900" id="selected-name"></p>
                                        <p class="text-sm text-gray-600" id="selected-detail"></p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            <i class="bi bi-mailbox mr-1"></i>
                                            Kode Pos: <span id="selected-zip"></span>
                                        </p>
                                    </div>
                                    <button type="button" onclick="clearDestination()" class="text-red-500 hover:text-red-700">
                                        <i class="bi bi-x-circle text-xl"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Hidden Fields -->
                            <input type="hidden" name="shipping_city" id="shipping_city" required>
                            <input type="hidden" name="shipping_province" id="shipping_province" required>
                            <input type="hidden" name="shipping_postal_code" id="shipping_postal_code" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan (Opsional)
                            </label>
                            <textarea name="notes" rows="2"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                      placeholder="Catatan untuk kurir atau penjual...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Shipping Method -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <i class="bi bi-truck text-orange-500 mr-2"></i>
                        Metode Pengiriman
                    </h2>

                    <!-- No Address -->
                    <div id="no-address-msg" class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
                        <i class="bi bi-info-circle text-gray-400 text-2xl mb-2"></i>
                        <p class="text-sm text-gray-600">Pilih alamat pengiriman terlebih dahulu</p>
                    </div>

                    <!-- Shipping Options -->
                    <div id="shipping-options" class="hidden space-y-3">
                        @php
                        $shippingOptions = [
                            ['name' => 'POS Indonesia', 'code' => 'pos', 'service' => 'Paket Kilat Khusus', 'description' => 'Layanan Kilat', 'cost' => 20000, 'etd' => '2-4 hari'],
                            ['name' => 'Ninja Xpress', 'code' => 'ninja', 'service' => 'REG', 'description' => 'Regular', 'cost' => 21000, 'etd' => '2-4 hari'],
                            ['name' => 'J&T Express', 'code' => 'jnt', 'service' => 'REG', 'description' => 'Regular', 'cost' => 22000, 'etd' => '2-3 hari'],
                            ['name' => 'TIKI', 'code' => 'tiki', 'service' => 'REG', 'description' => 'Regular Service', 'cost' => 23000, 'etd' => '3-4 hari'],
                            ['name' => 'JNE', 'code' => 'jne', 'service' => 'REG', 'description' => 'Layanan Reguler', 'cost' => 25000, 'etd' => '2-3 hari'],
                            ['name' => 'JNE', 'code' => 'jne', 'service' => 'YES', 'description' => 'Yakin Esok Sampai', 'cost' => 45000, 'etd' => '1 hari'],
                            ['name' => 'TIKI', 'code' => 'tiki', 'service' => 'ONS', 'description' => 'Over Night Service', 'cost' => 42000, 'etd' => '1 hari'],
                        ];
                        @endphp

                        @foreach($shippingOptions as $index => $option)
                        <label class="shipping-option flex items-center justify-between p-4 border-2 border-gray-200 rounded-lg cursor-pointer transition-all hover:border-orange-300">
                            <div class="flex items-center flex-1">
                                <input type="radio" 
                                       name="shipping_radio" 
                                       value="{{ $index }}"
                                       data-code="{{ $option['code'] }}"
                                       data-service="{{ $option['service'] }}"
                                       data-cost="{{ $option['cost'] }}"
                                       onchange="selectShipping(this)"
                                       class="text-orange-500 focus:ring-orange-500"
                                       required>
                                <div class="ml-3">
                                    <div class="font-semibold text-gray-900">{{ $option['name'] }} - {{ $option['service'] }}</div>
                                    <div class="text-xs text-gray-600">{{ $option['description'] }}</div>
                                    <div class="text-xs text-orange-600 mt-1">
                                        <i class="bi bi-clock mr-1"></i>
                                        Estimasi: {{ $option['etd'] }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-right ml-4">
                                <div class="text-lg font-bold text-orange-500">Rp {{ number_format($option['cost'], 0, ',', '.') }}</div>
                            </div>
                        </label>
                        @endforeach
                    </div>

                    <!-- Hidden Fields -->
                    <input type="hidden" name="courier_service" id="courier_service" required>
                    <input type="hidden" name="courier_type" id="courier_type" required>
                    <input type="hidden" name="shipping_cost" id="shipping_cost" value="0" required>
                    <input type="hidden" name="selected_items" value="{{ $cartItems->pluck('id_keranjang')->implode(',') }}" required>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg border border-gray-200 p-6 sticky top-20">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Ringkasan Pesanan</h2>

                    <!-- Products -->
                    <div class="space-y-3 mb-4 max-h-60 overflow-y-auto">
                        @foreach($cartItems as $item)
                        @php
                            $product = $item->product;
                            $variant = $item->productVariant;
                            $harga = $variant ? $variant->harga : $product->harga;
                            $imageUrl = $product->gambar_utama ?? 'products/default.jpg';
                        @endphp
                        <div class="flex gap-3">
                            <img src="{{ asset('storage/' . $imageUrl) }}" 
                                 alt="{{ $product->nama_produk }}"
                                 class="w-16 h-16 object-cover rounded border border-gray-200">
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-medium text-gray-900 truncate">{{ $product->nama_produk }}</h4>
                                @if($variant)
                                <div class="mt-1">
                                    <span class="inline-block bg-primary-100 text-primary-700 text-xs font-semibold px-2 py-0.5 rounded">
                                        <i class="bi bi-palette"></i> {{ $variant->nama_varian }}
                                    </span>
                                </div>
                                @endif
                                <p class="text-sm font-semibold text-gray-900 mt-1">
                                    {{ $item->jumlah }} × Rp {{ number_format($harga, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Price Details -->
                    <div class="border-t border-gray-200 pt-4 space-y-2 mb-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal ({{ $cartItems->sum('jumlah') }} barang)</span>
                            <span class="font-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Ongkos Kirim</span>
                            <span class="font-semibold" id="display-shipping">-</span>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="border-t-2 border-gray-300 pt-4 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-900">Total</span>
                            <span class="text-2xl font-bold text-orange-500" id="display-total">
                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <!-- Payment Info -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 mb-4">
                        <div class="flex items-start gap-2">
                            <i class="bi bi-credit-card text-red-600 text-lg mt-0.5"></i>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-blue-900">Pembayaran via Midtrans</p>
                                <p class="text-xs text-blue-700 mt-1">Berbagai metode pembayaran tersedia</p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            id="submit-btn"
                            class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-lg transition-colors shadow-lg">
                        <i class="bi bi-credit-card mr-2"></i>
                        Buat Pesanan
                    </button>

                    <p class="text-xs text-gray-500 mt-3 text-center">
                        Dengan melanjutkan, Anda menyetujui <a href="#" class="text-orange-500 hover:underline">Syarat & Ketentuan</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Midtrans Snap -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
// Variables
const subtotal = {{ $subtotal }};
let shippingCost = 0;
let searchTimeout;

// Destination Search
const searchInput = document.getElementById('destination-search');
const searchLoading = document.getElementById('search-loading');
const searchResults = document.getElementById('search-results');
const selectedDiv = document.getElementById('selected-destination');

searchInput.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const query = this.value.trim();
    
    if (query.length < 3) {
        searchResults.classList.add('hidden');
        return;
    }
    
    searchLoading.classList.remove('hidden');
    
    searchTimeout = setTimeout(async () => {
        try {
            const response = await fetch(`/api/rajaongkir/search?q=${encodeURIComponent(query)}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success && data.data.length > 0) {
                displayResults(data.data);
            } else {
                searchResults.innerHTML = '<div class="p-4 text-center text-gray-500 text-sm">Tidak ada hasil</div>';
                searchResults.classList.remove('hidden');
            }
        } catch (error) {
            console.error('Search error:', error);
        } finally {
            searchLoading.classList.add('hidden');
        }
    }, 500);
});

function displayResults(results) {
    let html = '';
    
    results.forEach((result, index) => {
        html += `
            <button type="button" onclick='selectDestination(${JSON.stringify(result)})' 
                    class="w-full text-left px-4 py-3 hover:bg-orange-50 border-b border-gray-100 last:border-b-0 transition-all duration-200 flex items-center justify-between group">
                <div class="flex-1">
                    <div class="font-semibold text-gray-900 group-hover:text-orange-600 transition-colors">${result.subdistrict_name}</div>
                    <div class="text-sm text-gray-600 mt-0.5">${result.city_name}, ${result.province_name}</div>
                    <div class="text-xs text-gray-500 mt-1 flex items-center">
                        <i class="bi bi-geo-alt-fill text-orange-500 mr-1"></i>
                        Kode Pos: ${result.zip_code}
                    </div>
                </div>
                <i class="bi bi-chevron-right text-gray-400 group-hover:text-orange-500 transition-colors"></i>
            </button>
        `;
    });
    
    searchResults.innerHTML = html;
    searchResults.classList.remove('hidden');
    
    // Scroll to top when results change
    searchResults.scrollTop = 0;
}

function selectDestination(destination) {
    console.log('📍 Selected:', destination);
    
    // Fill hidden fields
    document.getElementById('shipping_city').value = destination.city_name;
    document.getElementById('shipping_province').value = destination.province_name;
    document.getElementById('shipping_postal_code').value = destination.zip_code;
    
    // Show selected
    document.getElementById('selected-name').textContent = destination.subdistrict_name;
    document.getElementById('selected-detail').textContent = `${destination.city_name}, ${destination.province_name}`;
    document.getElementById('selected-zip').textContent = destination.zip_code;
    selectedDiv.classList.remove('hidden');
    
    // Hide search results
    searchResults.classList.add('hidden');
    searchInput.value = destination.label || destination.subdistrict_name;
    
    // Show shipping options - FORCE DISPLAY
    const noAddressMsg = document.getElementById('no-address-msg');
    const shippingOptions = document.getElementById('shipping-options');
    
    if (noAddressMsg) {
        noAddressMsg.style.display = 'none';
        noAddressMsg.classList.add('hidden');
    }
    
    if (shippingOptions) {
        shippingOptions.style.display = 'block';
        shippingOptions.classList.remove('hidden');
    }
    
    console.log('✅ Shipping options shown!');
    console.log('No address msg hidden:', noAddressMsg.classList.contains('hidden'));
    console.log('Shipping options visible:', !shippingOptions.classList.contains('hidden'));
}

function clearDestination() {
    document.getElementById('shipping_city').value = '';
    document.getElementById('shipping_province').value = '';
    document.getElementById('shipping_postal_code').value = '';
    selectedDiv.classList.add('hidden');
    searchInput.value = '';
    
    // Hide shipping options - FORCE HIDE
    const noAddressMsg = document.getElementById('no-address-msg');
    const shippingOptions = document.getElementById('shipping-options');
    
    if (noAddressMsg) {
        noAddressMsg.style.display = 'block';
        noAddressMsg.classList.remove('hidden');
    }
    
    if (shippingOptions) {
        shippingOptions.style.display = 'none';
        shippingOptions.classList.add('hidden');
    }
    
    // Reset shipping selection
    document.querySelectorAll('input[name="shipping_radio"]').forEach(radio => {
        radio.checked = false;
    });
    document.querySelectorAll('.shipping-option').forEach(opt => {
        opt.classList.remove('border-orange-500', 'bg-orange-50');
        opt.classList.add('border-gray-200');
    });
    
    // Reset shipping cost
    shippingCost = 0;
    updateTotal();
}

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
        opt.classList.remove('border-orange-500', 'bg-orange-50');
        opt.classList.add('border-gray-200');
    });
    radio.closest('.shipping-option').classList.remove('border-gray-200');
    radio.closest('.shipping-option').classList.add('border-orange-500', 'bg-orange-50');
    
    console.log('✅ Shipping selected:', code, service, 'Rp', cost.toLocaleString('id-ID'));
}

function updateTotal() {
    const total = subtotal + shippingCost;
    document.getElementById('display-shipping').textContent = shippingCost > 0 ? 'Rp ' + shippingCost.toLocaleString('id-ID') : '-';
    document.getElementById('display-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

// Handle form submission with AJAX for Snap payment
document.getElementById('checkout-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submit-btn');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    
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
                    console.log('=== PAYMENT SUCCESS (CHECKOUT) ===');
                    console.log('Result:', result);
                    console.log('Order ID:', result.order_id);
                    console.log('Transaction Status:', result.transaction_status);
                    console.log('Payment Type:', result.payment_type);
                    
                    // Redirect dengan order_id dari Midtrans
                    const finishUrl = '{{ route("payment.finish") }}?order_id=' + result.order_id;
                    console.log('Redirecting to:', finishUrl);
                    window.location.href = finishUrl;
                },
                onPending: function(result) {
                    console.log('=== PAYMENT PENDING (CHECKOUT) ===');
                    console.log('Result:', result);
                    console.log('Order ID:', result.order_id);
                    
                    const finishUrl = '{{ route("payment.finish") }}?order_id=' + result.order_id;
                    console.log('Redirecting to:', finishUrl);
                    window.location.href = finishUrl;
                },
                onError: function(result) {
                    console.log('=== PAYMENT ERROR (CHECKOUT) ===');
                    console.log('Result:', result);
                    console.log('Status Message:', result.status_message);
                    
                    alert('Pembayaran gagal: ' + (result.status_message || 'Silakan coba lagi'));
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                },
                onClose: function() {
                    console.log('=== PAYMENT POPUP CLOSED (CHECKOUT) ===');
                    console.log('User closed the payment popup');
                    
                    // Redirect ke halaman order
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

// Click outside to close search results
document.addEventListener('click', function(e) {
    if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
        searchResults.classList.add('hidden');
    }
});
</script>
@endsection
