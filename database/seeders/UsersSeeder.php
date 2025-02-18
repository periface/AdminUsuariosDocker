<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $area = \App\Models\Area::where('siglas', 'DGRH')->first();
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'apPaterno' => 'Test',
            'apMaterno' => 'User',
            'areaId' => $area->id,
            'is_active' => 1,
        ])->assignRole('ADM');

        User::factory()->create([
            'name' => 'Jose',
            'email' => 'user@example.com',
            'apPaterno' => 'User',
            'apMaterno' => 'Captura',
            'areaId' => $area->id,

            'is_active' => 1,
        ])->assignRole('ADC');


        // usuarios por rol ADM, GDI, REV, SPA, ADC

        User::factory()->create([
            'name' => 'Jose Luis',
            'email' => 'adm@tam.com',
            'apPaterno' => 'Lozano',
            'apMaterno' => 'Chavez',
            'areaId' => $area->id,

            'is_active' => 1,
        ])->assignRole('ADM');

        User::factory()->create([
            'name' => 'Pedro',
            'email' => 'gdi@tam.com',
            'apPaterno' => 'Chan',
            'apMaterno' => 'Gonzalez',
            'areaId' => $area->id,

            'is_active' => 1,
        ])->assignRole('GDI');

        User::factory()->create([
            'name' => 'Juan',
            'email' => 'rev@tam.com',
            'apPaterno' => 'Torres',
            'apMaterno' => 'Martinez',
            'areaId' => $area->id,

            'is_active' => 1,
        ])->assignRole('REV');

        User::factory()->create([
            'name' => 'Juan',
            'email' => 'spa@tam.com',
            'apPaterno' => 'Flores',
            'apMaterno' => 'Rosales',
            'areaId' => $area->id,

            'is_active' => 1,
        ])->assignRole('SPA');

        User::factory()->create([
            'name' => 'Juan',
            'email' => 'adc@tam.com',
            'apPaterno' => 'De la Cruz',
            'apMaterno' => 'Garcia',
            'areaId' => $area->id,

            'is_active' => 1,
        ])->assignRole('ADC');
    }
}
