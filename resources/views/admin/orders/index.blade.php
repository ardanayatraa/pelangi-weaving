@extends('layouts.admin')

@section('title', 'Pesanan')
@section('page-title', 'Manajemen Pesanan')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Invoice</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Pesanan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Pembayaran</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-800">{{ $order->nomor_invoice }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-800">{{ $order->pelanggan->nama }}</td>
                    <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-800">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($order->status_pesanan === 'baru')
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Baru</span>
                        @elseif($order->status_pesanan === 'diproses')
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Diproses</span>
                        @elseif($order->status_pesanan === 'dikirim')
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-cyan-100 text-cyan-800">Dikirim</span>
                        @elseif($order->status_pesanan === 'selesai')
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>
                        @else
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Batal</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($order->payment)
                            @if($order->payment->status_pembayaran === 'paid')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Lunas</span>
                            @elseif($order->payment->status_pembayaran === 'pending')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($order->payment->status_pembayaran) }}</span>
                            @endif
                        @else
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-800">{{ $order->created_at->format('d M Y, H:i') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <a href="{{ route('admin.orders.show', $order->id_pesanan) }}" 
                           class="inline-flex items-center px-3 py-1 border border-blue-600 text-blue-600 hover:bg-blue-50 rounded text-sm font-medium transition"
                           title="Detail">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">Belum ada pesanan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
