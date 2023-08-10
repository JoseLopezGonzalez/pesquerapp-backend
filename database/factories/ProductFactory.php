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
            'id_especie' => $this->faker->numberBetween(1, 5),
            'id_zona_captura' => $this->faker->numberBetween(1, 3),
            'GTIN' => $this->faker->numberBetween(0, 99999999999999),
            'GTIN_caja' => $this->faker->numberBetween(0, 99999999999999),
            'GTIN_palet' => $this->faker->numberBetween(0, 99999999999999),
            'peso_fijo' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
