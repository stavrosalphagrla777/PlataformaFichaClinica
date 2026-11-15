<?php

namespace Tests\Feature;

use App\Models\AuditEvent;
use App\Models\CaseAssignment;
use App\Models\ClinicalCase;
use App\Models\StudentCase;
use App\Models\User;
use App\Support\FourthYear;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Pruebas del ciclo auditado: caso -> asignación -> entrega -> evaluación.
 * Se ejecutan con SQLite en memoria según phpunit.xml; requieren pdo_sqlite.
 */
class ActivityAuditTest extends TestCase
{
    use RefreshDatabase;

    private function account(string $role, string $username, ?string $course = null): User
    {
        Role::findOrCreate($role, 'web');
        $user = User::factory()->create([
            'username' => $username,
            'email' => $username.'@example.test',
            'activo' => true,
            'curso' => $course,
            'nivel_educativo' => $role === 'estudiante' ? '4_medio' : null,
        ]);
        $user->assignRole($role);
        return $user;
    }

    private function case(User $teacher): ClinicalCase
    {
        return ClinicalCase::create([
            'docente_id' => $teacher->id,
            'codigo' => 'CAS-TEST-AUDIT',
            'titulo' => 'Caso de simulación de auditoría',
            'descripcion' => 'Caso ficticio sin datos personales de pacientes.',
            'nivel' => '4.º medio',
            'tipo' => 'Tarea',
            'estado' => 'Publicado',
            'servicio' => 'Servicio de urgencias',
            'duracion' => 90,
        ]);
    }

    private function assignmentPayload(string $code): array
    {
        return [
            'codigo' => $code, 'nivel' => '4.º medio', 'curso' => '4.º Medio A',
            'inicio' => now()->subMinutes(5)->format('Y-m-d\TH:i'),
            'termino' => now()->addDay()->format('Y-m-d\TH:i'),
            'duracion' => 90, 'estado' => 'Activa', 'observaciones' => '',
        ];
    }

    public function test_case_creation_and_deletion_are_logged(): void
    {
        $teacher = $this->account('docente', 'docente-audit');
        $this->actingAs($teacher)->postJson('/docente/casos', [
            'titulo' => 'Simulación de prueba', 'descripcion' => 'Caso educativo controlado',
            'nivel' => '4.º medio', 'tipo' => 'Tarea', 'estado' => 'Publicado',
            'servicio' => 'Servicio de urgencias', 'duracion' => 90,
        ])->assertCreated();
        $case = ClinicalCase::where('titulo', 'Simulación de prueba')->firstOrFail();
        $this->assertDatabaseHas('audit_events', ['action' => 'crear_caso', 'actor_id' => $teacher->id]);

        $this->actingAs($teacher)->deleteJson('/docente/casos/'.$case->id)->assertOk();
        $this->assertDatabaseHas('audit_events', ['action' => 'eliminar_caso', 'actor_id' => $teacher->id]);
    }

    public function test_assignment_submission_and_final_grade_are_logged_once(): void
    {
        $teacher = $this->account('docente', 'docente-ciclo');
        $student = $this->account('estudiante', 'estudiante-ciclo', '4° A');
        $case = $this->case($teacher);

        $this->actingAs($teacher)->postJson('/docente/asignaciones', $this->assignmentPayload($case->codigo))
            ->assertCreated();
        $this->assertDatabaseHas('audit_events', ['action' => 'asignar_caso', 'actor_id' => $teacher->id]);
        $assignment = CaseAssignment::where('clinical_case_id', $case->id)->firstOrFail();

        $this->actingAs($student)->putJson('/4to/casos/'.$case->codigo.'/secciones/destino', [
            'storage_key' => FourthYear::storageKey('destino', $case->codigo),
            'storage_type' => FourthYear::SECTIONS['destino'][2],
            'payload' => json_encode(['destino' => 'Alta', 'justificacion' => 'Simulación']),
        ])->assertOk();
        $this->actingAs($student)->postJson('/4to/casos/'.$case->codigo.'/entregar')->assertOk();
        $this->assertDatabaseHas('audit_events', [
            'action' => 'entregar_ficha', 'actor_id' => $student->id, 'target_id' => $student->id,
        ]);
        $this->actingAs($student)->postJson('/4to/casos/'.$case->codigo.'/entregar')->assertStatus(423);
        $this->assertSame(1, AuditEvent::where('action', 'entregar_ficha')->count());

        $record = StudentCase::where('case_assignment_id', $assignment->id)->firstOrFail();
        $this->actingAs($teacher)->postJson('/docente/fichas/'.$record->id.'/evaluacion', [
            'criterios' => array_fill(0, 9, 'si'),
            'notas' => array_fill(0, 9, ''),
            'retroalimentacion' => 'Simulación de cierre.', 'finalizar' => true,
        ])->assertOk()->assertJsonPath('data.estado', 'finalizada');

        $event = AuditEvent::where('action', 'evaluar_ficha')->firstOrFail();
        $this->assertSame($teacher->id, $event->actor_id);
        $this->assertSame($student->id, $event->target_id);
        $this->assertStringContainsString($case->codigo, $event->description);
        $this->assertStringContainsString('Nota: 7,0', $event->description);
        $this->assertStringContainsString('100,0%', $event->description);
        $this->actingAs($teacher)->postJson('/docente/fichas/'.$record->id.'/evaluacion', [
            'criterios' => array_fill(0, 9, 'si'), 'finalizar' => true,
        ])->assertStatus(409);
        $this->assertSame(1, AuditEvent::where('action', 'evaluar_ficha')->count());
    }

    public function test_student_cannot_read_administrative_history(): void
    {
        $student = $this->account('estudiante', 'student-no-admin', '4° A');
        $this->actingAs($student)->get('/admin/historial')->assertForbidden();
    }
}
