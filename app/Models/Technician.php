<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Technician extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'specialty',
        'specializations',
        'photo',
        'rating',
        'total_orders',
        'is_active',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'is_active' => 'boolean',
        'specializations' => 'array',
    ];

    const SPECIALIZATIONS = [
        'ac' => 'AC',
        'elektronik' => 'Elektronik',
        'instalasi' => 'Instalasi',
    ];

    public function hasSpecialization(string $category): bool
    {
        return in_array($category, $this->specializations ?? []);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&size=100';
    }
}
