<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Get categories
        $aksesoris = Kategori::where('slug', 'aksesoris-selendang')->first();
        $songketBawahan = Kategori::where('slug', 'kain-songket-bawahan')->first();
        $endekKatun = Kategori::where('slug', 'kain-endek-katun')->first();
        $endekSutra = Kategori::where('slug', 'kain-endek-sutra-premium')->first();

        // Get jenis
        $jenisSongket = \App\Models\Jenis::where('slug', 'songket-custom')->first();
        if (!$jenisSongket) {
            $jenisSongket = \App\Models\Jenis::first();
        }

        $products = [
            // KATEGORI 1: Aksesoris (Selendang) - 1 produk dengan 5 varian
            [
                'id_kategori' => $aksesoris->id_kategori,
                'id_jenis' => $jenisSongket->id_jenis,
                'nama_produk' => 'Selendang Songket',
                'slug' => 'selendang-songket',
                'deskripsi' => 'Selendang songket dengan berbagai motif tradisional Bali. Ditenun dengan teknik tradisional menggunakan benang berkualitas tinggi. Tersedia dalam berbagai motif: Tumpal, Perak, Bunga, Geometris, dan Wajik. Cocok untuk acara formal dan upacara adat.',
                'berat' => 200,
                'status' => 'aktif',
                'is_made_to_order' => false,
                'views' => rand(50, 200),
                'rating' => 4.8,
            ],

            // KATEGORI 2: Kain Songket & Bawahan - 1 produk dengan 4 varian
            [
                'id_kategori' => $songketBawahan->id_kategori,
                'id_jenis' => $jenisSongket->id_jenis,
                'nama_produk' => 'Kain Songket Premium',
                'slug' => 'kain-songket-premium',
                'deskripsi' => 'Koleksi kain songket premium dengan berbagai motif tradisional. Tersedia dalam varian Jembrana, Cendana, Banyumas Semi, dan Seseh Klasik. Kain berkualitas tinggi dengan detail yang sangat halus dan artistik.',
                'berat' => 650,
                'status' => 'aktif',
                'is_made_to_order' => false,
                'views' => rand(100, 300),
                'rating' => 4.8,
            ],

            // KATEGORI 3: Kain Endek Katun - 4 produk dengan varian
            [
                'id_kategori' => $endekKatun->id_kategori,
                'id_jenis' => $jenisSongket->id_jenis,
                'nama_produk' => 'Kain Endek Motif Bunga',
                'slug' => 'kain-endek-motif-bunga',
                'deskripsi' => 'Kain endek dengan berbagai motif bunga yang indah. Tersedia dalam warna pastel yang lembut dan cocok untuk berbagai kesempatan. Motif bunga yang fresh dan elegan.',
                'berat' => 300,
                'status' => 'aktif',
                'is_made_to_order' => false,
                'views' => rand(80, 250),
                'rating' => 4.5,
            ],
            [
                'id_kategori' => $endekKatun->id_kategori,
                'id_jenis' => $jenisSongket->id_jenis,
                'nama_produk' => 'Kain Endek Sidemen Klasik',
                'slug' => 'kain-endek-sidemen-klasik',
                'deskripsi' => 'Kain endek khas Sidemen dengan berbagai motif tradisional. Tersedia dalam berbagai warna dan motif yang autentik. Desain khas Sidemen yang berkualitas tinggi.',
                'berat' => 330,
                'status' => 'aktif',
                'is_made_to_order' => false,
                'views' => rand(80, 250),
                'rating' => 4.7,
            ],
            [
                'id_kategori' => $endekKatun->id_kategori,
                'id_jenis' => $jenisSongket->id_jenis,
                'nama_produk' => 'Kain Endek Motif Geometris',
                'slug' => 'kain-endek-motif-geometris',
                'deskripsi' => 'Kain endek dengan motif geometris modern namun tetap tradisional. Kombinasi warna yang harmonis dan pola yang simetris.',
                'berat' => 320,
                'status' => 'aktif',
                'is_made_to_order' => false,
                'views' => rand(80, 250),
                'rating' => 4.4,
            ],
            [
                'id_kategori' => $endekKatun->id_kategori,
                'id_jenis' => $jenisSongket->id_jenis,
                'nama_produk' => 'Kain Tenun Salur',
                'slug' => 'kain-tenun-salur',
                'deskripsi' => 'Kain tenun dengan motif salur (garis) yang klasik. Tersedia dalam berbagai kombinasi warna yang menarik dan versatile.',
                'berat' => 280,
                'status' => 'aktif',
                'is_made_to_order' => false,
                'views' => rand(80, 250),
                'rating' => 4.3,
            ],

            // KATEGORI 4: Kain Endek Sutra (Premium) - 2 produk dengan varian
            [
                'id_kategori' => $endekSutra->id_kategori,
                'id_jenis' => $jenisSongket->id_jenis,
                'nama_produk' => 'Kain Endek Sutra Motif Klasik',
                'slug' => 'kain-endek-sutra-motif-klasik',
                'deskripsi' => 'Kain endek sutra premium dengan motif klasik tradisional. Tersedia dalam berbagai warna dan motif yang elegan. Kualitas sutra terbaik dengan detail yang sangat halus.',
                'berat' => 420,
                'status' => 'aktif',
                'is_made_to_order' => false,
                'views' => rand(120, 350),
                'rating' => 4.8,
            ],
            [
                'id_kategori' => $endekSutra->id_kategori,
                'id_jenis' => $jenisSongket->id_jenis,
                'nama_produk' => 'Kain Endek Sutra Motif Modern',
                'slug' => 'kain-endek-sutra-motif-modern',
                'deskripsi' => 'Kain endek sutra premium dengan motif modern yang trendy. Kombinasi warna yang soft dan contemporary. Cocok untuk gaya modern namun tetap elegan.',
                'berat' => 430,
                'status' => 'aktif',
                'is_made_to_order' => false,
                'views' => rand(120, 350),
                'rating' => 4.9,
            ],
        ];

        foreach ($products as $productData) {
            $exists = Produk::where('slug', $productData['slug'])->exists();
            if (!$exists) {
                Produk::create($productData);
                echo "Produk {$productData['nama_produk']} berhasil ditambahkan!\n";
            } else {
                echo "Produk {$productData['nama_produk']} sudah ada, dilewati.\n";
            }
        }

        echo "Seeder Produk selesai!\n";
    }
}
