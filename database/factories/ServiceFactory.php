<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    protected $model = Service::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->words(3, true);
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(),
            'features' => ['Garansi', 'Teknisi Berpengalaman'],
            'image' => null,
            'price' => fake()->randomFloat(2, 50000, 500000),
            'duration_minutes' => fake()->randomElement([30, 60, 90, 120]),
            'icon' => 'wind',
            'is_active' => true,
            'sort_order' => 0,
        ];
    }
}
