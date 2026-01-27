<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add fields to admin table sesuai class diagram
        Schema::table('admin', function (Blueprint $table) {
            $table->boolean('is_owner')->default(false)->after('role');
            $table->boolean('can_manage_products')->default(false)->after('is_owner');
        });

        // Add fields to pelanggan table sesuai class diagram  
        Schema::table('pelanggan', function (Blueprint $table) {
            $table->string('whatsapp', 20)->nullable()->after('telepon');
            $table->date('tanggal_lahir')->nullable()->after('whatsapp');
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('tanggal_lahir');
            $table->boolean('email_verified')->default(false)->after('jenis_kelamin');
            $table->timestamp('email_verified_at')->nullable()->after('email_verified');
            $table->integer('points')->default(0)->after('email_verified_at');
        });

        // Add custom order relation to pesanan table
        Schema::table('pesanan', function (Blueprint $table) {
            $table->unsignedBigInteger('id_custom_order')->nullable()->after('id_pelanggan');
            
            $table->foreign('id_custom_order')->references('id_custom_order')->on('custom_orders')->onDelete('set null');
        });

        // Add fields to pembayaran table sesuai class diagram
        Schema::table('pembayaran', function (Blueprint $table) {
            // Field jumlah_bayar, transfer_receipt, nomor_rekening, status_bayar, tanggal_bayar
            // sudah ditambahkan di migration sebelumnya
        });

        // Add relation to produk table sesuai class diagram
        Schema::table('produk', function (Blueprint $table) {
            $table->unsignedBigInteger('id_jenis')->nullable()->after('id_kategori');
            $table->integer('views')->default(0)->after('status');
            $table->decimal('rating', 3, 2)->default(0)->after('views');
            
            $table->foreign('id_jenis')->references('id_jenis')->on('jenis')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('admin', function (Blueprint $table) {
            $table->dropColumn(['is_owner', 'can_manage_products']);
        });

        Schema::table('pelanggan', function (Blueprint $table) {
            $table->dropColumn(['whatsapp', 'tanggal_lahir', 'jenis_kelamin', 'email_verified', 'email_verified_at', 'points']);
        });

        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropForeign(['id_custom_order']);
            $table->dropColumn(['id_custom_order']);
        });

        Schema::table('pembayaran', function (Blueprint $table) {
            // Field akan dihapus di migration terpisah
        });

        Schema::table('produk', function (Blueprint $table) {
            $table->dropForeign(['id_jenis']);
            $table->dropColumn(['id_jenis', 'views', 'rating']);
        });
    }
};