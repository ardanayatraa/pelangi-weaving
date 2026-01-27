<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropColumn([
                'midtrans_order_id',
                'snap_token',
                'tipe_pembayaran',
                'status_pembayaran',
                'waktu_transaksi',
                'waktu_settlement',
                'fraud_status'
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->string('midtrans_order_id', 100)->nullable()->after('id_pesanan');
            $table->string('snap_token', 100)->nullable()->after('midtrans_order_id');
            $table->string('tipe_pembayaran', 50)->nullable()->after('snap_token');
            $table->string('status_pembayaran', 20)->default('unpaid')->after('tipe_pembayaran');
            $table->dateTime('waktu_transaksi')->nullable()->after('status_pembayaran');
            $table->dateTime('waktu_settlement')->nullable()->after('waktu_transaksi');
            $table->string('fraud_status', 50)->nullable()->after('waktu_settlement');
        });
    }
};