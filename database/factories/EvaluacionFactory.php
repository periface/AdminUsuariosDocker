<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Evaluacion>
 */
class EvaluacionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
    public $areaId;
    public $indicadorId;
    public $frecuencia_medicion;
    public $meta;

    public $fecha_fin;
    public $fecha_inicio;

    public $usuarioId;
    public $evaluable_formula;
    public $non_evaluable_formula;
    public $formula_literal;
    public $descripcion;
    public $pertenece_mir;
    public $mirId;
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        //
        return [
            "areaId" => $this->faker->numberBetween(1, 10),
            "indicadorId" => $this->faker->numberBetween(1, 10),
            "frecuencia_medicion" => $this->faker->randomElement(['diario', 'semanal', 'mensual', 'trimestral', 'semestral', 'anual']),
            "meta" => $this->faker->randomFloat(2, 0, 100),
            "fecha_fin" => $this->faker->date(),
            "fecha_inicio" => $this->faker->date(),
            "usuarioId" => $this->faker->numberBetween(1, 10),
            "evaluable_formula" => $this->faker->text(),
            "non_evaluable_formula" => $this->faker->text(),
            "formula_literal" => $this->faker->text(),
            "descripcion" => $this->faker->text(),
            "pertenece_mir" => $this->faker->boolean,
            "mirId" => $this->faker->numberBetween(1, 10),
        ];
    }
}
