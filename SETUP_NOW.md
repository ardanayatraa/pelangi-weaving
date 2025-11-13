# 🚀 Setup Database SEKARANG!

## Error yang Kamu Alami:
```
Column not found: 1054 Unknown column 'sort_order' in 'order clause'
```

**Penyebab**: Database belum di-setup! Migrations belum dijalankan.

---

## ✅ Langkah Setup (5 Menit)

### Step 1: Buat Database (1 menit)

**Pilih salah satu cara:**

#### Cara A: Via phpMyAdmin (Paling Mudah)
1. Buka browser: `http://localhost/phpmyadmin`
2. Klik tab **"SQL"**
3. Copy paste ini:
```sql
CREATE DATABASE pelangi_weaving CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```
4. Klik **"Go"** atau **"Kirim"**
5. ✅ Done!

#### Cara B: Via MySQL Command Line
```bash
mysql -u root -p
CREATE DATABASE pelangi_weaving CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit;
```

#### Cara C: Import File SQL
```bash
cd pelangi-weaving
mysql -u root -p < create_database.sql
```

---

### Step 2: Cek File .env (30 detik)

Buka file `pelangi-weaving/.env`, pastikan ini benar:

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

---

### Step 3: Run Migrations (1 menit)

Buka terminal/command prompt di folder `pelangi-weaving`:

```bash
cd pelangi-weaving
php artisan migrate
```

**Expected Output**:
```
Migration table created successfully.
Migrating: 0001_01_01_000000_create_users_table
Migrated:  0001_01_01_000000_create_users_table (50ms)
Migrating: 2025_11_06_043652_create_categories_table
Migrated:  2025_11_06_043652_create_categories_table (30ms)
...
Migrating: 2025_11_07_170822_add_sort_order_to_products_table
Migrated:  2025_11_07_170822_add_sort_order_to_products_table (20ms)
...
```

✅ Ini akan membuat **11 tabel** dan **semua kolom** termasuk `sort_order`!

---

### Step 4: Run Seeders (1 menit)

Masih di terminal yang sama:

```bash
php artisan db:seed
```

**Expected Output**:
```
Seeding: Database\Seeders\AdminSeeder
Seeded:  Database\Seeders\AdminSeeder (100ms)
Seeding: Database\Seeders\CategorySeeder
Seeded:  Database\Seeders\CategorySeeder (50ms)
Seeding: Database\Seeders\ProductSeeder
Seeded:  Database\Seeders\ProductSeeder (80ms)
Seeding: Database\Seeders\ProductVariantSeeder
Seeded:  Database\Seeders\ProductVariantSeeder (120ms)
```

✅ Ini akan mengisi database dengan:
- 2 users (admin & customer)
- 5 categories
- 5 products
- ~20 product variants

---

### Step 5: Refresh Browser (5 detik)

1. Kembali ke browser
2. Refresh halaman: `http://127.0.0.1:8000`
3. ✅ **Error hilang!** Website jalan!

---

## 🎯 Verifikasi Setup Berhasil

### Cek di phpMyAdmin:
1. Buka `http://localhost/phpmyadmin`
2. Pilih database `pelangi_weaving`
3. Lihat tabel-tabel:
   - ✅ users (2 rows)
   - ✅ categories (5 rows)
   - ✅ products (5 rows)
   - ✅ product_variants (~20 rows)
   - ✅ Dan 7 tabel lainnya

### Cek di Website:
1. Homepage: `http://127.0.0.1:8000`
   - ✅ Lihat categories
   - ✅ Lihat featured products
   - ✅ Lihat latest products

2. Login Admin: `http://127.0.0.1:8000/login`
   - Email: `admin@pelangiweaving.com`
   - Password: `admin123`
   - ✅ Masuk ke dashboard admin

3. Login Customer: `http://127.0.0.1:8000/login`
   - Email: `customer@example.com`
   - Password: `customer123`
   - ✅ Masuk ke homepage

---

## 🔧 Troubleshooting

### Error: "Access denied for user 'root'@'localhost'"
**Solusi**: 
1. Cek username & password MySQL di `.env`
2. Pastikan MySQL service running (XAMPP/WAMP)

### Error: "SQLSTATE[HY000] [2002] No connection"
**Solusi**:
1. Start MySQL service (XAMPP/WAMP)
2. Cek `DB_HOST` di `.env` (harus `127.0.0.1`)

### Error: "Base table or view already exists"
**Solusi**: Database sudah ada, reset dengan:
```bash
php artisan migrate:fresh --seed
```
⚠️ **Warning**: Ini akan **hapus semua data** dan buat ulang!

### Error: "Class 'Pengiriman' not found"
**Solusi**:
```bash
composer dump-autoload
```

---

## 📋 Quick Commands

```bash
# Cek status migrations
php artisan migrate:status

# Reset database (hapus semua data)
php artisan migrate:fresh --seed

# Clear cache
php artisan optimize:clear

# Test database connection
php artisan tinker
>>> User::count()
>>> exit
```

---

## ✅ Checklist

Setelah setup, pastikan ini semua ✅:

- [ ] Database `pelangi_weaving` sudah dibuat
- [ ] File `.env` sudah benar
- [ ] `php artisan migrate` berhasil (17 migrations)
- [ ] `php artisan db:seed` berhasil (4 seeders)
- [ ] Homepage bisa dibuka tanpa error
- [ ] Login admin berhasil
- [ ] Login customer berhasil
- [ ] Produk muncul di homepage

---

## 🎊 Setelah Setup Berhasil

Kamu bisa:
1. ✅ Browse products
2. ✅ Login sebagai admin/customer
3. ✅ Akses admin dashboard
4. ✅ Lihat categories & products
5. ✅ Test semua fitur yang sudah dibuat

---

## 📞 Masih Error?

Jika masih ada error setelah ikuti semua langkah:

1. **Screenshot error message**
2. **Cek file `.env`**
3. **Cek MySQL service running**
4. **Run**: `php artisan migrate:status`
5. **Share error message** untuk troubleshooting

---

**PENTING**: Jangan lupa **Step 1-4** harus dijalankan **berurutan**!

**Estimated Time**: 5 menit total

**Let's go!** 🚀
