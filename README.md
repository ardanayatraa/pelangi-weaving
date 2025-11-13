# 🧵 Pelangi Tradisional Weaving - E-Commerce Kain Songket

Sistem penjualan online untuk kain songket tradisional dengan fitur lengkap untuk customer dan admin.

## 🚀 Quick Start

### 1. Buat Database
Buka **phpMyAdmin** atau **MySQL Command Line**, lalu jalankan:
```sql
CREATE DATABASE pelangi_weaving CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Atau import file `create_database.sql`:
```bash
mysql -u root -p < create_database.sql
```

### 2. Setup Environment
File `.env` sudah ada, pastikan konfigurasi database benar:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pelangi_weaving
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Install Dependencies (jika belum)
```bash
composer install
npm install
```

### 4. Generate Key
```bash
php artisan key:generate
```

### 5. Run Migrations & Seeders
```bash
php artisan migrate
php artisan db:seed
```

### 6. Start Development Server
```bash
php artisan serve
```

Buka browser: `http://localhost:8000`

## 👤 Default Login

### Admin
- Email: `admin@pelangiweaving.com`
- Password: `admin123`

### Customer Demo
- Email: `customer@example.com`
- Password: `customer123`

## 📦 Fitur Utama

### Customer
- ✅ Registrasi & Login
- ✅ Browse produk songket
- ✅ Filter by kategori
- ✅ Lihat detail produk & varian
- ✅ Keranjang belanja
- ✅ Checkout & pembayaran
- ✅ Tracking pesanan
- ✅ Multiple alamat pengiriman

### Admin
- ✅ Dashboard penjualan
- ✅ Kelola kategori
- ✅ Kelola produk & varian
- ✅ Kelola pesanan
- ✅ Laporan penjualan
- ✅ Manajemen user

## 🗄️ Database Schema

### Tables
1. **users** - Data user (admin & customer)
2. **categories** - Kategori songket
3. **products** - Produk songket
4. **product_variants** - Varian (warna, ukuran, benang)
5. **product_images** - Gambar produk
6. **carts** - Shopping cart
7. **orders** - Data pesanan
8. **order_items** - Detail item pesanan
9. **payments** - Data pembayaran (Midtrans)
10. **user_addresses** - Alamat pengiriman

## 🎨 Tech Stack

- **Backend**: Laravel 12
- **Frontend**: Blade + Bootstrap 5
- **Database**: MySQL
- **Authentication**: Laravel Breeze
- **Payment**: Midtrans (ready)
- **Shipping**: RajaOngkir (ready)

## 📝 Sample Data

Setelah seeding, akan ada:
- 2 users (1 admin, 1 customer)
- 5 kategori songket
- 5 produk songket
- ~20 varian produk (kombinasi warna, ukuran, benang)

## 🔧 Development Commands

```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Reset database
php artisan migrate:fresh --seed

# Create new controller
php artisan make:controller ProductController

# Create new model
php artisan make:model Product -m
```

## 📞 Support

Untuk pertanyaan atau bantuan, hubungi tim development.

---

**Pelangi Tradisional Weaving** © 2025
