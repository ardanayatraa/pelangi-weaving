<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'nama_kategori' => 'Kain Songket Premium',
                'slug' => 'kain-songket-premium',
                'deskripsi' => 'Koleksi kain songket premium dengan benang emas dan perak asli, cocok untuk acara pernikahan dan upacara adat',
            ],
            [
                'nama_kategori' => 'Kain Endek Bali',
                'slug' => 'kain-endek-bali',
                'deskripsi' => 'Kain endek khas Bali dengan motif tradisional yang ditenun menggunakan teknik ikat',
            ],
            [
                'nama_kategori' => 'Selendang & Syal',
                'slug' => 'selendang-syal',
                'deskripsi' => 'Selendang dan syal tenun dengan berbagai motif dan warna yang elegan',
            ],
            [
                'nama_kategori' => 'Kain Gringsing',
                'slug' => 'kain-gringsing',
                'deskripsi' => 'Kain gringsing langka dari Tenganan dengan teknik double ikat yang unik',
            ],
            [
                'nama_kategori' => 'Kain Prada',
                'slug' => 'kain-prada',
                'deskripsi' => 'Kain dengan hiasan prada (emas) yang mewah untuk acara formal',
            ],
            [
                'nama_kategori' => 'Kain Cepuk',
                'slug' => 'kain-cepuk',
                'deskripsi' => 'Kain cepuk khas Bali dengan motif geometris yang khas',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        echo "✅ Kategori berhasil di-seed!\n";
    }
}
