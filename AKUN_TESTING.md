# 🔐 Akun Testing - Pelangi Weaving

## 📋 Daftar Akun

### 👨‍💼 ADMIN
```
Email    : admin@pelangi.com
Password : admin123
URL      : http://localhost:8000/admin/dashboard
```

**Akses:**
- Dashboard admin
- Kelola kategori
- Kelola produk & varian
- Kelola pesanan
- Lihat laporan

---

### 👤 CUSTOMER 1
```
Nama     : Budi Santoso
Email    : budi@gmail.com
Password : customer123
Alamat   : Jl. Sunset Road No. 123, Kuta, Badung, Bali
```

### 👤 CUSTOMER 2
```
Nama     : Siti Nurhaliza
Email    : siti@gmail.com
Password : customer123
Alamat   : Jl. Teuku Umar No. 45, Denpasar, Bali
```

### 👤 CUSTOMER 3
```
Nama     : Agung Wijaya
Email    : agung@gmail.com
Password : customer123
Alamat   : Jl. Raya Ubud No. 88, Gianyar, Bali
```

**Akses Customer:**
- Browse produk
- Tambah ke keranjang
- Checkout & pembayaran
- Lihat pesanan
- Update profil

---

## 🚀 Cara Menjalankan Seeder

### 1. Reset Database & Seed Semua Data
```bash
php artisan migrate:fresh --seed
```

### 2. Seed User Saja (Jika sudah ada data lain)
```bash
php artisan db:seed --class=UserSeeder
```

---

## 🧪 Testing Flow

### Flow Customer:
1. Login sebagai customer (budi@gmail.com)
2. Browse produk di homepage
3. Klik produk → Pilih varian → Tambah ke keranjang
4. Atau klik "Beli Sekarang" untuk langsung checkout
5. Isi form checkout → Pilih alamat pengiriman
6. Pilih kurir → Klik "Buat Pesanan"
7. Popup Midtrans muncul → Pilih metode pembayaran
8. Selesai → Lihat detail pesanan

### Flow Admin:
1. Login sebagai admin (admin@pelangi.com)
2. Akses dashboard admin
3. Kelola kategori, produk, varian
4. Lihat & update status pesanan
5. Lihat laporan penjualan

---

## 📝 Notes

- Semua password customer sama: `customer123`
- Password admin: `admin123`
- Email sudah verified semua
- Role sudah di-set (admin/customer)
- Alamat sudah diisi untuk testing checkout

---

## 🔄 Reset Password (Jika Lupa)

```bash
php artisan tinker
```

```php
// Reset password admin
$user = User::where('email', 'admin@pelangi.com')->first();
$user->password = Hash::make('admin123');
$user->save();

// Reset password customer
$user = User::where('email', 'budi@gmail.com')->first();
$user->password = Hash::make('customer123');
$user->save();
```

---

**Happy Testing! 🎉**
