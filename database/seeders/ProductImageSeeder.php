<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk;
use App\Models\VarianProduk;
use App\Models\GambarProduk;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        // Mapping gambar berdasarkan nama produk yang tepat sesuai file asli
        $productImageMapping = [
            // KATEGORI 1: Aksesoris (Selendang) - 1 produk dengan 5 gambar varian
            'selendang-songket' => [
                'products/Selendang songket tumpal.jpg',
                'products/Selendang songket Perak.jpg', 
                'products/Selendang songket Bunga.jpg',
                'products/Selendang songket Geometris.jpg',
                'products/Selendang songket Wajik.jpg'
            ],

            // KATEGORI 2: Kain Songket & Bawahan - 1 produk dengan 4 varian
            'kain-songket-premium' => [
                'products/Songket jembrana.jpeg',
                'products/Songket Cendana.jpeg',
                'products/Songket banyumas semi.jpeg',
                'products/Songket seseh klasik.jpeg'
            ],

            // KATEGORI 3: Kain Endek Katun - 4 produk
            'kain-endek-motif-bunga' => [
                'products/Kain Endek Bunga Pastel.jpeg',
                'products/Kain Endek Putih Pastel.jpeg'
            ],
            'kain-endek-sidemen-klasik' => [
                'products/Kain Endek Geometris 3 Warna.jpeg',
                'products/Kain Endek Sidemen Hitam.jpeg',
                'products/Kain Endek Sidemen Magenta.jpeg',
                'products/Kain Endek Sidemen Floral.jpeg',
                'products/Kain Endek Sidemen Motif Burung.jpeg',
                'products/Kain Endek Sidemen Biru Abu.jpeg',
                'products/Kain Endek Sidemen Hitam Klasik.jpeg'
            ],
            'kain-endek-motif-geometris' => [
                'products/Kain Endek Merah Floral.jpeg'
            ],
            'kain-tenun-salur' => [
                'products/Kain Tenun Salur (Stripes) Ungu.jpeg',
                'products/Kain Tenun Salur Multiwarna.jpeg'
            ],

            // KATEGORI 4: Kain Endek Sutra (Premium) - 2 produk
            'kain-endek-sutra-motif-klasik' => [
                'products/Kain Endek Sutra Sidemen Hijau.jpeg',
                'products/Kain Endek Sutra Cokelat Wajik.jpg',
                'products/Kain Endek Sutra Pastel.jpg'
            ],
            'kain-endek-sutra-motif-modern' => [
                'products/Kain Endek Sutra Dusty Pink.jpg',
                'products/Kain Endek Sutra Lavender Floral.jpg',
                'products/Kain Endek Sutra Cokelat Gold.jpg'
            ],
        ];

        $products = Produk::with(['variants', 'category'])->get();
        $totalImages = 0;

        foreach ($products as $product) {
            // Cari gambar berdasarkan slug produk
            $productImages = $productImageMapping[$product->slug] ?? ['products/songket-1.jpg']; // default fallback
            
            // Gambar utama produk saja (tidak untuk varian)
            foreach ($productImages as $index => $imagePath) {
                $exists = GambarProduk::where('id_produk', $product->id_produk)
                                    ->where('id_varian', null)
                                    ->where('path', $imagePath)
                                    ->exists();
                
                if (!$exists) {
                    GambarProduk::create([
                        'id_produk' => $product->id_produk,
                        'id_varian' => null, // Gambar utama produk
                        'path' => $imagePath,
                        'is_primary' => $index === 0, // Gambar pertama sebagai primary
                    ]);
                    
                    echo "Gambar produk {$product->nama_produk} berhasil ditambahkan!\n";
                    $totalImages++;
                } else {
                    echo "Gambar produk {$product->nama_produk} sudah ada, dilewati.\n";
                }
            }

            // Tambahkan gambar spesifik untuk setiap varian
            $variants = $product->variants;
            foreach ($variants as $variantIndex => $variant) {
                // Gunakan gambar yang sesuai dengan urutan varian
                $variantImageIndex = $variantIndex % count($productImages);
                $variantImagePath = $productImages[$variantImageIndex];
                
                // Cek apakah gambar varian sudah ada
                $variantImageExists = VarianProduk::where('id_varian', $variant->id_varian)
                                                 ->whereNotNull('gambar_varian')
                                                 ->exists();
                
                if (!$variantImageExists) {
                    // Update varian dengan gambar yang sesuai
                    $variant->update([
                        'gambar_varian' => $variantImagePath
                    ]);
                    
                    echo "Gambar varian {$variant->nama_varian} berhasil ditambahkan: {$variantImagePath}\n";
                    $totalImages++;
                } else {
                    echo "Gambar varian {$variant->nama_varian} sudah ada, dilewati.\n";
                }
            }
        }

        echo "Gambar produk berhasil di-seed! Total: $totalImages gambar\n";
        echo "CATATAN: Gambar sudah disesuaikan dengan file asli dari folder Gambar Produk\n";
        echo "Struktur gambar yang digunakan:\n";
        echo "   - Selendang: File asli dengan nama lengkap (.jpg)\n";
        echo "   - Songket: File asli dengan nama lengkap (.jpeg)\n";
        echo "   - Endek Katun: File asli dengan nama lengkap (.jpeg)\n";
        echo "   - Endek Sutra: File asli dengan nama lengkap (.jpg/.jpeg)\n";
    }
}