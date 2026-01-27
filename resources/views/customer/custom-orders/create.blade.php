@extends('layouts.customer')

@section('title', 'Buat Custom Order - Pelangi Weaving')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Buat Custom Order</h1>
            <p class="text-gray-600">Buat pesanan custom sesuai dengan kebutuhan dan desain Anda</p>
        </div>

        <!-- Progress Steps -->
        <div class="flex items-center justify-center mb-12">
            <div class="flex items-center space-x-4 md:space-x-8">
                <!-- Step 1: Detail Produk -->
                <div class="flex items-center">
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-primary-600 text-white rounded-full flex items-center justify-center font-semibold text-sm">
                        1
                    </div>
                    <span class="ml-2 md:ml-3 font-medium text-primary-600 text-sm md:text-base">Detail Produk</span>
                </div>
                
                <div class="w-8 md:w-16 h-1 bg-gray-300"></div>
                
                <!-- Step 2: Spesifikasi -->
                <div class="flex items-center">
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-semibold text-sm">
                        2
                    </div>
                    <span class="ml-2 md:ml-3 font-medium text-gray-600 text-sm md:text-base">Spesifikasi</span>
                </div>
                
                <div class="w-8 md:w-16 h-1 bg-gray-300"></div>
                
                <!-- Step 3: Konfirmasi -->
                <div class="flex items-center">
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-semibold text-sm">
                        3
                    </div>
                    <span class="ml-2 md:ml-3 font-medium text-gray-600 text-sm md:text-base">Konfirmasi</span>
                </div>
            </div>
        </div>

        <form action="{{ route('custom-orders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left: Form -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Informasi Dasar -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Informasi Dasar</h2>
                        
                        <!-- Nama Produk Custom -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Produk Custom *</label>
                            <input type="text" 
                                   name="nama_custom" 
                                   value="{{ old('nama_custom') }}"
                                   required
                                   maxlength="200"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('nama_custom') border-red-500 @enderror"
                                   placeholder="Masukkan nama produk custom Anda">
                            @error('nama_custom')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Kategori -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori *</label>
                                <select name="id_jenis" 
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('id_jenis') border-red-500 @enderror">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($jenisOptions as $jenis)
                                        <option value="{{ $jenis->id_jenis }}" {{ old('id_jenis') == $jenis->id_jenis ? 'selected' : '' }}>
                                            {{ $jenis->nama_jenis }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_jenis')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jenis -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis</label>
                                <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                    <option>Tradisional</option>
                                    <option>Modern</option>
                                    <option>Kontemporer</option>
                                </select>
                            </div>

                            <!-- Jumlah -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah *</label>
                                <input type="number" 
                                       name="jumlah" 
                                       value="{{ old('jumlah', 1) }}"
                                       required
                                       min="1"
                                       max="100"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('jumlah') border-red-500 @enderror">
                                @error('jumlah')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Deskripsi Produk -->
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Produk *</label>
                            <textarea name="deskripsi_custom" 
                                      rows="6"
                                      required
                                      maxlength="2000"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('deskripsi_custom') border-red-500 @enderror"
                                      placeholder="Jelaskan detail custom yang Anda inginkan:&#10;- Ukuran (panjang x lebar)&#10;- Warna dominan&#10;- Motif/desain yang diinginkan&#10;- Bahan yang diinginkan&#10;- Detail lainnya yang penting">{{ old('deskripsi_custom') }}</textarea>
                            <div class="flex justify-between text-sm text-gray-500 mt-1">
                                <span>Jelaskan sedetail mungkin agar kami dapat memahami keinginan Anda</span>
                                <span id="charCount">0/2000</span>
                            </div>
                            @error('deskripsi_custom')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Spesifikasi Tambahan -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Spesifikasi Tambahan</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Ukuran -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ukuran</label>
                                <input type="text" 
                                       name="ukuran"
                                       value="{{ old('ukuran') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                       placeholder="Contoh: 200cm x 115cm">
                            </div>

                            <!-- Warna Dominan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Warna Dominan</label>
                                <input type="text" 
                                       name="warna_dominan"
                                       value="{{ old('warna_dominan') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                       placeholder="Contoh: Merah, Emas, Biru">
                            </div>

                            <!-- Motif -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Motif/Desain</label>
                                <input type="text" 
                                       name="motif"
                                       value="{{ old('motif') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                       placeholder="Contoh: Bunga, Geometris, Tradisional">
                            </div>

                            <!-- Bahan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bahan yang Diinginkan</label>
                                <select name="bahan" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                    <option value="">Pilih Bahan</option>
                                    <option value="sutra">Sutra</option>
                                    <option value="katun">Katun</option>
                                    <option value="polyester">Polyester</option>
                                    <option value="campuran">Campuran</option>
                                </select>
                            </div>
                        </div>

                        <!-- Catatan Tambahan -->
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Tambahan</label>
                            <textarea name="catatan_pelanggan" 
                                      rows="3"
                                      maxlength="1000"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                      placeholder="Catatan tambahan, permintaan khusus, atau informasi lain yang perlu kami ketahui">{{ old('catatan_pelanggan') }}</textarea>
                        </div>

                        <!-- Upload Gambar Referensi -->
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Referensi</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-primary-400 transition-colors">
                                <input type="file" 
                                       name="gambar_referensi[]" 
                                       multiple
                                       accept="image/*"
                                       class="hidden"
                                       id="fileInput"
                                       onchange="previewImages(this)">
                                <label for="fileInput" class="cursor-pointer">
                                    <i class="bi bi-cloud-upload text-4xl text-gray-400 mb-3"></i>
                                    <p class="text-gray-600 mb-2">Klik untuk upload gambar referensi</p>
                                    <p class="text-sm text-gray-500">Maksimal 5 gambar, format JPG/PNG, ukuran maksimal 2MB per gambar</p>
                                </label>
                            </div>
                            
                            <!-- Preview Images -->
                            <div id="imagePreview" class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-4 hidden"></div>
                        </div>
                    </div>
                </div>

                <!-- Right: Ringkasan Pesanan -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sticky top-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Ringkasan Pesanan</h2>

                        <!-- Preview Produk -->
                        <div class="bg-gray-50 rounded-xl p-4 mb-6">
                            <div class="text-center">
                                <div class="w-20 h-20 bg-gray-200 rounded-lg mx-auto mb-3 flex items-center justify-center">
                                    <i class="bi bi-image text-gray-400 text-2xl"></i>
                                </div>
                                <p class="text-sm text-gray-600">Preview Produk</p>
                            </div>
                        </div>

                        <!-- Detail Pesanan -->
                        <div class="space-y-4 mb-6">
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">Nama Produk Custom</h4>
                                <p class="text-sm text-gray-600" id="preview-nama">Akan otomatis terisi berdasarkan form</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600">Kategori:</span>
                                    <p class="font-medium" id="preview-kategori">-</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Jenis:</span>
                                    <p class="font-medium" id="preview-jenis">-</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Jumlah:</span>
                                    <p class="font-medium" id="preview-jumlah">1 pcs</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Estimasi Waktu:</span>
                                    <p class="font-medium text-primary-600">7-14 hari kerja</p>
                                </div>
                            </div>
                        </div>

                        <!-- Estimasi Harga -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
                            <div class="text-center">
                                <h4 class="font-semibold text-yellow-800 mb-2">Estimasi Harga</h4>
                                <p class="text-sm text-yellow-700 mb-3">Akan dihitung setelah admin</p>
                                <div class="bg-yellow-100 rounded-lg p-3">
                                    <p class="text-xs text-yellow-800 font-medium">Harga akan ditentukan berdasarkan:</p>
                                    <ul class="text-xs text-yellow-700 mt-1 space-y-1">
                                        <li>• Kompleksitas desain</li>
                                        <li>• Bahan yang digunakan</li>
                                        <li>• Ukuran produk</li>
                                        <li>• Jumlah pesanan</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Proses Custom Order -->
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                            <h4 class="font-semibold text-blue-800 mb-3">Proses Custom Order</h4>
                            <ol class="text-xs text-blue-700 space-y-2">
                                <li class="flex items-start">
                                    <span class="w-4 h-4 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs mr-2 mt-0.5">1</span>
                                    <span>Submit custom order</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-4 h-4 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-xs mr-2 mt-0.5">2</span>
                                    <span>Admin review & tentukan harga</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-4 h-4 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-xs mr-2 mt-0.5">3</span>
                                    <span>Bayar DP 50%</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-4 h-4 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-xs mr-2 mt-0.5">4</span>
                                    <span>Produksi dimulai</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-4 h-4 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-xs mr-2 mt-0.5">5</span>
                                    <span>Produk selesai & siap kirim</span>
                                </li>
                            </ol>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="space-y-3">
                            <button type="submit" 
                                    class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-4 rounded-xl transition-colors text-lg">
                                <i class="bi bi-send mr-2"></i>
                                Submit Custom Order
                            </button>
                            <a href="{{ route('custom-orders.index') }}" 
                               class="block w-full text-center border-2 border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold py-3 rounded-xl transition-colors">
                                Batal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
    
    // Real-time preview updates
    setupPreviewUpdates();
});

// Setup real-time preview updates
function setupPreviewUpdates() {
    // Nama produk preview
    const namaInput = document.querySelector('input[name="nama_custom"]');
    const previewNama = document.getElementById('preview-nama');
    
    namaInput.addEventListener('input', function() {
        previewNama.textContent = this.value || 'Akan otomatis terisi berdasarkan form';
    });
    
    // Kategori preview
    const kategoriSelect = document.querySelector('select[name="id_jenis"]');
    const previewKategori = document.getElementById('preview-kategori');
    
    kategoriSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        previewKategori.textContent = selectedOption.text !== 'Pilih Kategori' ? selectedOption.text : '-';
    });
    
    // Jumlah preview
    const jumlahInput = document.querySelector('input[name="jumlah"]');
    const previewJumlah = document.getElementById('preview-jumlah');
    
    jumlahInput.addEventListener('input', function() {
        previewJumlah.textContent = (this.value || '1') + ' pcs';
    });
}

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
                        <div class="absolute bottom-2 left-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                            ${file.name.length > 15 ? file.name.substring(0, 15) + '...' : file.name}
                        </div>
                    `;
                    preview.appendChild(div);
                };
                
                reader.readAsDataURL(file);
            }
        });
        
        // Show upload summary
        const summary = document.createElement('div');
        summary.className = 'col-span-full text-center text-sm text-gray-600 bg-gray-50 rounded-lg p-3';
        summary.innerHTML = `
            <i class="bi bi-images mr-2"></i>
            ${files.length} gambar dipilih
        `;
        preview.appendChild(summary);
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
    } else {
        // Refresh preview
        previewImages(input);
    }
}

// Form validation before submit
document.querySelector('form').addEventListener('submit', function(e) {
    const namaCustom = document.querySelector('input[name="nama_custom"]').value.trim();
    const deskripsi = document.querySelector('textarea[name="deskripsi_custom"]').value.trim();
    const kategori = document.querySelector('select[name="id_jenis"]').value;
    const jumlah = document.querySelector('input[name="jumlah"]').value;
    
    if (!namaCustom || !deskripsi || !kategori || !jumlah) {
        e.preventDefault();
        alert('Mohon lengkapi semua field yang wajib diisi (*)');
        return false;
    }
    
    if (deskripsi.length < 50) {
        e.preventDefault();
        alert('Deskripsi produk minimal 50 karakter untuk membantu kami memahami kebutuhan Anda');
        return false;
    }
    
    // Show loading state
    const submitBtn = document.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<div class="flex items-center justify-center"><svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Memproses...</div>';
    
    // Re-enable after 10 seconds as fallback
    setTimeout(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }, 10000);
});

// Auto-save draft functionality (optional)
let autoSaveTimer;
function autoSaveDraft() {
    clearTimeout(autoSaveTimer);
    autoSaveTimer = setTimeout(() => {
        const formData = new FormData(document.querySelector('form'));
        // Here you could implement auto-save to localStorage or server
        console.log('Auto-saving draft...');
    }, 5000);
}

// Add auto-save listeners to form inputs
document.querySelectorAll('input, textarea, select').forEach(input => {
    input.addEventListener('input', autoSaveDraft);
    input.addEventListener('change', autoSaveDraft);
});
</script>
@endpush
@endsection