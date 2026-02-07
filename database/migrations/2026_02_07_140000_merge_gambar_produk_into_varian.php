<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('gambar_produk')) {
            return;
        }

        // Pindahkan path gambar ke varian_produk.gambar_varian
        $rows = DB::table('gambar_produk')->orderBy('is_primary', 'desc')->orderBy('id_gambar')->get();

        foreach ($rows as $row) {
            if ($row->id_varian) {
                // Gambar sudah punya varian: set gambar_varian jika belum ada
                $v = DB::table('varian_produk')->where('id_varian', $row->id_varian)->first();
                if ($v && empty($v->gambar_varian)) {
                    DB::table('varian_produk')->where('id_varian', $row->id_varian)->update(['gambar_varian' => $row->path]);
                }
            } else {
                // Gambar level produk: assign ke varian pertama produk
                $firstVariant = DB::table('varian_produk')
                    ->where('id_produk', $row->id_produk)
                    ->orderBy('id_varian')
                    ->first();
                if ($firstVariant && empty($firstVariant->gambar_varian)) {
                    DB::table('varian_produk')->where('id_varian', $firstVariant->id_varian)->update(['gambar_varian' => $row->path]);
                }
            }
        }

        Schema::dropIfExists('gambar_produk');
    }

    public function down(): void
    {
        Schema::create('gambar_produk', function (Blueprint $table) {
            $table->bigIncrements('id_gambar');
            $table->unsignedBigInteger('id_produk');
            $table->unsignedBigInteger('id_varian')->nullable();
            $table->string('path', 255);
            $table->boolean('is_primary')->default(false);
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('id_produk')->references('id_produk')->on('produk')->onDelete('cascade');
            $table->foreign('id_varian')->references('id_varian')->on('varian_produk')->onDelete('cascade');
        });

        // Isi ulang dari varian_produk.gambar_varian (satu row per varian yang punya gambar)
        $variants = DB::table('varian_produk')->whereNotNull('gambar_varian')->where('gambar_varian', '!=', '')->get();
        foreach ($variants as $v) {
            DB::table('gambar_produk')->insert([
                'id_produk' => $v->id_produk,
                'id_varian' => $v->id_varian,
                'path' => $v->gambar_varian,
                'is_primary' => true,
                'created_at' => now(),
            ]);
        }
    }
};
