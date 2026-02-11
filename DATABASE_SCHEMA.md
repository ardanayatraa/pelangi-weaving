# Database Schema - Pelangi Weaving

Dokumentasi lengkap struktur database sistem Pelangi Weaving E-Commerce.

---

## 📋 Daftar Tabel

1. [jenis](#1-jenis)
2. [kategori](#2-kategori)
3. [produk](#3-produk)
4. [varian_produk](#4-varian_produk)
5. [admin](#5-admin)
6. [pelanggan](#6-pelanggan)
7. [keranjang](#7-keranjang)
8. [custom_orders](#8-custom_orders)
9. [pesanan](#9-pesanan)
10. [detail_pesanan](#10-detail_pesanan)
11. [pembayaran](#11-pembayaran)
12. [pengiriman](#12-pengiriman)
13. [cache](#13-cache)
14. [jobs](#14-jobs)

---

## 1. jenis

Tabel untuk menyimpan jenis/kategori produk custom (contoh: Songket, Endek, Gringsing, dll).

| Kolom | Tipe | Nullable | Default | Keterangan |
|-------|------|----------|---------|------------|
| `id_jenis` | BIGINT UNSIGNED | NO | AUTO_INCREMENT | Primary Key |
| `nama_jenis` | VARCHAR(100) | NO | - | Nama jenis produk |
| `slug` | VARCHAR(100) | NO | - | URL-friendly identifier (UNIQUE) |
| `deskripsi` | TEXT | YES | NULL | Deskripsi jenis |
| `icon` | VARCHAR(255) | YES | NULL | Path icon/gambar |
| `status` | VARCHAR(20) | NO | 'active' | Status: active/inactive |
| `created_at` | TIMESTAMP | YES | NULL | Waktu dibuat |
| `updated_at` | TIMESTAMP | YES | NULL | Waktu diupdate |

**Indexes:**
- PRIMARY KEY: `id_jenis`
- UNIQUE: `slug`

**Relasi:**
- Has Many → `produk` (id_jenis)
- Has Many → `custom_orders` (id_jenis)

---

## 2. kategori

Tabel untuk menyimpan kategori produk reguler.

| Kolom | Tipe | Nullable | Default | Keterangan |
|-------|------|----------|---------|------------|
| `id_kategori` | BIGINT UNSIGNED | NO | AUTO_INCREMENT | Primary Key |
| `nama_kategori` | VARCHAR(100) | NO | - | Nama kategori |
| `slug` | VARCHAR(100) | NO | - | URL-friendly identifier (UNIQUE) |
| `deskripsi` | TEXT | YES | NULL | Deskripsi kategori |
| `created_at` | TIMESTAMP | YES | NULL | Waktu dibuat |
| `updated_at` | TIMESTAMP | YES | NULL | Waktu diupdate |

**Indexes:**
- PRIMARY KEY: `id_kategori`
- UNIQUE: `slug`

**Relasi:**
- Has Many → `produk` (id_kategori)
- Has Many → `keranjang` (id_kategori)

---

## 3. produk

Tabel untuk menyimpan data produk.

| Kolom | Tipe | Nullable | Default | Keterangan |
|-------|------|----------|---------|------------|
| `id_produk` | BIGINT UNSIGNED | NO | AUTO_INCREMENT | Primary Key |
| `id_kategori` | BIGINT UNSIGNED | NO | - | Foreign Key ke kategori |
| `id_jenis` | BIGINT UNSIGNED | YES | NULL | Foreign Key ke jenis |
| `nama_produk` | VARCHAR(150) | NO | - | Nama produk |
| `slug` | VARCHAR(150) | NO | - | URL-friendly identifier (UNIQUE) |
| `deskripsi` | TEXT | YES | NULL | Deskripsi produk |
| `berat` | DECIMAL(6,2) | NO | 0 | Berat dalam gram |
| `status` | VARCHAR(20) | NO | 'aktif' | Status: aktif/nonaktif |
| `is_made_to_order` | BOOLEAN | NO | FALSE | Apakah produk made to order |
| `views` | INTEGER | NO | 0 | Jumlah views/kunjungan |
| `created_at` | TIMESTAMP | YES | NULL | Waktu dibuat |
| `updated_at` | TIMESTAMP | YES | NULL | Waktu diupdate |

**Indexes:**
- PRIMARY KEY: `id_produk`
- UNIQUE: `slug`
- INDEX: `id_kategori`, `id_jenis`, `status`, `slug`

**Foreign Keys:**
- `id_kategori` → `kategori(id_kategori)` ON DELETE RESTRICT
- `id_jenis` → `jenis(id_jenis)` ON DELETE SET NULL

**Relasi:**
- Belongs To → `kategori` (id_kategori)
- Belongs To → `jenis` (id_jenis)
- Has Many → `varian_produk` (id_produk)
- Has Many → `keranjang` (id_produk)
- Has Many → `detail_pesanan` (id_produk)

---

## 4. varian_produk

Tabel untuk menyimpan varian produk (warna, ukuran, dll).

| Kolom | Tipe | Nullable | Default | Keterangan |
|-------|------|----------|---------|------------|
| `id_varian` | BIGINT UNSIGNED | NO | AUTO_INCREMENT | Primary Key |
| `id_produk` | BIGINT UNSIGNED | NO | - | Foreign Key ke produk |
| `nama_varian` | VARCHAR(100) | NO | - | Nama varian |
| `kode_varian` | VARCHAR(50) | NO | - | Kode unik varian (UNIQUE) |
| `gambar_varian` | VARCHAR(255) | YES | NULL | Path gambar varian |
| `harga` | DECIMAL(12,2) | NO | - | Harga varian |
| `stok` | INTEGER | NO | 0 | Stok tersedia |
| `berat` | DECIMAL(6,2) | YES | NULL | Berat dalam gram |
| `warna` | VARCHAR(50) | YES | NULL | Warna varian |
| `ukuran` | VARCHAR(50) | YES | NULL | Ukuran varian |
| `jenis_benang` | VARCHAR(50) | YES | NULL | Jenis benang |
| `status` | VARCHAR(20) | NO | 'tersedia' | Status: tersedia/habis |
| `created_at` | TIMESTAMP | YES | NULL | Waktu dibuat |
| `updated_at` | TIMESTAMP | YES | NULL | Waktu diupdate |

**Indexes:**
- PRIMARY KEY: `id_varian`
- UNIQUE: `kode_varian`
- INDEX: `id_produk`, `status`, `kode_varian`

**Foreign Keys:**
- `id_produk` → `produk(id_produk)` ON DELETE CASCADE

**Relasi:**
- Belongs To → `produk` (id_produk)
- Has Many → `keranjang` (id_varian)
- Has Many → `detail_pesanan` (id_varian)

---

## 5. admin

Tabel untuk menyimpan data administrator.

| Kolom | Tipe | Nullable | Default | Keterangan |
|-------|------|----------|---------|------------|
| `id_admin` | BIGINT UNSIGNED | NO | AUTO_INCREMENT | Primary Key |
| `nama` | VARCHAR(100) | NO | - | Nama admin |
| `email` | VARCHAR(100) | NO | - | Email (UNIQUE) |
| `password` | VARCHAR(255) | NO | - | Password (hashed) |
| `role` | VARCHAR(20) | NO | 'admin' | Role admin |
| `last_login` | DATETIME | YES | NULL | Waktu login terakhir |
| `created_at` | TIMESTAMP | YES | NULL | Waktu dibuat |
| `updated_at` | TIMESTAMP | YES | NULL | Waktu diupdate |

**Indexes:**
- PRIMARY KEY: `id_admin`
- UNIQUE: `email`

**Relasi:**
- Has Many → `custom_orders` (updated_by)

---

## 6. pelanggan

Tabel untuk menyimpan data pelanggan/customer.

| Kolom | Tipe | Nullable | Default | Keterangan |
|-------|------|----------|---------|------------|
| `id_pelanggan` | BIGINT UNSIGNED | NO | AUTO_INCREMENT | Primary Key |
| `nama` | VARCHAR(100) | NO | - | Nama pelanggan |
| `email` | VARCHAR(100) | NO | - | Email (UNIQUE) |
| `password` | VARCHAR(255) | NO | - | Password (hashed) |
| `alamat` | JSON | YES | NULL | Multiple alamat dalam format JSON |
| `alamat_default_index` | INTEGER | NO | 0 | Index alamat default |
| `telepon` | VARCHAR(20) | YES | NULL | Nomor telepon |
| `whatsapp` | VARCHAR(20) | YES | NULL | Nomor WhatsApp |
| `id_kota` | INTEGER | YES | NULL | ID kota (RajaOngkir) |
| `id_provinsi` | INTEGER | YES | NULL | ID provinsi (RajaOngkir) |
| `kode_pos` | VARCHAR(10) | YES | NULL | Kode pos |
| `remember_token` | VARCHAR(100) | YES | NULL | Remember token |
| `created_at` | TIMESTAMP | YES | NULL | Waktu dibuat |
| `updated_at` | TIMESTAMP | YES | NULL | Waktu diupdate |

**Indexes:**
- PRIMARY KEY: `id_pelanggan`
- UNIQUE: `email`

**Format JSON Alamat:**
```json
[
  {
    "label": "rumah",
    "nama_penerima": "John Doe",
    "telepon": "081234567890",
    "alamat_lengkap": "Jl. Contoh No. 123",
    "kota": "Denpasar",
    "provinsi": "Bali",
    "kode_pos": "80361"
  }
]
```

**Relasi:**
- Has Many → `keranjang` (id_pelanggan)
- Has Many → `pesanan` (id_pelanggan)
- Has Many → `custom_orders` (id_pelanggan)

---

## 7. keranjang

Tabel untuk menyimpan item keranjang belanja.

| Kolom | Tipe | Nullable | Default | Keterangan |
|-------|------|----------|---------|------------|
| `id_keranjang` | BIGINT UNSIGNED | NO | AUTO_INCREMENT | Primary Key |
| `id_pelanggan` | BIGINT UNSIGNED | NO | - | Foreign Key ke pelanggan |
| `id_produk` | BIGINT UNSIGNED | NO | - | Foreign Key ke produk |
| `id_varian` | BIGINT UNSIGNED | YES | NULL | Foreign Key ke varian_produk |
| `jumlah` | INTEGER | NO | 1 | Jumlah item |
| `created_at` | TIMESTAMP | YES | NULL | Waktu dibuat |
| `updated_at` | TIMESTAMP | YES | NULL | Waktu diupdate |

**Indexes:**
- PRIMARY KEY: `id_keranjang`

**Foreign Keys:**
- `id_pelanggan` → `pelanggan(id_pelanggan)` ON DELETE CASCADE
- `id_produk` → `produk(id_produk)` ON DELETE CASCADE
- `id_varian` → `varian_produk(id_varian)` ON DELETE CASCADE

**Relasi:**
- Belongs To → `pelanggan` (id_pelanggan)
- Belongs To → `produk` (id_produk)
- Belongs To → `varian_produk` (id_varian)

---

## 8. custom_orders

Tabel untuk menyimpan pesanan custom/made to order.

| Kolom | Tipe | Nullable | Default | Keterangan |
|-------|------|----------|---------|------------|
| `id_custom_order` | BIGINT UNSIGNED | NO | AUTO_INCREMENT | Primary Key |
| `id_pelanggan` | BIGINT UNSIGNED | NO | - | Foreign Key ke pelanggan |
| `id_jenis` | BIGINT UNSIGNED | NO | - | Foreign Key ke jenis |
| `nomor_custom_order` | VARCHAR(50) | NO | - | Nomor order (UNIQUE) |
| `nama_custom` | VARCHAR(200) | NO | - | Nama produk custom |
| `deskripsi_custom` | TEXT | NO | - | Deskripsi detail custom |
| `jumlah` | INTEGER | NO | 1 | Jumlah pesanan |
| `harga_final` | DECIMAL(12,2) | NO | 0 | Harga final |
| `dp_amount` | DECIMAL(12,2) | NO | 0 | Jumlah DP (Down Payment) |
| `status` | VARCHAR(30) | NO | 'draft' | Status order |
| `catatan_pelanggan` | TEXT | YES | NULL | Catatan dari pelanggan |
| `gambar_referensi` | JSON | YES | NULL | Array path gambar referensi |
| `midtrans_order_id` | VARCHAR(100) | YES | NULL | Order ID Midtrans |
| `dp_paid_at` | DATETIME | YES | NULL | Waktu DP dibayar |
| `fully_paid_at` | DATETIME | YES | NULL | Waktu pelunasan |
| `payment_response` | TEXT | YES | NULL | Response payment gateway |
| `updated_by` | BIGINT UNSIGNED | YES | NULL | Foreign Key ke admin |
| `progress_history` | JSON | YES | NULL | History progress order |
| `created_at` | TIMESTAMP | YES | NULL | Waktu dibuat |
| `updated_at` | TIMESTAMP | YES | NULL | Waktu diupdate |

**Indexes:**
- PRIMARY KEY: `id_custom_order`
- UNIQUE: `nomor_custom_order`

**Foreign Keys:**
- `id_pelanggan` → `pelanggan(id_pelanggan)` ON DELETE RESTRICT
- `id_jenis` → `jenis(id_jenis)` ON DELETE RESTRICT
- `updated_by` → `admin(id_admin)` ON DELETE SET NULL

**Status Values:**
- `draft` - Belum submit
- `menunggu_dp` - Menunggu pembayaran DP
- `dp_dibayar` - DP sudah dibayar
- `dalam_produksi` - Sedang diproduksi
- `menunggu_pelunasan` - Menunggu pelunasan
- `selesai` - Selesai
- `dibatalkan` - Dibatalkan

**Relasi:**
- Belongs To → `pelanggan` (id_pelanggan)
- Belongs To → `jenis` (id_jenis)
- Belongs To → `admin` (updated_by)
- Has One → `pembayaran` (id_custom_order)
- Has Many → `pesanan` (id_custom_order)

---

## 9. pesanan

Tabel untuk menyimpan data pesanan/order.

| Kolom | Tipe | Nullable | Default | Keterangan |
|-------|------|----------|---------|------------|
| `id_pesanan` | BIGINT UNSIGNED | NO | AUTO_INCREMENT | Primary Key |
| `id_pelanggan` | BIGINT UNSIGNED | NO | - | Foreign Key ke pelanggan |
| `id_custom_order` | BIGINT UNSIGNED | YES | NULL | Foreign Key ke custom_orders |
| `nomor_invoice` | VARCHAR(50) | NO | - | Nomor invoice (UNIQUE) |
| `tanggal_pesanan` | DATETIME | NO | - | Tanggal pesanan dibuat |
| `subtotal` | DECIMAL(12,2) | NO | - | Subtotal harga produk |
| `ongkir` | DECIMAL(12,2) | NO | 0 | Ongkos kirim |
| `total_bayar` | DECIMAL(12,2) | NO | - | Total yang harus dibayar |
| `status_pesanan` | VARCHAR(20) | NO | 'baru' | Status pesanan |
| `catatan` | TEXT | YES | NULL | Catatan pesanan |
| `created_at` | TIMESTAMP | YES | NULL | Waktu dibuat |
| `updated_at` | TIMESTAMP | YES | NULL | Waktu diupdate |

**Indexes:**
- PRIMARY KEY: `id_pesanan`
- UNIQUE: `nomor_invoice`
- INDEX: `id_pelanggan`, `id_custom_order`, `status_pesanan`, `tanggal_pesanan`, `nomor_invoice`

**Foreign Keys:**
- `id_pelanggan` → `pelanggan(id_pelanggan)` ON DELETE RESTRICT
- `id_custom_order` → `custom_orders(id_custom_order)` ON DELETE SET NULL

**Status Values:**
- `baru` - Pesanan baru
- `diproses` - Sedang diproses
- `dikirim` - Sedang dikirim
- `selesai` - Selesai
- `batal` - Dibatalkan

**Relasi:**
- Belongs To → `pelanggan` (id_pelanggan)
- Belongs To → `custom_orders` (id_custom_order)
- Has Many → `detail_pesanan` (id_pesanan)
- Has One → `pembayaran` (id_pesanan)
- Has One → `pengiriman` (id_pesanan)

---

## 10. detail_pesanan

Tabel untuk menyimpan detail item pesanan.

| Kolom | Tipe | Nullable | Default | Keterangan |
|-------|------|----------|---------|------------|
| `id_detail` | BIGINT UNSIGNED | NO | AUTO_INCREMENT | Primary Key |
| `id_pesanan` | BIGINT UNSIGNED | NO | - | Foreign Key ke pesanan |
| `id_produk` | BIGINT UNSIGNED | NO | - | Foreign Key ke produk |
| `id_varian` | BIGINT UNSIGNED | YES | NULL | Foreign Key ke varian_produk |
| `jumlah` | INTEGER | NO | - | Jumlah item |
| `harga_satuan` | DECIMAL(12,2) | NO | - | Harga per item |
| `subtotal` | DECIMAL(12,2) | NO | - | Subtotal (jumlah × harga_satuan) |

**Indexes:**
- PRIMARY KEY: `id_detail`

**Foreign Keys:**
- `id_pesanan` → `pesanan(id_pesanan)` ON DELETE CASCADE
- `id_produk` → `produk(id_produk)` ON DELETE RESTRICT
- `id_varian` → `varian_produk(id_varian)` ON DELETE RESTRICT

**Relasi:**
- Belongs To → `pesanan` (id_pesanan)
- Belongs To → `produk` (id_produk)
- Belongs To → `varian_produk` (id_varian)

---

## 11. pembayaran

Tabel untuk menyimpan data pembayaran (integrasi Midtrans).

| Kolom | Tipe | Nullable | Default | Keterangan |
|-------|------|----------|---------|------------|
| `id_pembayaran` | BIGINT UNSIGNED | NO | AUTO_INCREMENT | Primary Key |
| `id_pesanan` | BIGINT UNSIGNED | YES | NULL | Foreign Key ke pesanan (UNIQUE) |
| `id_custom_order` | BIGINT UNSIGNED | YES | NULL | Foreign Key ke custom_orders (UNIQUE) |
| `midtrans_order_id` | VARCHAR(100) | YES | NULL | Order ID dari Midtrans |
| `snap_token` | VARCHAR(100) | YES | NULL | Snap token Midtrans |
| `tipe_pembayaran` | VARCHAR(50) | YES | NULL | Tipe pembayaran |
| `status_pembayaran` | VARCHAR(20) | NO | 'unpaid' | Status pembayaran |
| `status_bayar` | VARCHAR(20) | YES | NULL | Status bayar alternatif |
| `waktu_transaksi` | DATETIME | YES | NULL | Waktu transaksi |
| `waktu_settlement` | DATETIME | YES | NULL | Waktu settlement |
| `fraud_status` | VARCHAR(50) | YES | NULL | Status fraud detection |
| `created_at` | TIMESTAMP | YES | NULL | Waktu dibuat |
| `updated_at` | TIMESTAMP | YES | NULL | Waktu diupdate |

**Indexes:**
- PRIMARY KEY: `id_pembayaran`
- UNIQUE: `id_pesanan`, `id_custom_order`
- INDEX: `midtrans_order_id`, `status_pembayaran`, `id_pesanan`, `id_custom_order`

**Foreign Keys:**
- `id_pesanan` → `pesanan(id_pesanan)` ON DELETE CASCADE
- `id_custom_order` → `custom_orders(id_custom_order)` ON DELETE CASCADE

**Status Values:**
- `unpaid` - Belum dibayar
- `pending` - Menunggu pembayaran
- `paid` - Sudah dibayar
- `cancel` - Dibatalkan
- `expire` - Kadaluarsa
- `failure` - Gagal

**Relasi:**
- Belongs To → `pesanan` (id_pesanan)
- Belongs To → `custom_orders` (id_custom_order)

---

## 12. pengiriman

Tabel untuk menyimpan data pengiriman (integrasi RajaOngkir).

| Kolom | Tipe | Nullable | Default | Keterangan |
|-------|------|----------|---------|------------|
| `id_pengiriman` | BIGINT UNSIGNED | NO | AUTO_INCREMENT | Primary Key |
| `id_pesanan` | BIGINT UNSIGNED | NO | - | Foreign Key ke pesanan (UNIQUE) |
| `id_kota_asal` | INTEGER | YES | NULL | ID kota asal (RajaOngkir) |
| `id_kota_tujuan` | INTEGER | YES | NULL | ID kota tujuan (RajaOngkir) |
| `kurir` | VARCHAR(50) | YES | NULL | Nama kurir (JNE, TIKI, POS) |
| `layanan` | VARCHAR(50) | YES | NULL | Layanan kurir (REG, YES, OKE) |
| `ongkir` | DECIMAL(12,2) | NO | 0 | Biaya ongkir |
| `estimasi_pengiriman` | VARCHAR(50) | YES | NULL | Estimasi waktu pengiriman |
| `alamat_pengiriman` | TEXT | YES | NULL | Alamat lengkap pengiriman |
| `no_resi` | VARCHAR(100) | YES | NULL | Nomor resi pengiriman |
| `status_pengiriman` | VARCHAR(30) | NO | 'menunggu' | Status pengiriman |
| `tanggal_kirim` | DATETIME | YES | NULL | Tanggal dikirim |
| `tanggal_terima` | DATETIME | YES | NULL | Tanggal diterima |

**Indexes:**
- PRIMARY KEY: `id_pengiriman`
- UNIQUE: `id_pesanan`

**Foreign Keys:**
- `id_pesanan` → `pesanan(id_pesanan)` ON DELETE CASCADE

**Status Values:**
- `menunggu` - Menunggu pengiriman
- `dalam_perjalanan` - Sedang dikirim
- `sampai` - Sudah sampai

**Relasi:**
- Belongs To → `pesanan` (id_pesanan)

---

## 13. cache

Tabel untuk menyimpan cache Laravel (sistem).

| Kolom | Tipe | Nullable | Default | Keterangan |
|-------|------|----------|---------|------------|
| `key` | VARCHAR(255) | NO | - | Cache key (PRIMARY) |
| `value` | MEDIUMTEXT | NO | - | Cache value |
| `expiration` | INTEGER | NO | - | Waktu expiration (unix timestamp) |

**Indexes:**
- PRIMARY KEY: `key`
- INDEX: `expiration`

---

## 14. jobs

Tabel untuk menyimpan queue jobs Laravel (sistem).

| Kolom | Tipe | Nullable | Default | Keterangan |
|-------|------|----------|---------|------------|
| `id` | BIGINT UNSIGNED | NO | AUTO_INCREMENT | Primary Key |
| `queue` | VARCHAR(255) | NO | - | Nama queue |
| `payload` | LONGTEXT | NO | - | Job payload |
| `attempts` | TINYINT UNSIGNED | NO | - | Jumlah percobaan |
| `reserved_at` | INTEGER UNSIGNED | YES | NULL | Waktu reserved |
| `available_at` | INTEGER UNSIGNED | NO | - | Waktu available |
| `created_at` | INTEGER UNSIGNED | NO | - | Waktu dibuat |

**Indexes:**
- PRIMARY KEY: `id`
- INDEX: `queue`

---

## 📊 Entity Relationship Diagram

```
jenis (1) ──────< (N) produk (1) ──────< (N) varian_produk
  │                    │                         │
  │                    │                         │
  └──< (N) custom_orders    │                    │
                       │                         │
kategori (1) ──────< (N)                        │
                                                 │
pelanggan (1) ──────< (N) keranjang >──────── (N)
    │                                            │
    │                                            │
    ├──────< (N) custom_orders                  │
    │                │                           │
    │                │                           │
    └──────< (N) pesanan (1) ──────< (N) detail_pesanan >──── (N)
                     │
                     ├──────< (1) pembayaran
                     │
                     └──────< (1) pengiriman

admin (1) ──────< (N) custom_orders (updated_by)
```

---

## 🔑 Konvensi Penamaan

- **Primary Key**: `id_{nama_tabel_singular}`
- **Foreign Key**: `id_{nama_tabel_referensi_singular}`
- **Timestamps**: `created_at`, `updated_at`
- **Soft Deletes**: `deleted_at` (jika digunakan)
- **Status**: Menggunakan VARCHAR dengan nilai spesifik
- **Boolean**: Menggunakan BOOLEAN atau TINYINT(1)
- **Decimal**: Untuk harga menggunakan DECIMAL(12,2)
- **JSON**: Untuk data kompleks/array

---

## 📝 Catatan Penting

1. **Sistem Varian**: Produk menggunakan sistem varian untuk harga dan stok. Harga dan stok ada di tabel `varian_produk`, bukan di tabel `produk`.

2. **Multiple Alamat**: Pelanggan dapat memiliki multiple alamat yang disimpan dalam format JSON di kolom `alamat`.

3. **Custom Orders**: Sistem mendukung pesanan custom dengan flow: draft → menunggu_dp → dp_dibayar → dalam_produksi → menunggu_pelunasan → selesai.

4. **Payment Gateway**: Menggunakan Midtrans untuk payment gateway dengan snap token.

5. **Shipping**: Menggunakan RajaOngkir API untuk kalkulasi ongkir dan data kota/provinsi.

---

**Last Updated**: 2026-02-11
**Version**: 1.0
