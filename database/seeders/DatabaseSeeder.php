<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\RoleAndPermissionSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            RoleAndPermissionSeeder::class,
            SecretariasSeeder::class,
            AreasSeeder::class,
            UsersSeeder::class,
            DimensionesSeeder::class,
            IndicadoresSeeder::class
        ]);
    }
}
