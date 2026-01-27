@extends('layouts.customer')

@section('title', 'Detail Custom Order')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-6xl mx-auto px-4">
        <!-- Breadcrumb -->
        <div class="flex items-center text-sm text-gray-600 mb-6">
            <a href="{{ route('home') }}" class="hover:text-red-600">Beranda</a>
            <i class="bi bi-chevron-right mx-2 text-xs"></i>
            <a href="{{ route('custom-orders.index') }}" class="hover:text-red-600">Custom Orders</a>
            <i class="bi bi-chevron-right mx-2 text-xs"></i>
            <span class="text-gray-900 font-medium">{{ $customOrder->nomor_custom_order }}</span>
        </div>

        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $customOrder->nomor_custom_order }}</h1>
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
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$customOrder->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst(str_replace('_', ' ', $customOrder->status)) }}
                        </span>
                    </div>
                    <p class="text-gray-600 text-lg">{{ $customOrder->nama_custom }}</p>
                </div>
                
                <div class="text-right">
                    @if($customOrder->harga_final > 0)
                        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($customOrder->harga_final, 0, ',', '.') }}</p>
                        @if($customOrder->dp_amount > 0)
                            <p class="text-sm text-gray-600">
                                DP: Rp {{ number_format($customOrder->dp_amount, 0, ',', '.') }}
                                @if($customOrder->isDpPaid())
                                    <span class="text-green-600 ml-1 font-medium">✓ Sudah dibayar</span>
                                @else
                                    <span class="text-red-600 ml-1 font-medium">✗ Belum dibayar</span>
                                @endif
                            </p>
                        @endif
                    @else
                        <p class="text-gray-500">Harga belum ditentukan</p>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3 mt-6 pt-6 border-t border-gray-100">
                @if($customOrder->status === 'draft')
                    <a href="{{ route('custom-orders.edit', $customOrder->nomor_custom_order) }}" 
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="bi bi-pencil mr-2"></i>Edit
                    </a>
                    
                    <form action="{{ route('custom-orders.submit', $customOrder->nomor_custom_order) }}" 
                          method="POST" 
                          class="inline"
                          onsubmit="return confirm('Yakin ingin submit untuk persetujuan? Setelah disubmit, Anda tidak bisa mengedit lagi.')">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            <i class="bi bi-send mr-2"></i>Submit untuk Persetujuan
                        </button>
                    </form>
                @endif
                
                @if($customOrder->status === 'approved' && !$customOrder->isDpPaid())
                    <a href="{{ route('custom-orders.payment', $customOrder->nomor_custom_order) }}" 
                       class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class="bi bi-credit-card mr-2"></i>Bayar DP
                    </a>
                @endif
                
                @if(in_array($customOrder->status, ['draft', 'pending_approval', 'approved']))
                    <form action="{{ route('custom-orders.cancel', $customOrder->nomor_custom_order) }}" 
                          method="POST" 
                          class="inline"
                          onsubmit="return confirm('Yakin ingin membatalkan custom order ini?')">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                            <i class="bi bi-x-circle mr-2"></i>Batalkan
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Detail Custom Order -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Detail Custom Order</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Custom</label>
                            <p class="text-sm text-gray-900">{{ $customOrder->jenis->nama_jenis }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                            <p class="text-sm text-gray-900">{{ $customOrder->jumlah }} pcs</p>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Custom</label>
                        <p class="text-sm text-gray-900 whitespace-pre-line">{{ $customOrder->deskripsi_custom }}</p>
                    </div>
                    
                    @if($customOrder->catatan_pelanggan)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan</label>
                            <p class="text-sm text-gray-900 whitespace-pre-line">{{ $customOrder->catatan_pelanggan }}</p>
                        </div>
                    @endif
                </div>

                <!-- Gambar Referensi -->
                @if($customOrder->gambar_referensi && count($customOrder->gambar_referensi) > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Gambar Referensi</h2>
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($customOrder->gambar_referensi as $image)
                                <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                                    <img src="{{ Storage::url($image) }}" 
                                         alt="Referensi" 
                                         class="w-full h-full object-cover cursor-pointer hover:opacity-75 transition"
                                         onclick="showImageModal('{{ Storage::url($image) }}')">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Progress History -->
                @if($customOrder->progress_history && count($customOrder->progress_history) > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Riwayat Progress</h2>
                        
                        <div class="space-y-4">
                            @foreach(array_reverse($customOrder->progress_history) as $history)
                                <div class="flex gap-4 p-4 bg-gray-50 rounded-lg">
                                    <div class="flex-shrink-0">
                                        @if(isset($history['status']))
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="bi bi-arrow-right text-blue-600 text-sm"></i>
                                            </div>
                                        @else
                                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                <i class="bi bi-chat-dots text-green-600 text-sm"></i>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-1">
                                            <p class="text-sm font-medium text-gray-900">
                                                @if(isset($history['status']))
                                                    Status diubah ke: {{ ucfirst(str_replace('_', ' ', $history['status'])) }}
                                                @else
                                                    Progress Update
                                                @endif
                                            </p>
                                            <span class="text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($history['timestamp'])->format('d/m/Y H:i') }}
                                            </span>
                                        </div>
                                        
                                        @if(isset($history['catatan']) && $history['catatan'])
                                            <p class="text-sm text-gray-600 mb-2">{{ $history['catatan'] }}</p>
                                        @endif
                                        
                                        @if(isset($history['note']) && $history['note'])
                                            <p class="text-sm text-gray-600 mb-2">{{ $history['note'] }}</p>
                                        @endif
                                        
                                        @if(isset($history['harga_final']))
                                            <p class="text-sm text-green-600 font-medium">Harga final: Rp {{ number_format($history['harga_final'], 0, ',', '.') }}</p>
                                        @endif
                                        
                                        @if(isset($history['images']) && count($history['images']) > 0)
                                            <div class="flex gap-2 mt-2">
                                                @foreach($history['images'] as $image)
                                                    <img src="{{ Storage::url($image) }}" 
                                                         alt="Progress" 
                                                         class="w-16 h-16 object-cover rounded cursor-pointer hover:opacity-75 transition"
                                                         onclick="showImageModal('{{ Storage::url($image) }}')">
                                                @endforeach
                                            </div>
                                        @endif
                                        
                                        <p class="text-xs text-gray-500 mt-1">oleh {{ $history['admin'] ?? 'System' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Status Info</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status Saat Ini</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$customOrder->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst(str_replace('_', ' ', $customOrder->status)) }}
                            </span>
                        </div>
                        
                        @if($customOrder->status === 'pending_approval')
                            <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <p class="text-sm text-yellow-800">
                                    <i class="bi bi-clock mr-1"></i>
                                    Custom order Anda sedang menunggu persetujuan dari admin. Kami akan segera menghubungi Anda.
                                </p>
                            </div>
                        @elseif($customOrder->status === 'approved' && !$customOrder->isDpPaid())
                            <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <p class="text-sm text-blue-800">
                                    <i class="bi bi-credit-card mr-1"></i>
                                    Custom order Anda telah disetujui! Silakan bayar DP untuk memulai produksi.
                                </p>
                            </div>
                        @elseif($customOrder->status === 'in_production')
                            <div class="p-3 bg-purple-50 border border-purple-200 rounded-lg">
                                <p class="text-sm text-purple-800">
                                    <i class="bi bi-gear mr-1"></i>
                                    Custom order Anda sedang dalam proses produksi. Kami akan update progress secara berkala.
                                </p>
                            </div>
                        @elseif($customOrder->status === 'completed')
                            <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                                <p class="text-sm text-green-800">
                                    <i class="bi bi-check-circle mr-1"></i>
                                    Custom order Anda telah selesai! Terima kasih telah mempercayai kami.
                                </p>
                            </div>
                        @elseif($customOrder->status === 'rejected')
                            <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-sm text-red-800">
                                    <i class="bi bi-x-circle mr-1"></i>
                                    Maaf, custom order Anda tidak dapat kami proses. Silakan lihat catatan dari admin.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Info Tanggal -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Info Tanggal</h2>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dibuat</label>
                            <p class="text-sm text-gray-900">{{ $customOrder->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Terakhir Update</label>
                            <p class="text-sm text-gray-900">{{ $customOrder->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                        
                        @if($customOrder->dp_paid_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">DP Dibayar</label>
                                <p class="text-sm text-green-600 font-medium">{{ $customOrder->dp_paid_at->format('d/m/Y H:i') }}</p>
                            </div>
                        @endif
                        
                        @if($customOrder->fully_paid_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Lunas</label>
                                <p class="text-sm text-green-600 font-medium">{{ $customOrder->fully_paid_at->format('d/m/Y H:i') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Butuh Bantuan?</h2>
                    
                    <div class="space-y-3">
                        <a href="https://wa.me/6281234567890" 
                           target="_blank"
                           class="flex items-center gap-3 p-3 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition">
                            <i class="bi bi-whatsapp text-green-600 text-xl"></i>
                            <div>
                                <p class="text-sm font-medium text-green-800">WhatsApp</p>
                                <p class="text-xs text-green-600">Chat dengan admin</p>
                            </div>
                        </a>
                        
                        <a href="tel:+6281234567890" 
                           class="flex items-center gap-3 p-3 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition">
                            <i class="bi bi-telephone text-blue-600 text-xl"></i>
                            <div>
                                <p class="text-sm font-medium text-blue-800">Telepon</p>
                                <p class="text-xs text-blue-600">+62 812-3456-7890</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full">
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
            <i class="bi bi-x-lg text-2xl"></i>
        </button>
        <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain">
    </div>
</div>

@push('scripts')
<script>
function showImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('bg-opacity-75')) {
        e.target.classList.add('hidden');
    }
});
</script>
@endpush
@endsection