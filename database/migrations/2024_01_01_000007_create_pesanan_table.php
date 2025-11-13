<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->bigIncrements('id_pesanan');
            $table->unsignedBigInteger('id_pelanggan');
            $table->string('nomor_invoice', 50)->unique();
            $table->dateTime('tanggal_pesanan');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('ongkir', 12, 2)->default(0);
            $table->decimal('total_bayar', 12, 2);
            $table->string('status_pesanan', 20)->default('baru'); // baru / diproses / dikirim / selesai / batal
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggan')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
