<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\VarianProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with(['category', 'variants'])->withCount('variants');
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_produk', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhereHas('category', function($q) use ($search) {
                      $q->where('nama_kategori', 'like', "%{$search}%");
                  });
            });
        }
        
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        
        if (in_array($sortBy, ['nama_produk', 'harga', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }
        
        $perPage = $request->get('per_page', 10);
        $products = $perPage == 'all' ? $query->get() : $query->paginate((int)$perPage);
        
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Kategori::orderBy('nama_kategori')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $rules = [
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'nama_produk' => 'required|string|max:150',
            'slug' => 'nullable|string|max:150|unique:produk,slug',
            'deskripsi' => 'nullable|string',
            'berat' => 'required|numeric|min:0',
            'status' => 'nullable|in:aktif,nonaktif',
            'is_made_to_order' => 'nullable|boolean',
            'lead_time_days' => 'nullable|integer|min:1',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];

        // Validasi varian jika ada (hanya baris yang diisi)
        if ($request->has('variants') && is_array($request->variants)) {
            foreach ($request->variants as $i => $v) {
                if (empty($v['nama_varian']) && empty($v['kode_varian'])) {
                    continue;
                }
                $rules["variants.{$i}.nama_varian"] = 'required|string|max:100';
                $rules["variants.{$i}.kode_varian"] = 'required|string|max:50|unique:varian_produk,kode_varian';
                $rules["variants.{$i}.harga"] = 'required|numeric|min:0';
                $rules["variants.{$i}.stok"] = 'required|integer|min:0';
                $rules["variants.{$i}.warna"] = 'nullable|string|max:50';
                $rules["variants.{$i}.ukuran"] = 'nullable|string|max:50';
                $rules["variants.{$i}.jenis_benang"] = 'nullable|string|max:50';
                $rules["variants.{$i}.berat"] = 'nullable|numeric|min:0';
                $rules["variants.{$i}.gambar_varian"] = 'nullable|image|mimes:jpeg,png,jpg|max:2048';
            }
        }

        $validated = $request->validate($rules);
        
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['nama_produk']);
        }
        
        $validated['status'] = $validated['status'] ?? 'aktif';
        $validated['is_made_to_order'] = $request->has('is_made_to_order');
        
        $product = Produk::create($validated);

        // Simpan varian dari form tambah produk (termasuk gambar per varian)
        if ($request->has('variants') && is_array($request->variants)) {
            foreach ($request->variants as $i => $v) {
                if (empty($v['nama_varian']) || empty($v['kode_varian'])) {
                    continue;
                }
                $gambarPath = null;
                if ($request->hasFile("variants.{$i}.gambar_varian")) {
                    $gambarPath = $request->file("variants.{$i}.gambar_varian")->store('variants', 'public');
                }
                VarianProduk::create([
                    'id_produk' => $product->id_produk,
                    'nama_varian' => $v['nama_varian'],
                    'kode_varian' => $v['kode_varian'],
                    'harga' => (float) ($v['harga'] ?? 0),
                    'stok' => (int) ($v['stok'] ?? 0),
                    'berat' => !empty($v['berat']) ? (float) $v['berat'] : $product->berat,
                    'warna' => $v['warna'] ?? null,
                    'ukuran' => $v['ukuran'] ?? null,
                    'jenis_benang' => $v['jenis_benang'] ?? null,
                    'status' => 'tersedia',
                    'gambar_varian' => $gambarPath,
                ]);
            }
        }

        // Gambar produk disimpan ke varian (gambar pertama ke varian pertama, dst)
        if ($request->hasFile('images')) {
            $variants = $product->variants()->orderBy('id_varian')->get();
            if ($variants->isEmpty()) {
                // Tidak ada varian: buat satu varian default agar bisa simpan gambar
                $firstFile = $request->file('images')[0];
                $path = $firstFile->store('variants', 'public');
                VarianProduk::create([
                    'id_produk' => $product->id_produk,
                    'nama_varian' => 'Default',
                    'kode_varian' => 'PRD-' . $product->id_produk . '-D',
                    'harga' => 0,
                    'stok' => 0,
                    'berat' => $product->berat,
                    'gambar_varian' => $path,
                    'status' => 'tersedia',
                ]);
                foreach ($request->file('images') as $index => $image) {
                    if ($index === 0) continue;
                    $path = $image->store('variants', 'public');
                    VarianProduk::create([
                        'id_produk' => $product->id_produk,
                        'nama_varian' => 'Varian ' . ($index + 1),
                        'kode_varian' => 'PRD-' . $product->id_produk . '-V' . ($index + 1),
                        'harga' => 0,
                        'stok' => 0,
                        'berat' => $product->berat,
                        'gambar_varian' => $path,
                        'status' => 'tersedia',
                    ]);
                }
            } else {
                foreach ($request->file('images') as $index => $image) {
                    if ($variants->has($index)) {
                        $path = $image->store('variants', 'public');
                        $variants[$index]->update(['gambar_varian' => $path]);
                    }
                }
            }
        }
        
        return redirect()->route('admin.products.show', $product->slug)
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function show($slug)
    {
        $product = Produk::with(['category', 'variants'])
            ->where('slug', $slug)
            ->firstOrFail();
        return view('admin.products.show', compact('product'));
    }

    public function edit($slug)
    {
        $product = Produk::with(['variants'])
            ->where('slug', $slug)
            ->firstOrFail();
        $categories = Kategori::orderBy('nama_kategori')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $slug)
    {
        $product = Produk::where('slug', $slug)->firstOrFail();
        
        $validated = $request->validate([
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'nama_produk' => 'required|string|max:150',
            'slug' => 'nullable|string|max:150|unique:produk,slug,' . $product->id_produk . ',id_produk',
            'deskripsi' => 'nullable|string',
            'berat' => 'required|numeric|min:0',
            'status' => 'nullable|in:aktif,nonaktif',
            'is_made_to_order' => 'nullable|boolean',
            'lead_time_days' => 'nullable|integer|min:1',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['nama_produk']);
        }
        
        $validated['is_made_to_order'] = $request->has('is_made_to_order');
        
        $product->update($validated);

        if ($request->hasFile('images')) {
            $variants = $product->variants()->orderBy('id_varian')->get();
            foreach ($request->file('images') as $index => $image) {
                if ($variants->has($index)) {
                    $path = $image->store('variants', 'public');
                    $variants[$index]->update(['gambar_varian' => $path]);
                }
            }
        }

        return redirect()->route('admin.products.show', $product->slug)
            ->with('success', 'Produk berhasil diupdate!');
    }

    public function destroy($slug)
    {
        $product = Produk::with('variants')->where('slug', $slug)->firstOrFail();

        foreach ($product->variants as $variant) {
            if ($variant->gambar_varian && Storage::disk('public')->exists($variant->gambar_varian)) {
                Storage::disk('public')->delete($variant->gambar_varian);
            }
        }

        $product->delete();
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }
    
    public function storeVariant(Request $request, $slug)
    {
        $product = Produk::where('slug', $slug)->firstOrFail();
        
        $validated = $request->validate([
            'nama_varian' => 'required|string|max:100',
            'kode_varian' => 'required|string|max:50|unique:varian_produk,kode_varian',
            'warna' => 'nullable|string|max:50',
            'ukuran' => 'nullable|string|max:50',
            'jenis_benang' => 'nullable|string|max:50',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'berat' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:tersedia,habis',
            'gambar_varian' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $validated['id_produk'] = $product->id_produk;
        $validated['berat'] = $validated['berat'] ?? $product->berat;
        $validated['status'] = $validated['status'] ?? 'tersedia';
        
        // Upload gambar varian jika ada
        if ($request->hasFile('gambar_varian')) {
            $path = $request->file('gambar_varian')->store('variants', 'public');
            $validated['gambar_varian'] = $path;
        }
        
        $variant = VarianProduk::create($validated);
        
        return redirect()->route('admin.products.show', $product->slug)
            ->with('success', 'Varian berhasil ditambahkan!');
    }
    
    public function updateVariant(Request $request, $slug, $variantId)
    {
        $product = Produk::where('slug', $slug)->firstOrFail();
        $variant = VarianProduk::findOrFail($variantId);
        
        $validated = $request->validate([
            'nama_varian' => 'required|string|max:100',
            'kode_varian' => 'required|string|max:50|unique:varian_produk,kode_varian,' . $variantId . ',id_varian',
            'warna' => 'nullable|string|max:50',
            'ukuran' => 'nullable|string|max:50',
            'jenis_benang' => 'nullable|string|max:50',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'berat' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:tersedia,habis',
            'gambar_varian' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        // Upload gambar varian baru jika ada
        if ($request->hasFile('gambar_varian')) {
            // Hapus gambar lama jika ada
            if ($variant->gambar_varian && Storage::disk('public')->exists($variant->gambar_varian)) {
                Storage::disk('public')->delete($variant->gambar_varian);
            }
            
            $path = $request->file('gambar_varian')->store('variants', 'public');
            $validated['gambar_varian'] = $path;
        }
        
        $variant->update($validated);
        
        return redirect()->route('admin.products.show', $product->slug)
            ->with('success', 'Varian berhasil diupdate!');
    }
    
    public function destroyVariant($slug, $variantId)
    {
        $product = Produk::where('slug', $slug)->firstOrFail();
        $variant = VarianProduk::findOrFail($variantId);
        
        // Hapus gambar varian jika ada
        if ($variant->gambar_varian && Storage::disk('public')->exists($variant->gambar_varian)) {
            Storage::disk('public')->delete($variant->gambar_varian);
        }
        
        $variant->delete();
        
        return redirect()->route('admin.products.show', $product->slug)
            ->with('success', 'Varian berhasil dihapus!');
    }
}
