# Status Penyesuaian Model, Controller, View, dan Seeder

## ✅ SUDAH DISESUAIKAN

### Models (100% Selesai)
- ✅ `app/Models/Category.php` - Tabel `kategori`
- ✅ `app/Models/Product.php` - Tabel `produk`
- ✅ `app/Models/ProductVariant.php` - Tabel `varian_produk`
- ✅ `app/Models/ProductImage.php` - Tabel `gambar_produk`
- ✅ `app/Models/Cart.php` - Tabel `keranjang`
- ✅ `app/Models/Order.php` - Tabel `pesanan`
- ✅ `app/Models/OrderItem.php` - Tabel `detail_pesanan`
- ✅ `app/Models/Payment.php` - Tabel `pembayaran`
- ✅ `app/Models/Pengiriman.php` - Tabel `pengiriman`
- ✅ `app/Models/Admin.php` - Tabel `admin` (BARU)
- ✅ `app/Models/Pelanggan.php` - Tabel `pelanggan` (BARU)

### Seeders (100% Selesai)
- ✅ `database/seeders/DatabaseSeeder.php`
- ✅ `database/seeders/AdminSeeder.php` - Menggunakan model Admin
- ✅ `database/seeders/PelangganSeeder.php` - Menggunakan model Pelanggan (BARU)
- ✅ `database/seeders/CategorySeeder.php` - Kolom Indonesia
- ✅ `database/seeders/ProductSeeder.php` - Kolom Indonesia
- ✅ `database/seeders/ProductVariantSeeder.php` - Kolom Indonesia

### Controllers Admin (50% Selesai)
- ✅ `app/Http/Controllers/Admin/ProductController.php`
- ✅ `app/Http/Controllers/Admin/CategoryController.php`
- ❌ `app/Http/Controllers/Admin/OrderController.php` - Perlu disesuaikan
- ❌ `app/Http/Controllers/Admin/DashboardController.php` - Perlu disesuaikan

### Controllers Customer (25% Selesai)
- ✅ `app/Http/Controllers/Customer/CartController.php`
- ❌ `app/Http/Controllers/Customer/CheckoutController.php` - Perlu disesuaikan
- ❌ `app/Http/Controllers/Customer/HomeController.php` - Perlu disesuaikan
- ❌ `app/Http/Controllers/Customer/OrderController.php` - Perlu disesuaikan
- ❌ `app/Http/Controllers/Customer/PaymentController.php` - Perlu disesuaikan
- ❌ `app/Http/Controllers/Customer/ProductController.php` - Perlu disesuaikan

---

## ❌ BELUM DISESUAIKAN

### Controllers yang Perlu Update

#### 1. Admin/OrderController.php
**Perubahan yang diperlukan:**
```php
// Lama
$query = Order::with(['user', 'items', 'payment']);
$query->where('order_number', 'like', "%{$search}%")

// Baru
$query = Order::with(['pelanggan', 'items', 'payment']);
$query->where('nomor_invoice', 'like', "%{$search}%")
```

#### 2. Customer/CheckoutController.php
**Perubahan yang diperlukan:**
```php
// Lama
$cartItems = Cart::where('user_id', Auth::id())->get();
Order::create([
    'user_id' => Auth::id(),
    'order_number' => $orderNumber,
    'total_amount' => $totalAmount,
]);

// Baru
$cartItems = Cart::where('id_pelanggan', Auth::id())->get();
Order::create([
    'id_pelanggan' => Auth::id(),
    'nomor_invoice' => $orderNumber,
    'total_bayar' => $totalAmount,
    'tanggal_pesanan' => now(),
]);
```

#### 3. Customer/ProductController.php
**Perubahan yang diperlukan:**
```php
// Lama
$query = Product::where('is_active', true);
$query->where('name', 'like', "%{$search}%");

// Baru
$query = Product::where('status', 'aktif');
$query->where('nama_produk', 'like', "%{$search}%");
```

### Authentication System
**Perlu dibuat/disesuaikan:**

1. **Guards untuk Admin dan Pelanggan**
```php
// config/auth.php
'guards' => [
    'admin' => [
        'driver' => 'session',
        'provider' => 'admins',
    ],
    'pelanggan' => [
        'driver' => 'session',
        'provider' => 'pelanggans',
    ],
],

'providers' => [
    'admins' => [
        'driver' => 'eloquent',
        'model' => App\Models\Admin::class,
    ],
    'pelanggans' => [
        'driver' => 'eloquent',
        'model' => App\Models\Pelanggan::class,
    ],
],
```

2. **Middleware untuk Role**
- `app/Http/Middleware/AdminMiddleware.php`
- `app/Http/Middleware/PelangganMiddleware.php`

3. **Auth Controllers**
- `app/Http/Controllers/Auth/AdminLoginController.php`
- `app/Http/Controllers/Auth/PelangganLoginController.php`
- `app/Http/Controllers/Auth/PelangganRegisterController.php`

### Views yang Perlu Update

#### Admin Views
```blade
<!-- Lama -->
{{ $product->name }}
{{ $product->base_price }}
{{ $category->name }}
{{ $order->order_number }}
{{ $order->total_amount }}

<!-- Baru -->
{{ $product->nama_produk }}
{{ $product->harga }}
{{ $category->nama_kategori }}
{{ $order->nomor_invoice }}
{{ $order->total_bayar }}
```

**File yang perlu disesuaikan:**
- `resources/views/admin/products/*.blade.php`
- `resources/views/admin/categories/*.blade.php`
- `resources/views/admin/orders/*.blade.php`
- `resources/views/admin/dashboard.blade.php`

#### Customer Views
```blade
<!-- Lama -->
{{ $product->name }}
{{ $variant->price }}
{{ $variant->stock_quantity }}
{{ $cart->quantity }}

<!-- Baru -->
{{ $product->nama_produk }}
{{ $variant->harga }}
{{ $variant->stok }}
{{ $cart->jumlah }}
```

**File yang perlu disesuaikan:**
- `resources/views/customer/products/*.blade.php`
- `resources/views/customer/cart/*.blade.php`
- `resources/views/customer/checkout/*.blade.php`
- `resources/views/customer/orders/*.blade.php`
- `resources/views/customer/home.blade.php`

### Routes yang Perlu Disesuaikan

```php
// routes/web.php

// Admin routes dengan guard admin
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('orders', OrderController::class);
});

// Customer routes dengan guard pelanggan
Route::middleware(['auth:pelanggan'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
});
```

### Services yang Perlu Disesuaikan

#### MidtransService.php
```php
// Perlu disesuaikan untuk menggunakan kolom baru
public function createTransaction($order, $cartItems)
{
    $params = [
        'transaction_details' => [
            'order_id' => $order->nomor_invoice, // bukan order_number
            'gross_amount' => $order->total_bayar, // bukan total_amount
        ],
        'customer_details' => [
            'first_name' => $order->pelanggan->nama, // bukan user->name
            'email' => $order->pelanggan->email,
            'phone' => $order->pelanggan->telepon,
        ],
    ];
}
```

#### RajaOngkirService.php
```php
// Perlu disesuaikan untuk menggunakan kolom baru dari pelanggan
public function getCost($destination, $weight, $courier)
{
    // Menggunakan id_kota dari pelanggan
}
```

---

## 📋 CHECKLIST LANGKAH SELANJUTNYA

### Prioritas Tinggi
- [ ] Update Authentication System (Guards, Middleware, Controllers)
- [ ] Update Admin/OrderController.php
- [ ] Update Customer/CheckoutController.php
- [ ] Update Customer/ProductController.php
- [ ] Update Routes dengan guard yang benar

### Prioritas Sedang
- [ ] Update semua Admin Views
- [ ] Update semua Customer Views
- [ ] Update MidtransService.php
- [ ] Update RajaOngkirService.php

### Prioritas Rendah
- [ ] Update Admin/DashboardController.php
- [ ] Update Customer/HomeController.php
- [ ] Update Customer/OrderController.php
- [ ] Update Customer/PaymentController.php

---

## 🧪 TESTING CHECKLIST

Setelah semua disesuaikan:

### Database
- [ ] `php artisan migrate:fresh`
- [ ] `php artisan db:seed`
- [ ] Verifikasi data di database

### Authentication
- [ ] Login sebagai Admin
- [ ] Login sebagai Pelanggan
- [ ] Register Pelanggan baru
- [ ] Logout

### Admin Panel
- [ ] CRUD Kategori
- [ ] CRUD Produk
- [ ] CRUD Varian Produk
- [ ] Lihat dan Update Order
- [ ] Update Status Pengiriman

### Customer Panel
- [ ] Browse Produk
- [ ] Lihat Detail Produk
- [ ] Tambah ke Keranjang
- [ ] Update Keranjang
- [ ] Checkout
- [ ] Pembayaran Midtrans
- [ ] Lihat Order History

### Integration
- [ ] RajaOngkir API (Cek Ongkir)
- [ ] Midtrans Payment Gateway
- [ ] Email Notifications (jika ada)

---

## 📝 CATATAN PENTING

1. **Backup Database**: Selalu backup database sebelum migrate
2. **Environment**: Pastikan `.env` sudah dikonfigurasi dengan benar
3. **API Keys**: Pastikan Midtrans dan RajaOngkir API keys sudah diset
4. **Storage Link**: Jalankan `php artisan storage:link` untuk upload gambar
5. **Cache Clear**: Jalankan `php artisan cache:clear` dan `php artisan config:clear`

---

## 🎯 PROGRESS KESELURUHAN

- **Models**: ✅ 100% (11/11)
- **Seeders**: ✅ 100% (6/6)
- **Controllers**: 🔄 37.5% (3/8)
- **Views**: ❌ 0% (0/~30)
- **Authentication**: ❌ 0%
- **Routes**: ❌ 0%
- **Services**: ❌ 0%

**Total Progress**: ~30%
