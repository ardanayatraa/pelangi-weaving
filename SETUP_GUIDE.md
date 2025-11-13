# 📖 Panduan Setup Pelangi Weaving

## Langkah-langkah Setup Project

### 1️⃣ Buat Database MySQL

Ada 2 cara:

#### Cara 1: Menggunakan phpMyAdmin
1. Buka browser, akses `http://localhost/phpmyadmin`
2. Klik tab **"SQL"**
3. Copy paste kode ini:
```sql
CREATE DATABASE pelangi_weaving CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```
4. Klik **"Go"** atau **"Kirim"**

#### Cara 2: Menggunakan Command Line
```bash
# Masuk ke MySQL
mysql -u root -p

# Buat database
CREATE DATABASE pelangi_weaving CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Keluar
exit;
```

#### Cara 3: Import File SQL
```bash
cd pelangi-weaving
mysql -u root -p < create_database.sql
```

### 2️⃣ Cek File .env

Pastikan file `.env` sudah ada dan konfigurasi database benar:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pelangi_weaving
DB_USERNAME=root
DB_PASSWORD=
```

**Catatan**: 
- Jika MySQL kamu pakai password, isi `DB_PASSWORD=password_kamu`
- Jika pakai XAMPP/WAMP biasanya password kosong

### 3️⃣ Install Dependencies (Jika Belum)

```bash
# Masuk ke folder project
cd pelangi-weaving

# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 4️⃣ Generate Application Key

```bash
php artisan key:generate
```

### 5️⃣ Jalankan Migrations

Ini akan membuat semua tabel di database:

```bash
php artisan migrate
```

Kamu akan lihat output seperti ini:
```
Migration table created successfully.
Migrating: 0001_01_01_000000_create_users_table
Migrated:  0001_01_01_000000_create_users_table (50.23ms)
Migrating: 2025_11_06_043652_create_categories_table
Migrated:  2025_11_06_043652_create_categories_table (30.45ms)
...
```

### 6️⃣ Jalankan Seeders

Ini akan mengisi database dengan data sample:

```bash
php artisan db:seed
```

Output:
```
Seeding: Database\Seeders\AdminSeeder
Seeded:  Database\Seeders\AdminSeeder (100.23ms)
Seeding: Database\Seeders\CategorySeeder
Seeded:  Database\Seeders\CategorySeeder (50.45ms)
...
```

### 7️⃣ Start Development Server

```bash
php artisan serve
```

Output:
```
INFO  Server running on [http://127.0.0.1:8000].

Press Ctrl+C to stop the server
```

### 8️⃣ Buka di Browser

Buka browser dan akses: `http://localhost:8000`

## 🔐 Login Credentials

### Admin
- **Email**: admin@pelangiweaving.com
- **Password**: admin123
- **URL**: http://localhost:8000/admin

### Customer Demo
- **Email**: customer@example.com
- **Password**: customer123
- **URL**: http://localhost:8000

## ✅ Verifikasi Setup Berhasil

Setelah seeding, cek di phpMyAdmin atau MySQL:

```sql
USE pelangi_weaving;

-- Cek jumlah data
SELECT COUNT(*) FROM users;        -- Harus ada 2 users
SELECT COUNT(*) FROM categories;   -- Harus ada 5 categories
SELECT COUNT(*) FROM products;     -- Harus ada 5 products
SELECT COUNT(*) FROM product_variants; -- Harus ada ~20 variants
```

## 🔧 Troubleshooting

### Error: "Unknown database 'pelangi_weaving'"
**Solusi**: Database belum dibuat. Ulangi langkah 1.

### Error: "Access denied for user 'root'@'localhost'"
**Solusi**: 
1. Cek username dan password MySQL di file `.env`
2. Pastikan MySQL service sudah running (XAMPP/WAMP)

### Error: "Class 'App\Models\Category' not found"
**Solusi**: 
```bash
composer dump-autoload
```

### Error: Migration sudah jalan tapi mau reset
**Solusi**: 
```bash
# Reset semua dan jalankan ulang
php artisan migrate:fresh --seed
```

### Port 8000 sudah dipakai
**Solusi**: 
```bash
# Gunakan port lain
php artisan serve --port=8001
```

## 📝 Perintah Berguna

```bash
# Lihat status migrations
php artisan migrate:status

# Rollback migration terakhir
php artisan migrate:rollback

# Reset database dan seed ulang
php artisan migrate:fresh --seed

# Clear semua cache
php artisan optimize:clear

# Lihat semua routes
php artisan route:list

# Buat controller baru
php artisan make:controller NamaController

# Buat model baru dengan migration
php artisan make:model NamaModel -m
```

## 🎯 Next Steps

Setelah setup berhasil, kamu bisa:

1. **Explore Admin Panel**
   - Login sebagai admin
   - Kelola kategori, produk, varian
   - Lihat dashboard

2. **Test Customer Flow**
   - Register akun baru atau login sebagai customer demo
   - Browse produk
   - Tambah ke keranjang
   - Checkout (payment gateway belum aktif)

3. **Development**
   - Mulai buat controllers
   - Buat views dengan Blade
   - Implementasi fitur-fitur

## 📞 Butuh Bantuan?

Jika ada error atau pertanyaan, catat:
1. Error message lengkap
2. Langkah yang sedang dilakukan
3. Screenshot jika perlu

---

**Happy Coding!** 🚀
