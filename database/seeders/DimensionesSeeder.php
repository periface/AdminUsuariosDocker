<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
class DimensionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        self::generar_dimensiones();
    }

    public static function generar_dimensiones()
    {
        $secretaria_admin = \App\Models\Secretaria::where('siglas', 'SA')->first();
        \App\Models\Dimension::factory()->create([
            'nombre' => 'Calidad',
            'descripcion' => 'Evaluación y seguimiento de la eficiencia y eficacia de los procesos y servicios',
            'status' => true,
            'secretariaId' => $secretaria_admin->id,
            'secretaria' => $secretaria_admin['nombre']
        ]);
        \App\Models\Dimension::factory()->create([
            'nombre' => 'Eficiencia',
            'descripcion' => 'Capacidad de realizar tareas o alcanzar objetivos utilizando la menor cantidad de recursos posible, maximizando la productividad sin desperdiciar tiempo, dinero o esfuerzo',
            'status' => true,
            'secretariaId' => $secretaria_admin->id,
            'secretaria' => $secretaria_admin['nombre']
        ]);

        \App\Models\Dimension::factory()->create([
            'nombre' => 'Eficacia',
            'descripcion' => 'Capacidad de realizar tareas o alcanzar objetivos utilizando la menor cantidad de recursos posible, maximizando la productividad sin desperdiciar tiempo, dinero o esfuerzo',
            'status' => true,
            'secretariaId' => $secretaria_admin->id,
            'secretaria' => $secretaria_admin['nombre']
        ]);

        \App\Models\Dimension::factory()->create([
            'nombre' => 'Economía',
            'descripcion' => 'Se refiere a la evaluación de cómo se utilizan y gestionan los recursos financieros y materiales en una organización para alcanzar sus objetivos de manera sostenible y eficiente',
            'status' => true,
            'secretariaId' => $secretaria_admin->id,
            'secretaria' => $secretaria_admin['nombre']
        ]);
    }
}
