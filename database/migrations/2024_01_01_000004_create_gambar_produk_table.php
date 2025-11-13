<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gambar_produk', function (Blueprint $table) {
            $table->bigIncrements('id_gambar');
            $table->unsignedBigInteger('id_produk');
            $table->unsignedBigInteger('id_varian')->nullable();
            $table->string('path', 255);
            $table->boolean('is_primary')->default(false);
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('id_produk')->references('id_produk')->on('produk')->onDelete('cascade');
            $table->foreign('id_varian')->references('id_varian')->on('varian_produk')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gambar_produk');
    }
};
