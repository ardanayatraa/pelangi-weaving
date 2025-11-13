<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengiriman', function (Blueprint $table) {
            $table->bigIncrements('id_pengiriman');
            $table->unsignedBigInteger('id_pesanan')->unique();
            $table->integer('id_kota_asal')->nullable();
            $table->integer('id_kota_tujuan')->nullable();
            $table->string('kurir', 50)->nullable();
            $table->string('layanan', 50)->nullable();
            $table->decimal('ongkir', 12, 2)->default(0);
            $table->string('estimasi_pengiriman', 50)->nullable();
            $table->text('alamat_pengiriman')->nullable();
            $table->string('no_resi', 100)->nullable();
            $table->string('status_pengiriman', 30)->default('menunggu'); // menunggu / dalam_perjalanan / sampai
            $table->dateTime('tanggal_kirim')->nullable();
            $table->dateTime('tanggal_terima')->nullable();

            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanan')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengiriman');
    }
};
