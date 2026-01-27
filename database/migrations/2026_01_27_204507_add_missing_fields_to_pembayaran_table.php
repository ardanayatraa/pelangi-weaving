<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->decimal('jumlah_bayar', 10, 2)->default(0)->after('status_bayar');
            $table->string('transfer_receipt', 255)->nullable()->after('jumlah_bayar');
            $table->string('nomor_rekening', 50)->nullable()->after('transfer_receipt');
            $table->datetime('tanggal_bayar')->nullable()->after('nomor_rekening');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropColumn(['jumlah_bayar', 'transfer_receipt', 'nomor_rekening', 'tanggal_bayar']);
        });
    }
};