<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // No crear usuarios de contraseña pública en una instalación normal.
        $this->call([RoleSeeder::class]);
    }
}
