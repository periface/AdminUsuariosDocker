<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AreasSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        self::generar_areas();
    }

    public static function generar_areas()
    {
        $secretaria = \App\Models\Secretaria::where('siglas', 'SA')->first();
        \App\Models\Area::factory()->create([
            'nombre' => 'Dirección General de Compras y Operaciones Patrimoniales',
            'responsableId' => null,
            'siglas' => 'DGCYOP',
            'secretariaId' => $secretaria->id,
            'status' => 1,
            'departamento' => ''
        ]);

        \App\Models\Area::factory()->create([
            'nombre' => 'Dirección General de Recursos Humanos',
            'responsableId' => null,
            'siglas' => 'DGRH',
            'secretariaId' => $secretaria->id,
            'status' => 1,
            'departamento' => ''
        ]);
    }
}
