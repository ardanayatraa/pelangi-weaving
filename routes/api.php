<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Services\RajaOngkirService;
use App\Services\MidtransService;
use App\Models\Order;
use App\Models\Payment;

/*
|--------------------------------------------------------------------------
| API Routes
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

// Midtrans Notification Webhook
Route::post('/midtrans/notification', function (Request $request) {
    $midtrans = app(MidtransService::class);
    
    try {
        // Get notification data
        $notification = $midtrans->handleNotification($request->all());
        
        $orderId = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status ?? 'accept';
        
        \Log::info('Midtrans Notification', [
            'order_id' => $orderId,
            'transaction_status' => $transactionStatus,
            'fraud_status' => $fraudStatus
        ]);
        
        // Find order
        $order = Order::where('order_number', $orderId)->first();
        
        if (!$order) {
            \Log::error('Order not found: ' . $orderId);
            return response()->json(['message' => 'Order not found'], 404);
        }
        
        // Find payment
        $payment = Payment::where('order_id', $order->id)->first();
        
        if (!$payment) {
            \Log::error('Payment not found for order: ' . $orderId);
            return response()->json(['message' => 'Payment not found'], 404);
        }
        
        // Update payment status based on transaction status
        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'accept') {
                $payment->status = 'paid';
                $order->status = 'processing';
            }
        } elseif ($transactionStatus == 'settlement') {
            $payment->status = 'paid';
            $order->status = 'processing';
        } elseif ($transactionStatus == 'pending') {
            $payment->status = 'pending';
            $order->status = 'pending';
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $payment->status = 'failed';
            $order->status = 'cancelled';
        }
        
        $payment->save();
        $order->save();
        
        \Log::info('Order & Payment updated', [
            'order_id' => $orderId,
            'order_status' => $order->status,
            'payment_status' => $payment->status
        ]);
        
        return response()->json(['message' => 'Notification processed']);
        
    } catch (\Exception $e) {
        \Log::error('Midtrans notification error: ' . $e->getMessage());
        return response()->json(['message' => 'Error processing notification'], 500);
    }
});
