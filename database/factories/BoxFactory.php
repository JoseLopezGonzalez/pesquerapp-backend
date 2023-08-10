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
            'id_palet' => null,
            'id_articulo' => $this->faker->numberBetween(1, 59),
            'lote' => $this->faker->regexify('[A-Za-z0-9]{10}'),
            'GS1_128' => $this->faker->numerify('########'),
            'peso_bruto' => $this->faker->randomFloat(2, 5, 50),
            'peso_neto' => $this->faker->randomFloat(2, 4, 45),
        ];
    }
}
