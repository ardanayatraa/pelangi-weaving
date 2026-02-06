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
        Schema::table('pelanggan', function (Blueprint $table) {
            // Hapus field yang tidak digunakan di sistem
            $table->dropColumn([
                'tanggal_lahir',
                'jenis_kelamin',
                'email_verified',
                'email_verified_at',
                'points'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelanggan', function (Blueprint $table) {
            // Kembalikan field jika rollback
            $table->date('tanggal_lahir')->nullable()->after('whatsapp');
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('tanggal_lahir');
            $table->boolean('email_verified')->default(false)->after('jenis_kelamin');
            $table->timestamp('email_verified_at')->nullable()->after('email_verified');
            $table->integer('points')->default(0)->after('email_verified_at');
        });
    }
};
