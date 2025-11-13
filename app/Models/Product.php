<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'id_produk';

    protected $fillable = [
        'id_kategori',
        'nama_produk',
        'slug',
        'deskripsi',
        'harga',
        'stok',
        'berat',
        'status',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'berat' => 'decimal:2',
        'stok' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'id_kategori', 'id_kategori');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'id_produk', 'id_produk');
    }

    public function activeVariants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'id_produk', 'id_produk')
            ->where('status', 'tersedia');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'id_produk', 'id_produk');
    }

    /**
     * Get price range for products with variants
     * Returns array with 'min' and 'max' keys
     */
    public function getPriceRange()
    {
        $variants = $this->activeVariants;
        
        if ($variants->isEmpty()) {
            return null;
        }

        $prices = $variants->pluck('harga');
        
        return [
            'min' => $prices->min(),
            'max' => $prices->max(),
        ];
    }

    /**
     * Check if product has variants
     */
    public function hasVariants(): bool
    {
        return $this->activeVariants()->exists();
    }

    /**
     * Get formatted price display
     * Shows range if has variants, single price otherwise
     */
    public function getFormattedPrice(): string
    {
        if ($this->hasVariants()) {
            $range = $this->getPriceRange();
            
            if ($range && $range['min'] != $range['max']) {
                return 'Rp ' . number_format($range['min'], 0, ',', '.') . ' - Rp ' . number_format($range['max'], 0, ',', '.');
            }
            
            // Jika semua varian harga sama
            return 'Rp ' . number_format($range['min'], 0, ',', '.');
        }
        
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }
}
