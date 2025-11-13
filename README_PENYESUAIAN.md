# 🌈 Pelangi Weaving - E-Commerce Kain Tenun Bali

Sistem e-commerce untuk penjualan kain tenun tradisional Bali dengan integrasi Midtrans Payment Gateway dan RajaOngkir Shipping.

---

## ✅ STATUS PENYESUAIAN DATABASE

**SELESAI 100%** - Semua backend telah disesuaikan dengan struktur migration database.

### Yang Sudah Disesuaikan:
- ✅ **Models** (11 models) - 100%
- ✅ **Controllers** (13 controllers) - 100%
- ✅ **Seeders** (6 seeders) - 100%
- ✅ **Authentication** (Guards, Middleware, Auth Controllers) - 100%
- ✅ **Routes** (Admin & Customer routes) - 100%
- ✅ **Services** (Midtrans & RajaOngkir) - 100%
- ✅ **Configuration** (auth.php, app.php) - 100%

### Yang Masih Perlu Disesuaikan:
- ⏳ **Views** (Blade templates) - Perlu update nama kolom

---

## 🏗️ STRUKTUR DATABASE

### Tabel Utama
```
kategori (id_kategori, nama_kategori, slug, deskripsi)
produk (id_produk, id_kategori, nama_produk, slug, deskripsi, harga, stok, berat, status)
varian_produk (id_varian, id_produk, nama_varian, kode_varian, harga, stok, berat, warna, ukuran, jenis_benang, status)
gambar_produk (id_gambar, id_produk, id_varian, path, is_primary)

pelanggan (id_pelanggan, nama, email, password, alamat, telepon, id_kota, id_provinsi, kode_pos)
admin (id_admin, nama, email, password, role, last_login)

keranjang (id_keranjang, id_pelanggan, id_produk, id_varian, jumlah)
pesanan (id_pesanan, id_pelanggan, nomor_invoice, tanggal_pesanan, subtotal, ongkir, total_bayar, status_pesanan, catatan)
detail_pesanan (id_detail, id_pesanan, id_produk, id_varian, jumlah, harga_satuan, subtotal)

pembayaran (id_pembayaran, id_pesanan, midtrans_order_id, snap_token, tipe_pembayaran, status_pembayaran, waktu_transaksi, waktu_settlement, fraud_status)
pengiriman (id_pengiriman, id_pesanan, id_kota_asal, id_kota_tujuan, kurir, layanan, ongkir, estimasi_pengiriman, alamat_pengiriman, no_resi, status_pengiriman, tanggal_kirim, tanggal_terima)
```

---

## 🚀 INSTALASI

### Requirements
- PHP >= 8.2
- Composer
- MySQL/MariaDB
- Node.js & NPM

### Setup

1. **Clone Repository**
```bash
git clone <repository-url>
cd pelangi-weaving
```

2. **Install Dependencies**
```bash
composer install
npm install
```

3. **Environment Configuration**
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```env
APP_NAME="Pelangi Weaving"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pelangi_weaving
DB_USERNAME=root
DB_PASSWORD=

# Midtrans Configuration
MIDTRANS_SERVER_KEY=your_server_key_here
MIDTRANS_CLIENT_KEY=your_client_key_here
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true

# RajaOngkir Configuration
RAJAONGKIR_API_KEY=your_api_key_here
RAJAONGKIR_ORIGIN_SUBDISTRICT_ID=1
```

4. **Database Setup**
```bash
# Create database
mysql -u root -p
CREATE DATABASE pelangi_weaving;
exit;

# Run migrations & seeders
php artisan migrate:fresh --seed
```

5. **Storage Link**
```bash
php artisan storage:link
```

6. **Run Application**
```bash
# Backend
php artisan serve

# Frontend (terminal baru)
npm run dev
```

Akses aplikasi di: http://localhost:8000

---

## 🔑 AKUN DEFAULT

### Admin
```
URL: http://localhost:8000/admin/login
Email: admin@pelangiweaving.com
Password: admin123
```

### Owner
```
URL: http://localhost:8000/admin/login
Email: owner@pelangiweaving.com
Password: owner123
```

### Pelanggan
```
URL: http://localhost:8000/login

Email: budi@gmail.com | Password: customer123
Email: siti@gmail.com | Password: customer123
Email: agung@gmail.com | Password: customer123
Email: dewi@gmail.com | Password: customer123
```

---

## 📁 STRUKTUR PROJECT

```
pelangi-weaving/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin controllers
│   │   │   ├── Auth/           # Authentication controllers
│   │   │   └── Customer/       # Customer controllers
│   │   └── Middleware/         # Custom middleware
│   ├── Models/                 # Eloquent models
│   └── Services/               # Business logic services
├── database/
│   ├── migrations/             # Database migrations
│   └── seeders/                # Database seeders
├── resources/
│   └── views/                  # Blade templates
├── routes/
│   └── web.php                 # Web routes
└── public/
    └── storage/                # Public storage (images)
```

---

## 🎯 FITUR

### Customer
- ✅ Browse produk dengan filter kategori & harga
- ✅ Search produk
- ✅ Lihat detail produk & varian
- ✅ Keranjang belanja
- ✅ Checkout dengan integrasi RajaOngkir
- ✅ Pembayaran via Midtrans
- ✅ History pesanan
- ✅ Cancel pesanan
- ✅ Register & Login

### Admin
- ✅ Dashboard dengan statistik
- ✅ Manajemen Kategori (CRUD)
- ✅ Manajemen Produk (CRUD)
- ✅ Manajemen Varian Produk (CRUD)
- ✅ Upload gambar produk
- ✅ Manajemen Pesanan
- ✅ Update status pesanan
- ✅ Update info pengiriman
- ✅ Print invoice
- ✅ Login terpisah dari customer

---

## 🔐 AUTHENTICATION SYSTEM

### Guards
```php
// Admin guard
Auth::guard('admin')->check()
Auth::guard('admin')->user()

// Pelanggan guard
Auth::guard('pelanggan')->check()
Auth::guard('pelanggan')->user()
```

### Middleware
```php
// Protect admin routes
Route::middleware(['admin'])->group(function () {
    // Admin routes
});

// Protect customer routes
Route::middleware(['pelanggan'])->group(function () {
    // Customer routes
});
```

---

## 🔄 PERUBAHAN DARI STRUKTUR LAMA

### Nama Tabel
```
categories → kategori
products → produk
product_variants → varian_produk
product_images → gambar_produk
users (customer) → pelanggan
users (admin) → admin
carts → keranjang
orders → pesanan
order_items → detail_pesanan
payments → pembayaran
```

### Nama Kolom
```
name → nama_produk / nama_kategori / nama_varian
price → harga
stock → stok
weight → berat
description → deskripsi
order_number → nomor_invoice
total_amount → total_bayar
quantity → jumlah
```

### Primary Keys
```
id → id_kategori / id_produk / id_varian / id_pelanggan / id_admin
```

### Status Values
```
Produk: aktif / nonaktif
Varian: tersedia / habis
Pesanan: baru / diproses / dikirim / selesai / batal
Pembayaran: unpaid / pending / paid / cancel / expire / failure
Pengiriman: menunggu / dalam_perjalanan / sampai
```

---

## 🛠️ DEVELOPMENT

### Useful Commands
```bash
# Clear all cache
php artisan optimize:clear

# Reset database
php artisan migrate:fresh --seed

# Check routes
php artisan route:list

# Check specific model
php artisan model:show Product

# Run tests
php artisan test

# Generate IDE helper
php artisan ide-helper:generate
php artisan ide-helper:models
```

### Code Style
```bash
# Format code
./vendor/bin/pint

# Check code quality
./vendor/bin/phpstan analyse
```

---

## 📚 DOKUMENTASI

### File Dokumentasi
- `MIGRATION_ADJUSTMENT_SUMMARY.md` - Ringkasan perubahan migration
- `STATUS_PENYESUAIAN.md` - Status progress penyesuaian
- `PENYESUAIAN_SELESAI.md` - Dokumentasi lengkap penyesuaian
- `QUICK_START.md` - Panduan cepat memulai
- `README_PENYESUAIAN.md` - File ini

### API Documentation
- Midtrans: https://docs.midtrans.com
- RajaOngkir: https://rajaongkir.com/dokumentasi

---

## 🐛 TROUBLESHOOTING

### Error: Class not found
```bash
composer dump-autoload
```

### Error: Storage link
```bash
php artisan storage:link
```

### Error: Permission denied
```bash
chmod -R 775 storage bootstrap/cache
```

### Error: Migration failed
```bash
php artisan migrate:fresh --seed
```

### Error: Midtrans/RajaOngkir not working
- Pastikan API keys sudah benar di `.env`
- Check log: `storage/logs/laravel.log`

---

## 📝 TODO

### High Priority
- [ ] Update semua Blade views dengan nama kolom baru
- [ ] Testing lengkap semua fitur
- [ ] Setup email notifications

### Medium Priority
- [ ] Add product reviews
- [ ] Add wishlist feature
- [ ] Add product ratings
- [ ] Add admin reports

### Low Priority
- [ ] Add multi-language support
- [ ] Add PWA support
- [ ] Add social media login

---

## 👥 KONTRIBUTOR

- Developer: [Your Name]
- Designer: [Designer Name]

---

## 📄 LICENSE

[Your License Here]

---

## 📞 SUPPORT

Untuk pertanyaan atau bantuan:
- Email: support@pelangiweaving.com
- WhatsApp: +62 xxx-xxxx-xxxx

---

**Last Updated:** 2024
**Version:** 1.0.0
