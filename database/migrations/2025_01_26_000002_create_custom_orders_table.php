<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_orders', function (Blueprint $table) {
            $table->bigIncrements('id_custom_order');
            $table->unsignedBigInteger('id_pelanggan');
            $table->unsignedBigInteger('id_jenis');
            $table->string('nomor_custom_order', 50)->unique();
            $table->string('nama_custom', 200);
            $table->text('deskripsi_custom');
            $table->integer('jumlah')->default(1);
            $table->decimal('harga_final', 12, 2)->default(0);
            $table->decimal('dp_amount', 12, 2)->default(0);
            $table->string('status', 30)->default('draft');
            $table->text('catatan_pelanggan')->nullable();
            $table->json('gambar_referensi')->nullable();
            $table->string('midtrans_order_id', 100)->nullable();
            $table->dateTime('dp_paid_at')->nullable();
            $table->dateTime('fully_paid_at')->nullable();
            $table->text('payment_response')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->json('progress_history')->nullable();
            $table->timestamps();

            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggan')->onDelete('restrict');
            $table->foreign('id_jenis')->references('id_jenis')->on('jenis')->onDelete('restrict');
            $table->foreign('updated_by')->references('id_admin')->on('admin')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_orders');
    }
};