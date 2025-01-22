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
        ])->assignRole('ADM');

        User::factory()->create([
            'name' => 'Jose',
            'email' => 'user@example.com',
            'apPaterno' => 'User',
            'apMaterno' => 'Captura',
            'areaId' => $area->id,
        ])->assignRole('ADC');
    }
}
