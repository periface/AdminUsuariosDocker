<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Variable>
 */
class VariableFactory extends Factory
{
    /**
     * Define the model's default state.
     *
    public $nombre;
    public $code;
    public $indicadorId;
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->word,
            'nombre' => $this->faker->word,
            'indicadorId' => $this->faker->randomNumber(8),
        ];
    }
}
