@extends('layouts.admin')

@section('title', 'Detail Custom Order')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="flex items-center justify-center gap-3 mb-4">
                <a href="{{ route('admin.custom-orders.index') }}" class="text-gray-500 hover:text-gray-700">
                    <i class="bi bi-arrow-left text-2xl"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">{{ $customOrder->nomor_custom_order }}</h1>
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
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $statusColors[$customOrder->status] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ ucfirst(str_replace('_', ' ', $customOrder->status)) }}
                </span>
            </div>
            <p class="text-gray-600 text-lg">{{ $customOrder->nama_custom }}</p>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex justify-center gap-3 mb-8">
            @if($customOrder->status === 'pending_approval')
                <button onclick="approveOrder()" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                    <i class="bi bi-check-circle mr-2"></i>Setujui
                </button>
                <button onclick="rejectOrder()" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                    <i class="bi bi-x-circle mr-2"></i>Tolak
                </button>
            @endif
            
            @if(in_array($customOrder->status, ['approved', 'in_production']))
                <button onclick="updateProgress()" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    <i class="bi bi-plus-circle mr-2"></i>Update Progress
                </button>
            @endif
            
            <button onclick="updateStatus()" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition font-medium">
                <i class="bi bi-pencil mr-2"></i>Update Status
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Detail Custom Order -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Detail Custom Order</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Custom</label>
                            <p class="text-sm text-gray-900">{{ $customOrder->jenis->nama_jenis }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                            <p class="text-sm text-gray-900">{{ $customOrder->jumlah }} pcs</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Harga Final</label>
                            <p class="text-sm text-gray-900">
                                @if($customOrder->harga_final > 0)
                                    Rp {{ number_format($customOrder->harga_final, 0, ',', '.') }}
                                @else
                                    <span class="text-gray-400">Belum ditentukan</span>
                                @endif
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">DP Amount</label>
                            <p class="text-sm text-gray-900">
                                @if($customOrder->dp_amount > 0)
                                    Rp {{ number_format($customOrder->dp_amount, 0, ',', '.') }}
                                    @if($customOrder->isDpPaid())
                                        <span class="text-green-600 text-xs ml-2">✓ Sudah dibayar</span>
                                    @else
                                        <span class="text-red-600 text-xs ml-2">✗ Belum dibayar</span>
                                    @endif
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Custom</label>
                        <p class="text-sm text-gray-900 whitespace-pre-line">{{ $customOrder->deskripsi_custom }}</p>
                    </div>
                    
                    @if($customOrder->catatan_pelanggan)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Pelanggan</label>
                            <p class="text-sm text-gray-900 whitespace-pre-line">{{ $customOrder->catatan_pelanggan }}</p>
                        </div>
                    @endif
                </div>

                <!-- Gambar Referensi -->
                @if($customOrder->gambar_referensi && count($customOrder->gambar_referensi) > 0)
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Gambar Referensi</h2>
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
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
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Riwayat Progress</h2>
                        
                        <div class="space-y-6">
                            @foreach(array_reverse($customOrder->progress_history) as $history)
                                <div class="flex gap-4 p-6 bg-gray-50 rounded-xl">
                                    <div class="flex-shrink-0">
                                        @if(isset($history['status']))
                                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="bi bi-arrow-right text-blue-600 text-lg"></i>
                                            </div>
                                        @else
                                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                                <i class="bi bi-chat-dots text-green-600 text-lg"></i>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
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
                                            <p class="text-sm text-green-600">Harga final: Rp {{ number_format($history['harga_final'], 0, ',', '.') }}</p>
                                        @endif
                                        
                                        @if(isset($history['images']) && count($history['images']) > 0)
                                            <div class="flex gap-2 mt-3">
                                                @foreach($history['images'] as $image)
                                                    <img src="{{ Storage::url($image) }}" 
                                                         alt="Progress" 
                                                         class="w-20 h-20 object-cover rounded cursor-pointer hover:opacity-75 transition"
                                                         onclick="showImageModal('{{ Storage::url($image) }}')">
                                                @endforeach
                                            </div>
                                        @endif
                                        
                                        <p class="text-xs text-gray-500 mt-2">oleh {{ $history['admin'] ?? 'System' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <!-- Info Pelanggan -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Info Pelanggan</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                            <p class="text-sm text-gray-900">{{ $customOrder->pelanggan->nama }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <p class="text-sm text-gray-900">{{ $customOrder->pelanggan->email }}</p>
                        </div>
                        
                        @if($customOrder->pelanggan->telepon)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Telepon</label>
                                <p class="text-sm text-gray-900">{{ $customOrder->pelanggan->telepon }}</p>
                            </div>
                        @endif
                        
                        @if($customOrder->pelanggan->whatsapp)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">WhatsApp</label>
                                <a href="https://wa.me/{{ $customOrder->pelanggan->whatsapp }}" 
                                   target="_blank"
                                   class="text-sm text-green-600 hover:text-green-700">
                                    {{ $customOrder->pelanggan->whatsapp }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Info Tanggal -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Info Tanggal</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dibuat</label>
                            <p class="text-sm text-gray-900">{{ $customOrder->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Terakhir Update</label>
                            <p class="text-sm text-gray-900">{{ $customOrder->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                        
                        @if($customOrder->admin)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Update oleh</label>
                                <p class="text-sm text-gray-900">{{ $customOrder->admin->nama }}</p>
                            </div>
                        @endif
                        
                        @if($customOrder->dp_paid_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">DP Dibayar</label>
                                <p class="text-sm text-green-600">{{ $customOrder->dp_paid_at->format('d/m/Y H:i') }}</p>
                            </div>
                        @endif
                        
                        @if($customOrder->fully_paid_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Lunas</label>
                                <p class="text-sm text-green-600">{{ $customOrder->fully_paid_at->format('d/m/Y H:i') }}</p>
                            </div>
                        @endif
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

<!-- Approve Modal -->
<div id="approveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
            <form action="{{ route('admin.custom-orders.approve', $customOrder->nomor_custom_order) }}" method="POST">
                @csrf
                <div class="p-8">
                    <h3 class="text-xl font-medium text-gray-900 mb-6">Setujui Custom Order</h3>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Harga Final *</label>
                        <input type="number" 
                               name="harga_final" 
                               required
                               min="1000"
                               step="1000"
                               value="{{ $customOrder->harga_final }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600">
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Admin</label>
                        <textarea name="catatan_admin" 
                                  rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600"
                                  placeholder="Catatan untuk pelanggan (opsional)"></textarea>
                    </div>
                </div>
                
                <div class="px-8 py-4 bg-gray-50 flex justify-end gap-3 rounded-b-xl">
                    <button type="button" 
                            onclick="closeModal('approveModal')"
                            class="px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-6 py-3 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
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
            <form action="{{ route('admin.custom-orders.reject', $customOrder->nomor_custom_order) }}" method="POST">
                @csrf
                <div class="p-8">
                    <h3 class="text-xl font-medium text-gray-900 mb-6">Tolak Custom Order</h3>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan *</label>
                        <textarea name="catatan_admin" 
                                  rows="4"
                                  required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600"
                                  placeholder="Jelaskan alasan penolakan..."></textarea>
                    </div>
                </div>
                
                <div class="px-8 py-4 bg-gray-50 flex justify-end gap-3 rounded-b-xl">
                    <button type="button" 
                            onclick="closeModal('rejectModal')"
                            class="px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-6 py-3 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                        Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
            <form action="{{ route('admin.custom-orders.update-status', $customOrder->nomor_custom_order) }}" method="POST">
                @csrf
                <div class="p-8">
                    <h3 class="text-xl font-medium text-gray-900 mb-6">Update Status</h3>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select name="status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600">
                            <option value="draft" {{ $customOrder->status == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="pending_approval" {{ $customOrder->status == 'pending_approval' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                            <option value="approved" {{ $customOrder->status == 'approved' ? 'selected' : '' }}>Disetujui</option>
                            <option value="in_production" {{ $customOrder->status == 'in_production' ? 'selected' : '' }}>Dalam Produksi</option>
                            <option value="completed" {{ $customOrder->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="cancelled" {{ $customOrder->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            <option value="rejected" {{ $customOrder->status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Harga Final</label>
                        <input type="number" 
                               name="harga_final" 
                               min="0"
                               step="1000"
                               value="{{ $customOrder->harga_final }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600">
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Admin</label>
                        <textarea name="catatan_admin" 
                                  rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600"
                                  placeholder="Catatan untuk pelanggan (opsional)"></textarea>
                    </div>
                </div>
                
                <div class="px-8 py-4 bg-gray-50 flex justify-end gap-3 rounded-b-xl">
                    <button type="button" 
                            onclick="closeModal('statusModal')"
                            class="px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-6 py-3 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Progress Modal -->
<div id="progressModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
            <form action="{{ route('admin.custom-orders.update-progress', $customOrder->nomor_custom_order) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-8">
                    <h3 class="text-xl font-medium text-gray-900 mb-6">Update Progress</h3>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Progress *</label>
                        <textarea name="progress_note" 
                                  rows="4"
                                  required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600"
                                  placeholder="Jelaskan progress terkini..."></textarea>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto Progress</label>
                        <input type="file" 
                               name="progress_images[]" 
                               multiple
                               accept="image/*"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600">
                        <p class="text-xs text-gray-500 mt-1">Maksimal 5 foto, format JPG/PNG</p>
                    </div>
                </div>
                
                <div class="px-8 py-4 bg-gray-50 flex justify-end gap-3 rounded-b-xl">
                    <button type="button" 
                            onclick="closeModal('progressModal')"
                            class="px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-6 py-3 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        Update Progress
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function approveOrder() {
    document.getElementById('approveModal').classList.remove('hidden');
}

function rejectOrder() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function updateStatus() {
    document.getElementById('statusModal').classList.remove('hidden');
}

function updateProgress() {
    document.getElementById('progressModal').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function showImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('bg-opacity-50') || e.target.classList.contains('bg-opacity-75')) {
        e.target.classList.add('hidden');
    }
});
</script>
@endpush
@endsection