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
        'midtrans_order_id',
        'snap_token',
        'tipe_pembayaran',
        'status_pembayaran',
        'waktu_transaksi',
        'waktu_settlement',
        'fraud_status',
    ];

    protected $casts = [
        'waktu_transaksi' => 'datetime',
        'waktu_settlement' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function isPaid(): bool
    {
        return $this->status_pembayaran === 'paid';
    }

    public function isPending(): bool
    {
        return $this->status_pembayaran === 'pending';
    }

    public function isUnpaid(): bool
    {
        return $this->status_pembayaran === 'unpaid';
    }
}
