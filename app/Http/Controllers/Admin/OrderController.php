<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Pengiriman;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Pesanan::with(['pelanggan', 'items', 'payment']);
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status_pesanan', $request->status);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_invoice', 'like', "%{$search}%")
                  ->orWhereHas('pelanggan', function($q) use ($search) {
                      $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('telepon', 'like', "%{$search}%");
                  });
            });
        }
        
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        
        if (in_array($sortBy, ['nomor_invoice', 'tanggal_pesanan', 'total_bayar', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }
        
        $perPage = $request->get('per_page', 10);
        $orders = $perPage == 'all' ? $query->get() : $query->paginate((int)$perPage);
        
        $status_counts = [
            'baru' => Pesanan::where('status_pesanan', 'baru')->count(),
            'diproses' => Pesanan::where('status_pesanan', 'diproses')->count(),
            'dikirim' => Pesanan::where('status_pesanan', 'dikirim')->count(),
            'selesai' => Pesanan::where('status_pesanan', 'selesai')->count(),
            'batal' => Pesanan::where('status_pesanan', 'batal')->count(),
        ];
        
        return view('admin.orders.index', compact('orders', 'status_counts'));
    }

    public function show($id)
    {
        $order = Pesanan::with(['pelanggan', 'items.produk.images', 'items.varian', 'payment', 'pengiriman'])
            ->findOrFail($id);
        
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Pesanan::findOrFail($id);
        
        $validated = $request->validate([
            'status_pesanan' => 'required|in:baru,diproses,dikirim,selesai,batal',
            'no_resi' => 'nullable|string|max:100',
        ]);
        
        $order->update(['status_pesanan' => $validated['status_pesanan']]);
        
        // Update no resi jika ada
        if ($request->filled('no_resi')) {
            // Buat pengiriman jika belum ada
            if (!$order->pengiriman) {
                $order->pengiriman()->create([
                    'no_resi' => $validated['no_resi'],
                    'ongkir' => $order->ongkir,
                    'status_pengiriman' => 'menunggu',
                ]);
            } else {
                $order->pengiriman->update(['no_resi' => $validated['no_resi']]);
            }
        }
        
        // Update status pengiriman berdasarkan status pesanan
        if ($order->pengiriman) {
            if ($validated['status_pesanan'] == 'dikirim') {
                $order->pengiriman->update([
                    'status_pengiriman' => 'dalam_perjalanan',
                    'tanggal_kirim' => $order->pengiriman->tanggal_kirim ?? now(),
                ]);
            } elseif ($validated['status_pesanan'] == 'selesai') {
                $order->pengiriman->update([
                    'status_pengiriman' => 'sampai',
                    'tanggal_terima' => $order->pengiriman->tanggal_terima ?? now(),
                ]);
            }
        }
        
        return redirect()->route('admin.orders.show', $id)
            ->with('success', 'Status pesanan berhasil diupdate!');
    }
    
    public function updateShipping(Request $request, $id)
    {
        $order = Pesanan::findOrFail($id);
        
        $validated = $request->validate([
            'kurir' => 'required|string|max:50',
            'layanan' => 'required|string|max:50',
            'no_resi' => 'required|string|max:100',
            'status_pengiriman' => 'required|in:menunggu,dalam_perjalanan,sampai',
        ]);
        
        $pengiriman = $order->pengiriman;
        
        if ($pengiriman) {
            $pengiriman->update($validated);
        } else {
            $order->pengiriman()->create(array_merge($validated, [
                'id_pesanan' => $order->id_pesanan,
                'ongkir' => $order->ongkir,
            ]));
        }
        
        if ($order->status_pesanan == 'diproses') {
            $order->update(['status_pesanan' => 'dikirim']);
        }
        
        return redirect()->route('admin.orders.show', $id)
            ->with('success', 'Informasi pengiriman berhasil diupdate!');
    }
    
    public function printInvoice($id)
    {
        $order = Pesanan::with(['pelanggan', 'items.product', 'items.productVariant', 'payment'])
            ->findOrFail($id);
        
        return view('admin.orders.invoice', compact('order'));
    }
}
