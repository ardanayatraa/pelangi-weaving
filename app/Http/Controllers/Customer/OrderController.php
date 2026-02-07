<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Pesanan::with(['items.produk.variants', 'items.varian', 'payment', 'pengiriman'])
            ->where('id_pelanggan', Auth::guard('pelanggan')->id());
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status_pesanan', $request->status);
        }
        
        $orders = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('customer.orders.index', compact('orders'));
    }

    public function show(Request $request, $nomorInvoice)
    {
        Log::info('=== ORDER SHOW CALLED ===', [
            'invoice' => $nomorInvoice,
            'payment_param' => $request->get('payment')
        ]);
        
        $order = Pesanan::with(['items.produk.variants', 'items.varian', 'payment', 'pengiriman'])
            ->where('nomor_invoice', $nomorInvoice)
            ->where('id_pelanggan', Auth::guard('pelanggan')->id())
            ->firstOrFail();
        
        // Handle payment callback
        if ($request->has('payment')) {
            $paymentStatus = $request->get('payment');
            Log::info('Payment callback detected', [
                'status' => $paymentStatus,
                'current_payment_status' => $order->payment->status_bayar ?? 'no payment'
            ]);
            
            if ($order->payment && in_array($paymentStatus, ['success', 'pending'])) {
                // Update payment status (simplified)
                if ($paymentStatus === 'success' && $order->payment->status_bayar !== 'paid') {
                    $order->payment->update([
                        'status_bayar' => 'paid',
                        'tanggal_bayar' => now(),
                    ]);
                    $order->update(['status_pesanan' => 'diproses']);
                }
                
                Log::info('Payment status after update', [
                    'payment_status' => $order->payment->status_bayar,
                    'order_status' => $order->status_pesanan
                ]);
                
                $message = $order->payment->status_bayar == 'paid' 
                    ? 'Pembayaran berhasil! Pesanan Anda sedang diproses.' 
                    : 'Terima kasih! Pembayaran Anda sedang diverifikasi.';
                
                return redirect()->route('orders.show', $nomorInvoice)
                    ->with('success', $message);
            }
        }
        
        return view('customer.orders.show', compact('order'));
    }

    public function cancel($nomorInvoice)
    {
        $order = Pesanan::where('nomor_invoice', $nomorInvoice)
            ->where('id_pelanggan', Auth::guard('pelanggan')->id())
            ->firstOrFail();
        
        if (!in_array($order->status_pesanan, ['baru', 'diproses'])) {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan!');
        }
        
        $order->update(['status_pesanan' => 'batal']);
        
        foreach ($order->items as $item) {
            if ($item->varian) {
                $item->varian->increment('stok', $item->jumlah);
            } elseif ($item->produk) {
                $item->produk->increment('stok', $item->jumlah);
            }
        }
        
        return back()->with('success', 'Pesanan berhasil dibatalkan!');
    }
    
    public function complete($nomorInvoice)
    {
        $order = Pesanan::where('nomor_invoice', $nomorInvoice)
            ->where('id_pelanggan', Auth::guard('pelanggan')->id())
            ->firstOrFail();
        
        // Hanya bisa konfirmasi jika status dikirim
        if ($order->status_pesanan != 'dikirim') {
            return back()->with('error', 'Pesanan belum dapat dikonfirmasi!');
        }
        
        // Update status pesanan menjadi selesai
        $order->update(['status_pesanan' => 'selesai']);
        
        // Update status pengiriman menjadi sampai
        if ($order->pengiriman) {
            $order->pengiriman->update([
                'status_pengiriman' => 'sampai',
                'tanggal_terima' => now(),
            ]);
        }
        
        return redirect()->route('orders.show', $nomorInvoice)
            ->with('success', 'Terima kasih! Pesanan telah dikonfirmasi selesai.');
    }
}
