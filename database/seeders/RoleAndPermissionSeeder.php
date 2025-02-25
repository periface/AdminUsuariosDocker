<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleAdmin = Role::create([
            'name' => 'ADM',
            'description' => 'Acceso total al sistema: puede ver y administrar todos los módulos, roles, usuarios, indicadores, catálogos, etc.',
            'alias' => 'Administrador'
        ]);

        $roleCaptura = Role::create([
            'name' => 'GDI',
            'description' => 'Responsable de cargar, actualizar y gestionar los indicadores  en el sistema.',
            'alias' => 'Gestor de Indicadores'
        ]);

        $roleCaptura = Role::create([
            'name' => 'REV',
            'description' => 'La función principal de este rol, es como su nombre lo indica realizar la captura de las evaluaciones para los indicadores asignados al área.',
            'alias' => 'Responsable de Evaluación'
        ]);

        $roleCaptura = Role::create([
            'name' => 'SPA',
            'description' => 'Este rol supervisa, realiza el seguimiento y evalúa los resultados de los indicadores en su área',
            'alias' => 'Supervisor de Área'
        ]);

        $roleCaptura = Role::create([
            'name' => 'ADC',
            'description' => 'Este rol se encargará de administrar los catálogos del sistema, altas, bajas, actualizaciones y eliminaciones.',
            'alias' => 'Administrador de Catálogos'
        ]);

        $roleCaptura = Role::create([
            'name' => 'EVAL',
            'description' => 'La función principal de este rol, es como su nombre lo indica realizar la captura de las evaluaciones para los indicadores asignados al área.',
            'alias' => 'Evaluador'
        ]);

        $roleCaptura = Role::create([
            'name' => 'RESP',
            'description' => 'Este rol supervisa, realiza el seguimiento y evalúa los resultados de los indicadores en su área',
            'alias' => 'Responsable de Área'
        ]);

        $roleCaptura = Role::create([
            'name' => 'AUD',
            'description' => 'La función principal de este rol es: Revisar las evaluaciones enviadas por los Responsables de Área. Aprobar o rechazar evaluaciones con comentarios.',
            'alias' => 'Validador'
        ]);
    }
}
