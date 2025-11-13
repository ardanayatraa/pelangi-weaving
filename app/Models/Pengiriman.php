<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengiriman extends Model
{
    protected $table = 'pengiriman';
    protected $primaryKey = 'id_pengiriman';
    public $timestamps = false;
    
    protected $fillable = [
        'id_pesanan',
        'id_kota_asal',
        'id_kota_tujuan',
        'kurir',
        'layanan',
        'ongkir',
        'estimasi_pengiriman',
        'alamat_pengiriman',
        'no_resi',
        'status_pengiriman',
        'tanggal_kirim',
        'tanggal_terima',
    ];

    protected $casts = [
        'ongkir' => 'decimal:2',
        'tanggal_kirim' => 'datetime',
        'tanggal_terima' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function isSampai(): bool
    {
        return $this->status_pengiriman === 'sampai';
    }

    public function isDalamPerjalanan(): bool
    {
        return $this->status_pengiriman === 'dalam_perjalanan';
    }
}
