<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\MedicalRecord;

class MedicalRecordSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Usuario Profesional / Docente
        $profesional = User::firstOrCreate(
            ['email' => 'docente@liceo.cl'],
            [
                'name' => 'Docente Prueba',
                'password' => bcrypt('password123'),
            ]
        );

        // 2. Usuario Paciente / Estudiante
        $paciente = User::firstOrCreate(
            ['email' => 'estudiante@liceo.cl'],
            [
                'name' => 'Juan Pérez',
                'password' => bcrypt('password123'),
            ]
        );

        // 3. Ficha Médica
        MedicalRecord::create([
            'user_id' => $profesional->id,
            'patient_id' => $paciente->id,
            'esi_triage' => 3,
        ]);
    }
}