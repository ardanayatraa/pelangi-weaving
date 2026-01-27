<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produk extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'id_produk';

    protected $fillable = [
        'id_kategori',
        'id_jenis',
        'nama_produk',
        'slug',
        'deskripsi',
        'berat',
        'status',
        'is_made_to_order',
        'lead_time_days',
        'views',
        'rating',
    ];

    protected $casts = [
        'berat' => 'decimal:2',
        'rating' => 'decimal:2',
        'views' => 'integer',
        'is_made_to_order' => 'boolean',
        'lead_time_days' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function jenis(): BelongsTo
    {
        return $this->belongsTo(Jenis::class, 'id_jenis', 'id_jenis');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(VarianProduk::class, 'id_produk', 'id_produk');
    }

    public function activeVariants(): HasMany
    {
        return $this->hasMany(VarianProduk::class, 'id_produk', 'id_produk')
            ->where('status', 'tersedia');
    }

    public function images(): HasMany
    {
        return $this->hasMany(GambarProduk::class, 'id_produk', 'id_produk')
            ->orderBy('is_primary', 'desc');
    }

    public function primaryImage()
    {
        return $this->hasOne(GambarProduk::class, 'id_produk', 'id_produk')
            ->where('is_primary', true);
    }

    // Methods sesuai class diagram
    public function hasVariants(): bool
    {
        return $this->activeVariants()->exists();
    }

    public function getTotalStock(): int
    {
        // Stok hanya ada di varian, bukan di produk utama
        return $this->activeVariants()->sum('stok');
    }

    /**
     * Accessor untuk mendapatkan stok total (backward compatibility)
     */
    public function getStokAttribute(): int
    {
        return $this->getTotalStock();
    }

    /**
     * Accessor untuk mendapatkan harga terendah (backward compatibility)
     */
    public function getHargaAttribute()
    {
        return $this->activeVariants()->min('harga') ?? 0;
    }

    /**
     * Helper untuk menampilkan harga yang diformat
     * Jika ada range harga, tampilkan range
     */
    public function getFormattedPrice(): string
    {
        $min = $this->activeVariants()->min('harga');
        $max = $this->activeVariants()->max('harga');

        if (!$min) return 'Rp 0';

        if ($min == $max) {
            return 'Rp ' . number_format($min, 0, ',', '.');
        }

        return 'Rp ' . number_format($min, 0, ',', '.') . ' - ' . number_format($max, 0, ',', '.');
    }

    /**
     * Generate SKU untuk produk
     */
    public function getSku(): string
    {
        $categoryCode = strtoupper(substr($this->category->nama_kategori ?? 'PRD', 0, 3));
        return $categoryCode . '-' . str_pad($this->id_produk, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Accessor untuk SKU
     */
    public function getSkuAttribute(): string
    {
        return $this->getSku();
    }

    public function updateStatus(): void
    {
        // Implementation for updating product status
    }
}
