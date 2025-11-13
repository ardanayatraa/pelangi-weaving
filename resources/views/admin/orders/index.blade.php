@extends('layouts.admin')

@section('title', 'Pesanan')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Manajemen Pesanan</h2>
    
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No. Invoice</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->nomor_invoice }}</td>
                            <td>{{ $order->pelanggan->nama }}</td>
                            <td>Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</td>
                            <td>
                                @if($order->status_pesanan === 'baru')
                                    <span class="badge bg-warning">Baru</span>
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
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id_pesanan) }}" class="btn btn-sm btn-dark">Detail</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada pesanan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
