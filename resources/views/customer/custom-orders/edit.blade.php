@extends('layouts.customer')

@section('title', 'Edit Custom Order')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-2xl mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Edit Custom Order</h1>
            <p class="text-gray-600">{{ $customOrder->nomor_custom_order }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8">
            <form action="{{ route('custom-orders.update', $customOrder->nomor_custom_order) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Jenis Custom -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-4">Jenis Custom *</label>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($jenisOptions as $jenis)
                            <label class="relative cursor-pointer">
                                <input type="radio" 
                                       name="id_jenis" 
                                       value="{{ $jenis->id_jenis }}" 
                                       class="sr-only peer"
                                       {{ old('id_jenis', $customOrder->id_jenis) == $jenis->id_jenis ? 'checked' : '' }}
                                       required>
                                <div class="p-4 border-2 border-gray-200 rounded-lg peer-checked:border-red-500 peer-checked:bg-red-50 hover:border-gray-300 transition">
                                    <div class="flex items-center gap-3">
                                        @if($jenis->icon)
                                            <i class="{{ $jenis->icon }} text-2xl text-red-600"></i>
                                        @else
                                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                                <i class="bi bi-tag text-red-600"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h3 class="font-medium text-gray-900">{{ $jenis->nama_jenis }}</h3>
                                            @if($jenis->deskripsi)
                                                <p class="text-sm text-gray-600">{{ Str::limit($jenis->deskripsi, 60) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    
                    @error('id_jenis')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Custom -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Custom *</label>
                    <input type="text" 
                           name="nama_custom" 
                           value="{{ old('nama_custom', $customOrder->nama_custom) }}"
                           required
                           maxlength="200"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600 @error('nama_custom') border-red-500 @enderror"
                           placeholder="Masukkan nama custom">
                    @error('nama_custom')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Deskripsi Custom -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Custom *</label>
                    <textarea name="deskripsi_custom" 
                              rows="6"
                              required
                              maxlength="2000"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600 @error('deskripsi_custom') border-red-500 @enderror"
                              placeholder="Jelaskan detail custom yang Anda inginkan:&#10;- Ukuran&#10;- Warna&#10;- Motif/desain&#10;- Bahan yang diinginkan&#10;- Detail lainnya">{{ old('deskripsi_custom', $customOrder->deskripsi_custom) }}</textarea>
                    <div class="flex justify-between text-sm text-gray-500 mt-1">
                        <span>Jelaskan sedetail mungkin agar kami dapat memahami keinginan Anda</span>
                        <span id="charCount">0/2000</span>
                    </div>
                    @error('deskripsi_custom')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Jumlah -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah *</label>
                    <input type="number" 
                           name="jumlah" 
                           value="{{ old('jumlah', $customOrder->jumlah) }}"
                           required
                           min="1"
                           max="100"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600 @error('jumlah') border-red-500 @enderror"
                           placeholder="Masukkan jumlah">
                    @error('jumlah')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Catatan Tambahan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Tambahan</label>
                    <textarea name="catatan_pelanggan" 
                              rows="3"
                              maxlength="1000"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600 @error('catatan_pelanggan') border-red-500 @enderror"
                              placeholder="Catatan tambahan, permintaan khusus, atau informasi lain yang perlu kami ketahui">{{ old('catatan_pelanggan', $customOrder->catatan_pelanggan) }}</textarea>
                    @error('catatan_pelanggan')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gambar Referensi Existing -->
                @if($customOrder->gambar_referensi && count($customOrder->gambar_referensi) > 0)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Referensi Saat Ini</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-4">
                            @foreach($customOrder->gambar_referensi as $index => $image)
                                <div class="relative aspect-square bg-gray-100 rounded-lg overflow-hidden">
                                    <img src="{{ Storage::url($image) }}" alt="Referensi {{ $index + 1 }}" class="w-full h-full object-cover">
                                    <button type="button" 
                                            onclick="removeExistingImage({{ $index }})"
                                            class="absolute top-2 right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600 transition">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Gambar Referensi Baru -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tambah Gambar Referensi</label>
                    <p class="text-sm text-gray-600 mb-4">Upload gambar referensi tambahan (opsional)</p>
                    
                    <input type="file" 
                           name="gambar_referensi[]" 
                           multiple
                           accept="image/*"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-red-600 @error('gambar_referensi.*') border-red-500 @enderror"
                           onchange="previewImages(this)">
                    <p class="text-xs text-gray-500 mt-1">Maksimal 5 gambar, format JPG/PNG, ukuran maksimal 2MB per gambar</p>
                    @error('gambar_referensi.*')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    
                    <!-- Preview Images -->
                    <div id="imagePreview" class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-4 hidden"></div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 pt-4">
                    <a href="{{ route('custom-orders.show', $customOrder->nomor_custom_order) }}" 
                       class="flex-1 px-6 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition text-center font-medium">
                        Batal
                    </a>
                    <button type="submit" 
                            class="flex-1 px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                        <i class="bi bi-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Character counter for description
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.querySelector('textarea[name="deskripsi_custom"]');
    const charCount = document.getElementById('charCount');
    
    function updateCharCount() {
        const count = textarea.value.length;
        charCount.textContent = count + '/2000';
        
        if (count > 1800) {
            charCount.classList.add('text-red-500');
        } else {
            charCount.classList.remove('text-red-500');
        }
    }
    
    textarea.addEventListener('input', updateCharCount);
    updateCharCount(); // Initial count
});

// Image preview
function previewImages(input) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    
    if (input.files && input.files.length > 0) {
        preview.classList.remove('hidden');
        
        // Limit to 5 images
        const files = Array.from(input.files).slice(0, 5);
        
        files.forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative aspect-square bg-gray-100 rounded-lg overflow-hidden';
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="Preview ${index + 1}" class="w-full h-full object-cover">
                        <button type="button" 
                                onclick="removePreview(this, ${index})"
                                class="absolute top-2 right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600 transition">
                            <i class="bi bi-x"></i>
                        </button>
                    `;
                    preview.appendChild(div);
                };
                
                reader.readAsDataURL(file);
            }
        });
    } else {
        preview.classList.add('hidden');
    }
}

function removePreview(button, index) {
    const input = document.querySelector('input[name="gambar_referensi[]"]');
    const preview = document.getElementById('imagePreview');
    
    // Remove preview element
    button.parentElement.remove();
    
    // Create new FileList without the removed file
    const dt = new DataTransfer();
    const files = Array.from(input.files);
    
    files.forEach((file, i) => {
        if (i !== index) {
            dt.items.add(file);
        }
    });
    
    input.files = dt.files;
    
    // Hide preview if no images left
    if (input.files.length === 0) {
        preview.classList.add('hidden');
    }
}

function removeExistingImage(index) {
    if (confirm('Yakin ingin menghapus gambar ini?')) {
        fetch(`{{ route('custom-orders.remove-image', $customOrder->nomor_custom_order) }}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                image_index: index
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal menghapus gambar: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus gambar');
        });
    }
}
</script>
@endpush
@endsection