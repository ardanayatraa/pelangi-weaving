@extends('layouts.customer')

@section('title', 'Pembayaran DP Custom Order')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-2xl mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Pembayaran DP</h1>
            <p class="text-gray-600">{{ $customOrder->nomor_custom_order }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8">
            <!-- Order Summary -->
            <div class="border-b border-gray-200 pb-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Pesanan</h2>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nama Custom:</span>
                        <span class="font-medium text-gray-900">{{ $customOrder->nama_custom }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jenis:</span>
                        <span class="font-medium text-gray-900">{{ $customOrder->jenis->nama_jenis }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jumlah:</span>
                        <span class="font-medium text-gray-900">{{ $customOrder->jumlah }} pcs</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Harga Total:</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($customOrder->harga_final, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="flex justify-between text-lg font-semibold text-red-600 pt-2 border-t">
                        <span>DP (50%):</span>
                        <span>Rp {{ number_format($customOrder->dp_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-start gap-3">
                    <i class="bi bi-info-circle text-blue-600 text-xl mt-0.5"></i>
                    <div>
                        <h3 class="font-medium text-blue-900 mb-2">Informasi Pembayaran DP</h3>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>• DP sebesar 50% dari total harga untuk memulai produksi</li>
                            <li>• Sisa pembayaran dilakukan saat produk selesai</li>
                            <li>• Produksi akan dimulai setelah DP terkonfirmasi</li>
                            <li>• Anda akan mendapat update progress secara berkala</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Pilih Metode Pembayaran</h3>
                
                <div class="space-y-3">
                    <!-- Bank Transfer -->
                    <label class="relative cursor-pointer">
                        <input type="radio" name="payment_method" value="bank_transfer" class="sr-only peer" checked>
                        <div class="p-4 border-2 border-gray-200 rounded-lg peer-checked:border-red-500 peer-checked:bg-red-50 hover:border-gray-300 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="bi bi-bank text-blue-600 text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Transfer Bank</h4>
                                    <p class="text-sm text-gray-600">Transfer ke rekening bank kami</p>
                                </div>
                            </div>
                        </div>
                    </label>

                    <!-- E-Wallet -->
                    <label class="relative cursor-pointer">
                        <input type="radio" name="payment_method" value="ewallet" class="sr-only peer">
                        <div class="p-4 border-2 border-gray-200 rounded-lg peer-checked:border-red-500 peer-checked:bg-red-50 hover:border-gray-300 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i class="bi bi-wallet2 text-green-600 text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">E-Wallet</h4>
                                    <p class="text-sm text-gray-600">OVO, GoPay, DANA, LinkAja</p>
                                </div>
                            </div>
                        </div>
                    </label>

                    <!-- Credit Card -->
                    <label class="relative cursor-pointer">
                        <input type="radio" name="payment_method" value="credit_card" class="sr-only peer">
                        <div class="p-4 border-2 border-gray-200 rounded-lg peer-checked:border-red-500 peer-checked:bg-red-50 hover:border-gray-300 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="bi bi-credit-card text-purple-600 text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Kartu Kredit/Debit</h4>
                                    <p class="text-sm text-gray-600">Visa, Mastercard, JCB</p>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Bank Transfer Details (shown by default) -->
            <div id="bankTransferDetails" class="bg-gray-50 rounded-lg p-4 mb-6">
                <h4 class="font-medium text-gray-900 mb-3">Detail Transfer Bank</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Bank:</span>
                        <span class="font-medium">BCA</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">No. Rekening:</span>
                        <span class="font-medium">1234567890</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Atas Nama:</span>
                        <span class="font-medium">Pelangi Traditional Weaving</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jumlah Transfer:</span>
                        <span class="font-medium text-red-600">Rp {{ number_format($customOrder->dp_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
                
                <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded">
                    <p class="text-sm text-yellow-800">
                        <i class="bi bi-exclamation-triangle mr-1"></i>
                        Pastikan jumlah transfer sesuai dengan nominal yang tertera untuk mempercepat konfirmasi.
                    </p>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="border-t border-gray-200 pt-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pelanggan</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                        <p class="text-sm text-gray-900">{{ $customOrder->pelanggan->nama }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <p class="text-sm text-gray-900">{{ $customOrder->pelanggan->email }}</p>
                    </div>
                    
                    @if($customOrder->pelanggan->telepon)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                            <p class="text-sm text-gray-900">{{ $customOrder->pelanggan->telepon }}</p>
                        </div>
                    @endif
                    
                    @if($customOrder->pelanggan->whatsapp)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp</label>
                            <p class="text-sm text-gray-900">{{ $customOrder->pelanggan->whatsapp }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('custom-orders.show', $customOrder->nomor_custom_order) }}" 
                   class="flex-1 px-6 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition text-center font-medium">
                    Kembali
                </a>
                
                <button type="button" 
                        onclick="processPayment()"
                        id="pay-button"
                        class="flex-1 px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                    <i class="bi bi-credit-card mr-2"></i>Bayar Sekarang
                </button>
            </div>

            <!-- Payment Instructions -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h4 class="font-medium text-gray-900 mb-2">Langkah Pembayaran:</h4>
                <ol class="text-sm text-gray-600 space-y-1 list-decimal list-inside">
                    <li>Pilih metode pembayaran yang diinginkan</li>
                    <li>Klik "Bayar Sekarang" untuk melanjutkan</li>
                    <li>Ikuti instruksi pembayaran yang muncul</li>
                    <li>Selesaikan pembayaran sesuai nominal yang tertera</li>
                    <li>Simpan bukti pembayaran untuk konfirmasi</li>
                    <li>Produksi akan dimulai setelah pembayaran terkonfirmasi</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Midtrans Snap JS -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
// Handle payment method selection
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const bankTransferDetails = document.getElementById('bankTransferDetails');
    
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            if (this.value === 'bank_transfer') {
                bankTransferDetails.style.display = 'block';
            } else {
                bankTransferDetails.style.display = 'none';
            }
        });
    });
});

function processPayment() {
    const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;
    const payButton = document.getElementById('pay-button');
    
    // Disable button to prevent double click
    payButton.disabled = true;
    payButton.innerHTML = '<i class="bi bi-hourglass-split mr-2"></i>Memproses...';
    
    if (selectedMethod === 'bank_transfer') {
        // Show bank transfer instructions
        alert('Silakan transfer ke rekening yang tertera. Setelah transfer, hubungi kami untuk konfirmasi pembayaran.');
        
        // Redirect to order detail page
        window.location.href = "{{ route('custom-orders.show', $customOrder->nomor_custom_order) }}";
        return;
    }
    
    // For e-wallet and credit card, use Midtrans
    fetch("{{ route('custom-orders.payment.process', $customOrder->nomor_custom_order) }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            payment_method: selectedMethod
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.snap_token) {
            // Open Midtrans Snap popup
            snap.pay(data.snap_token, {
                onSuccess: function(result) {
                    console.log('Payment success:', result);
                    window.location.href = "{{ route('custom-orders.payment.finish', $customOrder->nomor_custom_order) }}?order_id=" + result.order_id;
                },
                onPending: function(result) {
                    console.log('Payment pending:', result);
                    window.location.href = "{{ route('custom-orders.show', $customOrder->nomor_custom_order) }}";
                },
                onError: function(result) {
                    console.log('Payment error:', result);
                    alert('Pembayaran gagal: ' + (result.status_message || 'Terjadi kesalahan'));
                    // Re-enable button
                    payButton.disabled = false;
                    payButton.innerHTML = '<i class="bi bi-credit-card mr-2"></i>Bayar Sekarang';
                },
                onClose: function() {
                    console.log('Payment popup closed');
                    // Show recovery notification
                    showPaymentRecovery();
                    // Re-enable button
                    payButton.disabled = false;
                    payButton.innerHTML = '<i class="bi bi-credit-card mr-2"></i>Bayar Sekarang';
                }
            });
        } else {
            alert('Gagal memproses pembayaran: ' + (data.message || 'Terjadi kesalahan'));
            // Re-enable button
            payButton.disabled = false;
            payButton.innerHTML = '<i class="bi bi-credit-card mr-2"></i>Bayar Sekarang';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memproses pembayaran');
        // Re-enable button
        payButton.disabled = false;
        payButton.innerHTML = '<i class="bi bi-credit-card mr-2"></i>Bayar Sekarang';
    });
}

function showPaymentRecovery() {
    const recoveryNotification = document.createElement('div');
    recoveryNotification.className = 'fixed top-4 right-4 max-w-sm p-4 border-l-4 rounded-lg shadow-lg z-50 bg-blue-100 border-blue-500 text-blue-700';
    recoveryNotification.innerHTML = `
        <div class="flex items-start">
            <i class="bi bi-info-circle-fill text-xl mr-3 mt-0.5"></i>
            <div class="flex-1">
                <p class="font-semibold mb-2">Pembayaran Tertunda</p>
                <p class="text-sm mb-3">Anda dapat melanjutkan pembayaran kapan saja melalui halaman detail custom order.</p>
                <div class="flex gap-2">
                    <button onclick="this.parentElement.parentElement.parentElement.parentElement.remove()" 
                            class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded transition">
                        Tutup
                    </button>
                    <a href="{{ route('custom-orders.show', $customOrder->nomor_custom_order) }}" 
                       class="text-xs bg-white hover:bg-blue-50 text-blue-700 border border-blue-300 px-3 py-1 rounded transition">
                        Kembali ke Detail
                    </a>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(recoveryNotification);
    
    // Auto remove after 10 seconds
    setTimeout(() => {
        if (recoveryNotification.parentNode) {
            recoveryNotification.parentNode.removeChild(recoveryNotification);
        }
    }, 10000);
}
</script>
@endpush
@endsection