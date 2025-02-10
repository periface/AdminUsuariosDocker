<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Anexo>
 */
class AnexoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fileName' => $this->faker->word,
            'filePath' => $this->faker->word,
            'fileType' => $this->faker->word,
            'fileSize' => $this->faker->word,
            'fileExtension' => $this->faker->word,
            'fileDescription' => $this->faker->word,
            'fileStatus' => $this->faker->word,
            'evaluacionResultId' => $this->faker->randomNumber(),
        ];
    }
}
