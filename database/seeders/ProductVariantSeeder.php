<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk;
use App\Models\VarianProduk;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        // Data varian sesuai dengan tabel yang diberikan
        $variantData = [
            // KATEGORI 1: Aksesoris (Selendang) - 1 produk dengan 5 varian
            'selendang-songket' => [
                ['nama_varian' => 'Tumpal', 'warna' => 'Kuning', 'motif' => 'Motif Tumpal & Geometris Marun', 'jenis_tenun' => 'Tenun Songket', 'harga' => 800000, 'stok' => 15],
                ['nama_varian' => 'Perak', 'warna' => 'Cokelat Muda', 'motif' => 'Full Motif Perak', 'jenis_tenun' => 'Tenun Songket', 'harga' => 800000, 'stok' => 12],
                ['nama_varian' => 'Bunga', 'warna' => 'Biru Tua', 'motif' => 'Motif Bunga Besar Perak', 'jenis_tenun' => 'Tenun Songket', 'harga' => 800000, 'stok' => 18],
                ['nama_varian' => 'Geometris', 'warna' => 'Merah Marun', 'motif' => 'Motif Geometris Padat', 'jenis_tenun' => 'Tenun Songket', 'harga' => 800000, 'stok' => 20],
                ['nama_varian' => 'Wajik', 'warna' => 'Biru Muda', 'motif' => 'Motif Wajik/Diamond Perak', 'jenis_tenun' => 'Tenun Songket', 'harga' => 800000, 'stok' => 16],
            ],

            // KATEGORI 2: Kain Songket & Bawahan
            'kain-songket-premium' => [
                ['warna' => 'Navy Blue', 'motif' => 'Motif Figur/Wayang Emas', 'jenis_tenun' => 'Tenun Songket', 'harga' => 1200000, 'stok' => 8],
                ['warna' => 'Hijau Botol', 'motif' => 'Motif Bunga Warna-Warni', 'jenis_tenun' => 'Tenun Songket', 'harga' => 1800000, 'stok' => 6],
                ['warna' => 'Magenta Floral', 'motif' => 'Tumpal Orange/Emas', 'jenis_tenun' => 'Tenun Kombinasi', 'harga' => 2000000, 'stok' => 5],
                ['warna' => 'Ungu Magenta', 'motif' => 'Set dengan Selendang', 'jenis_tenun' => 'Tenun Songket', 'harga' => 2650000, 'stok' => 3],
            ],

            // KATEGORI 3: Kain Endek Katun
            'kain-endek-motif-bunga' => [
                ['warna' => 'Biru Telur Asin', 'motif' => 'Bunga Pink Salem', 'jenis_tenun' => 'Tenun Ikat (Endek)', 'harga' => 350000, 'stok' => 25],
                ['warna' => 'Putih Bersih', 'motif' => 'Bunga Hijau & Pink', 'jenis_tenun' => 'Tenun Ikat (Endek)', 'harga' => 375000, 'stok' => 22],
            ],
            'kain-endek-sidemen-klasik' => [
                ['warna' => 'Kombinasi Hitam, Cokelat, Marun', 'motif' => 'Geometris 3 Warna', 'jenis_tenun' => 'Tenun Ikat (Endek)', 'harga' => 400000, 'stok' => 20],
                ['warna' => 'Hitam', 'motif' => 'Motif Kotak Merah, Kuning, Biru', 'jenis_tenun' => 'Tenun Ikat (Endek)', 'harga' => 425000, 'stok' => 18],
                ['warna' => 'Magenta/Anggur', 'motif' => 'Motif Bunga Orange & Pink', 'jenis_tenun' => 'Tenun Ikat (Endek)', 'harga' => 425000, 'stok' => 16],
                ['warna' => 'Merah Anggur', 'motif' => 'Motif Floral Abstrak', 'jenis_tenun' => 'Tenun Ikat (Endek)', 'harga' => 425000, 'stok' => 19],
                ['warna' => 'Hitam', 'motif' => 'Motif Fauna Merak & Sulur', 'jenis_tenun' => 'Tenun Ikat (Endek)', 'harga' => 450000, 'stok' => 14],
                ['warna' => 'Biru Abu/Dusty Blue', 'motif' => 'Motif Abstrak Putih', 'jenis_tenun' => 'Tenun Ikat (Endek)', 'harga' => 400000, 'stok' => 21],
                ['warna' => 'Hitam', 'motif' => 'Bunga Putih & Aksen Merah', 'jenis_tenun' => 'Tenun Ikat (Endek)', 'harga' => 400000, 'stok' => 17],
            ],
            'kain-endek-motif-geometris' => [
                ['warna' => 'Merah Terang', 'motif' => 'Motif Bunga Putih/Kuning', 'jenis_tenun' => 'Tenun Ikat (Endek)', 'harga' => 400000, 'stok' => 23],
            ],
            'kain-tenun-salur' => [
                ['warna' => 'Garis Vertikal', 'motif' => 'Ungu, Biru, Orange', 'jenis_tenun' => 'Tenun Ikat / Salur', 'harga' => 275000, 'stok' => 30],
                ['warna' => 'Garis Vertikal', 'motif' => 'Biru, Hijau, Abu, Marun', 'jenis_tenun' => 'Tenun Ikat / Salur', 'harga' => 275000, 'stok' => 28],
            ],

            // KATEGORI 4: Kain Endek Sutra (Premium)
            'kain-endek-sutra-motif-klasik' => [
                ['warna' => 'Hijau Lumut', 'motif' => 'Motif Geometris', 'jenis_tenun' => 'Tenun Ikat (Endek Sutra)', 'harga' => 1850000, 'stok' => 7],
                ['warna' => 'Cokelat Terracotta', 'motif' => 'Motif Wajik Kecil', 'jenis_tenun' => 'Tenun Ikat (Endek Sutra)', 'harga' => 1900000, 'stok' => 6],
                ['warna' => 'Cokelat Abu/Mocca', 'motif' => 'Motif Pastel Pink', 'jenis_tenun' => 'Tenun Ikat (Endek Sutra)', 'harga' => 1950000, 'stok' => 5],
            ],
            'kain-endek-sutra-motif-modern' => [
                ['warna' => 'Dusty Pink/Mauve', 'motif' => 'Motif Floral Orange', 'jenis_tenun' => 'Tenun Ikat (Endek Sutra)', 'harga' => 2100000, 'stok' => 4],
                ['warna' => 'Lavender/Ungu Muda', 'motif' => 'Motif Bunga Pink', 'jenis_tenun' => 'Tenun Ikat (Endek Sutra)', 'harga' => 2100000, 'stok' => 4],
                ['warna' => 'Cokelat', 'motif' => 'Motif Besar Kuning Emas', 'jenis_tenun' => 'Tenun Ikat (Endek Sutra)', 'harga' => 2250000, 'stok' => 3],
            ],
        ];

        $totalVariants = 0;

        foreach ($variantData as $productSlug => $variants) {
            $product = Produk::where('slug', $productSlug)->first();
            
            if (!$product) {
                echo "Produk dengan slug '$productSlug' tidak ditemukan!\n";
                continue;
            }

            foreach ($variants as $index => $variantInfo) {
                $variantNumber = $index + 1;
                $kodeVarian = strtoupper(substr($product->slug, 0, 3)) . '-' . $product->id_produk . '-' . str_pad($variantNumber, 2, '0', STR_PAD_LEFT);
                
                // Cek apakah varian sudah ada
                $exists = VarianProduk::where('kode_varian', $kodeVarian)->exists();
                if ($exists) {
                    echo "Varian {$kodeVarian} sudah ada, dilewati.\n";
                    continue;
                }
                
                // Tentukan ukuran berdasarkan kategori
                $ukuran = '2 x 1 meter'; // Default
                if (strpos($product->slug, 'selendang') !== false) {
                    $ukuran = '1.8 x 0.5 meter';
                } elseif (strpos($product->slug, 'endek') !== false) {
                    $ukuran = '2.5 x 1.1 meter';
                }

                VarianProduk::create([
                    'id_produk' => $product->id_produk,
                    'nama_varian' => $variantInfo['warna'] . ' - ' . $variantInfo['motif'],
                    'kode_varian' => $kodeVarian,
                    'warna' => $variantInfo['warna'],
                    'ukuran' => $ukuran,
                    'jenis_benang' => $variantInfo['jenis_tenun'],
                    'harga' => $variantInfo['harga'],
                    'stok' => $variantInfo['stok'],
                    'berat' => $product->berat,
                    'status' => 'tersedia',
                ]);

                echo "Varian {$kodeVarian} berhasil ditambahkan!\n";
                $totalVariants++;
            }
        }

        echo "Varian produk berhasil di-seed! Total: $totalVariants varian\n";
    }
}
