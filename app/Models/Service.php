<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Service extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'features',
        'image',
        'price',
        'duration_minutes',
        'icon',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'features' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($service) {
            if (empty($service->slug)) {
                $service->slug = Str::slug($service->name);
            }
        });
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(ServicePrice::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getPriceForCapacity(string $capacity): ?ServicePrice
    {
        return $this->prices()->where('capacity', $capacity)->first();
    }

    public function getStartingPriceAttribute(): string
    {
        $minPrice = $this->prices()->min('price');
        if ($minPrice) {
            return 'Mulai Rp ' . number_format($minPrice, 0, ',', '.');
        }
        return $this->formatted_price;
    }

    public function getFormattedStartingPriceAttribute(): string
    {
        $minPrice = $this->prices()->min('price');
        if ($minPrice) {
            return 'Rp ' . number_format($minPrice, 0, ',', '.');
        }
        return $this->formatted_price;
    }
}
