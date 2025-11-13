# 🔑 API Setup Guide - Pelangi Weaving

## 📋 Daftar API yang Dibutuhkan

### 1. Midtrans Payment Gateway
### 2. RajaOngkir Shipping Calculator

---

## 💳 1. MIDTRANS SETUP

### Step 1: Daftar Akun Midtrans Sandbox
1. Buka https://dashboard.sandbox.midtrans.com/register
2. Isi form registrasi:
   - Email
   - Password
   - Nama Bisnis
3. Verifikasi email
4. Login ke dashboard

### Step 2: Ambil API Keys
1. Login ke https://dashboard.sandbox.midtrans.com/
2. Klik **Settings** di menu kiri
3. Pilih **Access Keys**
4. Copy credentials berikut:
   - **Merchant ID**: G123456789 (contoh)
   - **Client Key**: SB-Mid-client-xxxxxxxxxxxxxxxx
   - **Server Key**: SB-Mid-server-xxxxxxxxxxxxxxxx

### Step 3: Update .env
Buka file `pelangi-weaving/.env` dan update:

```env
# Midtrans Configuration
MIDTRANS_MERCHANT_ID=G123456789
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxxxxxxxxxx
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxxxxxxxxxx
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

### Step 4: Test Payment
Gunakan test card berikut:
```
Card Number: 4811 1111 1111 1114
CVV: 123
Exp Date: 01/25
OTP: 112233
```

---

## 🚚 2. RAJAONGKIR SETUP

### Step 1: Daftar Akun RajaOngkir
1. Buka https://rajaongkir.com/
2. Klik **Daftar** atau **Sign Up**
3. Isi form registrasi:
   - Nama
   - Email
   - Password
   - Nomor HP
4. Verifikasi email

### Step 2: Pilih Paket
1. Login ke https://rajaongkir.com/
2. Pilih paket **Starter** (GRATIS)
   - 1000 request/bulan
   - Akses ke JNE, TIKI, POS
   - Cukup untuk testing dan small business

### Step 3: Ambil API Key
1. Setelah login, masuk ke **Dashboard**
2. Klik menu **API Key**
3. Copy API Key Anda (format: xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx)

### Step 4: Cari Origin Subdistrict ID
Origin adalah lokasi toko/gudang Anda. Contoh untuk Jakarta:

1. Buka Postman atau browser
2. Test API untuk cari subdistrict:
```bash
curl --location 'https://rajaongkir.komerce.id/api/v1/destination/domestic-destination?search=jakarta%20pusat&limit=5&offset=0' \
--header 'key: YOUR_API_KEY'
```

3. Dari hasil, ambil `subdistrict_id` yang sesuai lokasi toko Anda
   - Contoh: Jakarta Pusat = 574
   - Bandung = 23
   - Surabaya = 444

### Step 5: Update .env
Buka file `pelangi-weaving/.env` dan update:

```env
# RajaOngkir Configuration
RAJAONGKIR_API_KEY=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
RAJAONGKIR_BASE_URL=https://rajaongkir.komerce.id/api/v1
RAJAONGKIR_ORIGIN_SUBDISTRICT_ID=574
```

**Ganti:**
- `xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx` dengan API Key Anda
- `574` dengan Subdistrict ID lokasi toko Anda

### Step 6: Test API
Test di browser atau Postman:

```bash
# Test search destination
curl --location 'https://rajaongkir.komerce.id/api/v1/destination/domestic-destination?search=bandung&limit=5&offset=0' \
--header 'key: YOUR_API_KEY'

# Test calculate cost
curl --location 'https://rajaongkir.komerce.id/api/v1/cost' \
--header 'key: YOUR_API_KEY' \
--header 'Content-Type: application/json' \
--data '{
    "origin_subdistrict_id": 574,
    "destination_subdistrict_id": 6234,
    "weight": 1000,
    "courier": "jne"
}'
```

---

## ✅ VERIFIKASI SETUP

### 1. Check .env File
Pastikan file `.env` sudah terisi lengkap:

```env
# Midtrans
MIDTRANS_MERCHANT_ID=G123456789
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxx
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxx

# RajaOngkir
RAJAONGKIR_API_KEY=xxxxxxxxxxxxxxxx
RAJAONGKIR_ORIGIN_SUBDISTRICT_ID=574
```

### 2. Clear Config Cache
```bash
php artisan config:clear
php artisan cache:clear
```

### 3. Test di Aplikasi
1. Jalankan aplikasi: `php artisan serve`
2. Buka http://localhost:8000
3. Login sebagai customer
4. Tambah produk ke cart
5. Checkout
6. **Test Search Wilayah**: Ketik "Jakarta" atau "Bandung"
7. Pilih wilayah dari dropdown
8. Lihat ongkir otomatis terhitung
9. Lanjut ke pembayaran
10. Test payment dengan Midtrans

---

## 🎯 FITUR YANG SUDAH TERINTEGRASI

### RajaOngkir Features:
✅ **Autocomplete Search** - Ketik nama kota/kecamatan
✅ **Real-time Calculation** - Ongkir otomatis terhitung
✅ **Multiple Couriers** - JNE, TIKI, POS, J&T, SiCepat, AnterAja
✅ **Subdistrict Precision** - Akurat sampai kelurahan
✅ **Caching** - Response di-cache untuk performa

### Midtrans Features:
✅ **Snap Payment** - Modern payment UI
✅ **Multiple Methods** - Credit card, bank transfer, e-wallet
✅ **Auto Status Update** - Order status otomatis update
✅ **Webhook Handler** - Ready untuk production

---

## 🔧 TROUBLESHOOTING

### Error: "Unauthorized" atau "Invalid API Key"
- Pastikan API key sudah benar di `.env`
- Jalankan `php artisan config:clear`
- Restart Laravel server

### Error: "Origin subdistrict not found"
- Pastikan `RAJAONGKIR_ORIGIN_SUBDISTRICT_ID` sudah diisi
- Cek ID dengan API search destination

### Ongkir tidak muncul
- Cek API key RajaOngkir valid
- Cek koneksi internet
- Lihat log error di `storage/logs/laravel.log`

### Payment tidak muncul
- Cek Midtrans credentials di `.env`
- Pastikan menggunakan Sandbox keys untuk testing
- Clear browser cache

---

## 📞 SUPPORT

### Midtrans
- Docs: https://docs.midtrans.com
- Support: support@midtrans.com
- Dashboard: https://dashboard.sandbox.midtrans.com

### RajaOngkir
- Docs: https://rajaongkir.com/dokumentasi
- Support: Via dashboard
- Dashboard: https://rajaongkir.com/dashboard

---

## 🚀 PRODUCTION DEPLOYMENT

Untuk production, ganti dengan production keys:

### Midtrans Production
1. Daftar di https://dashboard.midtrans.com (bukan sandbox)
2. Lengkapi verifikasi bisnis
3. Ambil production keys
4. Update `.env`:
```env
MIDTRANS_IS_PRODUCTION=true
MIDTRANS_CLIENT_KEY=Mid-client-xxxxxxxx
MIDTRANS_SERVER_KEY=Mid-server-xxxxxxxx
```

### RajaOngkir Production
1. Upgrade paket jika perlu (Basic/Pro)
2. API key tetap sama
3. Pastikan origin subdistrict sudah benar

---

**Setup Complete! 🎉**

Aplikasi siap digunakan dengan payment gateway dan shipping calculator yang terintegrasi!
