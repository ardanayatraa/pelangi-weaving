<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\VarianProduk;
use App\Models\GambarProduk;
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
        $validated = $request->validate([
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'nama_produk' => 'required|string|max:150',
            'slug' => 'nullable|string|max:150|unique:produk,slug',
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
        
        $validated['status'] = $validated['status'] ?? 'aktif';
        $validated['is_made_to_order'] = $request->has('is_made_to_order');
        
        $product = Produk::create($validated);
        
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                GambarProduk::create([
                    'id_produk' => $product->id_produk,
                    'path' => $path,
                    'is_primary' => $index === 0,
                ]);
            }
        }
        
        return redirect()->route('admin.products.show', $product->slug)
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function show($slug)
    {
        $product = Produk::with(['category', 'variants', 'images'])
            ->where('slug', $slug)
            ->firstOrFail();
        return view('admin.products.show', compact('product'));
    }

    public function edit($slug)
    {
        $product = Produk::with(['variants', 'images'])
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
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                GambarProduk::create([
                    'id_produk' => $product->id_produk,
                    'path' => $path,
                    'is_primary' => $product->images()->count() === 0 && $index === 0,
                ]);
            }
        }
        
        return redirect()->route('admin.products.show', $product->slug)
            ->with('success', 'Produk berhasil diupdate!');
    }

    public function destroy($slug)
    {
        $product = Produk::where('slug', $slug)->firstOrFail();
        
        foreach ($product->images as $image) {
            if (Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
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
