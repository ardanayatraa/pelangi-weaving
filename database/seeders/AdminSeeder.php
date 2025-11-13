<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'nama' => 'Admin Pelangi Weaving',
            'email' => 'admin@pelangiweaving.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        Admin::create([
            'nama' => 'Owner Pelangi Weaving',
            'email' => 'owner@pelangiweaving.com',
            'password' => Hash::make('owner123'),
            'role' => 'owner',
        ]);

        echo "✅ Admin berhasil di-seed!\n";
        echo "   Email Admin: admin@pelangiweaving.com | Password: admin123\n";
        echo "   Email Owner: owner@pelangiweaving.com | Password: owner123\n";
    }
}
