<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('varian_produk', function (Blueprint $table) {
            $table->string('gambar_varian', 255)->nullable()->after('kode_varian');
        });
    }

    public function down(): void
    {
        Schema::table('varian_produk', function (Blueprint $table) {
            $table->dropColumn('gambar_varian');
        });
    }
};
