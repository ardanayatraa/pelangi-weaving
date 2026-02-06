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
            // Add id_custom_order field (nullable, karena pembayaran bisa untuk pesanan atau custom order)
            $table->unsignedBigInteger('id_custom_order')->nullable()->after('id_pesanan');
            
            // Add foreign key
            $table->foreign('id_custom_order')
                  ->references('id_custom_order')
                  ->on('custom_orders')
                  ->onDelete('cascade');
            
            // Make id_pesanan nullable (karena sekarang bisa pembayaran pesanan atau custom order)
            $table->unsignedBigInteger('id_pesanan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            // Drop foreign key and column
            $table->dropForeign(['id_custom_order']);
            $table->dropColumn('id_custom_order');
            
            // Revert id_pesanan to not nullable
            $table->unsignedBigInteger('id_pesanan')->nullable(false)->change();
        });
    }
};
