<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\CustomOrder;
use App\Models\Pelanggan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));
        $reportType = $request->get('report_type', 'regular'); // regular, custom, all
        
        // Regular Orders Report
        $regularOrders = Pesanan::with(['pelanggan', 'items.product'])
            ->whereBetween('tanggal_pesanan', [$dateFrom, $dateTo])
            ->when($reportType === 'regular' || $reportType === 'all', function($q) {
                return $q;
            })
            ->when($reportType === 'custom', function($q) {
                return $q->whereRaw('1 = 0'); // No results for custom filter
            })
            ->orderBy('tanggal_pesanan', 'desc')
            ->get();
            
        // Custom Orders Report
        $customOrders = CustomOrder::with(['pelanggan', 'jenis'])
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->when($reportType === 'custom' || $reportType === 'all', function($q) {
                return $q;
            })
            ->when($reportType === 'regular', function($q) {
                return $q->whereRaw('1 = 0'); // No results for regular filter
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Sales Statistics
        $stats = [
            'regular_orders' => [
                'count' => $regularOrders->count(),
                'total_revenue' => $regularOrders->sum('total_bayar'),
                'completed' => $regularOrders->where('status_pesanan', 'selesai')->count(),
                'avg_order_value' => $regularOrders->count() > 0 ? $regularOrders->sum('total_bayar') / $regularOrders->count() : 0,
            ],
            'custom_orders' => [
                'count' => $customOrders->count(),
                'total_revenue' => $customOrders->where('status', 'completed')->sum('harga_final'),
                'completed' => $customOrders->where('status', 'completed')->count(),
                'avg_order_value' => $customOrders->where('status', 'completed')->count() > 0 ? 
                    $customOrders->where('status', 'completed')->sum('harga_final') / $customOrders->where('status', 'completed')->count() : 0,
            ]
        ];
        
        // Monthly trend data for charts
        $monthlyTrend = $this->getMonthlyTrend($dateFrom, $dateTo);
        
        // Top products - simplified query
        $topProducts = DB::table('detail_pesanan')
            ->join('pesanan', 'detail_pesanan.id_pesanan', '=', 'pesanan.id_pesanan')
            ->join('produk', 'detail_pesanan.id_produk', '=', 'produk.id_produk')
            ->whereBetween('pesanan.tanggal_pesanan', [$dateFrom, $dateTo])
            ->select('produk.nama_produk', 
                DB::raw('SUM(detail_pesanan.jumlah) as total_qty'),
                DB::raw('SUM(detail_pesanan.subtotal) as total_revenue'))
            ->groupBy('produk.nama_produk')
            ->orderBy('total_revenue', 'desc')
            ->limit(10)
            ->get();
        
        return view('admin.reports.sales', compact(
            'regularOrders', 'customOrders', 'stats', 'monthlyTrend', 'topProducts',
            'dateFrom', 'dateTo', 'reportType'
        ));
    }
    
    private function getMonthlyTrend($dateFrom, $dateTo)
    {
        $startDate = Carbon::parse($dateFrom)->startOfMonth();
        $endDate = Carbon::parse($dateTo)->endOfMonth();
        
        $months = [];
        $current = $startDate->copy();
        
        while ($current <= $endDate) {
            $monthStart = $current->copy()->startOfMonth();
            $monthEnd = $current->copy()->endOfMonth();
            
            $regularRevenue = Pesanan::whereBetween('tanggal_pesanan', [$monthStart, $monthEnd])
                ->sum('total_bayar');
                
            $customRevenue = CustomOrder::whereBetween('created_at', [$monthStart, $monthEnd])
                ->where('status', 'completed')
                ->sum('harga_final');
            
            $months[] = [
                'month' => $current->format('M Y'),
                'regular_revenue' => $regularRevenue,
                'custom_revenue' => $customRevenue,
                'total_revenue' => $regularRevenue + $customRevenue,
            ];
            
            $current->addMonth();
        }
        
        return $months;
    }
    
    public function exportSales(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));
        $reportType = $request->get('report_type', 'all');
        
        // Get the same data as the main report
        $regularOrders = Pesanan::with(['pelanggan', 'items.product'])
            ->whereBetween('tanggal_pesanan', [$dateFrom, $dateTo])
            ->when($reportType === 'regular' || $reportType === 'all', function($q) {
                return $q;
            })
            ->when($reportType === 'custom', function($q) {
                return $q->whereRaw('1 = 0');
            })
            ->orderBy('tanggal_pesanan', 'desc')
            ->get();
            
        $customOrders = CustomOrder::with(['pelanggan', 'jenis'])
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->when($reportType === 'custom' || $reportType === 'all', function($q) {
                return $q;
            })
            ->when($reportType === 'regular', function($q) {
                return $q->whereRaw('1 = 0');
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Sales Statistics
        $stats = [
            'regular_orders' => [
                'count' => $regularOrders->count(),
                'total_revenue' => $regularOrders->sum('total_bayar'),
                'completed' => $regularOrders->where('status_pesanan', 'selesai')->count(),
                'avg_order_value' => $regularOrders->count() > 0 ? $regularOrders->sum('total_bayar') / $regularOrders->count() : 0,
            ],
            'custom_orders' => [
                'count' => $customOrders->count(),
                'total_revenue' => $customOrders->where('status', 'completed')->sum('harga_final'),
                'completed' => $customOrders->where('status', 'completed')->count(),
                'avg_order_value' => $customOrders->where('status', 'completed')->count() > 0 ? 
                    $customOrders->where('status', 'completed')->sum('harga_final') / $customOrders->where('status', 'completed')->count() : 0,
            ]
        ];
        
        // Top products
        $topProducts = DB::table('detail_pesanan')
            ->join('pesanan', 'detail_pesanan.id_pesanan', '=', 'pesanan.id_pesanan')
            ->join('produk', 'detail_pesanan.id_produk', '=', 'produk.id_produk')
            ->whereBetween('pesanan.tanggal_pesanan', [$dateFrom, $dateTo])
            ->select('produk.nama_produk', 
                DB::raw('SUM(detail_pesanan.jumlah) as total_qty'),
                DB::raw('SUM(detail_pesanan.subtotal) as total_revenue'))
            ->groupBy('produk.nama_produk')
            ->orderBy('total_revenue', 'desc')
            ->limit(10)
            ->get();
        
        // Return a printable view instead of JSON
        return view('admin.reports.print-sales', compact(
            'regularOrders', 'customOrders', 'stats', 'topProducts',
            'dateFrom', 'dateTo', 'reportType'
        ));
    }
}