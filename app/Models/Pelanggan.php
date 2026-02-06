<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pelanggan extends Authenticatable
{
    use Notifiable;

    protected $table = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'alamat',
        'telepon',
        'whatsapp',
        'id_kota',
        'id_provinsi',
        'kode_pos',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function carts(): HasMany
    {
        return $this->hasMany(Keranjang::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function customOrders(): HasMany
    {
        return $this->hasMany(CustomOrder::class, 'id_pelanggan', 'id_pelanggan');
    }

    // Methods sesuai class diagram
    public function getCartTotal(): decimal
    {
        return $this->carts()->sum('jumlah');
    }

    // Route Key Name
    public function getRouteKeyName()
    {
        return 'id_pelanggan';
    }
}
