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
                'nama_kategori' => 'Aksesoris (Selendang)',
                'slug' => 'aksesoris-selendang',
                'deskripsi' => 'Koleksi selendang songket dengan berbagai motif tradisional seperti tumpal, geometris, dan bunga',
            ],
            [
                'nama_kategori' => 'Kain Songket & Bawahan',
                'slug' => 'kain-songket-bawahan',
                'deskripsi' => 'Kain songket premium untuk bawahan dengan motif figur, bunga, dan klasik tradisional',
            ],
            [
                'nama_kategori' => 'Kain Endek Katun',
                'slug' => 'kain-endek-katun',
                'deskripsi' => 'Kain endek berbahan katun dengan motif bunga, geometris, dan fauna khas Sidemen',
            ],
            [
                'nama_kategori' => 'Kain Endek Sutra (Premium)',
                'slug' => 'kain-endek-sutra-premium',
                'deskripsi' => 'Kain endek sutra premium dengan kualitas terbaik dan motif eksklusif',
            ],
        ];

        foreach ($categories as $category) {
            $exists = Kategori::where('slug', $category['slug'])->exists();
            if (!$exists) {
                Kategori::create($category);
                echo "Kategori {$category['nama_kategori']} berhasil ditambahkan!\n";
            } else {
                echo "Kategori {$category['nama_kategori']} sudah ada, dilewati.\n";
            }
        }

        echo "Seeder Kategori selesai!\n";
    }
}
