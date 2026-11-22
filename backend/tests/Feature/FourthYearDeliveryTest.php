<?php

namespace Tests\Feature;

use App\Models\CaseAssignment;
use App\Models\ClinicalCase;
use App\Models\StudentCase;
use App\Models\User;
use App\Support\FourthYear;
use Tests\TestCase;

class FourthYearDeliveryTest extends TestCase
{
    private function teacher(string $email = 'docente@prueba.cl'): User
    {
        $user = User::factory()->create(['email' => $email, 'activo' => true]);
        $user->assignRole('docente');
        return $user;
    }

    private function student(string $course = '4° A'): User
    {
        $user = User::factory()->create([
            'nivel_educativo' => '4_medio', 'curso' => $course, 'activo' => true,
        ]);
        $user->assignRole('estudiante');
        return $user;
    }

    private function assignment(User $teacher, string $course = '4.º Medio A'): CaseAssignment
    {
        $case = ClinicalCase::create([
            'docente_id' => $teacher->id, 'codigo' => 'CAS-INTEGRACION-4',
            'titulo' => 'Simulación controlada de urgencias',
            'descripcion' => 'Caso de prueba automatizado sin datos clínicos reales.',
            'nivel' => '4.º medio', 'tipo' => 'Tarea', 'estado' => 'Publicado',
            'servicio' => 'Servicio de urgencias', 'duracion' => 90,
        ]);
        return CaseAssignment::create([
            'clinical_case_id' => $case->id, 'docente_id' => $teacher->id,
            'nivel' => '4.º medio', 'curso' => $course,
            'inicio' => now()->subMinutes(10), 'termino' => now()->addDay(),
            'estado' => 'Activa',
        ]);
    }

    public function test_only_an_assigned_student_can_open_and_save_case_data(): void
    {
        $teacher = $this->teacher();
        $this->assignment($teacher);
        $wrongCourse = $this->student('4° B');
        $this->actingAs($wrongCourse)
            ->get('/estudiante/admision?caso=CAS-INTEGRACION-4')->assertNotFound();

        $rightCourse = $this->student('4° A');
        $this->actingAs($rightCourse)->get('/estudiante/admision?caso=CAS-INTEGRACION-4')->assertOk();
        $this->actingAs($rightCourse)->putJson('/4to/casos/CAS-INTEGRACION-4/secciones/admision', [
            'storage_key' => FourthYear::storageKey('admision', 'CAS-INTEGRACION-4'),
            'storage_type' => 'local', 'payload' => json_encode(['motivo' => 'Simulación educativa']),
        ])->assertOk();
        $this->assertDatabaseHas('case_sections', ['clave' => 'admision', 'storage_type' => 'local']);
    }

    public function test_submission_locks_the_student_record_and_the_teacher_can_evaluate_it(): void
    {
        $teacher = $this->teacher();
        $this->assignment($teacher);
        $student = $this->student();
        $this->actingAs($student)->get('/estudiante/triage?caso=CAS-INTEGRACION-4')->assertOk();
        $payload = [
            'storage_key' => FourthYear::storageKey('triage', 'CAS-INTEGRACION-4'),
            'storage_type' => 'local', 'payload' => json_encode([['esi' => 3]]),
        ];
        $this->actingAs($student)->putJson('/4to/casos/CAS-INTEGRACION-4/secciones/triage', $payload)->assertOk();
        $this->actingAs($student)->postJson('/4to/casos/CAS-INTEGRACION-4/entregar')->assertOk();
        $this->actingAs($student)->putJson('/4to/casos/CAS-INTEGRACION-4/secciones/triage', $payload)->assertStatus(423);

        $record = StudentCase::where('estudiante_id', $student->id)->firstOrFail();
        $this->actingAs($teacher)->postJson('/docente/fichas/'.$record->id.'/evaluacion', [
            'criterios' => ['si', 'si', 'si', 'si', 'si', 'si', 'no', 'no', 'no'],
            'notas' => array_fill(0, 9, ''), 'retroalimentacion' => 'Revisión de prueba.',
            'finalizar' => true,
        ])->assertOk()->assertJsonPath('data.estado', 'finalizada');
        $this->assertDatabaseHas('case_evaluations', [
            'student_case_id' => $record->id, 'estado' => 'finalizada',
        ]);
    }

    public function test_teacher_cannot_review_or_evaluate_another_teachers_students(): void
    {
        $owner = $this->teacher();
        $assignment = $this->assignment($owner);
        $student = $this->student();
        $record = StudentCase::create([
            'case_assignment_id' => $assignment->id, 'estudiante_id' => $student->id,
            'started_at' => now()->subMinutes(4), 'submitted_at' => now(), 'estado' => 'Entregada',
        ]);
        $otherTeacher = $this->teacher('otrodocente@prueba.cl');
        $this->actingAs($otherTeacher)->get('/docente/fichas/'.$record->id)->assertForbidden();
        $this->actingAs($otherTeacher)->postJson('/docente/fichas/'.$record->id.'/evaluacion', [
            'criterios' => array_fill(0, 9, 'si'), 'finalizar' => true,
        ])->assertForbidden();
    }

    public function test_case_cannot_be_started_after_assignment_deadline(): void
    {
        $teacher = $this->teacher();
        $assignment = $this->assignment($teacher);
        $assignment->update(['inicio' => now()->subDays(2), 'termino' => now()->subDay()]);
        $student = $this->student();
        $this->actingAs($student)->get('/estudiante/admision?caso=CAS-INTEGRACION-4')
            ->assertRedirect(route('preview.estudiante.casos'));
        $this->actingAs($student)->putJson('/4to/casos/CAS-INTEGRACION-4/secciones/admision', [
            'storage_key' => FourthYear::storageKey('admision', 'CAS-INTEGRACION-4'),
            'storage_type' => 'local', 'payload' => '{}',
        ])->assertStatus(409);
    }
}
