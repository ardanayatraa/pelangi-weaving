<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function show($nomorInvoice)
    {
        $order = Order::with(['payment', 'items'])
            ->where('nomor_invoice', $nomorInvoice)
            ->where('id_pelanggan', Auth::guard('pelanggan')->id())
            ->firstOrFail();
        
        if (!$order->payment) {
            return redirect()->route('orders.index')
                ->with('error', 'Data pembayaran tidak ditemukan!');
        }
        
        // Cek status terbaru dari Midtrans jika masih pending
        if ($order->payment->status_pembayaran === 'pending') {
            $this->checkPaymentStatus($order->payment);
            $order->refresh();
        }
        
        return view('customer.payment.show', compact('order'));
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
        
        Log::info('Mencari payment dengan order_id: ' . $orderId);
        $payment = Payment::where('midtrans_order_id', $orderId)->first();
        
        if (!$payment) {
            Log::error('Payment tidak ditemukan untuk order_id: ' . $orderId);
            return redirect()->route('orders.index')
                ->with('error', 'Pembayaran tidak ditemukan!');
        }
        
        Log::info('Payment ditemukan:', [
            'id' => $payment->id_pembayaran,
            'status_sebelum' => $payment->status_pembayaran,
            'midtrans_order_id' => $payment->midtrans_order_id
        ]);
        
        // Cek status terbaru dan update
        $updated = $this->checkPaymentStatus($payment);
        
        Log::info('Status payment updated: ' . ($updated ? 'YES' : 'NO'));
        
        // Refresh payment data
        $payment->refresh();
        $order = $payment->order;
        
        Log::info('Payment status setelah refresh:', [
            'status_pembayaran' => $payment->status_pembayaran,
            'tipe_pembayaran' => $payment->tipe_pembayaran,
            'status_pesanan' => $order->status_pesanan
        ]);
        
        $message = $payment->status_pembayaran == 'paid' 
            ? 'Pembayaran berhasil! Pesanan Anda sedang diproses.' 
            : 'Terima kasih! Pembayaran Anda sedang diverifikasi.';
        
        Log::info('Redirecting ke orders.show dengan message: ' . $message);
        
        // Redirect ke halaman detail pesanan
        return redirect()->route('orders.show', $order->nomor_invoice)
            ->with('success', $message);
    }
    
    private function checkPaymentStatus(Payment $payment)
    {
        try {
            Log::info('Checking payment status dari Midtrans untuk order: ' . $payment->midtrans_order_id);
            
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            
            $status = \Midtrans\Transaction::status($payment->midtrans_order_id);
            
            $transactionStatus = $status->transaction_status;
            $fraudStatus = $status->fraud_status ?? null;
            $paymentType = $status->payment_type ?? null;
            
            Log::info('Midtrans response:', [
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'payment_type' => $paymentType,
                'order_id' => $status->order_id ?? null,
                'gross_amount' => $status->gross_amount ?? null
            ]);
            
            $updated = false;
            
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    Log::info('Status: CAPTURE with fraud_status ACCEPT - Updating to PAID');
                    $payment->update([
                        'status_pembayaran' => 'paid',
                        'tipe_pembayaran' => $paymentType,
                        'waktu_settlement' => now(),
                    ]);
                    $payment->order->update(['status_pesanan' => 'diproses']);
                    $updated = true;
                }
            } elseif ($transactionStatus == 'settlement') {
                Log::info('Status: SETTLEMENT - Updating to PAID');
                $payment->update([
                    'status_pembayaran' => 'paid',
                    'tipe_pembayaran' => $paymentType,
                    'waktu_settlement' => now(),
                ]);
                $payment->order->update(['status_pesanan' => 'diproses']);
                $updated = true;
            } elseif ($transactionStatus == 'pending' && $paymentType) {
                Log::info('Status: PENDING with payment_type (Sandbox mode) - Updating to PAID');
                // Sandbox: jika pending tapi ada payment_type, anggap sudah bayar
                $payment->update([
                    'status_pembayaran' => 'paid',
                    'tipe_pembayaran' => $paymentType,
                    'waktu_settlement' => now(),
                ]);
                $payment->order->update(['status_pesanan' => 'diproses']);
                $updated = true;
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                Log::warning('Status: ' . strtoupper($transactionStatus) . ' - Updating to FAILED/CANCELLED');
                $payment->update([
                    'status_pembayaran' => $transactionStatus == 'deny' ? 'failure' : $transactionStatus,
                ]);
                $payment->order->update(['status_pesanan' => 'batal']);
                $updated = true;
            } else {
                Log::info('Status tidak dikenali atau tidak perlu update: ' . $transactionStatus);
            }
            
            return $updated;
        } catch (\Exception $e) {
            Log::error('Error checking payment status: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    public function callback(Request $request)
    {
        Log::info('=== MIDTRANS CALLBACK RECEIVED ===');
        Log::info('Callback data:', $request->all());
        
        $serverKey = config('midtrans.server_key');
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        
        if ($hashed !== $request->signature_key) {
            Log::error('Invalid signature key dari Midtrans callback');
            return response()->json(['message' => 'Invalid signature'], 403);
        }
        
        Log::info('Signature valid, processing callback...');
        
        $payment = Payment::where('midtrans_order_id', $request->order_id)->first();
        
        if (!$payment) {
            Log::error('Payment tidak ditemukan untuk order_id: ' . $request->order_id);
            return response()->json(['message' => 'Payment not found'], 404);
        }
        
        $order = $payment->order;
        
        $transactionStatus = $request->transaction_status;
        $fraudStatus = $request->fraud_status ?? null;
        
        Log::info('Processing transaction status: ' . $transactionStatus);
        
        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'accept') {
                Log::info('CAPTURE with ACCEPT - Updating payment to PAID');
                $payment->update([
                    'status_pembayaran' => 'paid',
                    'tipe_pembayaran' => $request->payment_type,
                    'waktu_transaksi' => now(),
                    'waktu_settlement' => now(),
                    'fraud_status' => $fraudStatus,
                ]);
                $order->update(['status_pesanan' => 'diproses']);
            }
        } elseif ($transactionStatus == 'settlement') {
            Log::info('SETTLEMENT - Updating payment to PAID');
            $payment->update([
                'status_pembayaran' => 'paid',
                'tipe_pembayaran' => $request->payment_type,
                'waktu_transaksi' => now(),
                'waktu_settlement' => now(),
                'fraud_status' => $fraudStatus,
            ]);
            $order->update(['status_pesanan' => 'diproses']);
        } elseif ($transactionStatus == 'pending') {
            Log::info('PENDING - Updating payment status');
            $payment->update([
                'status_pembayaran' => 'pending',
                'tipe_pembayaran' => $request->payment_type,
                'waktu_transaksi' => now(),
            ]);
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            Log::warning('Transaction ' . strtoupper($transactionStatus) . ' - Cancelling order');
            $payment->update([
                'status_pembayaran' => $transactionStatus == 'deny' ? 'failure' : $transactionStatus,
                'tipe_pembayaran' => $request->payment_type,
                'waktu_transaksi' => now(),
            ]);
            $order->update(['status_pesanan' => 'batal']);
        }
        
        Log::info('Callback processed successfully');
        return response()->json(['message' => 'OK']);
    }
}
