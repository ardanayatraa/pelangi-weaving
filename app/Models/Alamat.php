<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    protected $table = 'alamat';

    protected $fillable = [
        'id_pelanggan',
        'label',
        'nama_penerima',
        'telepon',
        'alamat_lengkap',
        'kota',
        'provinsi',
        'kode_pos',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }
}
