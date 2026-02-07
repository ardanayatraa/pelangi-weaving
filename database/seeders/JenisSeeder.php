<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JenisSeeder extends Seeder
{
    public function run(): void
    {
        $jenisData = [
            [
                'nama_jenis' => 'Rajutan Custom',
                'slug' => 'rajutan-custom',
                'deskripsi' => 'Pembuatan produk rajutan dengan pola dan warna custom',
                'icon' => 'bi bi-scissors',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jenis' => 'Aksesoris Custom',
                'slug' => 'aksesoris-custom',
                'deskripsi' => 'Pembuatan aksesoris dengan desain khusus',
                'icon' => 'bi bi-gem',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jenis' => 'Pakaian Custom',
                'slug' => 'pakaian-custom',
                'deskripsi' => 'Pembuatan pakaian dengan ukuran dan desain sesuai permintaan',
                'icon' => 'bi bi-person-badge',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jenis' => 'Songket Custom',
                'slug' => 'songket-custom',
                'deskripsi' => 'Pembuatan kain songket dengan motif dan warna sesuai permintaan',
                'icon' => 'bi bi-palette',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($jenisData as $jenis) {
            $exists = DB::table('jenis')->where('slug', $jenis['slug'])->exists();
            if (!$exists) {
                DB::table('jenis')->insert($jenis);
                echo "Jenis {$jenis['nama_jenis']} berhasil ditambahkan!\n";
            } else {
                echo "Jenis {$jenis['nama_jenis']} sudah ada, dilewati.\n";
            }
        }

        echo "Seeder Jenis selesai!\n";
    }
}