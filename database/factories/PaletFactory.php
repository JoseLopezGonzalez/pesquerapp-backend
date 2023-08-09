<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Palet>
 */
class PaletFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'observaciones' => $this->faker->sentence,
            'id_estado' => $this->faker->numberBetween(1, 6),
            'id_almacen' => $this->faker->numberBetween(1, 2),
        ];
    }
}
