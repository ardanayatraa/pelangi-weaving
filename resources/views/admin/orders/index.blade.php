@extends('layouts.admin')

@section('title', 'Pesanan')
@section('page-title', 'Manajemen Pesanan')

@section('content')
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No. Invoice</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Status Pesanan</th>
                        <th>Status Pembayaran</th>
                        <th>Tanggal</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td class="fw-semibold">{{ $order->nomor_invoice }}</td>
                        <td>{{ $order->pelanggan->nama }}</td>
                        <td class="fw-semibold">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</td>
                        <td>
                            @if($order->status_pesanan === 'baru')
                                <span class="badge bg-warning text-dark">Baru</span>
                            @elseif($order->status_pesanan === 'diproses')
                                <span class="badge bg-primary">Diproses</span>
                            @elseif($order->status_pesanan === 'dikirim')
                                <span class="badge bg-info">Dikirim</span>
                            @elseif($order->status_pesanan === 'selesai')
                                <span class="badge bg-success">Selesai</span>
                            @else
                                <span class="badge bg-danger">Batal</span>
                            @endif
                        </td>
                        <td>
                            @if($order->payment)
                                @if($order->payment->status_pembayaran === 'paid')
                                    <span class="badge bg-success">Lunas</span>
                                @elseif($order->payment->status_pembayaran === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($order->payment->status_pembayaran) }}</span>
                                @endif
                            @else
                                <span class="badge bg-secondary">-</span>
                            @endif
                        </td>
                        <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.orders.show', $order->id_pesanan) }}" class="btn btn-sm btn-outline-primary" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Belum ada pesanan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($orders->hasPages())
    <div class="card-footer">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
