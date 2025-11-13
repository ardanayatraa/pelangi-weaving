# Tema Merah Putih Hitam - Pelangi Weaving

## Palet Warna

Website telah diubah dengan skema warna merah-putih-hitam yang nyaman di mata:

### Warna Utama
- **Merah Primer**: `#DC2626` - Merah cerah tapi tidak menyilaukan
- **Merah Gelap**: `#B91C1C` - Untuk hover dan aksen
- **Merah Terang**: `#EF4444` - Untuk highlight

### Warna Netral
- **Hitam**: `#1F2937` - Hitam lembut untuk teks
- **Hitam Gelap**: `#111827` - Untuk background footer dan navbar admin
- **Putih**: `#FFFFFF` - Putih bersih
- **Off-White**: `#F9FAFB` - Background halaman yang lembut di mata
- **Abu-abu Terang**: `#E5E7EB` - Border dan divider
- **Abu-abu Medium**: `#6B7280` - Teks sekunder

## Perubahan yang Diterapkan

### Layout Customer (`resources/views/layouts/customer.blade.php`)
✅ Navbar: Gradient merah (DC2626 → B91C1C)
✅ Top bar: Background hitam dengan teks putih dan ikon merah
✅ Category bar: Ikon merah dengan hover effect
✅ Footer: Background hitam gelap dengan aksen merah
✅ Tombol: Merah dengan hover effect
✅ Card: Shadow merah saat hover
✅ Link: Hover berubah merah

### Layout Admin (`resources/views/layouts/admin.blade.php`)
✅ Navbar: Gradient hitam dengan hover merah
✅ Background: Off-white yang nyaman
✅ Tombol primary: Merah
✅ Table header: Hitam gelap
✅ Alert: Warna lembut yang konsisten

## Keunggulan Tema Ini

1. **Kontras Tinggi**: Kombinasi merah-putih-hitam memberikan kontras yang jelas
2. **Nyaman di Mata**: Menggunakan shade yang tidak terlalu terang
3. **Profesional**: Hitam memberikan kesan elegan
4. **Energik**: Merah memberikan kesan dinamis tanpa berlebihan
5. **Konsisten**: Semua elemen mengikuti palet warna yang sama

## Tips Penggunaan

- Gunakan class `text-red-custom` untuk teks merah
- Gunakan class `btn-primary-custom` untuk tombol merah
- Gunakan class `hover-red` untuk hover effect merah
- Background card otomatis putih dengan shadow
- Alert sudah disesuaikan dengan warna yang lembut

## File yang Dimodifikasi

1. `resources/views/layouts/customer.blade.php` - Layout customer dengan tema baru
2. `resources/views/layouts/admin.blade.php` - Layout admin dengan tema baru

Semua halaman yang menggunakan layout ini akan otomatis mengikuti tema merah-putih-hitam.
