<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GambarProduk extends Model
{
    protected $table = 'gambar_produk';
    protected $primaryKey = 'id_gambar';
    public $timestamps = false;

    protected $fillable = [
        'id_produk',
        'id_varian',
        'path',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(VarianProduk::class, 'id_varian', 'id_varian');
    }

    // Methods sesuai class diagram
    public function getImageUrl(): string
    {
        return asset('storage/' . $this->path);
    }
}
