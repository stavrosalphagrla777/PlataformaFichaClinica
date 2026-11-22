<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\MedicalRecord;
use Spatie\Permission\Models\Role;

class MedicalRecordSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear roles si no existen (por si el RoleSeeder no corrió antes)
        $adminRole = Role::firstOrCreate(['name' => 'administrador']);
        $docenteRole = Role::firstOrCreate(['name' => 'docente']);
        $estudianteRole = Role::firstOrCreate(['name' => 'estudiante']);

        // 2. Crear al Administrador
        $admin = User::firstOrCreate(
            ['email' => 'admin@liceo.cl'],
            [
                'name' => 'Administrador Prueba',
                'password' => bcrypt('password123'),
                'activo' => true,
            ]
        );
        $admin->assignRole($adminRole);

        // 3. Crear al Docente
        $docente = User::firstOrCreate(
            ['email' => 'docente@liceo.cl'],
            [
                'name' => 'Docente Prueba',
                'password' => bcrypt('password123'),
                'activo' => true,
            ]
        );
        $docente->assignRole($docenteRole);

        // 4. Crear Estudiante de 4.º Medio
        $estudiante4 = User::firstOrCreate(
            ['email' => 'estudiante4@liceo.cl'],
            [
                'name' => 'Estudiante 4 Medio',
                'password' => bcrypt('password123'),
                'nivel_educativo' => '4_medio',
                'activo' => true,
            ]
        );
        $estudiante4->assignRole($estudianteRole);

        // 5. Crear Estudiante de 3.º Medio
        $estudiante3 = User::firstOrCreate(
            ['email' => 'estudiante3@liceo.cl'],
            [
                'name' => 'Estudiante 3 Medio',
                'password' => bcrypt('password123'),
                'nivel_educativo' => '3_medio',
                'activo' => true,
            ]
        );
        $estudiante3->assignRole($estudianteRole);

        // 6. Crear una Ficha Médica de prueba para el estudiante de 4.º
        MedicalRecord::create([
            'user_id' => $estudiante4->id,
            'patient_id' => $estudiante4->id,
            'esi_triage' => 3,
        ]);
    }
}