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
        $products = [
            [
                'nama_produk' => 'Songket Cendana',
                'slug' => 'songket-cendana',
                'deskripsi' => 'Kain songket premium dengan motif cendana yang elegan. Ditenun dengan benang emas dan sutra berkualitas tinggi. Cocok untuk acara formal dan pernikahan adat.',
                'harga' => 1800000,
                'stok' => 15,
                'berat' => 600,
            ],
            [
                'nama_produk' => 'Songket Banyumas Semi',
                'slug' => 'songket-banyumas-semi',
                'deskripsi' => 'Songket semi dengan motif khas Banyumas yang memadukan tradisi Jawa dan Bali. Menggunakan benang berkualitas dengan detail yang indah.',
                'harga' => 2000000,
                'stok' => 12,
                'berat' => 650,
            ],
            [
                'nama_produk' => 'Selendang Songket',
                'slug' => 'selendang-songket',
                'deskripsi' => 'Selendang songket berukuran 1.8 x 0.5 meter dengan motif tradisional yang elegan. Cocok untuk melengkapi kebaya atau busana formal. Tersedia dalam berbagai warna.',
                'harga' => 800000,
                'stok' => 25,
                'berat' => 200,
            ],
            [
                'nama_produk' => 'Songket Jembrana',
                'slug' => 'songket-jembrana',
                'deskripsi' => 'Kain songket khas Jembrana dengan motif geometris yang khas. Ditenun dengan teknik tradisional menggunakan benang emas dan perak. Ukuran 2 x 1 meter.',
                'harga' => 1200000,
                'stok' => 18,
                'berat' => 550,
            ],
            [
                'nama_produk' => 'Songket Seseh Klasik',
                'slug' => 'songket-seseh-klasik',
                'deskripsi' => 'Songket klasik dari Seseh dengan motif tradisional yang sangat detail. Menggunakan benang emas 24 karat dan sutra premium. Sempurna untuk acara pernikahan adat Bali.',
                'harga' => 2650000,
                'stok' => 10,
                'berat' => 700,
            ],
        ];

        // Get first category or create default
        $category = Kategori::first();
        if (!$category) {
            $category = Kategori::create([
                'nama_kategori' => 'Kain Songket Premium',
                'slug' => 'kain-songket-premium',
                'deskripsi' => 'Koleksi kain songket premium dengan kualitas terbaik',
            ]);
        }

        foreach ($products as $productData) {
            Produk::create([
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

        echo "✅ 5 Produk berhasil di-seed!\n";
    }
}
