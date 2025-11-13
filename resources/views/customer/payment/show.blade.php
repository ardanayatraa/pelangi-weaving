@extends('layouts.customer')

@section('title', 'Pembayaran - Pelangi Weaving')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Payment Card -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-8 text-white">
                <div class="text-center">
                    <i class="bi bi-credit-card text-5xl mb-3"></i>
                    <h1 class="text-2xl font-bold mb-2">Pembayaran</h1>
                    <p class="text-red-100">Selesaikan pembayaran Anda</p>
                </div>
            </div>

            <!-- Order Info -->
            <div class="px-6 py-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-sm text-gray-600">Nomor Invoice</p>
                        <p class="text-lg font-bold text-gray-900">{{ $order->nomor_invoice }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Tanggal</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $order->tanggal_pesanan->format('d M Y') }}</p>
                    </div>
                </div>

                <!-- Products Summary -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm font-semibold text-gray-900 mb-3">Ringkasan Pesanan:</p>
                    <div class="space-y-2 max-h-40 overflow-y-auto">
                        @foreach($order->items as $item)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-700">{{ $item->product->nama_produk }} ({{ $item->jumlah }}x)</span>
                            <span class="font-semibold text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="px-6 py-6">
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-semibold text-gray-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Ongkos Kirim</span>
                        <span class="font-semibold text-gray-900">Rp {{ number_format($order->ongkir, 0, ',', '.') }}</span>
                    </div>
                    <div class="border-t-2 border-gray-300 pt-3">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-900">Total Pembayaran</span>
                            <span class="text-2xl font-bold text-red-600">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Status -->
                @if($order->payment->status_pembayaran == 'paid')
                <div class="bg-green-50 border-2 border-green-300 rounded-lg p-4 mb-6">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-check-circle-fill text-green-600 text-3xl"></i>
                        <div>
                            <p class="font-bold text-green-900">Pembayaran Berhasil!</p>
                            <p class="text-sm text-green-700">Terima kasih, pembayaran Anda telah diterima</p>
                        </div>
                    </div>
                </div>
                @elseif($order->payment->status_pembayaran == 'pending')
                <div class="bg-yellow-50 border-2 border-yellow-300 rounded-lg p-4 mb-6">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-clock-fill text-yellow-600 text-3xl"></i>
                        <div class="flex-1">
                            <p class="font-bold text-yellow-900">Menunggu Pembayaran</p>
                            <p class="text-sm text-yellow-700">Silakan selesaikan pembayaran Anda</p>
                        </div>
                        <a href="{{ route('payment.show', $order->nomor_invoice) }}" 
                           class="text-sm text-yellow-700 hover:text-yellow-900 underline">
                            <i class="bi bi-arrow-clockwise"></i> Refresh Status
                        </a>
                    </div>
                </div>
                @else
                <div class="bg-gray-50 border-2 border-gray-300 rounded-lg p-4 mb-6">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-info-circle-fill text-gray-600 text-3xl"></i>
                        <div>
                            <p class="font-bold text-blue-900">Siap untuk Pembayaran</p>
                            <p class="text-sm text-blue-700">Klik tombol di bawah untuk melanjutkan</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Payment Methods Info -->
                @if(in_array($order->payment->status_pembayaran, ['unpaid', 'pending']) && $order->status_pesanan != 'batal')
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <p class="text-sm font-semibold text-gray-900 mb-3">Metode Pembayaran Tersedia:</p>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="flex items-center gap-2 text-sm text-gray-700">
                            <i class="bi bi-credit-card text-red-600"></i>
                            <span>Kartu Kredit/Debit</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-700">
                            <i class="bi bi-bank text-red-600"></i>
                            <span>Transfer Bank</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-700">
                            <i class="bi bi-wallet2 text-red-600"></i>
                            <span>E-Wallet</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-700">
                            <i class="bi bi-shop text-red-600"></i>
                            <span>Gerai Retail</span>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="space-y-3">
                    @if(in_array($order->payment->status_pembayaran, ['unpaid', 'pending']) && $order->status_pesanan != 'batal')
                    <button type="button" 
                            id="pay-button"
                            class="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold py-4 rounded-lg transition-all shadow-lg transform hover:scale-105">
                        <i class="bi bi-credit-card mr-2"></i>
                        Bayar Sekarang
                    </button>
                    @elseif($order->payment->status_pembayaran == 'paid')
                    <div class="text-center py-4">
                        <i class="bi bi-check-circle-fill text-green-500 text-4xl mb-2"></i>
                        <p class="text-green-700 font-semibold">Pembayaran Telah Selesai</p>
                    </div>
                    @elseif($order->status_pesanan == 'batal')
                    <div class="text-center py-4">
                        <i class="bi bi-x-circle-fill text-red-500 text-4xl mb-2"></i>
                        <p class="text-red-700 font-semibold">Pesanan Telah Dibatalkan</p>
                    </div>
                    @endif
                    
                    <a href="{{ route('orders.show', $order->nomor_invoice) }}" 
                       class="block w-full text-center border-2 border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold py-4 rounded-lg transition-colors">
                        <i class="bi bi-eye mr-2"></i>
                        Lihat Detail Pesanan
                    </a>
                    
                    <a href="{{ route('orders.index') }}" 
                       class="block w-full text-center text-gray-600 hover:text-orange-500 font-medium py-2 transition-colors">
                        <i class="bi bi-arrow-left mr-2"></i>
                        Kembali ke Daftar Pesanan
                    </a>
                </div>
            </div>
        </div>

        <!-- Help Info -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Butuh bantuan? 
                <a href="#" class="text-red-600 hover:text-red-700 font-semibold">Hubungi Customer Service</a>
            </p>
        </div>
    </div>
</div>

<!-- Midtrans Snap -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
const payButton = document.getElementById('pay-button');

if (payButton) {
    payButton.addEventListener('click', function() {
        snap.pay('{{ $order->payment->snap_token }}', {
            onSuccess: function(result) {
                console.log('=== PAYMENT SUCCESS ===');
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
                console.log('=== PAYMENT PENDING ===');
                console.log('Result:', result);
                console.log('Order ID:', result.order_id);
                
                const finishUrl = '{{ route("payment.finish") }}?order_id=' + result.order_id;
                console.log('Redirecting to:', finishUrl);
                window.location.href = finishUrl;
            },
            onError: function(result) {
                console.log('=== PAYMENT ERROR ===');
                console.log('Result:', result);
                console.log('Status Message:', result.status_message);
                
                alert('Pembayaran gagal: ' + (result.status_message || 'Silakan coba lagi'));
                window.location.reload();
            },
            onClose: function() {
                console.log('=== PAYMENT POPUP CLOSED ===');
                console.log('User closed the payment popup');
                
                // Refresh untuk cek status terbaru
                window.location.reload();
            }
        });
    });
}
</scr
@endsection
