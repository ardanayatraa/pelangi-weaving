<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            [
                'nama' => 'Super Admin',
                'email' => 'admin@pelangiweaving.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_owner' => true,
                'can_manage_products' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Admin Staff',
                'email' => 'staff@pelangiweaving.com',
                'password' => Hash::make('staff123'),
                'role' => 'admin',
                'is_owner' => false,
                'can_manage_products' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($admins as $admin) {
            $exists = DB::table('admin')->where('email', $admin['email'])->exists();
            if (!$exists) {
                DB::table('admin')->insert($admin);
                echo "Admin {$admin['nama']} berhasil ditambahkan!\n";
            } else {
                echo "Admin {$admin['nama']} sudah ada, dilewati.\n";
            }
        }
    }
}