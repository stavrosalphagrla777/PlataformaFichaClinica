<?php

namespace Database\Seeders;

<<<<<<< HEAD
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (User::ROLES as $rol) {
            Role::firstOrCreate(['name' => $rol, 'guard_name' => 'web']);
        }
=======
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear los roles principales
        $adminRole = Role::create(['name' => 'administrador']);
        $healthRole = Role::create(['name' => 'medico_enfermero']);
        $studentRole = Role::create(['name' => 'estudiante']);

        // 2. Crear un usuario de prueba con rol de Profesional de Salud
        $healthUser = User::create([
            'name' => 'Profesional Salud Demo',
            'email' => 'salud@liceo.cl',
            'password' => Hash::make('12345678'),
        ]);
        $healthUser->assignRole($healthRole);

        // 3. Crear un usuario de prueba con rol de Estudiante
        $studentUser = User::create([
            'name' => 'Estudiante Demo',
            'email' => 'estudiante@liceo.cl',
            'password' => Hash::make('12345678'),
        ]);
        $studentUser->assignRole($studentRole);
>>>>>>> 6b61ff51a6ef315dbc9e8dc7c1fb0ae7e86644ff
    }
}
