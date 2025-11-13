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
        $query = Pesanan::with(['items.product', 'items.productVariant', 'payment', 'pengiriman'])
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
        
        $order = Pesanan::with(['items.product', 'items.productVariant', 'payment', 'pengiriman'])
            ->where('nomor_invoice', $nomorInvoice)
            ->where('id_pelanggan', Auth::guard('pelanggan')->id())
            ->firstOrFail();
        
        // Handle payment callback dari Midtrans
        if ($request->has('payment')) {
            $paymentStatus = $request->get('payment');
            Log::info('Payment callback detected', [
                'status' => $paymentStatus,
                'current_payment_status' => $order->payment->status_pembayaran ?? 'no payment'
            ]);
            
            if ($order->payment && in_array($paymentStatus, ['success', 'pending'])) {
                // Cek status terbaru dari Midtrans
                $this->checkPaymentStatus($order->payment);
                $order->refresh();
                
                Log::info('Payment status after check', [
                    'payment_status' => $order->payment->status_pembayaran,
                    'order_status' => $order->status_pesanan
                ]);
                
                $message = $order->payment->status_pembayaran == 'paid' 
                    ? 'Pembayaran berhasil! Pesanan Anda sedang diproses.' 
                    : 'Terima kasih! Pembayaran Anda sedang diverifikasi.';
                
                return redirect()->route('orders.show', $nomorInvoice)
                    ->with('success', $message);
            }
        }
        
        return view('customer.orders.show', compact('order'));
    }
    
    private function checkPaymentStatus($payment)
    {
        try {
            Log::info('Checking payment status dari Midtrans', [
                'order_id' => $payment->midtrans_order_id
            ]);
            
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            
            $status = \Midtrans\Transaction::status($payment->midtrans_order_id);
            
            $transactionStatus = $status->transaction_status;
            $fraudStatus = $status->fraud_status ?? null;
            $paymentType = $status->payment_type ?? null;
            
            Log::info('Midtrans response', [
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'payment_type' => $paymentType
            ]);
            
            $updated = false;
            
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    Log::info('CAPTURE with ACCEPT - Updating to PAID');
                    $payment->update([
                        'status_pembayaran' => 'paid',
                        'tipe_pembayaran' => $paymentType,
                        'waktu_settlement' => now(),
                    ]);
                    $payment->order->update(['status_pesanan' => 'diproses']);
                    $updated = true;
                }
            } elseif ($transactionStatus == 'settlement') {
                Log::info('SETTLEMENT - Updating to PAID');
                $payment->update([
                    'status_pembayaran' => 'paid',
                    'tipe_pembayaran' => $paymentType,
                    'waktu_settlement' => now(),
                ]);
                $payment->order->update(['status_pesanan' => 'diproses']);
                $updated = true;
            } elseif ($transactionStatus == 'pending' && $paymentType) {
                Log::info('PENDING with payment_type (Sandbox) - Updating to PAID');
                // Sandbox: jika pending tapi ada payment_type, anggap sudah bayar
                $payment->update([
                    'status_pembayaran' => 'paid',
                    'tipe_pembayaran' => $paymentType,
                    'waktu_settlement' => now(),
                ]);
                $payment->order->update(['status_pesanan' => 'diproses']);
                $updated = true;
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                Log::warning('Transaction ' . strtoupper($transactionStatus));
                $payment->update([
                    'status_pembayaran' => $transactionStatus == 'deny' ? 'failure' : $transactionStatus,
                ]);
                $payment->order->update(['status_pesanan' => 'batal']);
                $updated = true;
            }
            
            return $updated;
        } catch (\Exception $e) {
            Log::error('Error checking payment status: ' . $e->getMessage());
            return false;
        }
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
            if ($item->productVariant) {
                $item->productVariant->increment('stok', $item->jumlah);
            } else {
                $item->product->increment('stok', $item->jumlah);
            }
        }
        
        return back()->with('success', 'Pesanan berhasil dibatalkan!');
    }
}
