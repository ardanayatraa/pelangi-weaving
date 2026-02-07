<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tasCustom = DB::table('jenis')->where('slug', 'tas-custom')->first();
        if (!$tasCustom) {
            return;
        }

        $idTasCustom = $tasCustom->id_jenis;

        // Alihkan custom_orders yang pakai Tas Custom ke jenis pertama lain (Rajutan Custom)
        $otherJenis = DB::table('jenis')->where('id_jenis', '!=', $idTasCustom)->orderBy('id_jenis')->first();
        if ($otherJenis) {
            DB::table('custom_orders')->where('id_jenis', $idTasCustom)->update(['id_jenis' => $otherJenis->id_jenis]);
        }

        DB::table('jenis')->where('slug', 'tas-custom')->delete();
    }

    public function down(): void
    {
        DB::table('jenis')->insert([
            'nama_jenis' => 'Tas Custom',
            'slug' => 'tas-custom',
            'deskripsi' => 'Pembuatan tas dengan desain dan ukuran sesuai permintaan customer',
            'icon' => 'bi bi-bag',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
};
