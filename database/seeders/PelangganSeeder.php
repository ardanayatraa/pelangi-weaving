<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Pelanggan;

class PelangganSeeder extends Seeder
{
    public function run(): void
    {
        $pelanggan = [
            [
                'nama' => 'Budi Santoso',
                'email' => 'budi@gmail.com',
                'password' => Hash::make('customer123'),
                'alamat' => 'Jl. Sunset Road No. 123, Kuta, Badung, Bali',
                'telepon' => '081234567891',
                'id_kota' => 114, // Badung
                'id_provinsi' => 1, // Bali
                'kode_pos' => '80361',
            ],
            [
                'nama' => 'Siti Nurhaliza',
                'email' => 'siti@gmail.com',
                'password' => Hash::make('customer123'),
                'alamat' => 'Jl. Teuku Umar No. 45, Denpasar, Bali',
                'telepon' => '081234567892',
                'id_kota' => 114, // Denpasar
                'id_provinsi' => 1, // Bali
                'kode_pos' => '80114',
            ],
            [
                'nama' => 'Agung Wijaya',
                'email' => 'agung@gmail.com',
                'password' => Hash::make('customer123'),
                'alamat' => 'Jl. Raya Ubud No. 88, Gianyar, Bali',
                'telepon' => '081234567893',
                'id_kota' => 115, // Gianyar
                'id_provinsi' => 1, // Bali
                'kode_pos' => '80571',
            ],
            [
                'nama' => 'Dewi Lestari',
                'email' => 'dewi@gmail.com',
                'password' => Hash::make('customer123'),
                'alamat' => 'Jl. Raya Sanur No. 200, Denpasar, Bali',
                'telepon' => '081234567894',
                'id_kota' => 114, // Denpasar
                'id_provinsi' => 1, // Bali
                'kode_pos' => '80228',
            ],
        ];

        foreach ($pelanggan as $data) {
            $exists = Pelanggan::where('email', $data['email'])->exists();
            if (!$exists) {
                Pelanggan::create($data);
                echo "Pelanggan {$data['nama']} berhasil ditambahkan!\n";
            } else {
                echo "Pelanggan {$data['nama']} sudah ada, dilewati.\n";
            }
        }

        echo "Seeder Pelanggan selesai!\n";
        echo "   Email: budi@gmail.com | Password: customer123\n";
        echo "   Email: siti@gmail.com | Password: customer123\n";
        echo "   Email: agung@gmail.com | Password: customer123\n";
        echo "   Email: dewi@gmail.com | Password: customer123\n";
    }
}
