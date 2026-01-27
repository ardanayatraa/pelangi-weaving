<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jenis extends Model
{
    protected $table = 'jenis';
    protected $primaryKey = 'id_jenis';
    
    protected $fillable = [
        'nama_jenis',
        'slug',
        'deskripsi',
        'icon',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function customOrders(): HasMany
    {
        return $this->hasMany(CustomOrder::class, 'id_jenis', 'id_jenis');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Produk::class, 'id_jenis', 'id_jenis');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Route Key Name
    public function getRouteKeyName()
    {
        return 'id_jenis';
    }

    // Accessors
    public function getActiveCustomOrdersCountAttribute()
    {
        return $this->customOrders()->whereNotIn('status', ['completed', 'cancelled'])->count();
    }
}