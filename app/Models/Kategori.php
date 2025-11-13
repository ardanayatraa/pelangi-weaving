<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';

    protected $fillable = [
        'nama_kategori',
        'slug',
        'deskripsi',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Produk::class, 'id_kategori', 'id_kategori');
    }

    public function activeProducts(): HasMany
    {
        return $this->hasMany(Produk::class, 'id_kategori', 'id_kategori')
            ->where('status', 'aktif');
    }
}
