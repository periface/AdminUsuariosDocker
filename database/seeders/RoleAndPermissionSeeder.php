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
            'name' => 'ADC',
            'description' => 'Usuario con acceso a captura de información.',
            'alias' => 'Capturista'
        ]);
    }
}
