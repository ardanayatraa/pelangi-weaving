<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Produk::with(['category', 'activeVariants', 'images'])
            ->where('status', 'aktif')
            ->latest()
            ->take(8)
            ->get();
        
        $categories = Kategori::with(['products' => function($query) {
            $query->with('images')
                  ->where('status', 'aktif')
                  ->take(4); // Ambil 4 produk per kategori untuk slideshow
        }])
        ->withCount(['products' => function($query) {
            $query->where('status', 'aktif');
        }])
        ->orderBy('nama_kategori')
        ->take(6)
        ->get();
        
        // Get best sellers using subquery to avoid GROUP BY issues
        $bestSellerIds = DB::table('detail_pesanan')
            ->join('pesanan', 'detail_pesanan.id_pesanan', '=', 'pesanan.id_pesanan')
            ->where('pesanan.status_pesanan', 'selesai')
            ->select('detail_pesanan.id_produk', DB::raw('SUM(detail_pesanan.jumlah) as total_terjual'))
            ->groupBy('detail_pesanan.id_produk')
            ->orderBy('total_terjual', 'desc')
            ->take(6)
            ->pluck('id_produk');
            
        $bestSellers = Produk::with(['category', 'activeVariants', 'images'])
            ->whereIn('id_produk', $bestSellerIds)
            ->where('status', 'aktif')
            ->get();
        
        return view('customer.home', compact('featuredProducts', 'categories', 'bestSellers'));
    }
}
