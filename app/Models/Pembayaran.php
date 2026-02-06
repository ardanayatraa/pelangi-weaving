<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'id_pesanan',
        'id_custom_order',
        'jumlah_bayar',
        'transfer_receipt',
        'nomor_rekening',
        'status_bayar',
        'tanggal_bayar',
        'snap_token',
    ];

    protected $casts = [
        'tanggal_bayar' => 'datetime',
        'jumlah_bayar' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function customOrder(): BelongsTo
    {
        return $this->belongsTo(CustomOrder::class, 'id_custom_order', 'id_custom_order');
    }

    // Methods sesuai class diagram
    public function isPaid(): bool
    {
        return $this->status_bayar === 'paid';
    }

    public function canBeRefunded(): bool
    {
        return $this->isPaid() && $this->tanggal_bayar > now()->subDays(7);
    }
}
