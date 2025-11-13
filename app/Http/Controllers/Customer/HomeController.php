<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with(['category', 'activeVariants', 'images'])
            ->where('status', 'aktif')
            ->latest()
            ->take(8)
            ->get();
        
        $categories = Category::withCount(['products' => function($query) {
            $query->where('status', 'aktif');
        }])
        ->orderBy('nama_kategori')
        ->take(6)
        ->get();
        
        $bestSellers = DB::table('detail_pesanan')
            ->join('produk', 'detail_pesanan.id_produk', '=', 'produk.id_produk')
            ->join('pesanan', 'detail_pesanan.id_pesanan', '=', 'pesanan.id_pesanan')
            ->where('pesanan.status_pesanan', 'selesai')
            ->where('produk.status', 'aktif')
            ->select('produk.*', DB::raw('SUM(detail_pesanan.jumlah) as total_terjual'))
            ->groupBy('produk.id_produk')
            ->orderBy('total_terjual', 'desc')
            ->take(6)
            ->get();
        
        return view('customer.home', compact('featuredProducts', 'categories', 'bestSellers'));
    }
}
