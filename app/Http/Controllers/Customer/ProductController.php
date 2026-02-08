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
        $query = Produk::with(['category', 'activeVariants', 'variants'])
            ->where('status', 'aktif');
        
        // Category filter
        if ($request->has('category') && $request->category != '') {
            $query->where('id_kategori', $request->category);
        }
        
        // Price range filter
        if ($request->has('price_range') && $request->price_range != '') {
            $priceRange = explode('-', $request->price_range);
            if (count($priceRange) == 2) {
                $minPrice = (int)$priceRange[0];
                $maxPrice = (int)$priceRange[1];
                
                // Filter by variant price
                $query->whereHas('activeVariants', function($vq) use ($minPrice, $maxPrice) {
                    $vq->whereBetween('harga', [$minPrice, $maxPrice]);
                });
            }
        }
        
        // Search filter
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_produk', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhereHas('category', function($cq) use ($search) {
                      $cq->where('nama_kategori', 'like', "%{$search}%");
                  });
            });
        }
        
        // Sorting
        $sortBy = $request->get('sort', 'newest');
        switch ($sortBy) {
            case 'price_low':
                // Sort by minimum variant price
                $query->join('varian_produk', 'produk.id_produk', '=', 'varian_produk.id_produk')
                      ->where('varian_produk.status', 'tersedia')
                      ->select('produk.*')
                      ->selectRaw('MIN(varian_produk.harga) as min_price')
                      ->groupBy('produk.id_produk')
                      ->orderBy('min_price', 'asc');
                break;
            case 'price_high':
                // Sort by maximum variant price
                $query->join('varian_produk', 'produk.id_produk', '=', 'varian_produk.id_produk')
                      ->where('varian_produk.status', 'tersedia')
                      ->select('produk.*')
                      ->selectRaw('MAX(varian_produk.harga) as max_price')
                      ->groupBy('produk.id_produk')
                      ->orderBy('max_price', 'desc');
                break;
            case 'name':
                $query->orderBy('nama_produk', 'asc');
                break;
            case 'popular':
                // Order by views
                $query->orderBy('views', 'desc');
                break;
            case 'newest':
            default:
                $query->latest();
        }
        
        $products = $query->paginate(20)->withQueryString();
        
        // Get categories with product counts
        $categories = Kategori::withCount(['products' => function($query) {
            $query->where('status', 'aktif');
        }])->orderBy('nama_kategori')->get();
        
        return view('customer.products.index', compact('products', 'categories'));
    }

    public function show($slug)
    {
        $product = Produk::with(['category', 'activeVariants', 'variants'])
            ->where('slug', $slug)
            ->where('status', 'aktif')
            ->firstOrFail();
        
        // Increment views
        $product->increment('views');
        
        $relatedProducts = Produk::with(['category', 'activeVariants', 'variants'])
            ->where('id_kategori', $product->id_kategori)
            ->where('id_produk', '!=', $product->id_produk)
            ->where('status', 'aktif')
            ->take(4)
            ->get();
        
        return view('customer.products.show', compact('product', 'relatedProducts'));
    }
}
