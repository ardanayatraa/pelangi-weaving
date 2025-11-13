@extends('layouts.admin')

@section('title', $product->name)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.products.index') }}" class="text-gray-700 hover:text-gray-900 font-medium inline-flex items-center">
        <i class="bi bi-arrow-left mr-1"></i> Kembali ke Daftar Produk
    </a>
</div>

<div class="flex items-center justify-between mb-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h1>
        <p class="text-sm text-gray-600 mt-1">Detail produk dan varian</p>
    </div>
    <div class="flex items-center space-x-2">
        <a href="{{ route('admin.products.edit', $product) }}" 
           class="btn-primary px-4 py-2 text-sm font-semibold inline-flex items-center">
            <i class="bi bi-pencil mr-1"></i> Edit
        </a>
        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    onclick="return confirm('Yakin hapus produk ini?')"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 text-sm font-semibold">
                <i class="bi bi-trash mr-1"></i> Hapus
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-4">
        <!-- Product Info -->
        <div class="bg-white border border-gray-200">
            <div class="px-4 py-3 border-b border-gray-200" style="background: #f9f9f9;">
                <h2 class="text-sm font-bold text-gray-900 uppercase">Informasi Produk</h2>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-600 mb-1">Nama Produk</p>
                        <p class="font-semibold text-gray-900">{{ $product->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 mb-1">Kategori</p>
                        <p class="font-semibold text-gray-900">{{ $product->category->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 mb-1">Harga Dasar</p>
                        <p class="font-bold text-gray-900 text-lg">Rp {{ number_format($product->base_price, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 mb-1">Berat</p>
                        <p class="font-semibold text-gray-900">{{ $product->weight }} gram</p>
                    </div>
                </div>

                <div class="mt-4">
                    <p class="text-xs text-gray-600 mb-1">Deskripsi</p>
                    <p class="text-sm text-gray-900">{{ $product->description }}</p>
                </div>

                <div class="mt-4 flex items-center space-x-2">
                    @if($product->is_active)
                        <span class="badge-status bg-green-100 text-green-800">
                            <i class="bi bi-check-circle mr-1"></i> Aktif
                        </span>
                    @else
                        <span class="badge-status bg-gray-100 text-gray-800">
                            <i class="bi bi-x-circle mr-1"></i> Nonaktif
                        </span>
                    @endif
                    
                    @if($product->is_featured)
                        <span class="badge-status bg-yellow-100 text-yellow-800">
                            <i class="bi bi-star mr-1"></i> Unggulan
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Product Images -->
        @if($product->images->count() > 0)
        <div class="bg-white border border-gray-200">
            <div class="px-4 py-3 border-b border-gray-200" style="background: #f9f9f9;">
                <h2 class="text-sm font-bold text-gray-900 uppercase">Gambar Produk</h2>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-5 gap-2">
                    @foreach($product->images as $image)
                    <div class="relative cursor-pointer" onclick="openImageModal('{{ asset('storage/' . $image->image_path) }}', '{{ $product->name }}')">
                        <img src="{{ asset('storage/' . $image->image_path) }}" 
                             alt="{{ $product->name }}"
                             class="w-full h-24 object-cover border border-gray-200 bg-gray-100">
                        @if($image->is_primary)
                        <span class="absolute top-1 left-1 px-1.5 py-0.5 bg-red-600 text-white text-xs font-bold">
                            UTAMA
                        </span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Product Variants -->
        <div class="bg-white border border-gray-200" x-data="{ 
            showModal: false, 
            showEditModal: false, 
            showDeleteModal: false,
            editVariantId: null,
            deleteVariantId: null,
            deleteVariantName: ''
        }">
            <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between" style="background: #f9f9f9;">
                <h2 class="text-sm font-bold text-gray-900 uppercase">Varian Produk ({{ $product->variants->count() }})</h2>
                <button @click="showModal = true" 
                        class="btn-primary px-3 py-1.5 text-xs font-semibold">
                    <i class="bi bi-plus-circle mr-1"></i> Tambah Varian
                </button>
            </div>
            
            <!-- Modal Add Variant -->
            <div x-show="showModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-50 overflow-y-auto"
                 style="display: none;">
                <div class="flex items-center justify-center min-h-screen px-4">
                    <!-- Backdrop -->
                    <div @click="showModal = false" class="fixed inset-0 bg-black bg-opacity-50"></div>
                    
                    <!-- Modal Content -->
                    <div class="relative bg-white w-full max-w-2xl border border-gray-300 shadow-lg">
                        <!-- Modal Header -->
                        <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between" style="background: #f9f9f9;">
                            <h3 class="text-sm font-bold text-gray-900 uppercase">Tambah Varian Baru</h3>
                            <button @click="showModal = false" class="text-gray-500 hover:text-gray-700">
                                <i class="bi bi-x-lg text-xl"></i>
                            </button>
                        </div>
                        
                        <!-- Modal Body -->
                        <form action="{{ route('admin.products.variants.store', $product) }}" method="POST" enctype="multipart/form-data" class="p-4">
                            @csrf
                            <div class="space-y-3">
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">Warna *</label>
                                        <input type="text" name="color" required
                                               class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400"
                                               placeholder="Merah Marun">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">Ukuran *</label>
                                        <input type="text" name="size" required
                                               class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400"
                                               placeholder="2 x 1 meter">
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">Jenis Benang *</label>
                                        <input type="text" name="thread_type" required
                                               class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400"
                                               placeholder="Benang Emas 24K">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Varian *</label>
                                        <input type="text" name="name" required
                                               class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400"
                                               placeholder="Merah Marun - 2x1m">
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">Harga (Rp) *</label>
                                        <input type="number" name="price" required min="0"
                                               class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400"
                                               placeholder="2500000">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">Stok *</label>
                                        <input type="number" name="stock_quantity" required min="0"
                                               class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400"
                                               placeholder="10">
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">Berat (gram)</label>
                                        <input type="number" name="weight" min="0" value="{{ $product->weight }}"
                                               class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">SKU</label>
                                        <input type="text" name="sku"
                                               class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400"
                                               placeholder="PW-001-MER">
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Gambar Varian (Opsional)</label>
                                    <input type="file" name="images[]" multiple accept="image/*" id="variantImages"
                                           class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400">
                                    <p class="mt-1 text-xs text-gray-500">Upload gambar khusus untuk varian ini. Format: JPG, PNG, JPEG.</p>
                                    
                                    <!-- Preview Container -->
                                    <div id="variantImagePreview" class="grid grid-cols-4 gap-2 mt-3" style="display: none;"></div>
                                </div>
                            </div>
                            
                            <!-- Modal Footer -->
                            <div class="flex items-center justify-end space-x-2 mt-4 pt-4 border-t border-gray-200">
                                <button type="button" 
                                        @click="showModal = false"
                                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 text-sm font-semibold">
                                    Batal
                                </button>
                                <button type="submit" 
                                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 text-sm font-semibold">
                                    <i class="bi bi-check-circle mr-1"></i> Simpan Varian
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Modal Edit Variant -->
            @foreach($product->variants as $variant)
            <div x-show="showEditModal && editVariantId === {{ $variant->id }}" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-50 overflow-y-auto"
                 style="display: none;">
                <div class="flex items-center justify-center min-h-screen px-4">
                    <div @click="showEditModal = false" class="fixed inset-0 bg-black bg-opacity-50"></div>
                    
                    <div class="relative bg-white w-full max-w-2xl border border-gray-300 shadow-lg">
                        <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between" style="background: #f9f9f9;">
                            <h3 class="text-sm font-bold text-gray-900 uppercase">Edit Varian: {{ $variant->name }}</h3>
                            <button @click="showEditModal = false" class="text-gray-500 hover:text-gray-700">
                                <i class="bi bi-x-lg text-xl"></i>
                            </button>
                        </div>
                        
                        <form action="{{ route('admin.products.variants.update', [$product, $variant]) }}" method="POST" enctype="multipart/form-data" class="p-4">
                            @csrf
                            @method('PUT')
                            <div class="space-y-3">
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">Warna *</label>
                                        <input type="text" name="color" required value="{{ $variant->color }}"
                                               class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">Ukuran *</label>
                                        <input type="text" name="size" required value="{{ $variant->size }}"
                                               class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400">
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">Jenis Benang *</label>
                                        <input type="text" name="thread_type" required value="{{ $variant->thread_type }}"
                                               class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Varian *</label>
                                        <input type="text" name="name" required value="{{ $variant->name }}"
                                               class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400">
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">Harga (Rp) *</label>
                                        <input type="number" name="price" required min="0" value="{{ $variant->price }}"
                                               class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">Stok *</label>
                                        <input type="number" name="stock_quantity" required min="0" value="{{ $variant->stock_quantity }}"
                                               class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400">
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">Berat (gram)</label>
                                        <input type="number" name="weight" min="0" value="{{ $variant->weight }}"
                                               class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">SKU</label>
                                        <input type="text" name="sku" value="{{ $variant->sku }}"
                                               class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400">
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between py-2 border-t border-gray-200">
                                    <label class="text-xs font-semibold text-gray-700">Status Aktif</label>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ $variant->is_active ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                    </label>
                                </div>
                                
                                @if($variant->images->count() > 0)
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Gambar Saat Ini</label>
                                    <div class="grid grid-cols-4 gap-2">
                                        @foreach($variant->images as $image)
                                        <img src="{{ asset('storage/' . $image->image_path) }}" 
                                             alt="{{ $variant->name }}"
                                             class="w-full h-20 object-cover border border-gray-200">
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Upload Gambar Baru (Opsional)</label>
                                    <input type="file" name="images[]" multiple accept="image/*" id="variantImagesEdit{{ $variant->id }}"
                                           class="w-full px-3 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-400">
                                    <p class="mt-1 text-xs text-gray-500">Upload gambar baru akan menambahkan ke gambar yang sudah ada.</p>
                                    
                                    <!-- Preview Container -->
                                    <div id="variantImagePreviewEdit{{ $variant->id }}" class="grid grid-cols-4 gap-2 mt-3" style="display: none;"></div>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-end space-x-2 mt-4 pt-4 border-t border-gray-200">
                                <button type="button" 
                                        @click="showEditModal = false"
                                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 text-sm font-semibold">
                                    Batal
                                </button>
                                <button type="submit" 
                                        class="btn-primary px-4 py-2 text-sm font-semibold">
                                    <i class="bi bi-check-circle mr-1"></i> Update Varian
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
            
            <!-- Modal Delete Confirmation -->
            <div x-show="showDeleteModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-50 overflow-y-auto"
                 style="display: none;">
                <div class="flex items-center justify-center min-h-screen px-4">
                    <div @click="showDeleteModal = false" class="fixed inset-0 bg-black bg-opacity-50"></div>
                    
                    <div class="relative bg-white w-full max-w-md border border-gray-300 shadow-lg">
                        <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between" style="background: #f9f9f9;">
                            <h3 class="text-sm font-bold text-gray-900 uppercase">Konfirmasi Hapus</h3>
                            <button @click="showDeleteModal = false" class="text-gray-500 hover:text-gray-700">
                                <i class="bi bi-x-lg text-xl"></i>
                            </button>
                        </div>
                        
                        <div class="p-4">
                            <div class="flex items-start space-x-3 mb-4">
                                <div class="w-12 h-12 bg-red-100 flex items-center justify-center flex-shrink-0">
                                    <i class="bi bi-exclamation-triangle text-red-600 text-2xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 mb-1">Yakin hapus varian ini?</p>
                                    <p class="text-sm text-gray-600" x-text="deleteVariantName"></p>
                                    <p class="text-xs text-red-600 mt-2">Tindakan ini tidak dapat dibatalkan!</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-end space-x-2">
                                <button @click="showDeleteModal = false" 
                                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 text-sm font-semibold">
                                    Batal
                                </button>
                                <form :action="'/admin/products/{{ $product->id }}/variants/' + deleteVariantId" 
                                      method="POST" 
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 text-sm font-semibold">
                                        <i class="bi bi-trash mr-1"></i> Ya, Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Gambar</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">SKU</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Varian</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Harga</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Stok</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Status</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($product->variants as $variant)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-5">
                                @if($variant->images->first())
                                    <div class="w-14 h-14 overflow-hidden rounded border border-gray-200 bg-gray-100 cursor-pointer" 
                                         onclick="openImageModal('{{ asset('storage/' . $variant->images->first()->image_path) }}', '{{ $variant->name }}')">
                                        <img src="{{ asset('storage/' . $variant->images->first()->image_path) }}" 
                                             alt="{{ $variant->name }}"
                                             class="w-full h-full object-cover">
                                    </div>
                                @elseif($product->images->first())
                                    <div class="w-14 h-14 overflow-hidden rounded border border-gray-200 bg-gray-100 opacity-50 cursor-pointer"
                                         onclick="openImageModal('{{ asset('storage/' . $product->images->first()->image_path) }}', '{{ $product->name }}')">
                                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                             alt="{{ $product->name }}"
                                             class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div class="w-14 h-14 bg-gray-100 rounded border border-gray-200 flex items-center justify-center">
                                        <i class="bi bi-image text-gray-400"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-sm text-gray-600">{{ $variant->sku }}</td>
                            <td class="px-6 py-5">
                                <div class="text-sm font-semibold text-gray-900">{{ $variant->name }}</div>
                                <div class="text-xs text-gray-500">{{ $variant->color }} • {{ $variant->size }}</div>
                            </td>
                            <td class="px-6 py-5 text-sm font-semibold text-gray-900">
                                Rp {{ number_format($variant->price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $variant->stock_quantity > 10 ? 'bg-green-50 text-green-700' : ($variant->stock_quantity > 0 ? 'bg-yellow-50 text-yellow-700' : 'bg-red-50 text-red-700') }}">
                                    {{ $variant->stock_quantity }} unit
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                @if($variant->is_active)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700">Aktif</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center justify-center gap-3">
                                    <button @click="editVariantId = {{ $variant->id }}; showEditModal = true" 
                                            class="text-blue-600 hover:text-blue-800 transition-colors"
                                            title="Edit">
                                        <i class="bi bi-pencil-square text-lg"></i>
                                    </button>
                                    <button @click="deleteVariantId = {{ $variant->id }}; deleteVariantName = '{{ $variant->name }}'; showDeleteModal = true" 
                                            class="text-red-600 hover:text-red-800 transition-colors"
                                            title="Hapus">
                                        <i class="bi bi-trash text-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-400 text-sm">
                                    Belum ada varian. <button @click="showModal = true" class="text-blue-600 hover:text-blue-800 font-medium">Tambah varian pertama</button>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-4">
        <!-- Quick Stats -->
        <div class="bg-white border border-gray-200 p-4">
            <h2 class="text-sm font-bold text-gray-900 uppercase mb-3">Statistik</h2>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-600">Total Varian</span>
                    <span class="font-bold text-gray-900">{{ $product->variants->count() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-600">Total Stok</span>
                    <span class="font-bold text-gray-900">{{ $product->variants->sum('stock_quantity') }} unit</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-600">Harga Terendah</span>
                    <span class="font-bold text-gray-900">Rp {{ number_format($product->variants->min('price') ?? $product->base_price, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-600">Harga Tertinggi</span>
                    <span class="font-bold text-gray-900">Rp {{ number_format($product->variants->max('price') ?? $product->base_price, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Meta Info -->
        <div class="bg-white border border-gray-200 p-4">
            <h2 class="text-sm font-bold text-gray-900 uppercase mb-3">Informasi Lainnya</h2>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-xs text-gray-600 mb-1">Dibuat</p>
                    <p class="font-medium text-gray-900 text-xs">{{ $product->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-600 mb-1">Terakhir Diupdate</p>
                    <p class="font-medium text-gray-900 text-xs">{{ $product->updated_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-600 mb-1">Slug</p>
                    <p class="font-mono text-xs text-gray-900 bg-gray-100 px-2 py-1">{{ $product->slug }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 z-50 hidden" style="backdrop-filter: blur(8px); background-color: rgba(0, 0, 0, 0.7);" onclick="closeImageModal()">
    <div class="flex items-center justify-center min-h-screen p-4">
        <!-- Close Button -->
        <button onclick="closeImageModal()" class="fixed top-4 right-4 z-10 w-10 h-10 flex items-center justify-center bg-white hover:bg-gray-100 text-gray-800 rounded-full shadow-lg transition-all duration-200">
            <i class="bi bi-x text-2xl font-bold"></i>
        </button>
        
        <!-- Image Container -->
        <div class="relative max-w-2xl w-full" onclick="event.stopPropagation()">
            <img id="modalImage" src="" alt="" class="w-full h-auto max-h-[60vh] object-contain bg-white border-4 border-white shadow-2xl rounded">
        </div>
    </div>
</div>

@push('scripts')
<script>
// Image Modal Functions
function openImageModal(imageSrc, altText) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('modalImage').alt = altText;
    document.getElementById('imageModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});

// Image Preview for Add Variant
const variantImagesInput = document.getElementById('variantImages');
if (variantImagesInput) {
    variantImagesInput.addEventListener('change', function(e) {
        const preview = document.getElementById('variantImagePreview');
        preview.innerHTML = '';
        preview.style.display = 'grid';
        
        const files = Array.from(e.target.files);
        
        files.forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-20 object-cover border border-gray-200">
                        <span class="absolute top-1 left-1 px-2 py-0.5 bg-black bg-opacity-70 text-white text-xs font-bold">
                            ${index + 1}
                        </span>
                    `;
                    preview.appendChild(div);
                };
                
                reader.readAsDataURL(file);
            }
        });
        
        if (files.length === 0) {
            preview.style.display = 'none';
        }
    });
}

// Image Preview for Edit Variant (multiple variants)
@foreach($product->variants as $variant)
const variantImagesEdit{{ $variant->id }} = document.getElementById('variantImagesEdit{{ $variant->id }}');
if (variantImagesEdit{{ $variant->id }}) {
    variantImagesEdit{{ $variant->id }}.addEventListener('change', function(e) {
        const preview = document.getElementById('variantImagePreviewEdit{{ $variant->id }}');
        preview.innerHTML = '';
        preview.style.display = 'grid';
        
        const files = Array.from(e.target.files);
        
        files.forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-20 object-cover border border-gray-200">
                        <span class="absolute top-1 left-1 px-2 py-0.5 bg-black bg-opacity-70 text-white text-xs font-bold">
                            NEW ${index + 1}
                        </span>
                    `;
                    preview.appendChild(div);
                };
                
                reader.readAsDataURL(file);
            }
        });
        
        if (files.length === 0) {
            preview.style.display = 'none';
        }
    });
}
@endforeach
</script>
@endpush
