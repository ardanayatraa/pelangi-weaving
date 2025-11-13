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
        'id_kota',
        'id_provinsi',
        'kode_pos',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'id_pelanggan', 'id_pelanggan');
    }
}
