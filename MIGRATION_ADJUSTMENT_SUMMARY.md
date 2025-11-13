# Ringkasan Penyesuaian Model, View, dan Controller dengan Migration Database

## Perubahan Struktur Database

Migration database menggunakan nama tabel dan kolom dalam Bahasa Indonesia dengan konvensi yang berbeda dari model awal:

### Tabel dan Primary Key yang Disesuaikan:

1. **kategori** (bukan `categories`)
   - Primary Key: `id_kategori`
   - Kolom: `nama_kategori`, `slug`, `deskripsi`

2. **produk** (bukan `products`)
   - Primary Key: `id_produk`
   - Foreign Key: `id_kategori`
   - Kolom: `nama_produk`, `slug`, `deskripsi`, `harga`, `stok`, `berat`, `status`

3. **varian_produk** (bukan `product_variants`)
   - Primary Key: `id_varian`
   - Foreign Key: `id_produk`
   - Kolom: `nama_varian`, `kode_varian`, `harga`, `stok`, `berat`, `warna`, `ukuran`, `jenis_benang`, `status`

4. **gambar_produk** (bukan `product_images`)
   - Primary Key: `id_gambar`
   - Foreign Keys: `id_produk`, `id_varian`
   - Kolom: `path`, `is_primary`
   - Tidak ada timestamps

5. **pelanggan** (tabel terpisah, bukan role di `users`)
   - Primary Key: `id_pelanggan`
   - Kolom: `nama`, `email`, `password`, `alamat`, `telepon`, `id_kota`, `id_provinsi`, `kode_pos`

6. **admin** (tabel terpisah, bukan role di `users`)
   - Primary Key: `id_admin`
   - Kolom: `nama`, `email`, `password`, `role`, `last_login`

7. **keranjang** (bukan `carts`)
   - Primary Key: `id_keranjang`
   - Foreign Keys: `id_pelanggan`, `id_produk`, `id_varian`
   - Kolom: `jumlah`

8. **pesanan** (bukan `orders`)
   - Primary Key: `id_pesanan`
   - Foreign Key: `id_pelanggan`
   - Kolom: `nomor_invoice`, `tanggal_pesanan`, `subtotal`, `ongkir`, `total_bayar`, `status_pesanan`, `catatan`

9. **detail_pesanan** (bukan `order_items`)
   - Primary Key: `id_detail`
   - Foreign Keys: `id_pesanan`, `id_produk`, `id_varian`
   - Kolom: `jumlah`, `harga_satuan`, `subtotal`
   - Tidak ada timestamps

10. **pembayaran** (bukan `payments`)
    - Primary Key: `id_pembayaran`
    - Foreign Key: `id_pesanan` (unique)
    - Kolom: `midtrans_order_id`, `snap_token`, `tipe_pembayaran`, `status_pembayaran`, `waktu_transaksi`, `waktu_settlement`, `fraud_status`

11. **pengiriman** (sudah sesuai)
    - Primary Key: `id_pengiriman`
    - Foreign Key: `id_pesanan` (unique)
    - Kolom: `id_kota_asal`, `id_kota_tujuan`, `kurir`, `layanan`, `ongkir`, `estimasi_pengiriman`, `alamat_pengiriman`, `no_resi`, `status_pengiriman`, `tanggal_kirim`, `tanggal_terima`

## Model yang Telah Disesuaikan:

### ✅ Model Baru:
- `app/Models/Admin.php` - Model untuk tabel admin
- `app/Models/Pelanggan.php` - Model untuk tabel pelanggan

### ✅ Model yang Diupdate:
- `app/Models/Category.php` - Disesuaikan dengan tabel `kategori`
- `app/Models/Product.php` - Disesuaikan dengan tabel `produk`
- `app/Models/ProductVariant.php` - Disesuaikan dengan tabel `varian_produk`
- `app/Models/ProductImage.php` - Disesuaikan dengan tabel `gambar_produk`
- `app/Models/Cart.php` - Disesuaikan dengan tabel `keranjang`
- `app/Models/Order.php` - Disesuaikan dengan tabel `pesanan`
- `app/Models/OrderItem.php` - Disesuaikan dengan tabel `detail_pesanan`
- `app/Models/Payment.php` - Disesuaikan dengan tabel `pembayaran`
- `app/Models/Pengiriman.php` - Disesuaikan dengan tabel `pengiriman`

## Controller yang Telah Disesuaikan:

### ✅ Admin Controllers:
- `app/Http/Controllers/Admin/ProductController.php` - Disesuaikan dengan nama kolom baru
- `app/Http/Controllers/Admin/CategoryController.php` - Disesuaikan dengan nama kolom baru

### ✅ Customer Controllers:
- `app/Http/Controllers/Customer/CartController.php` - Disesuaikan dengan nama kolom baru

## Yang Perlu Disesuaikan Selanjutnya:

### 🔄 Controllers yang Masih Perlu Update:
1. `app/Http/Controllers/Admin/OrderController.php`
2. `app/Http/Controllers/Admin/DashboardController.php`
3. `app/Http/Controllers/Customer/CheckoutController.php`
4. `app/Http/Controllers/Customer/HomeController.php`
5. `app/Http/Controllers/Customer/OrderController.php`
6. `app/Http/Controllers/Customer/PaymentController.php`
7. `app/Http/Controllers/Customer/ProductController.php`
8. `app/Http/Controllers/Auth/*` - Perlu disesuaikan untuk menggunakan model Pelanggan/Admin

### 🔄 Views yang Perlu Update:
Semua view yang menggunakan nama kolom lama perlu disesuaikan:
- `resources/views/admin/**/*.blade.php`
- `resources/views/customer/**/*.blade.php`

Contoh perubahan di view:
```blade
<!-- Lama -->
{{ $product->name }}
{{ $product->base_price }}
{{ $category->name }}

<!-- Baru -->
{{ $product->nama_produk }}
{{ $product->harga }}
{{ $category->nama_kategori }}
```

### 🔄 Routes yang Perlu Disesuaikan:
Route model binding perlu disesuaikan untuk menggunakan primary key yang benar:
```php
// Lama
Route::get('/products/{product}', ...);

// Baru (jika menggunakan route model binding)
Route::get('/products/{id}', ...);
// atau konfigurasi di model untuk route key name
```

### 🔄 Authentication:
Perlu menyesuaikan sistem authentication untuk:
- Menggunakan model `Pelanggan` untuk customer
- Menggunakan model `Admin` untuk admin
- Memisahkan guard untuk pelanggan dan admin

### 🔄 Middleware:
Perlu membuat/menyesuaikan middleware untuk:
- Autentikasi pelanggan
- Autentikasi admin
- Role-based access control

## Catatan Penting:

1. **Primary Key Custom**: Semua model menggunakan custom primary key, pastikan route model binding disesuaikan
2. **Timestamps**: Beberapa tabel tidak menggunakan timestamps (`gambar_produk`, `detail_pesanan`)
3. **Foreign Key**: Semua relasi menggunakan nama kolom Indonesia
4. **Status Values**: 
   - Produk: `aktif` / `nonaktif`
   - Varian: `tersedia` / `habis`
   - Pesanan: `baru` / `diproses` / `dikirim` / `selesai` / `batal`
   - Pembayaran: `unpaid` / `pending` / `paid` / `cancel` / `expire` / `failure`
   - Pengiriman: `menunggu` / `dalam_perjalanan` / `sampai`

## Testing:

Setelah semua perubahan, pastikan untuk:
1. Jalankan migration: `php artisan migrate:fresh`
2. Jalankan seeder jika ada: `php artisan db:seed`
3. Test semua fitur CRUD
4. Test authentication untuk pelanggan dan admin
5. Test checkout dan payment flow
6. Test shipping integration
