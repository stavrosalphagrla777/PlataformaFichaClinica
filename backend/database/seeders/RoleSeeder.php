<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Usamos firstOrCreate para que no falle si el rol ya existe
        Role::firstOrCreate(['name' => 'administrador', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'docente', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'estudiante', 'guard_name' => 'web']);
    }
}