<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->bigIncrements('id_pembayaran');
            $table->unsignedBigInteger('id_pesanan')->unique()->nullable();
            $table->unsignedBigInteger('id_custom_order')->unique()->nullable();
            $table->string('midtrans_order_id', 100)->nullable();
            $table->string('snap_token', 100)->nullable();
            $table->string('tipe_pembayaran', 50)->nullable();
            $table->string('status_pembayaran', 20)->default('unpaid'); // unpaid / pending / paid / cancel / expire / failure
            $table->string('status_bayar', 20)->nullable();
            $table->dateTime('waktu_transaksi')->nullable();
            $table->dateTime('waktu_settlement')->nullable();
            $table->string('fraud_status', 50)->nullable();
            $table->timestamps();

            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanan')->onDelete('cascade');
            $table->foreign('id_custom_order')->references('id_custom_order')->on('custom_orders')->onDelete('cascade');
            
            $table->index('midtrans_order_id');
            $table->index('status_pembayaran');
            $table->index('id_pesanan');
            $table->index('id_custom_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
