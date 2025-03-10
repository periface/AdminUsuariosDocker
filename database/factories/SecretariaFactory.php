<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Secretaria>
 */
class SecretariaFactory extends Factory
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
            'siglas' => $this->faker->text(),
            'type' => $this->faker->randomElement(['DEPENDENCIA', 'SECRETARIA','UNIVERSIDAD']),
        ];
    }
}
