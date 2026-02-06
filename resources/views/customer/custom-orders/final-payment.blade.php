@extends('layouts.customer')

@section('title', 'Pembayaran Pelunasan Custom Order')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('custom-orders.show', $customOrder->nomor_custom_order) }}" 
               class="inline-flex items-center text-primary-600 hover:text-primary-700 mb-4">
                <i class="bi bi-arrow-left mr-2"></i>
                Kembali ke Detail
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Pembayaran Pelunasan</h1>
            <p class="text-gray-600 mt-2">Selesaikan pembayaran untuk pengiriman pesanan</p>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            <!-- Payment Info -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Informasi Pembayaran</h2>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between py-3 border-b">
                            <span class="text-gray-600">Nomor Order</span>
                            <span class="font-semibold">{{ $customOrder->nomor_custom_order }}</span>
                        </div>
                        
                        <div class="flex justify-between py-3 border-b">
                            <span class="text-gray-600">Nama Custom</span>
                            <span class="font-semibold">{{ $customOrder->nama_custom }}</span>
                        </div>
                        
                        <div class="flex justify-between py-3 border-b">
                            <span class="text-gray-600">Harga Total</span>
                            <span class="font-semibold">Rp {{ number_format($customOrder->harga_final, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="flex justify-between py-3 border-b">
                            <span class="text-gray-600">DP Sudah Dibayar</span>
                            <span class="font-semibold text-green-600">
                                <i class="bi bi-check-circle mr-1"></i>
                                Rp {{ number_format($customOrder->dp_amount, 0, ',', '.') }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between py-3 bg-primary-50 -mx-6 px-6 rounded-lg">
                            <span class="text-lg font-bold text-gray-900">Sisa Pembayaran</span>
                            <span class="text-2xl font-bold text-primary-600">
                                Rp {{ number_format($remainingAmount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Payment Button -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Metode Pembayaran</h3>
                    <p class="text-gray-600 mb-6">
                        Setelah pembayaran pelunasan berhasil, pesanan Anda akan segera dikirim.
                    </p>
                    
                    <button type="button" 
                            id="pay-button"
                            class="w-full bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-bold py-4 rounded-xl transition-all duration-200 text-lg shadow-lg">
                        <i class="bi bi-credit-card mr-2"></i>
                        Bayar Sekarang
                    </button>
                    
                    <div class="mt-4 flex items-center justify-center text-sm text-gray-500">
                        <i class="bi bi-shield-check mr-2"></i>
                        Pembayaran aman dengan Midtrans
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 sticky top-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Ringkasan Pesanan</h3>
                    
                    @if($customOrder->gambar_referensi && count($customOrder->gambar_referensi) > 0)
                        <div class="mb-4">
                            <img src="{{ asset('storage/' . $customOrder->gambar_referensi[0]) }}" 
                                 alt="{{ $customOrder->nama_custom }}"
                                 class="w-full h-48 object-cover rounded-lg">
                        </div>
                    @endif
                    
                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="text-gray-600">Jenis</span>
                            <p class="font-semibold">{{ $customOrder->jenis->nama_jenis }}</p>
                        </div>
                        
                        <div>
                            <span class="text-gray-600">Jumlah</span>
                            <p class="font-semibold">{{ $customOrder->jumlah }} pcs</p>
                        </div>
                        
                        <div>
                            <span class="text-gray-600">Status</span>
                            <p class="font-semibold">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                    <i class="bi bi-check-circle mr-1"></i>
                                    Produksi Selesai
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-6 border-t">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="bi bi-info-circle mr-2"></i>
                            <span>Pesanan akan dikirim setelah pelunasan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Midtrans Snap -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
document.getElementById('pay-button').addEventListener('click', function() {
    snap.pay('{{ $snapToken }}', {
        onSuccess: function(result) {
            console.log('Final payment success:', result);
            window.location.href = '{{ route("custom-orders.final-payment.finish", $customOrder->nomor_custom_order) }}';
        },
        onPending: function(result) {
            console.log('Final payment pending:', result);
            window.location.href = '{{ route("custom-orders.final-payment.finish", $customOrder->nomor_custom_order) }}';
        },
        onError: function(result) {
            console.log('Final payment error:', result);
            alert('Pembayaran gagal: ' + (result.status_message || 'Silakan coba lagi'));
        },
        onClose: function() {
            console.log('Payment popup closed');
            // User closed the popup
        }
    });
});
</script>
@endsection
