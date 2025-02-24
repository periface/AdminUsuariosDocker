<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IndicadorCategoria>
 */
class IndicadorCategoriaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->name(),
            'descripcion' => $this->faker->text(),
            'status' => $this->faker->boolean(),
            'secretariaId' => $this->faker->numberBetween(1, 10),
            'secretaria' => $this->faker->name(),
        ];
    }
}
