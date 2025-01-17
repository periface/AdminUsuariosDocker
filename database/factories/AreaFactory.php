<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Area>
 */
class AreaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->word,
            'responsableId' => $this->faker->randomNumber(8),
            'siglas' => $this->faker->word,
            'status' => $this->faker->boolean,
            'secretariaId' => $this->faker->randomNumber(8),
        ];
    }
}
