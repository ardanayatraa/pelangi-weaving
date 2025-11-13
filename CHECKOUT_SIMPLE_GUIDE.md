# 🛒 Checkout Simple - Tanpa API RajaOngkir

## Perubahan yang Sudah Dilakukan:

### 1. ✅ Field Alamat Disederhanakan
- ❌ Hapus destination search (autocomplete)
- ✅ Input manual: Kota, Provinsi, Kode Pos

### 2. ✅ Shipping Options Langsung Tampil
- ❌ Tidak perlu API call
- ✅ Dummy data langsung load
- ✅ Auto-select opsi pertama (termurah)

### 3. ✅ Opsi Kurir Dummy:
```
1. POS Indonesia - Paket Kilat Khusus: Rp 20.000 (2-4 hari)
2. Ninja Xpress - REG: Rp 21.000 (2-4 hari)
3. J&T Express - REG: Rp 22.000 (2-3 hari)
4. TIKI - REG: Rp 23.000 (3-4 hari)
5. JNE - REG: Rp 25.000 (2-3 hari)
6. JNE - YES: Rp 45.000 (1 hari)
7. TIKI - ONS: Rp 42.000 (1 hari)
```

## 🧪 Cara Testing:

1. **Buka checkout**
2. **Isi form customer info**
3. **Isi alamat:**
   - Alamat Lengkap: Jl. Raya Ubud No. 88
   - Kota: Gianyar
   - Provinsi: Bali
   - Kode Pos: 80571
4. **Scroll ke "Metode Pengiriman"**
5. **Opsi kurir langsung muncul!** ✅
6. **Pilih kurir** → Total otomatis update
7. **Klik "Buat Pesanan"**
8. **Snap popup muncul** → Bayar

## ✅ Keuntungan:

- ✅ Tidak perlu API Key RajaOngkir
- ✅ Tidak perlu internet untuk calculate shipping
- ✅ Lebih cepat (no API delay)
- ✅ Lebih simple untuk testing
- ✅ Opsi kurir langsung terlihat

## 🔄 Jika Ingin Pakai API Real Nanti:

Tinggal uncomment code API di `calculateShipping()` function dan comment dummy data.

---

**Sekarang checkout sudah simple dan langsung berfungsi!** 🎉
