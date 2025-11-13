# 🚀 QUICK START GUIDE

## Setup Cepat (5 Menit)

### 1. Clone & Install
```bash
composer install
npm install
```

### 2. Environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```env
DB_DATABASE=pelangi_weaving
DB_USERNAME=root
DB_PASSWORD=

MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false

RAJAONGKIR_API_KEY=your_api_key
```

### 3. Database
```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

### 4. Run
```bash
php artisan serve
npm run dev
```

## 🔑 Login

### Admin
- URL: http://localhost:8000/admin/login
- Email: `admin@pelangiweaving.com`
- Password: `admin123`

### Customer
- URL: http://localhost:8000/login
- Email: `budi@gmail.com`
- Password: `customer123`

## 📝 Cheat Sheet

### Nama Kolom Baru
```
name → nama_produk/nama_kategori/nama_varian
price → harga
stock → stok
weight → berat
quantity → jumlah
order_number → nomor_invoice
total_amount → total_bayar
```

### Status Values
```
Produk: aktif / nonaktif
Varian: tersedia / habis
Pesanan: baru / diproses / dikirim / selesai / batal
Pembayaran: unpaid / pending / paid / cancel / expire / failure
Pengiriman: menunggu / dalam_perjalanan / sampai
```

### Guards
```php
Auth::guard('admin')->user()
Auth::guard('pelanggan')->user()
```

### Routes
```
Customer: /
Admin: /admin/dashboard
Login Customer: /login
Login Admin: /admin/login
```

## ⚡ Commands

```bash
# Clear cache
php artisan optimize:clear

# Reset database
php artisan migrate:fresh --seed

# Check routes
php artisan route:list

# Check models
php artisan model:show Product
```

## 🐛 Troubleshooting

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

### Error: Migration
```bash
php artisan migrate:fresh --seed
```
