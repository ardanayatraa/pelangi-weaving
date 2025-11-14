<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'nama_kategori' => 'Songket Premium',
                'slug' => 'songket-premium',
                'deskripsi' => 'Koleksi kain songket premium dengan benang emas dan perak asli, cocok untuk acara pernikahan dan upacara adat',
            ],
            [
                'nama_kategori' => 'Endek Bali',
                'slug' => 'endek-bali',
                'deskripsi' => 'Kain endek khas Bali dengan motif tradisional yang ditenun menggunakan teknik ikat',
            ],
            [
                'nama_kategori' => 'Selendang',
                'slug' => 'selendang',
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
            Kategori::create($category);
        }

        echo "✅ Kategori berhasil di-seed!\n";
    }
}
