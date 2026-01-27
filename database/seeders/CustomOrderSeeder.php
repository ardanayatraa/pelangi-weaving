<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CustomOrder;
use App\Models\Pelanggan;
use App\Models\Jenis;
use App\Models\Admin;

class CustomOrderSeeder extends Seeder
{
    public function run(): void
    {
        $pelanggan = Pelanggan::first();
        $admin = Admin::first();
        $jenis = Jenis::all();

        if (!$pelanggan || !$admin || $jenis->isEmpty()) {
            echo "Pastikan sudah ada data pelanggan, admin, dan jenis sebelum menjalankan CustomOrderSeeder\n";
            return;
        }

        // Generate unique order numbers with timestamp to avoid duplicates
        $baseDate = now()->format('Ymd');
        $timestamp = now()->format('His');

        $customOrders = [
            [
                'nomor_custom_order' => 'CO' . $baseDate . $timestamp . '001',
                'id_pelanggan' => $pelanggan->id_pelanggan,
                'id_jenis' => $jenis->random()->id_jenis,
                'nama_custom' => 'Selendang Songket Wedding Custom',
                'deskripsi_custom' => 'Selendang songket khusus untuk pernikahan dengan motif bunga mawar dan warna gold dominan. Ukuran 2 x 0.6 meter dengan detail benang emas 24K.',
                'jumlah' => 2,
                'harga_final' => 1500000,
                'dp_amount' => 750000,
                'status' => 'completed',
                'catatan_pelanggan' => 'Untuk acara pernikahan tanggal 15 Februari. Mohon dikerjakan dengan detail yang bagus.',
                'gambar_referensi' => ['custom-orders/ref-1.jpg', 'custom-orders/ref-2.jpg'],
                'dp_paid_at' => now()->subDays(30),
                'fully_paid_at' => now()->subDays(10),
                'updated_by' => $admin->id_admin,
                'progress_history' => [
                    [
                        'status' => 'pending_approval',
                        'catatan' => 'Custom order diterima',
                        'admin' => $admin->nama,
                        'timestamp' => now()->subDays(35)->toISOString()
                    ],
                    [
                        'status' => 'approved',
                        'catatan' => 'Desain disetujui, harga final Rp 1.500.000',
                        'harga_final' => 1500000,
                        'admin' => $admin->nama,
                        'timestamp' => now()->subDays(32)->toISOString()
                    ],
                    [
                        'status' => 'in_production',
                        'catatan' => 'Mulai proses tenun',
                        'admin' => $admin->nama,
                        'timestamp' => now()->subDays(25)->toISOString()
                    ],
                    [
                        'type' => 'progress_update',
                        'note' => 'Progress 50% - motif dasar selesai',
                        'images' => ['custom-orders/progress-1.jpg'],
                        'admin' => $admin->nama,
                        'timestamp' => now()->subDays(15)->toISOString()
                    ],
                    [
                        'status' => 'completed',
                        'catatan' => 'Custom order selesai dan siap dikirim',
                        'admin' => $admin->nama,
                        'timestamp' => now()->subDays(5)->toISOString()
                    ]
                ],
                'created_at' => now()->subDays(35),
                'updated_at' => now()->subDays(5),
            ],
            [
                'nomor_custom_order' => 'CO' . $baseDate . $timestamp . '002',
                'id_pelanggan' => $pelanggan->id_pelanggan,
                'id_jenis' => $jenis->random()->id_jenis,
                'nama_custom' => 'Kain Endek Motif Keluarga',
                'deskripsi_custom' => 'Kain endek dengan motif khusus yang menggambarkan pohon keluarga. Warna dasar hijau dengan aksen kuning dan merah.',
                'jumlah' => 1,
                'harga_final' => 850000,
                'dp_amount' => 425000,
                'status' => 'in_production',
                'catatan_pelanggan' => 'Ini untuk hadiah ulang tahun pernikahan orang tua. Mohon dibuat dengan penuh cinta.',
                'gambar_referensi' => ['custom-orders/ref-3.jpg'],
                'dp_paid_at' => now()->subDays(10),
                'updated_by' => $admin->id_admin,
                'progress_history' => [
                    [
                        'status' => 'pending_approval',
                        'catatan' => 'Custom order diterima',
                        'admin' => $admin->nama,
                        'timestamp' => now()->subDays(15)->toISOString()
                    ],
                    [
                        'status' => 'approved',
                        'catatan' => 'Desain disetujui, harga final Rp 850.000',
                        'harga_final' => 850000,
                        'admin' => $admin->nama,
                        'timestamp' => now()->subDays(12)->toISOString()
                    ],
                    [
                        'status' => 'in_production',
                        'catatan' => 'Mulai proses desain dan tenun',
                        'admin' => $admin->nama,
                        'timestamp' => now()->subDays(8)->toISOString()
                    ],
                    [
                        'type' => 'progress_update',
                        'note' => 'Progress 30% - desain motif selesai, mulai tenun',
                        'images' => ['custom-orders/progress-2.jpg'],
                        'admin' => $admin->nama,
                        'timestamp' => now()->subDays(3)->toISOString()
                    ]
                ],
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(3),
            ],
            [
                'nomor_custom_order' => 'CO' . $baseDate . $timestamp . '003',
                'id_pelanggan' => $pelanggan->id_pelanggan,
                'id_jenis' => $jenis->random()->id_jenis,
                'nama_custom' => 'Songket Modern Fusion',
                'deskripsi_custom' => 'Songket dengan perpaduan motif tradisional dan modern. Warna navy blue dengan aksen silver dan touch warna pastel.',
                'jumlah' => 1,
                'harga_final' => 2200000,
                'dp_amount' => 1100000,
                'status' => 'approved',
                'catatan_pelanggan' => 'Untuk fashion show tema "Traditional Meets Modern". Butuh yang unik dan eye-catching.',
                'gambar_referensi' => ['custom-orders/ref-4.jpg', 'custom-orders/ref-5.jpg', 'custom-orders/ref-6.jpg'],
                'updated_by' => $admin->id_admin,
                'progress_history' => [
                    [
                        'status' => 'pending_approval',
                        'catatan' => 'Custom order diterima',
                        'admin' => $admin->nama,
                        'timestamp' => now()->subDays(7)->toISOString()
                    ],
                    [
                        'status' => 'approved',
                        'catatan' => 'Konsep menarik! Harga final Rp 2.200.000 karena kompleksitas desain',
                        'harga_final' => 2200000,
                        'admin' => $admin->nama,
                        'timestamp' => now()->subDays(5)->toISOString()
                    ]
                ],
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(5),
            ],
            [
                'nomor_custom_order' => 'CO' . $baseDate . $timestamp . '004',
                'id_pelanggan' => $pelanggan->id_pelanggan,
                'id_jenis' => $jenis->random()->id_jenis,
                'nama_custom' => 'Set Kain Keluarga Besar',
                'deskripsi_custom' => 'Set 5 kain dengan motif yang sama tapi variasi warna untuk acara keluarga besar. Motif geometris klasik.',
                'jumlah' => 5,
                'status' => 'pending_approval',
                'catatan_pelanggan' => 'Untuk acara reuni keluarga besar. Butuh 5 kain dengan motif sama tapi warna berbeda: merah, biru, hijau, kuning, ungu.',
                'gambar_referensi' => ['custom-orders/ref-7.jpg'],
                'updated_by' => null,
                'progress_history' => [
                    [
                        'status' => 'pending_approval',
                        'catatan' => 'Custom order diterima, menunggu review admin',
                        'timestamp' => now()->subDays(2)->toISOString()
                    ]
                ],
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'nomor_custom_order' => 'CO' . $baseDate . $timestamp . '005',
                'id_pelanggan' => $pelanggan->id_pelanggan,
                'id_jenis' => $jenis->random()->id_jenis,
                'nama_custom' => 'Selendang Wisuda Spesial',
                'deskripsi_custom' => 'Selendang untuk wisuda dengan nama dan tahun wisuda ditenun dalam kain. Warna sesuai fakultas (biru tua).',
                'jumlah' => 1,
                'status' => 'draft',
                'catatan_pelanggan' => 'Untuk wisuda S2 bulan depan. Ingin ada nama "Dr. Sarah Wijaya" dan tahun "2025" dalam tenun.',
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
        ];

        foreach ($customOrders as $orderData) {
            $exists = CustomOrder::where('nomor_custom_order', $orderData['nomor_custom_order'])->exists();
            if (!$exists) {
                CustomOrder::create($orderData);
                echo "Custom order {$orderData['nomor_custom_order']} berhasil ditambahkan!\n";
            } else {
                echo "Custom order {$orderData['nomor_custom_order']} sudah ada, dilewati.\n";
            }
        }

        echo "Sample custom orders berhasil di-seed! Total: " . count($customOrders) . " custom orders\n";
        echo "CATATAN: Gambar referensi dan progress menggunakan placeholder\n";
        echo "Struktur folder yang dibutuhkan:\n";
        echo "   - storage/app/public/custom-orders/ref-1.jpg sampai ref-7.jpg (gambar referensi)\n";
        echo "   - storage/app/public/custom-orders/progress-1.jpg sampai progress-2.jpg (gambar progress)\n";
    }
}