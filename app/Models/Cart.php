<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    protected $table = 'keranjang';
    protected $primaryKey = 'id_keranjang';

    protected $fillable = [
        'id_pelanggan',
        'id_produk',
        'id_varian',
        'jumlah',
    ];

    protected $casts = [
        'jumlah' => 'integer',
    ];

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'id_produk', 'id_produk');
    }

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'id_varian', 'id_varian');
    }

    public function getSubtotalAttribute(): float
    {
        $harga = $this->productVariant ? $this->productVariant->harga : $this->product->harga;
        return $this->jumlah * $harga;
    }
}
