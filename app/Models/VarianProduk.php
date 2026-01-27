<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function images(): HasMany
    {
        return $this->hasMany(GambarProduk::class, 'id_varian', 'id_varian');
    }

    // Methods sesuai class diagram
    public function isInStock(): bool
    {
        return $this->stok > 0;
    }

    public function getPrimaryImage(): string
    {
        return $this->gambar_varian ?? $this->product->images()->where('is_primary', true)->first()?->path ?? '';
    }
}
