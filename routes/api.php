<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Services\RajaOngkirService;
use App\Services\MidtransService;
use App\Models\Pesanan;
use App\Models\Pembayaran;

/*
|--------------------------------------------------------------------------
| API Routes (prefix /api otomatis oleh Laravel)
|--------------------------------------------------------------------------
*/

// RajaOngkir API Routes
Route::prefix('rajaongkir')->middleware('web')->group(function () {
    $rajaongkir = app(RajaOngkirService::class);
    
    // Direct Search Method (Modern)
    Route::get('/search', function (Request $request) use ($rajaongkir) {
        $query = $request->input('q', '');
        $limit = $request->input('limit', 10);
        
        if (strlen($query) < 3) {
            return response()->json([
                'success' => false,
                'message' => 'Query minimal 3 karakter'
            ], 400);
        }
        
        $results = $rajaongkir->searchDestination($query, $limit);
        
        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    });
    
    // Step-by-Step Method
    Route::get('/provinces', function () use ($rajaongkir) {
        $provinces = $rajaongkir->getProvinces();
        return response()->json([
            'success' => true,
            'data' => $provinces
        ]);
    });
    
    Route::get('/cities/{provinceId}', function ($provinceId) use ($rajaongkir) {
        $cities = $rajaongkir->getCities($provinceId);
        return response()->json([
            'success' => true,
            'data' => $cities
        ]);
    });
    
    Route::get('/districts/{cityId}', function ($cityId) use ($rajaongkir) {
        $districts = $rajaongkir->getDistricts($cityId);
        return response()->json([
            'success' => true,
            'data' => $districts
        ]);
    });
    
    Route::get('/subdistricts/{districtId}', function ($districtId) use ($rajaongkir) {
        $subdistricts = $rajaongkir->getSubdistricts($districtId);
        return response()->json([
            'success' => true,
            'data' => $subdistricts
        ]);
    });
    
    // Calculate shipping cost
    Route::post('/calculate-cost', function (Request $request) use ($rajaongkir) {
        $validated = $request->validate([
            'destination_id' => 'required|integer',
            'weight' => 'required|integer|min:1',
            'courier' => 'required|string',
            'price' => 'nullable|in:lowest,highest',
        ]);
        
        $originId = env('RAJAONGKIR_ORIGIN_SUBDISTRICT_ID', 6234);
        
        $costs = $rajaongkir->calculateCost(
            $originId,
            $validated['destination_id'],
            $validated['weight'],
            $validated['courier'],
            $validated['price'] ?? null
        );
        
        return response()->json([
            'success' => true,
            'data' => $costs
        ]);
    });
    
    // Get available couriers
    Route::get('/couriers', function () use ($rajaongkir) {
        $couriers = $rajaongkir->getAvailableCouriers();
        return response()->json([
            'success' => true,
            'data' => $couriers
        ]);
    });
});

// Midtrans Notification Webhook (pakai model Pesanan & Pembayaran)
Route::post('/midtrans/notification', function (Request $request) {
    $midtrans = app(MidtransService::class);

    try {
        $notification = $midtrans->handleNotification($request->all());
        $orderId = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status ?? 'accept';

        \Log::info('Midtrans Notification (API)', [
            'order_id' => $orderId,
            'transaction_status' => $transactionStatus,
            'fraud_status' => $fraudStatus,
        ]);

        $order = Pesanan::where('nomor_invoice', $orderId)->first();
        if (!$order) {
            \Log::error('Order not found: ' . $orderId);
            return response()->json(['message' => 'Order not found'], 404);
        }

        $payment = $order->payment;
        if (!$payment) {
            \Log::error('Payment not found for order: ' . $orderId);
            return response()->json(['message' => 'Payment not found'], 404);
        }

        if ($transactionStatus == 'capture' && $fraudStatus == 'accept') {
            $payment->update(['status_bayar' => 'paid', 'tanggal_bayar' => now()]);
            $order->update(['status_pesanan' => 'diproses']);
        } elseif ($transactionStatus == 'settlement') {
            $payment->update(['status_bayar' => 'paid', 'tanggal_bayar' => now()]);
            $order->update(['status_pesanan' => 'diproses']);
        } elseif ($transactionStatus == 'pending') {
            $payment->update(['status_bayar' => 'pending']);
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $payment->update(['status_bayar' => 'failed']);
            $order->update(['status_pesanan' => 'batal']);
        }

        \Log::info('Order & Payment updated (API)', [
            'order_id' => $orderId,
            'status_pesanan' => $order->status_pesanan,
            'status_bayar' => $payment->status_bayar,
        ]);

        return response()->json(['message' => 'Notification processed']);
    } catch (\Exception $e) {
        \Log::error('Midtrans notification error: ' . $e->getMessage());
        return response()->json(['message' => 'Error processing notification'], 500);
    }
});
