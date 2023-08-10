<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Box>
 */
class BoxFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pallet_id' => null,
            'article_id' => $this->faker->numberBetween(1, 59),
            'lot' => $this->faker->regexify('[A-Za-z0-9]{10}'),
            'gs1_128' => $this->faker->numerify('########'),
            'gross_weight' => $this->faker->randomFloat(2, 5, 50),
            'net_weight' => $this->faker->randomFloat(2, 4, 45),
        ];
    }
}
