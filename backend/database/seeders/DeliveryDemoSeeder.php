<?php

namespace Database\Seeders;

use App\Models\CaseAssignment;
use App\Models\ClinicalCase;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

/**
 * Datos descartables para mostrar el flujo 4.º medio + docentes.
 * Ejecutar explícitamente SOLO en una instalación local de desarrollo.
 */
class DeliveryDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (!app()->environment(['local', 'testing'])) {
            throw new \RuntimeException('DeliveryDemoSeeder solo puede ejecutarse en local/testing.');
        }
        foreach (['docente', 'estudiante'] as $role) Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        $teacher = User::firstOrCreate(['email' => 'docente@liceo.cl'], [
            'name' => 'Docente Prueba', 'password' => bcrypt('DemoClinica123!'), 'activo' => true,
        ]);
        $student = User::firstOrCreate(['email' => 'estudiante4@liceo.cl'], [
            'name' => 'Estudiante 4.º medio', 'password' => bcrypt('DemoClinica123!'), 'activo' => true,
            'nivel_educativo' => '4_medio', 'curso' => '4° A',
        ]);
        $teacher->assignRole('docente');
        $student->update(['nivel_educativo' => '4_medio', 'curso' => '4° A', 'activo' => true]);
        $student->assignRole('estudiante');
        $case = ClinicalCase::firstOrCreate(['codigo' => 'CAS-DEMO-4A'], [
            'docente_id' => $teacher->id, 'titulo' => 'Caso de demostración: atención de urgencias',
            'descripcion' => 'Caso artificial de prueba para validar el registro y evaluación de fichas clínicas, sin datos de pacientes reales.',
            'nivel' => '4.º medio', 'tipo' => 'Tarea', 'estado' => 'Publicado',
            'servicio' => 'Servicio de urgencias', 'duracion' => 120,
            'paciente' => 'Paciente simulado',
        ]);
        CaseAssignment::firstOrCreate(
            ['clinical_case_id' => $case->id, 'curso' => '4.º Medio A'],
            ['docente_id' => $teacher->id, 'nivel' => '4.º medio',
             'inicio' => now()->subDay(), 'termino' => now()->addWeek(), 'estado' => 'Activa']
        );
    }
}
