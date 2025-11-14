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
        Schema::table('produk', function (Blueprint $table) {
            $table->boolean('is_made_to_order')->default(false)->after('status');
            $table->integer('lead_time_days')->nullable()->after('is_made_to_order')->comment('Waktu pengerjaan dalam hari');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->dropColumn(['is_made_to_order', 'lead_time_days']);
        });
    }
};
