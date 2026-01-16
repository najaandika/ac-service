<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Promo extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'min_order',
        'max_discount',
        'service_id',
        'usage_limit',
        'usage_count',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_order' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'usage_limit' => 'integer',
        'usage_count' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the service this promo is restricted to.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get orders that used this promo.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Scope for active promos.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if promo is valid for use.
     */
    public function isValid(?int $serviceId = null, float $subtotal = 0): array
    {
        // Check if active
        if (!$this->is_active) {
            return ['valid' => false, 'message' => 'Kode promo tidak aktif'];
        }

        // Check start date
        if ($this->start_date && now()->lt($this->start_date)) {
            return ['valid' => false, 'message' => 'Kode promo belum berlaku'];
        }

        // Check end date
        if ($this->end_date && now()->gt($this->end_date)) {
            return ['valid' => false, 'message' => 'Kode promo sudah expired'];
        }

        // Check usage limit
        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) {
            return ['valid' => false, 'message' => 'Kode promo sudah mencapai batas penggunaan'];
        }

        // Check service restriction
        if ($this->service_id && $this->service_id !== $serviceId) {
            return ['valid' => false, 'message' => 'Kode promo hanya berlaku untuk layanan ' . ($this->service->name ?? 'tertentu')];
        }

        // Check minimum order
        if ($this->min_order && $subtotal < $this->min_order) {
            return ['valid' => false, 'message' => 'Minimum order Rp ' . number_format($this->min_order, 0, ',', '.')];
        }

        return ['valid' => true, 'message' => 'Kode promo valid'];
    }

    /**
     * Calculate discount amount for given subtotal.
     */
    public function calculateDiscount(float $subtotal): float
    {
        if ($this->type === 'percentage') {
            $discount = $subtotal * ($this->value / 100);
            
            // Apply max discount cap if set
            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }
        } else {
            // Fixed amount
            $discount = $this->value;
        }

        // Discount should not exceed subtotal
        return min($discount, $subtotal);
    }

    /**
     * Increment usage count.
     */
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    /**
     * Get formatted value based on type.
     */
    public function getFormattedValueAttribute(): string
    {
        if ($this->type === 'percentage') {
            return $this->value . '%';
        }
        return 'Rp ' . number_format($this->value, 0, ',', '.');
    }
    /**
     * Check if promo is expired.
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->end_date && now()->gt($this->end_date);
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        if (!$this->is_active) {
            return 'Nonaktif';
        }
        if ($this->end_date && now()->gt($this->end_date)) {
            return 'Expired';
        }
        if ($this->start_date && now()->lt($this->start_date)) {
            return 'Belum Berlaku';
        }
        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) {
            return 'Habis';
        }
        return 'Aktif';
    }

    /**
     * Get status color for badge.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status_label) {
            'Aktif' => 'success',
            'Nonaktif' => 'error',
            'Expired' => 'warning',
            'Belum Berlaku' => 'info',
            'Habis' => 'warning',
            default => 'info',
        };
    }
}
