<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'id_pesanan';

    protected $fillable = [
        'id_pelanggan',
        'id_custom_order',
        'nomor_invoice',
        'tanggal_pesanan',
        'subtotal',
        'ongkir',
        'total_bayar',
        'status_pesanan',
        'catatan',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'ongkir' => 'decimal:2',
        'total_bayar' => 'decimal:2',
        'tanggal_pesanan' => 'datetime',
    ];

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function customOrder(): BelongsTo
    {
        return $this->belongsTo(CustomOrder::class, 'id_custom_order', 'id_custom_order');
    }

    public function items(): HasMany
    {
        return $this->hasMany(DetailPesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Pembayaran::class, 'id_pesanan', 'id_pesanan');
    }

    public function pengiriman(): HasOne
    {
        return $this->hasOne(Pengiriman::class, 'id_pesanan', 'id_pesanan');
    }

    public function shipping(): HasOne
    {
        return $this->hasOne(Pengiriman::class, 'id_pesanan', 'id_pesanan');
    }

    // Methods sesuai class diagram
    public function canBeCancelled(): bool
    {
        return in_array($this->status_pesanan, ['baru', 'diproses']);
    }
}
