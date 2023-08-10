<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'species_id' => $this->faker->numberBetween(1, 5),
            'capture_zone_id' => $this->faker->numberBetween(1, 3),
            'article_gtin' => $this->faker->numberBetween(0, 99999999999999),
            'box_gtin' => $this->faker->numberBetween(0, 99999999999999),
            'pallet_gtin' => $this->faker->numberBetween(0, 99999999999999),
            'fixed_weight' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
