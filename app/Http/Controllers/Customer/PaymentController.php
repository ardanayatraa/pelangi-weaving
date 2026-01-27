<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }
    public function show($nomorInvoice)
    {
        $order = Pesanan::with(['payment', 'items'])
            ->where('nomor_invoice', $nomorInvoice)
            ->where('id_pelanggan', Auth::guard('pelanggan')->id())
            ->firstOrFail();
        
        if (!$order->payment) {
            return redirect()->route('orders.index')
                ->with('error', 'Data pembayaran tidak ditemukan!');
        }
        
        // Check if payment token needs refresh (if it's older than 23 hours)
        if ($order->payment->created_at->diffInHours(now()) > 23) {
            $this->refreshPaymentToken($order);
        }
        
        return view('customer.payment.show', compact('order'));
    }
    
    private function refreshPaymentToken($order)
    {
        try {
            // Generate new Snap token using MidtransService
            $snapToken = $this->midtransService->createTransaction($order);
            
            // Update payment with new token
            $order->payment->update([
                'snap_token' => $snapToken,
                'updated_at' => now(),
            ]);
            
            Log::info('Payment token refreshed for order: ' . $order->nomor_invoice);
            
        } catch (\Exception $e) {
            Log::error('Failed to refresh payment token: ' . $e->getMessage());
            // Don't throw exception, just log it so the page still loads
        }
    }
    
    public function refresh($nomorInvoice)
    {
        $order = Pesanan::with(['payment'])
            ->where('nomor_invoice', $nomorInvoice)
            ->where('id_pelanggan', Auth::guard('pelanggan')->id())
            ->firstOrFail();
        
        if (!$order->payment) {
            return response()->json([
                'success' => false,
                'message' => 'Data pembayaran tidak ditemukan!'
            ], 404);
        }
        
        // Refresh payment token
        $this->refreshPaymentToken($order);
        
        return response()->json([
            'success' => true,
            'message' => 'Token pembayaran berhasil diperbarui',
            'snap_token' => $order->payment->fresh()->snap_token
        ]);
    }
    
    public function finish(Request $request)
    {
        Log::info('=== PAYMENT FINISH CALLED ===');
        Log::info('Request data:', $request->all());
        
        $orderId = $request->order_id;
        
        if (!$orderId) {
            Log::warning('Order ID tidak ditemukan di request');
            return redirect()->route('orders.index')
                ->with('error', 'Order ID tidak ditemukan!');
        }
        
        // Find order by nomor_invoice instead of midtrans_order_id
        $order = Pesanan::where('nomor_invoice', $orderId)->first();
        
        if (!$order || !$order->payment) {
            Log::error('Order atau payment tidak ditemukan untuk order_id: ' . $orderId);
            return redirect()->route('orders.index')
                ->with('error', 'Pembayaran tidak ditemukan!');
        }
        
        $payment = $order->payment;
        
        Log::info('Payment ditemukan:', [
            'id' => $payment->id_pembayaran,
            'status_bayar' => $payment->status_bayar,
            'nomor_invoice' => $order->nomor_invoice
        ]);
        
        // Update payment status to paid (simplified)
        if ($payment->status_bayar !== 'paid') {
            $payment->update([
                'status_bayar' => 'paid',
                'tanggal_bayar' => now(),
            ]);
            $order->update(['status_pesanan' => 'diproses']);
        }
        
        $message = $payment->status_bayar == 'paid' 
            ? 'Pembayaran berhasil! Pesanan Anda sedang diproses.' 
            : 'Terima kasih! Pembayaran Anda sedang diverifikasi.';
        
        Log::info('Redirecting ke orders.show dengan message: ' . $message);
        
        // Redirect ke halaman detail pesanan
        return redirect()->route('orders.show', $order->nomor_invoice)
            ->with('success', $message);
    }

    public function callback(Request $request)
    {
        Log::info('=== PAYMENT CALLBACK RECEIVED ===');
        Log::info('Callback data:', $request->all());
        
        // Simplified callback handling
        $orderId = $request->order_id;
        
        if (!$orderId) {
            Log::error('Order ID tidak ditemukan dalam callback');
            return response()->json(['message' => 'Order ID required'], 400);
        }
        
        $order = Pesanan::where('nomor_invoice', $orderId)->first();
        
        if (!$order || !$order->payment) {
            Log::error('Order atau payment tidak ditemukan untuk order_id: ' . $orderId);
            return response()->json(['message' => 'Order not found'], 404);
        }
        
        $payment = $order->payment;
        $transactionStatus = $request->transaction_status;
        
        Log::info('Processing transaction status: ' . $transactionStatus);
        
        // Simplified status handling
        if (in_array($transactionStatus, ['capture', 'settlement'])) {
            Log::info('Payment successful - Updating to PAID');
            $payment->update([
                'status_bayar' => 'paid',
                'tanggal_bayar' => now(),
            ]);
            $order->update(['status_pesanan' => 'diproses']);
        } elseif ($transactionStatus == 'pending') {
            Log::info('Payment pending');
            $payment->update(['status_bayar' => 'pending']);
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            Log::warning('Payment failed/cancelled');
            $payment->update(['status_bayar' => 'failed']);
            $order->update(['status_pesanan' => 'batal']);
        }
        
        Log::info('Callback processed successfully');
        return response()->json(['message' => 'OK']);
    }
}
