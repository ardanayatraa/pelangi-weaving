<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin Account
        User::create([
            'name' => 'Admin Pelangi',
            'email' => 'admin@pelangi.com',
            'password' => Hash::make('admin123'),
            'phone' => '081234567890',
            'alamat' => 'Jl. Raya Bebandem, Karangasem, Bali',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Customer Account 1
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('customer123'),
            'phone' => '081234567891',
            'alamat' => 'Jl. Sunset Road No. 123, Kuta, Badung, Bali',
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        // Customer Account 2
        User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@gmail.com',
            'password' => Hash::make('customer123'),
            'phone' => '081234567892',
            'alamat' => 'Jl. Teuku Umar No. 45, Denpasar, Bali',
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        // Customer Account 3
        User::create([
            'name' => 'Agung Wijaya',
            'email' => 'agung@gmail.com',
            'password' => Hash::make('customer123'),
            'phone' => '081234567893',
            'alamat' => 'Jl. Raya Ubud No. 88, Gianyar, Bali',
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        echo "Users seeded successfully!\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "AKUN TESTING:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        
        echo "ADMIN:\n";
        echo "   Email    : admin@pelangi.com\n";
        echo "   Password : admin123\n";
        echo "   URL      : /admin/dashboard\n\n";
        
        echo "CUSTOMER 1:\n";
        echo "   Email    : budi@gmail.com\n";
        echo "   Password : customer123\n\n";
        
        echo "CUSTOMER 2:\n";
        echo "   Email    : siti@gmail.com\n";
        echo "   Password : customer123\n\n";
        
        echo "CUSTOMER 3:\n";
        echo "   Email    : agung@gmail.com\n";
        echo "   Password : customer123\n\n";
        
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    }
}
