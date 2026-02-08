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
        'alamat_default_index',
        'telepon',
        'whatsapp',
        'id_kota',
        'id_provinsi',
        'kode_pos',
    ];

    protected $casts = [
        'password' => 'hashed',
        'alamat' => 'array',
        'alamat_default_index' => 'integer',
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

    // Helper methods untuk multiple alamat
    public function getAlamatList(): array
    {
        $alamat = $this->alamat;
        return is_array($alamat) ? $alamat : [];
    }

    public function getDefaultAlamat(): ?array
    {
        $list = $this->getAlamatList();
        $index = $this->alamat_default_index ?? 0;
        return $list[$index] ?? null;
    }

    public function addAlamat(array $alamat): void
    {
        $list = $this->getAlamatList();
        
        // Jika ini alamat pertama, set sebagai default
        if (empty($list)) {
            $this->alamat_default_index = 0;
        }
        
        $list[] = $alamat;
        $this->alamat = $list;
        $this->save();
    }

    public function updateAlamat(int $index, array $alamat): void
    {
        $list = $this->getAlamatList();
        if (isset($list[$index])) {
            $list[$index] = $alamat;
            $this->alamat = $list;
            $this->save();
        }
    }

    public function deleteAlamat(int $index): void
    {
        $list = $this->getAlamatList();
        if (isset($list[$index])) {
            unset($list[$index]);
            $list = array_values($list); // Re-index array
            
            // Adjust default index if needed
            if ($this->alamat_default_index >= count($list)) {
                $this->alamat_default_index = max(0, count($list) - 1);
            }
            
            $this->alamat = $list;
            $this->save();
        }
    }

    public function setDefaultAlamat(int $index): void
    {
        $list = $this->getAlamatList();
        if (isset($list[$index])) {
            $this->alamat_default_index = $index;
            $this->save();
        }
    }

    // Route Key Name
    public function getRouteKeyName()
    {
        return 'id_pelanggan';
    }
}
