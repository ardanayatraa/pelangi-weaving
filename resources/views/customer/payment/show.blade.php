@extends('layouts.customer')

@section('title', 'Pembayaran - Pelangi Weaving')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Pembayaran</h1>
            <p class="text-gray-600">Selesaikan pembayaran untuk melanjutkan pesanan Anda</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Payment Status & Timer -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Payment Status Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    @if($order->payment->status_bayar == 'paid')
                    <!-- Success State -->
                    <div class="text-center py-8">
                        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="bi bi-check-lg text-green-600 text-3xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-green-600 mb-2">Pembayaran Berhasil!</h2>
                        <p class="text-gray-600 mb-4">Terima kasih, pembayaran Anda telah diterima dan sedang diproses</p>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center justify-center text-green-700">
                                <i class="bi bi-info-circle mr-2"></i>
                                <span class="text-sm">Pesanan Anda akan segera diproses dan dikirim</span>
                            </div>
                        </div>
                    </div>
                    @elseif($order->payment->status_bayar == 'pending')
                    <!-- Pending State with Timer -->
                    <div class="text-center py-8">
                        <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="bi bi-clock text-yellow-600 text-3xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Menunggu Pembayaran</h2>
                        <p class="text-gray-600 mb-6">Silakan lakukan pembayaran sebelum batas waktu berakhir</p>
                        
                        <!-- Countdown Timer -->
                        <div class="bg-yellow-50 border-2 border-yellow-200 rounded-xl p-6 mb-6">
                            <p class="text-sm text-yellow-800 mb-3 font-medium">Batas waktu pembayaran</p>
                            <div class="flex items-center justify-center space-x-4 text-2xl font-bold text-yellow-600" id="countdown-timer">
                                <div class="text-center">
                                    <div class="bg-yellow-600 text-white rounded-lg px-3 py-2 min-w-[60px]" id="hours">23</div>
                                    <div class="text-xs text-yellow-700 mt-1">JAM</div>
                                </div>
                                <div class="text-yellow-600">:</div>
                                <div class="text-center">
                                    <div class="bg-yellow-600 text-white rounded-lg px-3 py-2 min-w-[60px]" id="minutes">45</div>
                                    <div class="text-xs text-yellow-700 mt-1">MENIT</div>
                                </div>
                                <div class="text-yellow-600">:</div>
                                <div class="text-center">
                                    <div class="bg-yellow-600 text-white rounded-lg px-3 py-2 min-w-[60px]" id="seconds">32</div>
                                    <div class="text-xs text-yellow-700 mt-1">DETIK</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mb-6">
                            <p class="text-sm text-gray-600 mb-2">Order ID: <span class="font-mono font-semibold">{{ $order->nomor_invoice }}</span></p>
                        </div>
                    </div>
                    @else
                    <!-- Unpaid State -->
                    <div class="text-center py-8">
                        <div class="w-20 h-20 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="bi bi-credit-card text-primary-600 text-3xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Siap untuk Pembayaran</h2>
                        <p class="text-gray-600 mb-6">Pilih metode pembayaran yang Anda inginkan</p>
                    </div>
                    @endif

                    <!-- Payment Methods (only show if not paid) -->
                    @if(in_array($order->payment->status_bayar, ['unpaid', 'pending']) && $order->status_pesanan != 'batal')
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Pilih Metode Pembayaran</h3>
                        
                        <!-- Payment Method Options -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <!-- GoPay -->
                            <div class="payment-method border-2 border-gray-200 rounded-xl p-4 hover:border-primary-300 cursor-pointer transition-all">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                        <i class="bi bi-wallet2 text-green-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">GoPay</h4>
                                        <p class="text-sm text-gray-600">E-Wallet Payment</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Bank Transfer -->
                            <div class="payment-method border-2 border-gray-200 rounded-xl p-4 hover:border-primary-300 cursor-pointer transition-all">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="bi bi-bank text-blue-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Transfer Bank</h4>
                                        <p class="text-sm text-gray-600">Virtual Account</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Credit Card -->
                            <div class="payment-method border-2 border-gray-200 rounded-xl p-4 hover:border-primary-300 cursor-pointer transition-all">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <i class="bi bi-credit-card text-purple-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Kartu Kredit</h4>
                                        <p class="text-sm text-gray-600">Visa, Mastercard</p>
                                    </div>
                                </div>
                            </div>

                            <!-- OVO -->
                            <div class="payment-method border-2 border-gray-200 rounded-xl p-4 hover:border-primary-300 cursor-pointer transition-all">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                                        <i class="bi bi-phone text-indigo-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">OVO</h4>
                                        <p class="text-sm text-gray-600">E-Wallet Payment</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pay Button -->
                        <button type="button" 
                                id="pay-button"
                                class="w-full bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-bold py-4 rounded-xl transition-all duration-200 text-lg shadow-lg shadow-primary-200 hover:shadow-xl hover:shadow-primary-300 transform hover:-translate-y-0.5">
                            <i class="bi bi-credit-card mr-2"></i>
                            Bayar Sekarang
                        </button>
                    </div>
                    @endif
                </div>

                <!-- Security Info -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="bi bi-shield-check text-green-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Keamanan Terjamin</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                        <div class="flex items-center">
                            <i class="bi bi-check-circle text-green-500 mr-2"></i>
                            <span>Enkripsi SSL 256-bit</span>
                        </div>
                        <div class="flex items-center">
                            <i class="bi bi-check-circle text-green-500 mr-2"></i>
                            <span>PCI DSS Compliant</span>
                        </div>
                        <div class="flex items-center">
                            <i class="bi bi-check-circle text-green-500 mr-2"></i>
                            <span>Data pribadi aman</span>
                        </div>
                        <div class="flex items-center">
                            <i class="bi bi-check-circle text-green-500 mr-2"></i>
                            <span>Transaksi terpercaya</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sticky top-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="bi bi-receipt mr-2 text-primary-600"></i>
                        Detail Pesanan
                    </h2>

                    <!-- Order Info -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-600">Invoice</span>
                            <span class="font-mono text-sm font-semibold text-gray-900">{{ $order->nomor_invoice }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Tanggal</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $order->tanggal_pesanan->format('d M Y') }}</span>
                        </div>
                    </div>

                    <!-- Products -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Produk</h4>
                        <div class="space-y-3 max-h-48 overflow-y-auto">
                            @foreach($order->items as $item)
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                @if($item->product->primary_image_path)
                                <img src="{{ asset('storage/' . $item->product->primary_image_path) }}" 
                                     alt="{{ $item->product->nama_produk }}"
                                     class="w-12 h-12 object-cover rounded-lg">
                                @else
                                <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <i class="bi bi-image text-gray-400"></i>
                                </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <h5 class="font-semibold text-gray-900 text-sm truncate">{{ $item->product->nama_produk }}</h5>
                                    <div class="flex justify-between items-center mt-1">
                                        <p class="text-xs text-gray-600">Qty: {{ $item->jumlah }}</p>
                                        <p class="font-bold text-primary-600 text-sm">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Breakdown -->
                    <div class="border-t border-gray-200 pt-4 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-semibold">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Ongkos Kirim</span>
                            <span class="font-semibold">Rp {{ number_format($order->ongkir, 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t-2 border-gray-300 pt-3">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-gray-900">Total</span>
                                <span class="text-2xl font-bold text-primary-600">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 space-y-3">
                        @if($order->payment->status_bayar == 'paid')
                        <a href="{{ route('orders.show', $order->nomor_invoice) }}" 
                           class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-xl transition-colors">
                            <i class="bi bi-eye mr-2"></i>
                            Lihat Detail Pesanan
                        </a>
                        @else
                        <button type="button" 
                                id="retry-payment-button"
                                class="block w-full text-center bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 rounded-xl transition-colors mb-2">
                            <i class="bi bi-credit-card mr-2"></i>
                            Lanjutkan Pembayaran
                        </button>
                        <button type="button" 
                                id="refresh-token-button"
                                class="block w-full text-center border-2 border-primary-300 text-primary-600 hover:bg-primary-50 font-semibold py-3 rounded-xl transition-colors mb-2">
                            <i class="bi bi-arrow-clockwise mr-2"></i>
                            Perbarui Token Pembayaran
                        </button>
                        <a href="{{ route('payment.show', $order->nomor_invoice) }}" 
                           class="block w-full text-center border-2 border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold py-3 rounded-xl transition-colors">
                            <i class="bi bi-arrow-clockwise mr-2"></i>
                            Refresh Status
                        </a>
                        @endif
                        
                        <a href="{{ route('orders.index') }}" 
                           class="block w-full text-center text-gray-600 hover:text-primary-600 font-medium py-2 transition-colors">
                            <i class="bi bi-arrow-left mr-1"></i>
                            Kembali ke Pesanan
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Midtrans Snap -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" 
        data-client-key="{{ config('midtrans.client_key') }}"
        onerror="handleSnapLoadError()"
        onload="handleSnapLoadSuccess()"></script>

<script>
// Handle Snap.js loading
function handleSnapLoadError() {
    console.error('Failed to load Midtrans Snap.js');
    showPaymentResult('error', 'Gagal memuat sistem pembayaran. Silakan refresh halaman.');
}

function handleSnapLoadSuccess() {
    console.log('Midtrans Snap.js loaded successfully');
}

// Check if snap token is available
@if(isset($order->payment->snap_token) && $order->payment->snap_token)
window.snapToken = '{{ $order->payment->snap_token }}';
console.log('Snap token loaded:', window.snapToken ? 'Available' : 'Missing');
@else
console.warn('No snap token available for this order');
@endif
// Countdown Timer
@if($order->payment->status_bayar == 'pending')
function startCountdown() {
    // Set countdown to 24 hours from now (you can adjust this based on your business logic)
    const countdownDate = new Date().getTime() + (24 * 60 * 60 * 1000);
    
    const timer = setInterval(function() {
        const now = new Date().getTime();
        const distance = countdownDate - now;
        
        if (distance < 0) {
            clearInterval(timer);
            document.getElementById("countdown-timer").innerHTML = '<div class="text-red-600 font-bold">EXPIRED</div>';
            return;
        }
        
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        document.getElementById("hours").innerHTML = hours.toString().padStart(2, '0');
        document.getElementById("minutes").innerHTML = minutes.toString().padStart(2, '0');
        document.getElementById("seconds").innerHTML = seconds.toString().padStart(2, '0');
    }, 1000);
}

// Start countdown when page loads
document.addEventListener('DOMContentLoaded', function() {
    startCountdown();
});
@endif

// Payment Method Selection
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethods = document.querySelectorAll('.payment-method');
    
    paymentMethods.forEach(method => {
        method.addEventListener('click', function() {
            // Remove active class from all methods
            paymentMethods.forEach(m => {
                m.classList.remove('border-primary-600', 'bg-primary-50');
                m.classList.add('border-gray-200');
            });
            
            // Add active class to selected method
            this.classList.remove('border-gray-200');
            this.classList.add('border-primary-600', 'bg-primary-50');
        });
    });
});

// Payment Button Handler
const payButton = document.getElementById('pay-button');
const retryPaymentButton = document.getElementById('retry-payment-button');

// Function to handle payment process
function processPayment() {
    const button = event.target;
    const originalText = button.innerHTML;
    
    // Show loading state
    button.disabled = true;
    button.innerHTML = '<div class="flex items-center justify-center"><svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Memproses...</div>';
    
    // Initialize Midtrans Snap
    const snapToken = window.snapToken || '{{ $order->payment->snap_token ?? "" }}';
    
    // Check if snap token exists
    if (!snapToken) {
        console.error('Snap token is missing');
        showPaymentResult('error', 'Token pembayaran tidak tersedia. Silakan perbarui token.');
        button.disabled = false;
        button.innerHTML = originalText;
        return;
    }
    
    // Check if snap is loaded
    if (typeof snap === 'undefined') {
        console.error('Midtrans Snap is not loaded');
        showPaymentResult('error', 'Sistem pembayaran belum siap. Silakan refresh halaman.');
        button.disabled = false;
        button.innerHTML = originalText;
        return;
    }
    
    snap.pay(snapToken, {
        onSuccess: function(result) {
            console.log('=== PAYMENT SUCCESS ===');
            console.log('Result:', result);
            console.log('Order ID:', result.order_id);
            console.log('Transaction Status:', result.transaction_status);
            console.log('Payment Type:', result.payment_type);
            
            // Show success message
            showPaymentResult('success', 'Pembayaran berhasil! Terima kasih atas pesanan Anda.');
            
            // Redirect dengan order_id dari Midtrans
            setTimeout(() => {
                const finishUrl = '{{ route("payment.finish") }}?order_id=' + result.order_id;
                console.log('Redirecting to:', finishUrl);
                window.location.href = finishUrl;
            }, 2000);
        },
        onPending: function(result) {
            console.log('=== PAYMENT PENDING ===');
            console.log('Result:', result);
            console.log('Order ID:', result.order_id);
            
            // Show pending message
            showPaymentResult('pending', 'Pembayaran sedang diproses. Mohon tunggu konfirmasi.');
            
            setTimeout(() => {
                const finishUrl = '{{ route("payment.finish") }}?order_id=' + result.order_id;
                console.log('Redirecting to:', finishUrl);
                window.location.href = finishUrl;
            }, 2000);
        },
        onError: function(result) {
            console.log('=== PAYMENT ERROR ===');
            console.log('Result:', result);
            console.log('Status Message:', result.status_message);
            
            // Show error message with recovery options
            const errorMessage = result.status_message || 'Silakan coba lagi';
            showPaymentResult('error', 'Pembayaran gagal: ' + errorMessage);
            
            // If token expired, suggest refresh
            if (errorMessage.toLowerCase().includes('expired') || errorMessage.toLowerCase().includes('invalid')) {
                setTimeout(() => {
                    showTokenExpiredMessage();
                }, 2000);
            }
            
            // Reset button
            button.disabled = false;
            button.innerHTML = originalText;
        },
        onClose: function() {
            console.log('=== PAYMENT POPUP CLOSED ===');
            console.log('User closed the payment popup');
            
            // Reset button
            button.disabled = false;
            button.innerHTML = originalText;
            
            // Show recovery message with option to retry
            showPaymentRecoveryMessage();
        }
    });
}

// Add event listeners for both buttons
if (payButton) {
    payButton.addEventListener('click', processPayment);
}

if (retryPaymentButton) {
    retryPaymentButton.addEventListener('click', processPayment);
}

// Refresh token button handler
const refreshTokenButton = document.getElementById('refresh-token-button');
if (refreshTokenButton) {
    refreshTokenButton.addEventListener('click', function() {
        const originalText = this.innerHTML;
        this.disabled = true;
        this.innerHTML = '<div class="flex items-center justify-center"><svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Memperbarui...</div>';
        
        fetch('{{ route("payment.refresh", $order->nomor_invoice) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showPaymentResult('success', 'Token pembayaran berhasil diperbarui!');
                // Update the snap token in the page
                window.snapToken = data.snap_token;
            } else {
                showPaymentResult('error', data.message || 'Gagal memperbarui token');
            }
        })
        .catch(error => {
            console.error('Error refreshing token:', error);
            showPaymentResult('error', 'Terjadi kesalahan saat memperbarui token');
        })
        .finally(() => {
            this.disabled = false;
            this.innerHTML = originalText;
        });
    });
}

// Show payment result notification
function showPaymentResult(type, message) {
    const colors = {
        success: 'bg-green-100 border-green-500 text-green-700',
        error: 'bg-red-100 border-red-500 text-red-700',
        pending: 'bg-yellow-100 border-yellow-500 text-yellow-700',
        info: 'bg-blue-100 border-blue-500 text-blue-700'
    };
    
    const icons = {
        success: 'bi-check-circle-fill',
        error: 'bi-x-circle-fill',
        pending: 'bi-clock-fill',
        info: 'bi-info-circle-fill'
    };
    
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 max-w-sm p-4 border-l-4 rounded-lg shadow-lg z-50 ${colors[type]}`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="bi ${icons[type]} text-xl mr-3"></i>
            <div>
                <p class="font-semibold">${message}</p>
            </div>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 5000);
}

// Show payment recovery message when popup is closed
function showPaymentRecoveryMessage() {
    const recoveryModal = document.createElement('div');
    recoveryModal.className = 'fixed inset-0 z-[9999] overflow-y-auto flex items-center justify-center p-4 bg-black bg-opacity-50';
    recoveryModal.innerHTML = `
        <div class="relative bg-white rounded-xl shadow-2xl max-w-md w-full overflow-hidden border-4 border-blue-500">
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-blue-200">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                        <i class="bi bi-info-circle text-white text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Pembayaran Tertunda</h3>
                        <p class="text-sm text-gray-600">Popup pembayaran ditutup</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <p class="text-gray-700 mb-4">
                    Jangan khawatir! Anda dapat melanjutkan pembayaran kapan saja. 
                    Pesanan Anda masih tersimpan dan menunggu pembayaran.
                </p>
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
                    <p class="text-sm text-blue-800">
                        <i class="bi bi-lightbulb mr-1"></i>
                        <strong>Tips:</strong> Anda dapat kembali ke halaman ini melalui menu "Pesanan Saya" untuk melanjutkan pembayaran.
                    </p>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 flex justify-end gap-3">
                <button onclick="closeRecoveryModal()" 
                        class="px-5 py-2.5 bg-white hover:bg-gray-100 text-gray-700 font-semibold rounded-lg border border-gray-300 transition">
                    Nanti Saja
                </button>
                <button onclick="retryPaymentFromModal()" 
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition hover:shadow-lg">
                    <i class="bi bi-credit-card mr-2"></i>
                    Coba Lagi
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(recoveryModal);
    
    // Add global functions for modal actions
    window.closeRecoveryModal = function() {
        if (recoveryModal.parentNode) {
            recoveryModal.parentNode.removeChild(recoveryModal);
        }
    };
    
    window.retryPaymentFromModal = function() {
        closeRecoveryModal();
        // Trigger payment process again
        const retryButton = document.getElementById('retry-payment-button') || document.getElementById('pay-button');
        if (retryButton) {
            retryButton.click();
        }
    };
}

// Show token expired message
function showTokenExpiredMessage() {
    const expiredModal = document.createElement('div');
    expiredModal.className = 'fixed inset-0 z-[9999] overflow-y-auto flex items-center justify-center p-4 bg-black bg-opacity-50';
    expiredModal.innerHTML = `
        <div class="relative bg-white rounded-xl shadow-2xl max-w-md w-full overflow-hidden border-4 border-orange-500">
            <div class="bg-gradient-to-r from-orange-50 to-orange-100 px-6 py-4 border-b border-orange-200">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center">
                        <i class="bi bi-exclamation-triangle text-white text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Token Pembayaran Kedaluwarsa</h3>
                        <p class="text-sm text-gray-600">Perlu memperbarui token</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <p class="text-gray-700 mb-4">
                    Token pembayaran Anda telah kedaluwarsa. Silakan perbarui token untuk melanjutkan pembayaran.
                </p>
                
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 mb-4">
                    <p class="text-sm text-orange-800">
                        <i class="bi bi-lightbulb mr-1"></i>
                        <strong>Tips:</strong> Token pembayaran berlaku selama 24 jam. Setelah itu perlu diperbarui.
                    </p>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 flex justify-end gap-3">
                <button onclick="closeExpiredModal()" 
                        class="px-5 py-2.5 bg-white hover:bg-gray-100 text-gray-700 font-semibold rounded-lg border border-gray-300 transition">
                    Tutup
                </button>
                <button onclick="refreshTokenFromModal()" 
                        class="px-5 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg transition hover:shadow-lg">
                    <i class="bi bi-arrow-clockwise mr-2"></i>
                    Perbarui Token
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(expiredModal);
    
    // Add global functions for modal actions
    window.closeExpiredModal = function() {
        if (expiredModal.parentNode) {
            expiredModal.parentNode.removeChild(expiredModal);
        }
    };
    
    window.refreshTokenFromModal = function() {
        closeExpiredModal();
        // Trigger refresh token button
        const refreshButton = document.getElementById('refresh-token-button');
        if (refreshButton) {
            refreshButton.click();
        }
    };
}

// Auto refresh status every 30 seconds for pending payments
@if($order->payment->status_bayar == 'pending')
setInterval(function() {
    // Check if we're still on the same page
    if (window.location.pathname.includes('payment')) {
        fetch(window.location.href, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(response => {
            if (response.ok) {
                // If status changed, reload the page
                response.text().then(html => {
                    if (!html.includes('Menunggu Pembayaran')) {
                        window.location.reload();
                    }
                });
            }
        }).catch(error => {
            console.log('Status check failed:', error);
        });
    }
}, 30000); // Check every 30 seconds
@endif
</script>
@endsection
