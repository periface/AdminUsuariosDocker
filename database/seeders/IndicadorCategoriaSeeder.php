<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IndicadorCategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        self::generar_categorias();
    }

    public static function generar_categorias()
    {


        $secretaria_admin = \App\Models\Secretaria::where('siglas', 'SA')->first();
        \App\Models\IndicadorCategoria::factory()->create([
            'nombre' => 'Capital Humano',
            'descripcion' => 'Habilidades, conocimientos y experiencia de los empleados que aportan valor a la organización.',
            'status' => true,
            'secretariaId' => $secretaria_admin->id,
            'secretaria' => $secretaria_admin['nombre']
        ]);

        \App\Models\IndicadorCategoria::factory()->create([
            'nombre' => 'Capital Organizacional',
            'descripcion' => 'Estructuras, procesos y cultura interna que optimizan la gestión y el desempeño organizacional.',
            'status' => true,
            'secretariaId' => $secretaria_admin->id,
            'secretaria' => $secretaria_admin['nombre']
        ]);

        \App\Models\IndicadorCategoria::factory()->create([
            'nombre' => 'Capital Tecnologico',
            'descripcion' => 'Infraestructura, herramientas y conocimientos tecnológicos que facilitan la innovación y eficiencia.',
            'status' => true,
            'secretariaId' => $secretaria_admin->id,
            'secretaria' => $secretaria_admin['nombre']
        ]);

        \App\Models\IndicadorCategoria::factory()->create([
            'nombre' => 'Capital Financiero',
            'descripcion' => 'Recursos monetarios y activos económicos disponibles para la operación y crecimiento de una organización.',
            'status' => true,
            'secretariaId' => $secretaria_admin->id,
            'secretaria' => $secretaria_admin['nombre']
        ]);

        \App\Models\IndicadorCategoria::factory()->create([
            'nombre' => 'Capital Relacional',
            'descripcion' => 'Redes, alianzas y relaciones con clientes, proveedores y otras partes interesadas que generan valor estratégico.',
            'status' => true,
            'secretariaId' => $secretaria_admin->id,
            'secretaria' => $secretaria_admin['nombre']
        ]);
    }
}
