<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('varian_produk', function (Blueprint $table) {
            $table->bigIncrements('id_varian');
            $table->unsignedBigInteger('id_produk');
            $table->string('nama_varian', 100);
            $table->string('kode_varian', 50)->unique();
            $table->string('gambar_varian', 255)->nullable();
            $table->decimal('harga', 12, 2);
            $table->integer('stok')->default(0);
            $table->decimal('berat', 6, 2)->nullable(); // dalam kg
            $table->string('warna', 50)->nullable();
            $table->string('ukuran', 50)->nullable();
            $table->string('jenis_benang', 50)->nullable();
            $table->string('status', 20)->default('tersedia'); // tersedia / habis
            $table->timestamps();

            $table->foreign('id_produk')->references('id_produk')->on('produk')->onDelete('cascade');
            
            $table->index('kode_varian');
            $table->index('id_produk');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('varian_produk');
    }
};
