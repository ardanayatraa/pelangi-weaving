@extends('layouts.admin')

@section('title', 'Custom Orders')
@section('page-title', 'Custom Orders')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Custom Orders</h1>
    <p class="text-sm text-gray-600 mt-1">Kelola pesanan custom dari pelanggan</p>
</div>

<!-- Tab Khusus: Approval & Status (pendekatan mirip navigasi produk) -->
<div class="bg-white rounded-lg shadow border border-gray-200 p-4 mb-6">
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.custom-orders.index', request()->only('search', 'jenis')) }}" 
           class="px-5 py-2.5 rounded-lg text-sm font-medium transition {{ !request('status') ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Semua
        </a>
        <a href="{{ route('admin.custom-orders.index', array_merge(request()->only('search', 'jenis'), ['status' => 'pending_approval'])) }}" 
           class="px-5 py-2.5 rounded-lg text-sm font-medium transition flex items-center gap-2 {{ request('status') == 'pending_approval' ? 'bg-amber-500 text-white' : 'bg-amber-50 text-amber-800 hover:bg-amber-100 border border-amber-200' }}">
            <i class="bi bi-clipboard-check"></i>
            <span>Approval</span>
            @if($pendingApprovalCount > 0)
            <span class="inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1.5 text-xs font-bold rounded-full bg-white/90 text-amber-600">{{ $pendingApprovalCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.custom-orders.index', array_merge(request()->only('search', 'jenis'), ['status' => 'in_production'])) }}" 
           class="px-5 py-2.5 rounded-lg text-sm font-medium transition {{ request('status') == 'in_production' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Dalam Produksi
        </a>
        <a href="{{ route('admin.custom-orders.index', array_merge(request()->only('search', 'jenis'), ['status' => 'completed'])) }}" 
           class="px-5 py-2.5 rounded-lg text-sm font-medium transition {{ request('status') == 'completed' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Selesai
        </a>
        <a href="{{ route('admin.custom-orders.index', array_merge(request()->only('search', 'jenis'), ['status' => 'draft'])) }}" 
           class="px-5 py-2.5 rounded-lg text-sm font-medium transition {{ request('status') == 'draft' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Draft
        </a>
        <a href="{{ route('admin.custom-orders.index', array_merge(request()->only('search', 'jenis'), ['status' => 'rejected'])) }}" 
           class="px-5 py-2.5 rounded-lg text-sm font-medium transition {{ request('status') == 'rejected' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Ditolak
        </a>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow border border-gray-200 p-6 mb-6">
            <form method="GET" class="flex flex-wrap gap-4">
                @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="flex-1 min-w-64">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Cari nomor order, nama custom, atau pelanggan..."
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600">
                </div>
                
                <div class="min-w-40">
                    <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600">
                        <option value="">Semua Status</option>
                        @foreach($statusOptions as $value => $label)
                            <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="min-w-40">
                    <select name="jenis" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600">
                        <option value="">Semua Jenis</option>
                        @foreach($jenisOptions as $jenis)
                            <option value="{{ $jenis->id_jenis }}" {{ request('jenis') == $jenis->id_jenis ? 'selected' : '' }}>
                                {{ $jenis->nama_jenis }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                        <i class="bi bi-search mr-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.custom-orders.index', request()->only('status')) }}" class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition font-medium">
                        <i class="bi bi-arrow-clockwise mr-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-xl">
                        <i class="bi bi-clock text-yellow-600 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Menunggu Persetujuan</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $customOrders->where('status', 'pending_approval')->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-xl">
                        <i class="bi bi-gear text-blue-600 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Dalam Produksi</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $customOrders->where('status', 'in_production')->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-xl">
                        <i class="bi bi-check-circle text-green-600 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Selesai</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $customOrders->where('status', 'completed')->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 rounded-xl">
                        <i class="bi bi-currency-dollar text-red-600 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Nilai</p>
                        <p class="text-3xl font-bold text-gray-900">Rp {{ number_format($customOrders->sum('harga_final'), 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Custom Orders Table -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Custom Order
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pelanggan
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jenis
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Harga
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($customOrders as $order)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $order->nomor_custom_order }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($order->nama_custom, 30) }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $order->pelanggan->nama }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->pelanggan->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $order->jenis->nama_jenis }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'draft' => 'bg-gray-100 text-gray-800',
                                            'pending_approval' => 'bg-yellow-100 text-yellow-800',
                                            'approved' => 'bg-blue-100 text-blue-800',
                                            'in_production' => 'bg-purple-100 text-purple-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                            'rejected' => 'bg-red-100 text-red-800'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusOptions[$order->status] ?? $order->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($order->harga_final > 0)
                                        Rp {{ number_format($order->harga_final, 0, ',', '.') }}
                                    @else
                                        <span class="text-gray-400">Belum ditentukan</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.custom-orders.show', $order->nomor_custom_order) }}" 
                                           class="text-red-600 hover:text-red-900 transition">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        
                                        @if($order->status === 'pending_approval')
                                            <button onclick="approveOrder('{{ $order->nomor_custom_order }}')"
                                                    class="text-green-600 hover:text-green-900 transition">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                            <button onclick="rejectOrder('{{ $order->nomor_custom_order }}')"
                                                    class="text-red-600 hover:text-red-900 transition">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        @endif
                                        
                                        @if(in_array($order->status, ['draft', 'cancelled', 'rejected']))
                                            <form action="{{ route('admin.custom-orders.destroy', $order->nomor_custom_order) }}" 
                                                  method="POST" 
                                                  class="inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus custom order ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 transition">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i class="bi bi-inbox text-4xl mb-4"></i>
                                        <p>Belum ada custom order</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($customOrders->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $customOrders->links() }}
                </div>
            @endif
        </div>

<!-- Approve Modal -->
<div id="approveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
            <form id="approveForm" method="POST">
                @csrf
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Setujui Custom Order</h3>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Harga Final *</label>
                        <input type="number" 
                               name="harga_final" 
                               required
                               min="1000"
                               step="1000"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600"
                               placeholder="Masukkan harga final">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Admin</label>
                        <textarea name="catatan_admin" 
                                  rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600"
                                  placeholder="Catatan untuk pelanggan (opsional)"></textarea>
                    </div>
                </div>
                
                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 rounded-b-xl">
                    <button type="button" 
                            onclick="closeModal('approveModal')"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                        Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
            <form id="rejectForm" method="POST">
                @csrf
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Tolak Custom Order</h3>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan *</label>
                        <textarea name="catatan_admin" 
                                  rows="4"
                                  required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600"
                                  placeholder="Jelaskan alasan penolakan..."></textarea>
                    </div>
                </div>
                
                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 rounded-b-xl">
                    <button type="button" 
                            onclick="closeModal('rejectModal')"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                        Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function approveOrder(nomorCustomOrder) {
    document.getElementById('approveForm').action = `/admin/custom-orders/${nomorCustomOrder}/approve`;
    document.getElementById('approveModal').classList.remove('hidden');
}

function rejectOrder(nomorCustomOrder) {
    document.getElementById('rejectForm').action = `/admin/custom-orders/${nomorCustomOrder}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('bg-opacity-50')) {
        e.target.classList.add('hidden');
    }
});
</script>
@endpush
@endsection