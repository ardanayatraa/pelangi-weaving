<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class VarianProduk extends Model
{
    protected $table = 'varian_produk';
    protected $primaryKey = 'id_varian';

    protected $fillable = [
        'id_produk',
        'nama_varian',
        'kode_varian',
        'gambar_varian',
        'harga',
        'stok',
        'berat',
        'warna',
        'ukuran',
        'jenis_benang',
        'status',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'berat' => 'decimal:2',
        'stok' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    /**
     * Gambar varian disimpan di kolom gambar_varian (gambar produk sudah digabung ke varian).
     */
    public function getPrimaryImage(): string
    {
        return $this->gambar_varian ?? '';
    }

    public function isInStock(): bool
    {
        return $this->stok > 0;
    }
}
