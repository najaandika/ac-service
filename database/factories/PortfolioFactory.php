<?php

namespace Database\Factories;

use App\Models\Portfolio;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Portfolio>
 */
class PortfolioFactory extends Factory
{
    protected $model = Portfolio::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'before_image' => 'portfolios/before-' . fake()->uuid() . '.jpg',
            'after_image' => 'portfolios/after-' . fake()->uuid() . '.jpg',
            'service_id' => null,
            'is_published' => true,
            'sort_order' => 0,
        ];
    }

    /**
     * Indicate that the portfolio is unpublished.
     */
    public function unpublished(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
        ]);
    }
}
