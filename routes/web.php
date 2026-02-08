<?php

use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\ProductController as CustomerProductController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\CustomOrderController as CustomerCustomOrderController;
use App\Http\Controllers\Customer\PaymentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CustomOrderController as AdminCustomOrderController;
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
    Route::post('/orders/{nomorInvoice}/complete', [CustomerOrderController::class, 'complete'])->name('orders.complete');
    
    // Custom Orders
    Route::prefix('custom-orders')->name('custom-orders.')->group(function () {
        Route::get('/', [CustomerCustomOrderController::class, 'index'])->name('index');
        Route::get('/create', [CustomerCustomOrderController::class, 'create'])->name('create');
        Route::post('/', [CustomerCustomOrderController::class, 'store'])->name('store');
        Route::get('/{nomorCustomOrder}', [CustomerCustomOrderController::class, 'show'])->name('show');
        Route::get('/{nomorCustomOrder}/edit', [CustomerCustomOrderController::class, 'edit'])->name('edit');
        Route::put('/{nomorCustomOrder}', [CustomerCustomOrderController::class, 'update'])->name('update');
        Route::post('/{nomorCustomOrder}/cancel', [CustomerCustomOrderController::class, 'cancel'])->name('cancel');
        Route::post('/{nomorCustomOrder}/submit', [CustomerCustomOrderController::class, 'submitForApproval'])->name('submit');
        
        // DP Payment
        Route::get('/{nomorCustomOrder}/payment', [CustomerCustomOrderController::class, 'payment'])->name('payment');
        Route::post('/{nomorCustomOrder}/payment', [CustomerCustomOrderController::class, 'processPayment'])->name('payment.process');
        Route::get('/{nomorCustomOrder}/payment/finish', [CustomerCustomOrderController::class, 'paymentFinish'])->name('payment.finish');
        
        // Final Payment (Pelunasan)
        Route::get('/{nomorCustomOrder}/final-payment', [CustomerCustomOrderController::class, 'finalPayment'])->name('final-payment');
        Route::post('/{nomorCustomOrder}/final-payment', [CustomerCustomOrderController::class, 'processFinalPayment'])->name('final-payment.process');
        Route::get('/{nomorCustomOrder}/final-payment/finish', [CustomerCustomOrderController::class, 'finalPaymentFinish'])->name('final-payment.finish');
        
        Route::delete('/{nomorCustomOrder}/image', [CustomerCustomOrderController::class, 'removeImage'])->name('remove-image');
    });
    
    // Payment
    Route::get('/payment/finish', [PaymentController::class, 'finish'])->name('payment.finish');
    Route::get('/payment/{nomorInvoice}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{nomorInvoice}/refresh', [PaymentController::class, 'refresh'])->name('payment.refresh');
    
    // Profile
    Route::get('/profile', [App\Http\Controllers\Customer\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [App\Http\Controllers\Customer\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\Customer\ProfileController::class, 'updatePassword'])->name('profile.password');
    
    // Alamat Management
    Route::post('/alamat', [App\Http\Controllers\Customer\ProfileController::class, 'storeAlamat'])->name('alamat.store');
    Route::put('/alamat/{index}', [App\Http\Controllers\Customer\ProfileController::class, 'updateAlamat'])->name('alamat.update');
    Route::delete('/alamat/{index}', [App\Http\Controllers\Customer\ProfileController::class, 'deleteAlamat'])->name('alamat.delete');
    Route::post('/alamat/{index}/set-default', [App\Http\Controllers\Customer\ProfileController::class, 'setDefaultAlamat'])->name('alamat.set-default');
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
    Route::get('/products/{slug}', [AdminProductController::class, 'show'])->name('products.show');
    Route::get('/products/{slug}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{slug}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{slug}', [AdminProductController::class, 'destroy'])->name('products.destroy');
    
    // Product Variants
    Route::post('/products/{slug}/variants', [AdminProductController::class, 'storeVariant'])->name('products.variants.store');
    Route::put('/products/{slug}/variants/{variantId}', [AdminProductController::class, 'updateVariant'])->name('products.variants.update');
    Route::delete('/products/{slug}/variants/{variantId}', [AdminProductController::class, 'destroyVariant'])->name('products.variants.destroy');
    
    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/update-status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('/orders/{id}/update-shipping', [AdminOrderController::class, 'updateShipping'])->name('orders.update-shipping');
    Route::get('/orders/{id}/invoice', [AdminOrderController::class, 'printInvoice'])->name('orders.invoice');
    
    // Custom Orders
    Route::prefix('custom-orders')->name('custom-orders.')->group(function () {
        Route::get('/', [AdminCustomOrderController::class, 'index'])->name('index');
        Route::get('/{nomorCustomOrder}', [AdminCustomOrderController::class, 'show'])->name('show');
        Route::post('/{nomorCustomOrder}/update-status', [AdminCustomOrderController::class, 'updateStatus'])->name('update-status');
        Route::post('/{nomorCustomOrder}/approve', [AdminCustomOrderController::class, 'approve'])->name('approve');
        Route::post('/{nomorCustomOrder}/reject', [AdminCustomOrderController::class, 'reject'])->name('reject');
        Route::post('/{nomorCustomOrder}/update-progress', [AdminCustomOrderController::class, 'updateProgress'])->name('update-progress');
        Route::post('/{nomorCustomOrder}/mark-completed', [AdminCustomOrderController::class, 'markCompleted'])->name('mark-completed');
        Route::delete('/{nomorCustomOrder}', [AdminCustomOrderController::class, 'destroy'])->name('destroy');
    });
    
    // Jenis Management
    Route::get('/jenis', [App\Http\Controllers\Admin\JenisController::class, 'index'])->name('jenis.index');
    Route::get('/jenis/create', [App\Http\Controllers\Admin\JenisController::class, 'create'])->name('jenis.create');
    Route::post('/jenis', [App\Http\Controllers\Admin\JenisController::class, 'store'])->name('jenis.store');
    Route::get('/jenis/{jenis}', [App\Http\Controllers\Admin\JenisController::class, 'show'])->name('jenis.show');
    Route::get('/jenis/{jenis}/edit', [App\Http\Controllers\Admin\JenisController::class, 'edit'])->name('jenis.edit');
    Route::put('/jenis/{jenis}', [App\Http\Controllers\Admin\JenisController::class, 'update'])->name('jenis.update');
    Route::delete('/jenis/{jenis}', [App\Http\Controllers\Admin\JenisController::class, 'destroy'])->name('jenis.destroy');
    
    // Pelanggan Management
    Route::get('/pelanggan', [App\Http\Controllers\Admin\PelangganController::class, 'index'])->name('pelanggan.index');
    Route::get('/pelanggan/{pelanggan}', [App\Http\Controllers\Admin\PelangganController::class, 'show'])->name('pelanggan.show');
    Route::delete('/pelanggan/{pelanggan}', [App\Http\Controllers\Admin\PelangganController::class, 'destroy'])->name('pelanggan.destroy');
    
    // Reports
    Route::get('/reports/sales', [App\Http\Controllers\Admin\ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/export-sales', [App\Http\Controllers\Admin\ReportController::class, 'exportSales'])->name('reports.export-sales');
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
