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
                'alamat' => [
                    [
                        'label' => 'rumah',
                        'nama_penerima' => 'Budi Santoso',
                        'telepon' => '081234567891',
                        'alamat_lengkap' => 'Jl. Sunset Road No. 123, Kuta',
                        'kota' => 'Badung',
                        'provinsi' => 'Bali',
                        'kode_pos' => '80361',
                    ],
                    [
                        'label' => 'kantor',
                        'nama_penerima' => 'Budi Santoso',
                        'telepon' => '081234567891',
                        'alamat_lengkap' => 'Jl. Raya Kuta No. 88, Kuta',
                        'kota' => 'Badung',
                        'provinsi' => 'Bali',
                        'kode_pos' => '80361',
                    ],
                ],
                'alamat_default_index' => 0,
                'telepon' => '081234567891',
                'whatsapp' => '081234567891',
                'id_kota' => 114,
                'id_provinsi' => 1,
                'kode_pos' => '80361',
            ],
            [
                'nama' => 'Siti Nurhaliza',
                'email' => 'siti@gmail.com',
                'password' => Hash::make('customer123'),
                'alamat' => [
                    [
                        'label' => 'rumah',
                        'nama_penerima' => 'Siti Nurhaliza',
                        'telepon' => '081234567892',
                        'alamat_lengkap' => 'Jl. Teuku Umar No. 45, Denpasar',
                        'kota' => 'Denpasar',
                        'provinsi' => 'Bali',
                        'kode_pos' => '80114',
                    ],
                ],
                'alamat_default_index' => 0,
                'telepon' => '081234567892',
                'whatsapp' => '081234567892',
                'id_kota' => 114,
                'id_provinsi' => 1,
                'kode_pos' => '80114',
            ],
            [
                'nama' => 'Agung Wijaya',
                'email' => 'agung@gmail.com',
                'password' => Hash::make('customer123'),
                'alamat' => [
                    [
                        'label' => 'rumah',
                        'nama_penerima' => 'Agung Wijaya',
                        'telepon' => '081234567893',
                        'alamat_lengkap' => 'Jl. Raya Ubud No. 88, Ubud',
                        'kota' => 'Gianyar',
                        'provinsi' => 'Bali',
                        'kode_pos' => '80571',
                    ],
                    [
                        'label' => 'kantor',
                        'nama_penerima' => 'Agung Wijaya',
                        'telepon' => '081234567893',
                        'alamat_lengkap' => 'Jl. Monkey Forest, Ubud',
                        'kota' => 'Gianyar',
                        'provinsi' => 'Bali',
                        'kode_pos' => '80571',
                    ],
                    [
                        'label' => 'lainnya',
                        'nama_penerima' => 'Wayan (Saudara)',
                        'telepon' => '081234567899',
                        'alamat_lengkap' => 'Jl. Raya Tegallalang No. 10',
                        'kota' => 'Gianyar',
                        'provinsi' => 'Bali',
                        'kode_pos' => '80561',
                    ],
                ],
                'alamat_default_index' => 0,
                'telepon' => '081234567893',
                'whatsapp' => '081234567893',
                'id_kota' => 115,
                'id_provinsi' => 1,
                'kode_pos' => '80571',
            ],
            [
                'nama' => 'Dewi Lestari',
                'email' => 'dewi@gmail.com',
                'password' => Hash::make('customer123'),
                'alamat' => [
                    [
                        'label' => 'rumah',
                        'nama_penerima' => 'Dewi Lestari',
                        'telepon' => '081234567894',
                        'alamat_lengkap' => 'Jl. Raya Sanur No. 200, Sanur',
                        'kota' => 'Denpasar',
                        'provinsi' => 'Bali',
                        'kode_pos' => '80228',
                    ],
                ],
                'alamat_default_index' => 0,
                'telepon' => '081234567894',
                'whatsapp' => '081234567894',
                'id_kota' => 114,
                'id_provinsi' => 1,
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
