<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'features',
        'image',
        'price',
        'category',
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

    const CATEGORY_AC = 'ac';
    const CATEGORY_ELEKTRONIK = 'elektronik';
    const CATEGORY_INSTALASI = 'instalasi';

    const CATEGORIES = [
        self::CATEGORY_AC => 'AC',
        self::CATEGORY_ELEKTRONIK => 'Elektronik',
        self::CATEGORY_INSTALASI => 'Instalasi',
    ];

    public function isAcService(): bool
    {
        return $this->category === self::CATEGORY_AC;
    }

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
        return \App\Helpers\FormatHelper::rupiah($this->price);
    }

    public function getPriceForCapacity(string $capacity): float
    {
        $priceRecord = $this->prices()->where('capacity', $capacity)->first();
        return $priceRecord ? (float) $priceRecord->price : (float) $this->price;
    }

    public function getStartingPriceAttribute(): string
    {
        $minPrice = $this->prices()->min('price');
        if ($minPrice) {
            return 'Mulai ' . \App\Helpers\FormatHelper::rupiah($minPrice);
        }
        return $this->formatted_price;
    }

    public function getFormattedStartingPriceAttribute(): string
    {
        $minPrice = $this->prices()->min('price');
        if ($minPrice) {
            return \App\Helpers\FormatHelper::rupiah($minPrice);
        }
        return $this->formatted_price;
    }
}
