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

        $secretaria = \App\Models\Secretaria::where('siglas', 'SA')->first();
        $area = \App\Models\Area::where('siglas', 'DGRH')->first();
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'apPaterno' => 'Test',
            'apMaterno' => 'User',
            'secretariaId' => $secretaria->id,
            'areaId' => $area->id,
        ])->assignRole('ADM');
    }
}
