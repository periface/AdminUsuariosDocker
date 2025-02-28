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

        // $area = \App\Models\Area::where('siglas', 'DGRH')->first();
        $areas = \App\Models\Area::all();

        $area = $areas->where('siglas', 'DGRH')->first();
        
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'apPaterno' => 'Test',
            'apMaterno' => 'User',
            'areaId' => $area->id,
            'is_active' => 1,
        ])->assignRole('ADM');

        $area = $areas->where('siglas', 'DGCYOP')->first();
        $user = User::factory()->create([
            'name' => 'Jose',
            'email' => 'user@example.com',
            'apPaterno' => 'User',
            'apMaterno' => 'Captura',
            'areaId' =>  $area->id,

            'is_active' => 1,
        ])->assignRole('RESP');

        if($user->hasRole('RESP')){
            $area->update(['responsableId' => $user->id]);
        }

        // usuarios por rol ADM, GDI, REV, SPA, ADC

        User::factory()->create([
            'name' => 'Jose Luis',
            'email' => 'adm@tam.com',
            'apPaterno' => 'Lozano',
            'apMaterno' => 'Chavez',
            'areaId' => $area->id,

            'is_active' => 1,
        ])->assignRole('EVAL');

        User::factory()->create([
            'name' => 'Pedro',
            'email' => 'gdi@tam.com',
            'apPaterno' => 'Chan',
            'apMaterno' => 'Gonzalez',
            'areaId' => $area->id,

            'is_active' => 1,
        ])->assignRole('AUD');

        $area = $areas->where('siglas', 'DPATRIMONIO')->first();
        $user = User::factory()->create([
            'name' => 'Juan',
            'email' => 'rev@tam.com',
            'apPaterno' => 'Torres',
            'apMaterno' => 'Martinez',
            'areaId' => $area->id,

            'is_active' => 1,
        ])->assignRole('RESP');

        if($user->hasRole('RESP')){
            $area->update(['responsableId' => $user->id]);
        }

        User::factory()->create([
            'name' => 'Juan',
            'email' => 'spa@tam.com',
            'apPaterno' => 'Flores',
            'apMaterno' => 'Rosales',
            'areaId' => $area->id,

            'is_active' => 1,
        ])->assignRole('EVAL');

        User::factory()->create([
            'name' => 'Juan',
            'email' => 'adc@tam.com',
            'apPaterno' => 'De la Cruz',
            'apMaterno' => 'Garcia',
            'areaId' => $area->id,

            'is_active' => 1,
        ])->assignRole('AUD');
    }
}
