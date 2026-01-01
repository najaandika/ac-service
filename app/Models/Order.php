<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'order_code',
        'customer_id',
        'service_id',
        'technician_id',
        'ac_type',
        'ac_capacity',
        'ac_quantity',
        'scheduled_date',
        'scheduled_time',
        'notes',
        'photo',
        'service_price',
        'additional_fee',
        'discount',
        'total_price',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'completed_at' => 'datetime',
        'service_price' => 'decimal:2',
        'additional_fee' => 'decimal:2',
        'discount' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($order) {
            if (empty($order->order_code)) {
                $order->order_code = self::generateOrderCode();
            }
        });
    }

    public static function generateOrderCode(): string
    {
        do {
            $code = 'AC' . strtoupper(Str::random(6));
        } while (self::where('order_code', $code)->exists());
        
        return $code;
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(Technician::class);
    }

    public function getFormattedTotalAttribute(): string
    {
        return \App\Helpers\FormatHelper::rupiah($this->total_price);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu Konfirmasi',
            'confirmed' => 'Dikonfirmasi',
            'on_the_way' => 'Teknisi Dalam Perjalanan',
            'in_progress' => 'Sedang Dikerjakan',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'confirmed' => 'info',
            'on_the_way' => 'info',
            'in_progress' => 'alert',
            'completed' => 'success',
            'cancelled' => 'error',
            default => 'info',
        };
    }

    public function getScheduledTimeSlotAttribute(): string
    {
        return match($this->scheduled_time) {
            'pagi' => '08:00 - 12:00',
            'siang' => '12:00 - 15:00',
            'sore' => '15:00 - 18:00',
            default => $this->scheduled_time,
        };
    }

    /**
     * Get the review for this order.
     */
    public function review(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Review::class);
    }

    /**
     * Check if order has been reviewed.
     */
    public function hasReview(): bool
    {
        return $this->review()->exists();
    }
}
