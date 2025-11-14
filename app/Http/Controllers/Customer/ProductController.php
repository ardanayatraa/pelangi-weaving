<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with(['category', 'activeVariants', 'images'])
            ->where('status', 'aktif');
        
        if ($request->has('category') && $request->category != '') {
            $query->where('id_kategori', $request->category);
        }
        
        if ($request->has('price') && $request->price != '') {
            $priceRange = explode('-', $request->price);
            if (count($priceRange) == 2) {
                $query->whereBetween('harga', [(int)$priceRange[0], (int)$priceRange[1]]);
            }
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_produk', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }
        
        $sortBy = $request->get('sort', 'newest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('harga', 'asc');
                break;
            case 'price_high':
                $query->orderBy('harga', 'desc');
                break;
            case 'name':
                $query->orderBy('nama_produk', 'asc');
                break;
            default:
                $query->latest();
        }
        
        $products = $query->paginate(20);
        
        $categories = Kategori::withCount('products')->orderBy('nama_kategori')->get();
        
        return view('customer.products.index', compact('products', 'categories'));
    }

    public function show($slug)
    {
        $product = Produk::with(['category', 'activeVariants.images', 'images'])
            ->where('slug', $slug)
            ->where('status', 'aktif')
            ->firstOrFail();
        
        $relatedProducts = Produk::with(['category', 'activeVariants', 'images'])
            ->where('id_kategori', $product->id_kategori)
            ->where('id_produk', '!=', $product->id_produk)
            ->where('status', 'aktif')
            ->take(5)
            ->get();
        
        return view('customer.products.show', compact('product', 'relatedProducts'));
    }
}
