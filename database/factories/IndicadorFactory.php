<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Indicador>
 */
class IndicadorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        //
        return [
            'nombre' => $this->faker->name(),
            'descripcion' => $this->faker->text(),
            'status' => $this->faker->boolean(),
            'sentido' => $this->faker->randomElement(['ascendente', 'descendente', 'constante']),
            'unidad_medida' => $this->faker->randomElement(['$', '%', 'kg']),
            'metodo_calculo' => $this->faker->text(),
            'dimensionId' => $this->faker->numberBetween(1, 10),
            'evaluable_formula' => $this->faker->text(),
            'non_evaluable_formula' => $this->faker->text(),
            'indicador_confirmado' => $this->faker->boolean,
            'secretariaId' => $this->faker->numberBetween(1, 10),
            'medio_verificacion' => $this->faker->text(),
            'requiere_anexo' => $this->faker->boolean,
            'secretaria' => $this->faker->name(),

        ];
    }
}
