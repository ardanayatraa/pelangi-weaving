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
            $table->unsignedBigInteger('id_jenis')->nullable();
            $table->string('nama_produk', 150);
            $table->string('slug', 150)->unique();
            $table->text('deskripsi')->nullable();
            $table->decimal('berat', 6, 2)->default(0); // dalam gram
            $table->string('status', 20)->default('aktif'); // aktif / nonaktif
            $table->boolean('is_made_to_order')->default(false);
            $table->integer('views')->default(0);
            $table->timestamps();

            $table->foreign('id_kategori')->references('id_kategori')->on('kategori')->onDelete('restrict');
            $table->foreign('id_jenis')->references('id_jenis')->on('jenis')->onDelete('set null');
            
            $table->index('slug');
            $table->index('status');
            $table->index('id_kategori');
            $table->index('id_jenis');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
