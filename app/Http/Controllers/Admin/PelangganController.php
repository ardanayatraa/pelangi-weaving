<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $query = Pelanggan::with(['pesanan', 'customOrders']);
        
        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telepon', 'like', "%{$search}%");
            });
        }
        
        // Filter by registration date
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        
        if (in_array($sortBy, ['nama', 'email', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }
        
        $perPage = $request->get('per_page', 15);
        $pelanggan = $perPage == 'all' ? $query->get() : $query->paginate((int)$perPage);
        
        // Add statistics
        $stats = [
            'total_pelanggan' => Pelanggan::count(),
            'pelanggan_bulan_ini' => Pelanggan::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)->count(),
            'pelanggan_aktif' => Pelanggan::whereHas('pesanan', function($q) {
                $q->where('created_at', '>=', now()->subMonths(3));
            })->count(),
        ];
        
        return view('admin.pelanggan.index', compact('pelanggan', 'stats'));
    }

    public function show(Pelanggan $pelanggan)
    {
        $pelanggan->load([
            'pesanan' => function($q) {
                $q->with(['items.product', 'pembayaran', 'pengiriman'])->latest();
            },
            'customOrders' => function($q) {
                $q->with('jenis')->latest();
            }
        ]);
        
        // Statistics for this customer
        $customerStats = [
            'total_pesanan' => $pelanggan->pesanan->count(),
            'total_custom_orders' => $pelanggan->customOrders->count(),
            'total_pembelian' => $pelanggan->pesanan->sum('total_bayar'),
            'pesanan_selesai' => $pelanggan->pesanan->where('status_pesanan', 'selesai')->count(),
        ];
        
        return view('admin.pelanggan.show', compact('pelanggan', 'customerStats'));
    }

    public function destroy(Pelanggan $pelanggan)
    {
        // Check if customer has orders
        if ($pelanggan->pesanan()->exists() || $pelanggan->customOrders()->exists()) {
            return back()->with('error', 'Pelanggan tidak dapat dihapus karena memiliki riwayat pesanan!');
        }
        
        $pelanggan->delete();
        
        return redirect()->route('admin.pelanggan.index')
            ->with('success', 'Pelanggan berhasil dihapus!');
    }
}