<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pallet>
 */
class PalletFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'observations' => $this->faker->sentence,
            'state_id' => $this->faker->numberBetween(1, 6),
            'store_id' => $this->faker->numberBetween(1, 2),
        ];
    }
}
