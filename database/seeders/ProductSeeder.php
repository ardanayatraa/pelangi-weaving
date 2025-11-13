<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Kain Songket Premium
            [
                'category' => 'kain-songket-premium',
                'nama_produk' => 'Songket Bali Motif Pucuk Rebung',
                'slug' => 'songket-bali-pucuk-rebung',
                'deskripsi' => 'Kain songket premium dengan motif pucuk rebung yang melambangkan pertumbuhan dan kemakmuran. Ditenun dengan benang emas 24 karat dan sutra berkualitas tinggi. Ukuran 2 x 1 meter, cocok untuk kebaya dan busana adat.',
                'harga' => 2850000,
                'stok' => 15,
                'berat' => 600,
            ],
            [
                'category' => 'kain-songket-premium',
                'nama_produk' => 'Songket Bali Motif Cempaka',
                'slug' => 'songket-bali-cempaka',
                'deskripsi' => 'Songket eksklusif dengan motif bunga cempaka yang anggun. Menggunakan benang perak dan emas dengan detail yang sangat halus. Sempurna untuk acara pernikahan adat Bali.',
                'harga' => 3200000,
                'stok' => 10,
                'berat' => 650,
            ],
            [
                'category' => 'kain-songket-premium',
                'nama_produk' => 'Songket Bali Motif Sekar Jagat',
                'slug' => 'songket-bali-sekar-jagat',
                'deskripsi' => 'Kain songket dengan motif sekar jagat (bunga dunia) yang penuh makna filosofis. Ditenun oleh pengrajin berpengalaman dengan teknik tradisional. Ukuran 2.5 x 1.2 meter.',
                'harga' => 4500000,
                'stok' => 8,
                'berat' => 700,
            ],

            // Kain Endek Bali
            [
                'category' => 'kain-endek-bali',
                'nama_produk' => 'Endek Bali Motif Wayang',
                'slug' => 'endek-bali-wayang',
                'deskripsi' => 'Kain endek dengan motif wayang khas Bali. Ditenun menggunakan teknik ikat tradisional dengan pewarna alami. Ukuran 2 x 1.1 meter, cocok untuk kemeja dan dress.',
                'harga' => 850000,
                'stok' => 25,
                'berat' => 400,
            ],
            [
                'category' => 'kain-endek-bali',
                'nama_produk' => 'Endek Bali Motif Ceplok',
                'slug' => 'endek-bali-ceplok',
                'deskripsi' => 'Endek dengan motif ceplok geometris yang modern namun tetap tradisional. Warna cerah dan tahan lama. Cocok untuk berbagai keperluan fashion.',
                'harga' => 650000,
                'stok' => 30,
                'berat' => 380,
            ],
            [
                'category' => 'kain-endek-bali',
                'nama_produk' => 'Endek Bali Motif Bintang',
                'slug' => 'endek-bali-bintang',
                'deskripsi' => 'Kain endek dengan motif bintang yang cantik dan elegan. Menggunakan benang katun berkualitas dengan pewarna ramah lingkungan. Ukuran 2 x 1 meter.',
                'harga' => 750000,
                'stok' => 20,
                'berat' => 390,
            ],

            // Selendang & Syal
            [
                'category' => 'selendang-syal',
                'nama_produk' => 'Selendang Songket Mini',
                'slug' => 'selendang-songket-mini',
                'deskripsi' => 'Selendang songket berukuran 1.8 x 0.5 meter dengan motif tradisional yang elegan. Cocok untuk melengkapi kebaya atau busana formal. Tersedia dalam berbagai warna.',
                'harga' => 450000,
                'stok' => 40,
                'berat' => 200,
            ],
            [
                'category' => 'selendang-syal',
                'nama_produk' => 'Syal Tenun Pelangi',
                'slug' => 'syal-tenun-pelangi',
                'deskripsi' => 'Syal tenun dengan gradasi warna pelangi yang indah. Lembut dan nyaman dipakai. Ukuran 1.5 x 0.4 meter, cocok untuk gaya kasual maupun formal.',
                'harga' => 280000,
                'stok' => 50,
                'berat' => 150,
            ],

            // Kain Gringsing
            [
                'category' => 'kain-gringsing',
                'nama_produk' => 'Gringsing Wayang Kebo',
                'slug' => 'gringsing-wayang-kebo',
                'deskripsi' => 'Kain gringsing langka dari Desa Tenganan dengan motif wayang kebo. Ditenun dengan teknik double ikat yang membutuhkan waktu berbulan-bulan. Memiliki nilai spiritual dan seni yang tinggi.',
                'harga' => 15000000,
                'stok' => 3,
                'berat' => 500,
            ],
            [
                'category' => 'kain-gringsing',
                'nama_produk' => 'Gringsing Lubeng',
                'slug' => 'gringsing-lubeng',
                'deskripsi' => 'Kain gringsing dengan motif lubeng (kotak-kotak) yang klasik. Dipercaya memiliki kekuatan magis untuk menolak bala. Ukuran 2 x 1 meter.',
                'harga' => 12500000,
                'stok' => 5,
                'berat' => 480,
            ],

            // Kain Prada
            [
                'category' => 'kain-prada',
                'nama_produk' => 'Kain Prada Motif Bunga',
                'slug' => 'kain-prada-bunga',
                'deskripsi' => 'Kain dengan hiasan prada (emas) motif bunga yang mewah. Cocok untuk kebaya pengantin atau acara formal. Ukuran 2.5 x 1.1 meter dengan detail prada yang rapat.',
                'harga' => 1850000,
                'stok' => 12,
                'berat' => 450,
            ],
            [
                'category' => 'kain-prada',
                'nama_produk' => 'Kain Prada Motif Burung',
                'slug' => 'kain-prada-burung',
                'deskripsi' => 'Kain prada dengan motif burung phoenix yang melambangkan keabadian. Dikerjakan dengan teknik cap prada tradisional. Sangat eksklusif dan elegan.',
                'harga' => 2100000,
                'stok' => 10,
                'berat' => 470,
            ],

            // Kain Cepuk
            [
                'category' => 'kain-cepuk',
                'nama_produk' => 'Kain Cepuk Motif Klasik',
                'slug' => 'kain-cepuk-klasik',
                'deskripsi' => 'Kain cepuk dengan motif geometris klasik khas Bali. Ditenun dengan teknik tradisional menggunakan benang katun berkualitas. Ukuran 2 x 1.1 meter.',
                'harga' => 580000,
                'stok' => 35,
                'berat' => 420,
            ],
            [
                'category' => 'kain-cepuk',
                'nama_produk' => 'Kain Cepuk Motif Modern',
                'slug' => 'kain-cepuk-modern',
                'deskripsi' => 'Kain cepuk dengan sentuhan modern yang tetap mempertahankan nilai tradisional. Warna-warna cerah dan motif yang fresh. Cocok untuk fashion kontemporer.',
                'harga' => 620000,
                'stok' => 30,
                'berat' => 410,
            ],
        ];

        foreach ($products as $productData) {
            $category = Category::where('slug', $productData['category'])->first();
            
            if ($category) {
                Product::create([
                    'id_kategori' => $category->id_kategori,
                    'nama_produk' => $productData['nama_produk'],
                    'slug' => $productData['slug'],
                    'deskripsi' => $productData['deskripsi'],
                    'harga' => $productData['harga'],
                    'stok' => $productData['stok'],
                    'berat' => $productData['berat'],
                    'status' => 'aktif',
                ]);
            }
        }

        echo "✅ Produk berhasil di-seed!\n";
    }
}
