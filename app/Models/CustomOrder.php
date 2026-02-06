<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CustomOrder extends Model
{
    protected $table = 'custom_orders';
    protected $primaryKey = 'id_custom_order';
    
    protected $fillable = [
        'id_pelanggan',
        'id_jenis',
        'nomor_custom_order',
        'nama_custom',
        'deskripsi_custom',
        'jumlah',
        'harga_final',
        'dp_amount',
        'status',
        'catatan_pelanggan',
        'gambar_referensi',
        'dp_paid_at',
        'fully_paid_at',
        'payment_response',
        'updated_by',
        'progress_history'
    ];

    protected $casts = [
        'gambar_referensi' => 'array',
        'progress_history' => 'array',
        'dp_paid_at' => 'datetime',
        'fully_paid_at' => 'datetime',
        'harga_final' => 'decimal:2',
        'dp_amount' => 'decimal:2',
        'jumlah' => 'integer',
    ];

    // Relationships sesuai class diagram
    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function jenis(): BelongsTo
    {
        return $this->belongsTo(Jenis::class, 'id_jenis', 'id_jenis');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'updated_by', 'id_admin');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Pembayaran::class, 'id_custom_order', 'id_custom_order');
    }

    // Methods sesuai class diagram
    public function updateStatus(): void
    {
        // Implementation for updating status
    }

    public function calculateDpAmount(): float
    {
        // Calculate DP amount (default 50%)
        return ($this->harga_final * 50) / 100;
    }

    public function isDpPaid(): bool
    {
        return !is_null($this->dp_paid_at);
    }

    public function isFullyPaid(): bool
    {
        return !is_null($this->fully_paid_at);
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['draft', 'pending_approval', 'approved']);
    }

    public function getProgressPercentage(): int
    {
        // Calculate progress percentage from progress_history
        if (!$this->progress_history) return 0;
        
        $totalSteps = count($this->progress_history);
        $completedSteps = collect($this->progress_history)->where('completed', true)->count();
        
        return $totalSteps > 0 ? round(($completedSteps / $totalSteps) * 100) : 0;
    }

    public function hasReferenceImages(): bool
    {
        return !empty($this->gambar_referensi);
    }

    public function processPayment(): bool
    {
        // Implementation for processing payment
        return true;
    }

    // Boot method for auto-generating nomor_custom_order
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->nomor_custom_order)) {
                $model->nomor_custom_order = $model->generateCustomOrderNumber();
            }
        });
    }

    private function generateCustomOrderNumber(): string
    {
        $prefix = 'CO';
        $date = now()->format('Ymd');
        $lastOrder = static::whereDate('created_at', now()->toDateString())
            ->orderBy('id_custom_order', 'desc')
            ->first();
        
        $sequence = $lastOrder ? (int) substr($lastOrder->nomor_custom_order, -3) + 1 : 1;
        
        return $prefix . $date . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }
}