<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServicePrice extends Model
{
    protected $fillable = [
        'service_id',
        'capacity',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getCapacityLabelAttribute(): string
    {
        return match($this->capacity) {
            '0.5pk' => '½ PK',
            '0.75pk' => '¾ PK',
            '1pk' => '1 PK',
            '1.5pk' => '1½ PK',
            '2pk' => '2 PK',
            '2.5pk' => '2½ PK',
            '3pk' => '3 PK',
            '5pk' => '5 PK',
            default => $this->capacity,
        };
    }
}
