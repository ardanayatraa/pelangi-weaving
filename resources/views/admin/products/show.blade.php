@extends('layouts.admin')

@section('title', $product->nama_produk)
@section('page-title', 'Detail Produk')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <a href="{{ route('admin.products.index') }}" class="inline-flex items-center text-gray-700 hover:text-gray-900 font-medium">
        <i class="bi bi-arrow-left mr-2"></i> Kembali
    </a>
    <div class="flex space-x-2">
        <a href="{{ route('admin.products.edit', $product->slug) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
            <i class="bi bi-pencil mr-2"></i> Edit
        </a>
        <form action="{{ route('admin.products.destroy', $product->slug) }}" method="POST" class="inline-block">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    onclick="return confirm('Yakin hapus produk ini?')"
                    class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition">
                <i class="bi bi-trash mr-2"></i> Hapus
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Product Info -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h5 class="text-lg font-semibold text-gray-800">Informasi Produk</h5>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Nama Produk</label>
                        <p class="font-bold text-gray-800">{{ $product->nama_produk }}</p>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Kategori</label>
                        <p class="font-bold text-gray-800">{{ $product->category->nama_kategori }}</p>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Harga</label>
                        <p class="font-bold text-blue-600 text-xl">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Berat</label>
                        <p class="font-bold text-gray-800">{{ $product->berat }} gram</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs text-gray-500 mb-1">Deskripsi</label>
                        <p class="text-gray-700">{{ $product->deskripsi }}</p>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Status</label>
                        <div class="flex gap-2">
                            @if($product->status == 'aktif')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                    <i class="bi bi-check-circle mr-1"></i> Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-gray-100 text-gray-800">
                                    <i class="bi bi-x-circle mr-1"></i> Nonaktif
                                </span>
                            @endif
                            
                            @if($product->is_made_to_order)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-purple-100 text-purple-800">
                                    <i class="bi bi-clock-history mr-1"></i> Made to Order
                                </span>
                            @endif
                        </div>
                        @if($product->is_made_to_order && $product->lead_time_days)
                            <p class="text-xs text-gray-600 mt-1">Waktu pengerjaan: {{ $product->lead_time_days }} hari</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Images -->
        @if($product->images->count() > 0)
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h5 class="text-lg font-semibold text-gray-800">Gambar Produk</h5>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-4 gap-2">
                    @foreach($product->images as $image)
                    <div class="relative">
                        <img src="{{ asset('storage/' . $image->path) }}" 
                             alt="{{ $product->nama_produk }}"
                             class="w-full h-32 object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-75 transition"
                             onclick="showImageModal('{{ asset('storage/' . $image->path) }}')">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Product Variants -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center" x-data="{ showAddModal: false }">
                <h5 class="text-lg font-semibold text-gray-800">Varian Produk ({{ $product->variants->count() }})</h5>
                <button type="button" @click="showAddModal = true" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg text-sm transition">
                    <i class="bi bi-plus-circle mr-2"></i> Tambah Varian
                </button>
                
                <!-- Add Variant Modal -->
                <div x-show="showAddModal" x-cloak @click.self="showAddModal = false" class="fixed inset-0 z-50 overflow-y-auto" style="backdrop-filter: blur(8px); background-color: rgba(0, 0, 0, 0.6);">
                    <div class="flex items-center justify-center min-h-screen px-4">
                        <div @click.away="showAddModal = false" x-show="showAddModal" x-transition class="bg-white rounded-xl shadow-2xl max-w-3xl w-full overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 flex justify-between items-start">
                                <div>
                                    <h5 class="text-lg font-bold text-gray-900">Tambah Varian Baru</h5>
                                    <p class="text-sm text-gray-600 mt-1">Isi informasi varian produk</p>
                                </div>
                                <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600">
                                    <i class="bi bi-x-lg text-xl"></i>
                                </button>
                            </div>
                            <form action="{{ route('admin.products.variants.store', $product->slug) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="px-6 py-5">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Varian <span class="text-red-600">*</span></label>
                                            <input type="text" name="nama_varian" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required placeholder="Merah Marun - 2x1m">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Kode Varian <span class="text-red-600">*</span></label>
                                            <input type="text" name="kode_varian" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required placeholder="PW-001-MER">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Warna</label>
                                            <input type="text" name="warna" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Merah Marun">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Ukuran</label>
                                            <input type="text" name="ukuran" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="2 x 1 meter">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Benang</label>
                                            <input type="text" name="jenis_benang" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Benang Emas 24K">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Harga (Rp) <span class="text-red-600">*</span></label>
                                            <input type="number" name="harga" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required min="0" placeholder="2500000">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Stok <span class="text-red-600">*</span></label>
                                            <input type="number" name="stok" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required min="0" placeholder="10">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Berat (gram)</label>
                                            <input type="number" name="berat" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" min="0" value="{{ $product->berat }}">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Varian (Opsional)</label>
                                            <input type="file" name="gambar_varian" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" accept="image/*">
                                            <p class="mt-2 text-xs text-gray-500">Format: JPG, PNG, JPEG. Maks 2MB</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 flex justify-end space-x-3">
                                    <button type="button" @click="showAddModal = false" class="px-5 py-2.5 bg-white hover:bg-gray-100 text-gray-700 font-semibold rounded-lg border border-gray-300 transition">Batal</button>
                                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition hover:shadow-lg">
                                        <i class="bi bi-check-circle mr-2"></i>Simpan Varian
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gambar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Varian</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($product->variants as $variant)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($variant->gambar_varian)
                                    <img src="{{ asset('storage/' . $variant->gambar_varian) }}" 
                                         alt="{{ $variant->nama_varian }}"
                                         class="w-12 h-12 object-cover rounded cursor-pointer"
                                         onclick="showImageModal('{{ asset('storage/' . $variant->gambar_varian) }}')"
                                         onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-12 h-12 bg-gray-100 rounded flex items-center justify-center\'><i class=\'bi bi-image text-gray-400\'></i></div>';">
                                @elseif($product->primary_image_path)
                                    <img src="{{ asset('storage/' . $product->primary_image_path) }}" 
                                         alt="{{ $product->nama_produk }}"
                                         class="w-12 h-12 object-cover rounded opacity-50"
                                         onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-12 h-12 bg-gray-100 rounded flex items-center justify-center\'><i class=\'bi bi-image text-gray-400\'></i></div>';">
                                @else
                                    <div class="w-12 h-12 bg-gray-100 rounded flex items-center justify-center">
                                        <i class="bi bi-image text-gray-400"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-xs text-gray-600">{{ $variant->kode_varian }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $variant->nama_varian }}</div>
                                <div class="text-xs text-gray-600">
                                    @if($variant->warna) {{ $variant->warna }} @endif
                                    @if($variant->ukuran) • {{ $variant->ukuran }} @endif
                                    @if($variant->jenis_benang) • {{ $variant->jenis_benang }} @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-800">Rp {{ number_format($variant->harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $variant->stok > 10 ? 'bg-green-100 text-green-800' : ($variant->stok > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $variant->stok }} unit
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($variant->status == 'tersedia')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Tersedia</span>
                                @else
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Habis</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center" x-data="{ showEditModal: false }">
                                <button type="button" @click="showEditModal = true" class="inline-flex items-center px-3 py-1 border border-blue-600 text-blue-600 hover:bg-blue-50 rounded text-sm font-medium transition" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('admin.products.variants.destroy', [$product->slug, $variant->id_varian]) }}" method="POST" class="inline-block ml-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1 border border-red-600 text-red-600 hover:bg-red-50 rounded text-sm font-medium transition" onclick="return confirm('Yakin hapus varian {{ $variant->nama_varian }}?')" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                
                                <!-- Edit Variant Modal -->
                                <div x-show="showEditModal" x-cloak @click.self="showEditModal = false" class="fixed inset-0 z-50 overflow-y-auto" style="backdrop-filter: blur(8px); background-color: rgba(0, 0, 0, 0.6);">
                                    <div class="flex items-center justify-center min-h-screen px-4">
                                        <div @click.away="showEditModal = false" x-show="showEditModal" x-transition class="bg-white rounded-xl shadow-2xl max-w-3xl w-full overflow-hidden">
                                            <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 flex justify-between items-start">
                                                <div>
                                                    <h5 class="text-lg font-bold text-gray-900">Edit Varian</h5>
                                                    <p class="text-sm text-gray-600 mt-1">{{ $variant->nama_varian }}</p>
                                                </div>
                                                <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600">
                                                    <i class="bi bi-x-lg text-xl"></i>
                                                </button>
                                            </div>
                                            <form action="{{ route('admin.products.variants.update', [$product->slug, $variant->id_varian]) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="px-6 py-4">
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Varian <span class="text-red-600">*</span></label>
                                                            <input type="text" name="nama_varian" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required value="{{ $variant->nama_varian }}">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Varian <span class="text-red-600">*</span></label>
                                                            <input type="text" name="kode_varian" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required value="{{ $variant->kode_varian }}">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Warna</label>
                                                            <input type="text" name="warna" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ $variant->warna }}">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Ukuran</label>
                                                            <input type="text" name="ukuran" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ $variant->ukuran }}">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Benang</label>
                                                            <input type="text" name="jenis_benang" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ $variant->jenis_benang }}">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp) <span class="text-red-600">*</span></label>
                                                            <input type="number" name="harga" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required min="0" value="{{ $variant->harga }}">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Stok <span class="text-red-600">*</span></label>
                                                            <input type="number" name="stok" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required min="0" value="{{ $variant->stok }}">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Berat (gram)</label>
                                                            <input type="number" name="berat" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" min="0" value="{{ $variant->berat }}">
                                                        </div>
                                                        <div class="md:col-span-2">
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                                <option value="tersedia" {{ $variant->status == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                                                <option value="habis" {{ $variant->status == 'habis' ? 'selected' : '' }}>Habis</option>
                                                            </select>
                                                        </div>
                                                        @if($variant->gambar_varian)
                                                        <div class="md:col-span-2">
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Saat Ini</label>
                                                            <img src="{{ asset('storage/' . $variant->gambar_varian) }}" alt="{{ $variant->nama_varian }}" class="w-48 h-48 object-cover rounded-lg border border-gray-200 cursor-pointer" onclick="showImageModal('{{ asset('storage/' . $variant->gambar_varian) }}')">
                                                        </div>
                                                        @endif
                                                        <div class="md:col-span-2">
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Upload Gambar Baru (Opsional)</label>
                                                            <input type="file" name="gambar_varian" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" accept="image/*">
                                                            <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah gambar</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 flex justify-end space-x-3">
                                                    <button type="button" @click="showEditModal = false" class="px-5 py-2.5 bg-white hover:bg-gray-100 text-gray-700 font-semibold rounded-lg border border-gray-300 transition">Batal</button>
                                                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition hover:shadow-lg">
                                                        <i class="bi bi-check-circle mr-2"></i>Update Varian
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                Belum ada varian. 
                                <button type="button" class="text-blue-600 hover:text-blue-800 font-medium" data-bs-toggle="modal" data-bs-target="#addVariantModal">
                                    Tambah varian pertama
                                </button>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Quick Stats -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h6 class="text-base font-semibold text-gray-800">Statistik</h6>
            </div>
            <div class="p-6 space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Total Varian</span>
                    <span class="font-bold text-gray-800">{{ $product->variants->count() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Total Stok</span>
                    <span class="font-bold text-gray-800">{{ $product->variants->sum('stok') }} unit</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Harga Terendah</span>
                    <span class="font-bold text-gray-800">Rp {{ number_format($product->variants->min('harga') ?? $product->harga, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Harga Tertinggi</span>
                    <span class="font-bold text-gray-800">Rp {{ number_format($product->variants->max('harga') ?? $product->harga, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Meta Info -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h6 class="text-base font-semibold text-gray-800">Informasi Lainnya</h6>
            </div>
            <div class="p-6 space-y-3">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Dibuat</label>
                    <p class="text-sm text-gray-800">{{ $product->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Terakhir Diupdate</label>
                    <p class="text-sm text-gray-800">{{ $product->updated_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Slug</label>
                    <p class="text-sm"><code class="text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded">{{ $product->slug }}</code></p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function showImageModal(imageSrc) {
    // Simple image modal with Alpine
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 z-50 flex items-center justify-center p-4';
    modal.style.backdropFilter = 'blur(8px)';
    modal.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
    modal.innerHTML = `
        <div class="relative max-w-6xl w-full">
            <button onclick="this.closest('div[class*=fixed]').remove()" class="absolute -top-4 -right-4 w-12 h-12 bg-white hover:bg-gray-100 rounded-full flex items-center justify-center text-gray-600 hover:text-gray-900 shadow-xl transition z-10">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
            <img src="${imageSrc}" alt="Preview" class="w-full rounded-2xl shadow-2xl">
        </div>
    `;
    modal.onclick = (e) => {
        if (e.target === modal) modal.remove();
    };
    document.body.appendChild(modal);
}
</script>
@endpush
