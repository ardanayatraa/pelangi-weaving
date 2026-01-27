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
        
        // Category filter
        if ($request->has('category') && $request->category != '') {
            $query->where('id_kategori', $request->category);
        }
        
        // Price range filter (new format)
        if ($request->has('price_range') && $request->price_range != '') {
            $priceRange = explode('-', $request->price_range);
            if (count($priceRange) == 2) {
                $minPrice = (int)$priceRange[0];
                $maxPrice = (int)$priceRange[1];
                
                // Filter by product price or variant price
                $query->where(function($q) use ($minPrice, $maxPrice) {
                    $q->whereBetween('harga', [$minPrice, $maxPrice])
                      ->orWhereHas('activeVariants', function($vq) use ($minPrice, $maxPrice) {
                          $vq->whereBetween('harga', [$minPrice, $maxPrice]);
                      });
                });
            }
        }
        
        // Legacy price filter support
        if ($request->has('min_price') && $request->min_price != '') {
            $minPrice = (int)$request->min_price;
            $query->where(function($q) use ($minPrice) {
                $q->where('harga', '>=', $minPrice)
                  ->orWhereHas('activeVariants', function($vq) use ($minPrice) {
                      $vq->where('harga', '>=', $minPrice);
                  });
            });
        }
        
        if ($request->has('max_price') && $request->max_price != '') {
            $maxPrice = (int)$request->max_price;
            $query->where(function($q) use ($maxPrice) {
                $q->where('harga', '<=', $maxPrice)
                  ->orWhereHas('activeVariants', function($vq) use ($maxPrice) {
                      $vq->where('harga', '<=', $maxPrice);
                  });
            });
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
        
        // Stock filter
        if ($request->has('stock') && $request->stock != '') {
            switch ($request->stock) {
                case 'available':
                    $query->where('stok', '>', 10);
                    break;
                case 'limited':
                    $query->where('stok', '>', 0)->where('stok', '<=', 10);
                    break;
                case 'out_of_stock':
                    $query->where('stok', 0);
                    break;
            }
        }
        
        // Sorting
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
            case 'popular':
                // Order by stock (assuming popular items have lower stock due to sales)
                $query->orderBy('stok', 'asc');
                break;
            case 'newest':
            default:
                $query->latest();
        }
        
        $products = $query->paginate(20);
        
        // Get categories with product counts
        $categories = Kategori::withCount(['products' => function($query) {
            $query->where('status', 'aktif');
        }])->orderBy('nama_kategori')->get();
        
        return view('customer.products.index', compact('products', 'categories'));
    }

    public function show($slug)
    {
        $product = Produk::with(['category', 'activeVariants', 'images'])
            ->where('slug', $slug)
            ->where('status', 'aktif')
            ->firstOrFail();
        
        // Increment views
        $product->increment('views');
        
        $relatedProducts = Produk::with(['category', 'activeVariants', 'images'])
            ->where('id_kategori', $product->id_kategori)
            ->where('id_produk', '!=', $product->id_produk)
            ->where('status', 'aktif')
            ->take(4)
            ->get();
        
        return view('customer.products.show', compact('product', 'relatedProducts'));
    }
}
