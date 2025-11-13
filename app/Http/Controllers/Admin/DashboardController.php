<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Pelanggan;
use App\Models\Kategori;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total statistik
        $totalOrders = Pesanan::count();
        $totalProducts = Produk::count();
        $totalCustomers = Pelanggan::count();
        $totalRevenue = Pesanan::where('status_pesanan', 'selesai')->sum('total_bayar');
        
        // Pesanan hari ini
        $todayOrders = Pesanan::whereDate('created_at', today())->count();
        $todayRevenue = Pesanan::whereDate('created_at', today())
            ->where('status_pesanan', 'selesai')
            ->sum('total_bayar');
        
        // Pesanan pending
        $pendingOrders = Pesanan::where('status_pesanan', 'baru')->count();
        
        // Produk stok rendah (< 10)
        $lowStockProducts = Produk::where('stok', '<', 10)
            ->where('status', 'aktif')
            ->count();
        
        // Recent orders
        $recentOrders = Pesanan::with(['pelanggan', 'payment'])
            ->latest()
            ->take(10)
            ->get();
        
        // Top selling products (bulan ini)
        $topProducts = DB::table('detail_pesanan')
            ->join('produk', 'detail_pesanan.id_produk', '=', 'produk.id_produk')
            ->join('pesanan', 'detail_pesanan.id_pesanan', '=', 'pesanan.id_pesanan')
            ->whereMonth('pesanan.created_at', date('m'))
            ->whereYear('pesanan.created_at', date('Y'))
            ->select('produk.nama_produk', DB::raw('SUM(detail_pesanan.jumlah) as total_terjual'))
            ->groupBy('produk.id_produk', 'produk.nama_produk')
            ->orderBy('total_terjual', 'desc')
            ->take(5)
            ->get();
        
        // Revenue chart data (7 hari terakhir)
        $revenueChart = Pesanan::where('status_pesanan', 'selesai')
            ->where('created_at', '>=', now()->subDays(7))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_bayar) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        return view('admin.dashboard', compact(
            'totalOrders',
            'totalProducts',
            'totalCustomers',
            'totalRevenue',
            'todayOrders',
            'todayRevenue',
            'pendingOrders',
            'lowStockProducts',
            'recentOrders',
            'topProducts',
            'revenueChart'
        ));
    }
}
