<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->bigIncrements('id_pelanggan');
            $table->string('nama', 100);
            $table->string('email', 100)->unique();
            $table->string('password', 255);
            $table->json('alamat')->nullable(); // JSON untuk multiple alamat
            $table->integer('alamat_default_index')->default(0); // Index alamat default
            $table->string('telepon', 20)->nullable();
            $table->string('whatsapp', 20)->nullable();
            $table->integer('id_kota')->nullable();
            $table->integer('id_provinsi')->nullable();
            $table->string('kode_pos', 10)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggan');
    }
};
