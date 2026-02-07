@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $order->nomor_invoice)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.orders.index') }}" class="text-gray-700 hover:text-gray-900 font-medium inline-flex items-center">
        <i class="bi bi-arrow-left mr-1"></i> Kembali ke Daftar Pesanan
    </a>
</div>

<div class="flex items-center justify-between mb-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">
            Detail Pesanan #{{ $order->nomor_invoice }}
        </h1>
        <p class="text-sm text-gray-600 mt-1">{{ $order->tanggal_pesanan->format('d F Y, H:i') }} WIB</p>
    </div>
    <div>
        @if($order->status_pesanan == 'baru')
            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                <i class="bi bi-clock mr-1"></i> Baru
            </span>
        @elseif($order->status_pesanan == 'diproses')
            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                <i class="bi bi-arrow-repeat mr-1"></i> Diproses
            </span>
        @elseif($order->status_pesanan == 'dikirim')
            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-cyan-100 text-cyan-800">
                <i class="bi bi-truck mr-1"></i> Dikirim
            </span>
        @elseif($order->status_pesanan == 'selesai')
            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                <i class="bi bi-check-all mr-1"></i> Selesai
            </span>
        @elseif($order->status_pesanan == 'batal')
            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                <i class="bi bi-x-circle mr-1"></i> Dibatalkan
            </span>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-4">
        <!-- Order Items -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-sm font-bold text-gray-900 uppercase">Produk yang Dipesan</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex items-start space-x-4 pb-4 border-b last:border-b-0">
                        @if($item->varian && $item->varian->gambar_varian)
                            <img src="{{ asset('storage/' . $item->varian->gambar_varian) }}" 
                                 alt="{{ $item->varian->nama_varian }}"
                                 class="w-16 h-16 object-cover rounded-lg border border-gray-200 flex-shrink-0">
                        @elseif($item->produk && $item->produk->primary_image_path)
                            <img src="{{ asset('storage/' . $item->produk->primary_image_path) }}" 
                                 alt="{{ $item->produk->nama_produk }}"
                                 class="w-16 h-16 object-cover rounded-lg border border-gray-200 flex-shrink-0">
                        @else
                            <div class="w-16 h-16 bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center flex-shrink-0">
                                <i class="bi bi-box-seam text-gray-400 text-xl"></i>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-sm text-gray-900">{{ $item->produk->nama_produk ?? 'Produk' }}</h3>
                            <p class="text-xs text-gray-600 mt-1">Varian: {{ $item->varian->nama_varian ?? '-' }}</p>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-xs text-gray-600">{{ $item->jumlah }} x Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                                <span class="font-bold text-sm text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Order Summary -->
                <div class="mt-6 pt-4 border-t space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal Produk</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Ongkos Kirim ({{ $order->pengiriman->kurir ?? 'Kurir' }})</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($order->ongkir, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold pt-3 border-t border-gray-300">
                        <span class="text-gray-900">Total</span>
                        <span class="text-blue-600">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Info -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-sm font-bold text-gray-900 uppercase">Informasi Pengiriman</h2>
            </div>
            <div class="p-6">
                @if($order->pengiriman)
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div>
                        <p class="text-xs text-gray-600 mb-1">Kurir</p>
                        <p class="font-semibold text-sm text-gray-900">{{ strtoupper($order->pengiriman->kurir ?? 'N/A') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 mb-1">Layanan</p>
                        <p class="font-semibold text-sm text-gray-900">{{ $order->pengiriman->layanan ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 mb-1">Status Pengiriman</p>
                        <p class="font-semibold text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $order->pengiriman->status_pengiriman)) }}</p>
                    </div>
                </div>
                
                @if($order->pengiriman->no_resi)
                <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-xs text-blue-600 font-semibold mb-1">Nomor Resi</p>
                    <p class="font-bold text-blue-900 text-lg">{{ $order->pengiriman->no_resi }}</p>
                </div>
                @endif

                <div>
                    <p class="text-xs text-gray-600 font-semibold mb-2">Alamat Pengiriman</p>
                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                        <p class="font-semibold text-sm text-gray-900">{{ $order->pengiriman->alamat_pengiriman ?? 'Alamat belum diisi' }}</p>
                    </div>
                </div>
                @else
                <div class="text-center py-4 text-gray-500">
                    <i class="bi bi-inbox text-3xl mb-2"></i>
                    <p>Informasi pengiriman belum tersedia</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-4">
        <!-- Customer Info -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-sm font-bold text-gray-900 uppercase">Informasi Pelanggan</h2>
            </div>
            <div class="p-6 space-y-3">
                <div>
                    <p class="text-xs text-gray-600 mb-1">Nama</p>
                    <p class="font-semibold text-sm text-gray-900">{{ $order->pelanggan->nama }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-600 mb-1">Email</p>
                    <p class="font-semibold text-sm text-gray-900">{{ $order->pelanggan->email }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-600 mb-1">Telepon</p>
                    <p class="font-semibold text-sm text-gray-900">{{ $order->pelanggan->no_telepon }}</p>
                </div>
            </div>
        </div>

        <!-- Payment Info -->
        @if($order->payment)
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-sm font-bold text-gray-900 uppercase">Informasi Pembayaran</h2>
            </div>
            <div class="p-6 space-y-3">
                <div>
                    <p class="text-xs text-gray-600 mb-1">Metode</p>
                    <p class="font-semibold text-sm text-gray-900">{{ $order->payment->metode_pembayaran ?? 'Belum dipilih' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-600 mb-1">Status</p>
                    <p class="font-semibold text-sm {{ $order->payment->status_bayar == 'paid' ? 'text-green-700' : 'text-yellow-700' }}">
                        {{ ucfirst($order->payment->status_bayar) }}
                    </p>
                </div>
                @if($order->payment->tanggal_bayar)
                <div>
                    <p class="text-xs text-gray-600 mb-1">Dibayar Pada</p>
                    <p class="font-semibold text-sm text-gray-900">{{ \Carbon\Carbon::parse($order->payment->tanggal_bayar)->format('d M Y, H:i') }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Update Status -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-sm font-bold text-gray-900 uppercase">Update Status</h2>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.orders.update-status', $order->id_pesanan) }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Pesanan</label>
                        <select name="status_pesanan" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            <option value="baru" {{ $order->status_pesanan == 'baru' ? 'selected' : '' }}>Baru</option>
                            <option value="diproses" {{ $order->status_pesanan == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="dikirim" {{ $order->status_pesanan == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                            <option value="selesai" {{ $order->status_pesanan == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="batal" {{ $order->status_pesanan == 'batal' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Resi (Opsional)</label>
                        <input type="text" 
                               name="no_resi" 
                               value="{{ $order->pengiriman->no_resi ?? '' }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                               placeholder="Masukkan nomor resi">
                    </div>

                    <button type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg transition hover:shadow-lg">
                        <i class="bi bi-check-circle mr-2"></i>
                        Update Status
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
