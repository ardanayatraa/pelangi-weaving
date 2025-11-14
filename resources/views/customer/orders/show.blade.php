@extends('layouts.customer')

@section('title', 'Detail Pesanan - Pelangi Weaving')

@section('content')
<!-- Breadcrumb -->
<div class="bg-white py-4 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center text-sm text-gray-600">
            <a href="{{ route('home') }}" class="hover:text-red-600">Beranda</a>
            <i class="bi bi-chevron-right mx-2 text-xs"></i>
            <a href="{{ route('orders.index') }}" class="hover:text-red-600">Pesanan Saya</a>
            <i class="bi bi-chevron-right mx-2 text-xs"></i>
            <span class="text-gray-900 font-medium">{{ $order->nomor_invoice }}</span>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-4 md:space-y-6">
            <!-- Order Status -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $order->nomor_invoice }}</h2>
                        <p class="text-sm text-gray-600 mt-1">{{ $order->tanggal_pesanan->format('d F Y, H:i') }} WIB</p>
                    </div>
                    
                    @php
                    $statusConfig = [
                        'baru' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'clock-history', 'label' => 'Pesanan Baru'],
                        'diproses' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'gear', 'label' => 'Sedang Diproses'],
                        'dikirim' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'icon' => 'truck', 'label' => 'Sedang Dikirim'],
                        'selesai' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'check-circle', 'label' => 'Selesai'],
                        'batal' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'x-circle', 'label' => 'Dibatalkan'],
                    ];
                    $status = $statusConfig[$order->status_pesanan] ?? $statusConfig['baru'];
                    @endphp
                    
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold {{ $status['bg'] }} {{ $status['text'] }}">
                        <i class="bi bi-{{ $status['icon'] }} mr-2"></i>
                        {{ $status['label'] }}
                    </span>
                </div>

                <!-- Status Timeline -->
                <div class="relative">
                    <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                    
                    <div class="space-y-6">
                        <!-- Pesanan Dibuat -->
                        <div class="relative flex items-start">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-green-500 text-white z-10">
                                <i class="bi bi-check text-sm"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="font-semibold text-gray-900">Pesanan Dibuat</p>
                                <p class="text-sm text-gray-600">{{ $order->tanggal_pesanan->format('d M Y, H:i') }}</p>
                            </div>
                        </div>

                        <!-- Pembayaran -->
                        <div class="relative flex items-start">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $order->payment && $order->payment->status_pembayaran == 'paid' ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600' }} z-10">
                                <i class="bi bi-{{ $order->payment && $order->payment->status_pembayaran == 'paid' ? 'check' : 'clock' }} text-sm"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="font-semibold text-gray-900">Pembayaran</p>
                                @if($order->payment && $order->payment->status_pembayaran == 'paid')
                                <p class="text-sm text-green-600">Pembayaran berhasil</p>
                                @else
                                <p class="text-sm text-gray-600">Menunggu pembayaran</p>
                                @endif
                            </div>
                        </div>

                        <!-- Diproses -->
                        <div class="relative flex items-start">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full {{ in_array($order->status_pesanan, ['diproses', 'dikirim', 'selesai']) ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600' }} z-10">
                                <i class="bi bi-{{ in_array($order->status_pesanan, ['diproses', 'dikirim', 'selesai']) ? 'check' : 'clock' }} text-sm"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="font-semibold text-gray-900">Pesanan Diproses</p>
                                <p class="text-sm text-gray-600">Pesanan sedang disiapkan</p>
                            </div>
                        </div>

                        <!-- Dikirim -->
                        <div class="relative flex items-start">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full {{ in_array($order->status_pesanan, ['dikirim', 'selesai']) ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600' }} z-10">
                                <i class="bi bi-{{ in_array($order->status_pesanan, ['dikirim', 'selesai']) ? 'check' : 'clock' }} text-sm"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="font-semibold text-gray-900">Pesanan Dikirim</p>
                                @if($order->pengiriman && $order->pengiriman->no_resi)
                                <p class="text-sm text-gray-600">No. Resi: <span class="font-mono font-semibold">{{ $order->pengiriman->no_resi }}</span></p>
                                @else
                                <p class="text-sm text-gray-600">Menunggu pengiriman</p>
                                @endif
                            </div>
                        </div>

                        <!-- Selesai -->
                        <div class="relative flex items-start">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $order->status_pesanan == 'selesai' ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600' }} z-10">
                                <i class="bi bi-{{ $order->status_pesanan == 'selesai' ? 'check' : 'clock' }} text-sm"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="font-semibold text-gray-900">Pesanan Selesai</p>
                                <p class="text-sm text-gray-600">Pesanan telah diterima</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Produk yang Dipesan</h3>
                
                <div class="space-y-4">
                    @foreach($order->items as $item)
                    @php
                        $produk = $item->produk;
                        $varian = $item->varian;
                        
                        // Get image URL
                        $imageUrl = null;
                        if ($varian && $varian->gambar_varian) {
                            $imageUrl = $varian->gambar_varian;
                        } elseif ($produk && $produk->images->first()) {
                            $imageUrl = $produk->images->first()->path;
                        }
                    @endphp
                    <div class="flex gap-4 pb-4 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
                        @if($imageUrl)
                            <img src="{{ asset('storage/' . $imageUrl) }}" 
                                 alt="{{ $produk->nama_produk }}"
                                 class="w-24 h-24 object-cover rounded border border-gray-200"
                                 onerror="this.src='{{ asset('images/placeholder.png') }}'">
                        @else
                            <div class="w-24 h-24 bg-gray-100 rounded border border-gray-200 flex items-center justify-center">
                                <i class="bi bi-image text-gray-400 text-3xl"></i>
                            </div>
                        @endif
                        
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 mb-1">{{ $produk->nama_produk }}</h4>
                            @if($varian)
                            <p class="text-sm text-gray-600 mb-2">
                                {{ $varian->nama_varian }}
                            </p>
                            @endif
                            <div class="flex items-center justify-between">
                                <p class="text-sm text-gray-600">{{ $item->jumlah }} × Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</p>
                                <p class="font-bold text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Shipping Info -->
            @if($order->pengiriman)
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="bi bi-truck text-orange-500 mr-2"></i>
                    Informasi Pengiriman
                </h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Kurir</span>
                        <span class="font-bold text-gray-900 text-right">
                            {{ strtoupper($order->pengiriman->kurir ?? 'POS') }} - {{ strtoupper($order->pengiriman->layanan ?? 'PAKET KILAT KHUSUS') }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Status</span>
                        @php
                        $statusPengiriman = [
                            'menunggu' => ['text' => 'Menunggu', 'class' => 'text-yellow-600'],
                            'dalam_perjalanan' => ['text' => 'Dalam Perjalanan', 'class' => 'text-blue-600'],
                            'sampai' => ['text' => 'Sampai', 'class' => 'text-green-600'],
                        ];
                        $currentStatus = $statusPengiriman[$order->pengiriman->status_pengiriman] ?? ['text' => 'Menunggu', 'class' => 'text-gray-600'];
                        @endphp
                        <span class="font-bold {{ $currentStatus['class'] }} text-right">{{ $currentStatus['text'] }}</span>
                    </div>
                    
                    @if($order->pengiriman->no_resi)
                    <div class="pt-3 border-t border-gray-200">
                        <p class="text-sm text-gray-600 mb-1">No. Resi:</p>
                        <p class="font-mono font-bold text-gray-900 text-lg">{{ $order->pengiriman->no_resi }}</p>
                    </div>
                    @endif
                    
                    <div class="pt-3 border-t border-gray-200">
                        <p class="text-sm text-gray-600 mb-2">Alamat Pengiriman:</p>
                        <p class="text-gray-900 leading-relaxed">{{ $order->pengiriman->alamat_pengiriman }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Payment Summary -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 sticky top-20">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Ringkasan Pembayaran</h3>
                
                <div class="space-y-3 mb-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal ({{ $order->items->count() }} produk)</span>
                        <span class="font-semibold text-gray-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Ongkos Kirim</span>
                        <span class="font-semibold text-gray-900">Rp {{ number_format($order->ongkir, 0, ',', '.') }}</span>
                    </div>
                </div>
                
                <div class="pt-4 border-t-2 border-gray-300 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-gray-900">Total</span>
                        <span class="text-2xl font-bold text-orange-500">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Payment Status -->
                @if($order->payment)
                <div class="mb-4 p-4 rounded-lg {{ $order->payment->status_pembayaran == 'paid' ? 'bg-green-50 border border-green-200' : 'bg-yellow-50 border border-yellow-200' }}">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="bi bi-{{ $order->payment->status_pembayaran == 'paid' ? 'check-circle-fill text-green-600' : 'clock-fill text-yellow-600' }}"></i>
                        <span class="font-semibold {{ $order->payment->status_pembayaran == 'paid' ? 'text-green-900' : 'text-yellow-900' }}">
                            {{ $order->payment->status_pembayaran == 'paid' ? 'Pembayaran Berhasil' : 'Menunggu Pembayaran' }}
                        </span>
                    </div>
                    @if($order->payment->status_pembayaran != 'paid')
                    <p class="text-sm {{ $order->payment->status_pembayaran == 'paid' ? 'text-green-700' : 'text-yellow-700' }}">
                        Silakan selesaikan pembayaran Anda
                    </p>
                    @endif
                </div>
                @endif

                <!-- Actions -->
                <div class="space-y-2" x-data="{ showConfirmModal: false }">
                    @if($order->payment && in_array($order->payment->status_pembayaran, ['unpaid', 'pending']) && !in_array($order->status_pesanan, ['batal', 'selesai']))
                    <a href="{{ route('payment.show', $order->nomor_invoice) }}" 
                       class="block w-full text-center px-4 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold rounded-lg transition-all shadow-lg transform hover:scale-105">
                        <i class="bi bi-credit-card mr-2"></i>
                        Bayar Sekarang
                    </a>
                    @endif
                    
                    @if($order->status_pesanan == 'dikirim')
                    <button @click="showConfirmModal = true" 
                            class="block w-full px-4 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-bold rounded-lg transition-all shadow-lg transform hover:scale-105">
                        <i class="bi bi-check-circle mr-2"></i>
                        Pesanan Sudah Diterima
                    </button>
                    
                    <!-- Confirmation Modal (No Backdrop) -->
                    <div x-show="showConfirmModal" x-cloak 
                         class="fixed inset-0 z-[9999] overflow-y-auto flex items-center justify-center p-4" 
                         style="display: none;">
                        <div @click.away="showConfirmModal = false" x-show="showConfirmModal" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-90"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-90"
                             class="relative bg-white rounded-xl shadow-2xl max-w-md w-full overflow-hidden border-4 border-green-500">
                            <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b border-green-200">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                                        <i class="bi bi-check-circle text-white text-2xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">Konfirmasi Penerimaan</h3>
                                        <p class="text-sm text-gray-600">Pesanan sudah diterima?</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="p-6">
                                <p class="text-gray-700 mb-4">
                                    Dengan mengkonfirmasi, Anda menyatakan bahwa pesanan telah diterima dengan baik dan sesuai. 
                                    Status pesanan akan diubah menjadi <strong>Selesai</strong>.
                                </p>
                                
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                                    <p class="text-sm text-yellow-800">
                                        <i class="bi bi-exclamation-triangle mr-1"></i>
                                        Pastikan Anda sudah menerima dan memeriksa pesanan sebelum konfirmasi.
                                    </p>
                                </div>
                            </div>
                            
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 flex justify-end gap-3">
                                <button @click="showConfirmModal = false" 
                                        class="px-5 py-2.5 bg-white hover:bg-gray-100 text-gray-700 font-semibold rounded-lg border border-gray-300 transition">
                                    Batal
                                </button>
                                <form action="{{ route('orders.complete', $order->nomor_invoice) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition hover:shadow-lg">
                                        <i class="bi bi-check-circle mr-2"></i>
                                        Ya, Sudah Diterima
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if(in_array($order->status_pesanan, ['baru']) && $order->payment && $order->payment->status_pembayaran != 'paid')
                    <form action="{{ route('orders.cancel', $order->nomor_invoice) }}" method="POST" 
                          onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                        @csrf
                        <button type="submit" class="block w-full px-4 py-3 border-2 border-red-300 text-red-600 hover:bg-red-50 font-semibold rounded-lg transition-colors">
                            <i class="bi bi-x-circle mr-2"></i>
                            Batalkan Pesanan
                        </button>
                    </form>
                    @endif
                    
                    <a href="{{ route('orders.index') }}" 
                       class="block w-full text-center px-4 py-3 border-2 border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold rounded-lg transition-colors">
                        <i class="bi bi-arrow-left mr-2"></i>
                        Kembali ke Daftar Pesanan
                    </a>
                </div>

                @if($order->catatan)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-sm font-semibold text-gray-900 mb-2">Catatan:</p>
                    <p class="text-sm text-gray-600">{{ $order->catatan }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
