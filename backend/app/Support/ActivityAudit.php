<?php

namespace App\Support;

use App\Models\AuditEvent;
use App\Models\CaseAssignment;
use App\Models\CaseEvaluation;
use App\Models\ClinicalCase;
use App\Models\StudentCase;
use App\Models\User;
use Illuminate\Support\Str;

/**
 * Bitácora de acciones con repercusión en coordinación académica.
 *
 * Invocar DENTRO de la misma transacción que modifica los datos: si la operación
 * falla, tampoco se registra un éxito inexistente. No guardar síntomas ni
 * contenido clínico aquí: únicamente identidades, códigos, fechas y calificaciones.
 * La tabla audit_events ya existe (migración 2026_11_12_110000).
 */
final class ActivityAudit
{
    /** Registra quién actuó, qué usuario fue afectado y una descripción breve. */
    public static function record(User $actor, string $action, string $description, ?User $target = null): void
    {
        AuditEvent::create([
            'actor_id' => $actor->id,
            'target_id' => $target?->id,
            'action' => $action,
            'description' => Str::limit($description, 255, ''),
        ]);
    }

    public static function caseCreated(User $actor, ClinicalCase $case): void
    {
        self::record($actor, 'crear_caso',
            "Caso {$case->codigo} · {$case->titulo} · {$case->nivel} · Estado: {$case->estado}");
    }

    public static function caseDeleted(User $actor, ClinicalCase $case): void
    {
        self::record($actor, 'eliminar_caso', "Caso clínico eliminado: {$case->codigo} · {$case->titulo}");
    }

    /** $action = asignar_caso | actualizar_asignacion | eliminar_asignacion. */
    public static function assignment(User $actor, CaseAssignment $assignment, string $action): void
    {
        $assignment->loadMissing('clinicalCase');
        self::record($actor, $action,
            "Caso {$assignment->clinicalCase->codigo} · {$assignment->nivel} · Curso: {$assignment->curso} · " .
            "Asignación #{$assignment->id} · Estado: {$assignment->estado} · " .
            'Inicio: '.$assignment->inicio->format('d/m/Y H:i'));
    }

    /** Se registra solo la entrega definitiva; nunca los guardados de borrador. */
    public static function submission(User $student, StudentCase $record): void
    {
        $record->loadMissing('assignment.clinicalCase');
        self::record($student, 'entregar_ficha',
            "Ficha #{$record->id} entregada · Caso {$record->assignment->clinicalCase->codigo} · " .
            "Curso: {$record->assignment->curso}", $student);
    }

    /** Registra solo evaluaciones finalizadas, con nota y porcentaje efectivos. */
    public static function evaluation(User $teacher, StudentCase $record, CaseEvaluation $evaluation): void
    {
        $record->loadMissing('assignment.clinicalCase', 'estudiante');
        $grade = $evaluation->nota === null ? 'Sin nota' : number_format((float) $evaluation->nota, 1, ',', '');
        $percent = $evaluation->porcentaje === null ? 'Sin porcentaje' :
            number_format((float) $evaluation->porcentaje, 1, ',', '').'%';
        self::record($teacher, 'evaluar_ficha',
            "Caso {$record->assignment->clinicalCase->codigo} · Nota: {$grade} · Logro: {$percent} · " .
            "Ficha #{$record->id} · Estudiante: {$record->estudiante->name}", $record->estudiante);
    }
}
