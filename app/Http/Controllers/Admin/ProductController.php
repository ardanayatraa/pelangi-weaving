<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'variants'])->withCount('variants');
        
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
        $categories = Category::orderBy('nama_kategori')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'nama_produk' => 'required|string|max:150',
            'slug' => 'nullable|string|max:150|unique:produk,slug',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'berat' => 'required|numeric|min:0',
            'status' => 'nullable|in:aktif,nonaktif',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['nama_produk']);
        }
        
        $validated['status'] = $validated['status'] ?? 'aktif';
        
        $product = Product::create($validated);
        
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'id_produk' => $product->id_produk,
                    'path' => $path,
                    'is_primary' => $index === 0,
                ]);
            }
        }
        
        return redirect()->route('admin.products.show', $product->id_produk)
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function show($id)
    {
        $product = Product::with(['category', 'variants', 'images'])->findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::with(['variants', 'images'])->findOrFail($id);
        $categories = Category::orderBy('nama_kategori')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $validated = $request->validate([
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'nama_produk' => 'required|string|max:150',
            'slug' => 'nullable|string|max:150|unique:produk,slug,' . $id . ',id_produk',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'berat' => 'required|numeric|min:0',
            'status' => 'nullable|in:aktif,nonaktif',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['nama_produk']);
        }
        
        $product->update($validated);
        
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'id_produk' => $product->id_produk,
                    'path' => $path,
                    'is_primary' => $product->images()->count() === 0 && $index === 0,
                ]);
            }
        }
        
        return redirect()->route('admin.products.show', $product->id_produk)
            ->with('success', 'Produk berhasil diupdate!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        foreach ($product->images as $image) {
            if (Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }
        }
        
        $product->delete();
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }
    
    public function storeVariant(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        
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
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $validated['id_produk'] = $product->id_produk;
        $validated['berat'] = $validated['berat'] ?? $product->berat;
        $validated['status'] = $validated['status'] ?? 'tersedia';
        
        $variant = ProductVariant::create($validated);
        
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('variants', 'public');
                ProductImage::create([
                    'id_produk' => $product->id_produk,
                    'id_varian' => $variant->id_varian,
                    'path' => $path,
                    'is_primary' => $index === 0,
                ]);
            }
        }
        
        return redirect()->route('admin.products.show', $product->id_produk)
            ->with('success', 'Varian berhasil ditambahkan!');
    }
    
    public function updateVariant(Request $request, $productId, $variantId)
    {
        $product = Product::findOrFail($productId);
        $variant = ProductVariant::findOrFail($variantId);
        
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
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $variant->update($validated);
        
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('variants', 'public');
                ProductImage::create([
                    'id_produk' => $product->id_produk,
                    'id_varian' => $variant->id_varian,
                    'path' => $path,
                    'is_primary' => $variant->images()->count() === 0 && $index === 0,
                ]);
            }
        }
        
        return redirect()->route('admin.products.show', $product->id_produk)
            ->with('success', 'Varian berhasil diupdate!');
    }
    
    public function destroyVariant($productId, $variantId)
    {
        $variant = ProductVariant::findOrFail($variantId);
        $variant->delete();
        
        return redirect()->route('admin.products.show', $productId)
            ->with('success', 'Varian berhasil dihapus!');
    }
}
