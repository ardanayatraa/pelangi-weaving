<?php

use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\ProductController as CustomerProductController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\PaymentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\PelangganLoginController;
use App\Http\Controllers\Auth\PelangganRegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [CustomerProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [CustomerProductController::class, 'show'])->name('products.show');

/*
|--------------------------------------------------------------------------
| Pelanggan Authentication Routes
|--------------------------------------------------------------------------
*/

Route::get('/login', [PelangganLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [PelangganLoginController::class, 'login']);
Route::post('/logout', [PelangganLoginController::class, 'logout'])->name('logout');

Route::get('/register', [PelangganRegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [PelangganRegisterController::class, 'register']);

/*
|--------------------------------------------------------------------------
| Pelanggan Routes (Authenticated)
|--------------------------------------------------------------------------
*/

Route::middleware(['pelanggan'])->group(function () {
    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
    
    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    
    // Orders
    Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{nomorInvoice}', [CustomerOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{nomorInvoice}/cancel', [CustomerOrderController::class, 'cancel'])->name('orders.cancel');
    
    // Payment
    Route::get('/payment/finish', [PaymentController::class, 'finish'])->name('payment.finish');
    Route::get('/payment/{nomorInvoice}', [PaymentController::class, 'show'])->name('payment.show');
    
    // Profile
    Route::get('/profile', [App\Http\Controllers\Customer\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [App\Http\Controllers\Customer\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\Customer\ProfileController::class, 'updatePassword'])->name('profile.password');
});

// Payment Callback (Public - untuk Midtrans webhook)
Route::post('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login']);
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Authenticated)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Categories
    Route::resource('categories', CategoryController::class);
    
    // Products
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}', [AdminProductController::class, 'show'])->name('products.show');
    Route::get('/products/{id}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('products.destroy');
    
    // Product Variants
    Route::post('/products/{productId}/variants', [AdminProductController::class, 'storeVariant'])->name('products.variants.store');
    Route::put('/products/{productId}/variants/{variantId}', [AdminProductController::class, 'updateVariant'])->name('products.variants.update');
    Route::delete('/products/{productId}/variants/{variantId}', [AdminProductController::class, 'destroyVariant'])->name('products.variants.destroy');
    
    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/update-status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('/orders/{id}/update-shipping', [AdminOrderController::class, 'updateShipping'])->name('orders.update-shipping');
    Route::get('/orders/{id}/invoice', [AdminOrderController::class, 'printInvoice'])->name('orders.invoice');
});

/*
|--------------------------------------------------------------------------
| RajaOngkir API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('api/rajaongkir')->middleware('pelanggan')->group(function () {
    $rajaongkir = app(\App\Services\RajaOngkirService::class);
    
    Route::get('/search', function (Illuminate\Http\Request $request) use ($rajaongkir) {
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
    
    Route::post('/calculate-cost', function (Illuminate\Http\Request $request) use ($rajaongkir) {
        $validated = $request->validate([
            'destination_subdistrict_id' => 'required|integer',
            'weight' => 'required|integer|min:1',
            'courier' => 'required|string',
        ]);
        
        $originSubdistrictId = config('rajaongkir.origin_subdistrict_id', 1);
        
        $costs = $rajaongkir->calculateCost(
            $originSubdistrictId,
            $validated['destination_subdistrict_id'],
            $validated['weight'],
            $validated['courier']
        );
        
        return response()->json([
            'success' => true,
            'data' => $costs
        ]);
    });
    
    Route::get('/couriers', function () use ($rajaongkir) {
        $couriers = $rajaongkir->getAvailableCouriers();
        return response()->json([
            'success' => true,
            'data' => $couriers
        ]);
    });
});
