<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->bigIncrements('id_produk');
            $table->unsignedBigInteger('id_kategori');
            $table->string('nama_produk', 150);
            $table->string('slug', 150)->unique();
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 12, 2)->default(0); // harga produk
            $table->integer('stok')->default(0); // stok produk
            $table->decimal('berat', 6, 2)->default(0); // dalam gram
            $table->string('status', 20)->default('aktif'); // aktif / nonaktif
            $table->timestamps();

            $table->foreign('id_kategori')->references('id_kategori')->on('kategori')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
