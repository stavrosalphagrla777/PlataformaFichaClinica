<?php

namespace App\Support;

use App\Models\CaseAssignment;
use App\Models\StudentCase;
use App\Models\User;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class FourthYear
{
    public const SECTIONS = [
        'admision' => ['Admisión', 'ficha-tp-admision-demo-', 'local'],
        'triage' => ['Triage', 'ficha-tp-triage-demo-', 'local'],
        'signos-vitales' => ['Signos vitales', 'fctp_signos_vitales_v2_', 'session'],
        'abcde' => ['Evaluación ABCDE', 'fctp_abcde_v1_', 'session'],
        'anamnesis' => ['Anamnesis', 'fctp_anamnesis_v1_', 'session'],
        'examen-fisico' => ['Examen físico', 'fctp_examen_fisico_v1_', 'session'],
        'procedimientos' => ['Procedimientos TENS', 'fctp_procedimientos_v1_', 'session'],
        'medicamentos_4to' => ['Administración de medicamentos', 'fctp_medicamentos_v1_', 'session'],
        'examenes' => ['Revisión de exámenes', 'fctp_solicitudes_examenes_v2_', 'session'],
        'acciones-enfermeria' => ['Acciones de enfermería', 'fctp_acciones_enfermeria_v1_', 'session'],
        'destino' => ['Destino y cierre', 'fctp_destino_v1_', 'session'],
    ];

    public static function isFourthYear(User $user): bool
    {
        return (bool) preg_match('/4/', (string) $user->nivel_educativo);
    }

    public static function matchesCourse(?string $studentCourse, string $assignedCourse): bool
    {
        if ($assignedCourse === 'Todos los cursos') return true;
        // Los formatos históricos pueden ser "4° A", "4.º Medio A" o "4to A".
        preg_match('/([A-D])\s*$/iu', trim((string) $studentCourse), $student);
        preg_match('/([A-D])\s*$/iu', trim($assignedCourse), $assigned);
        return isset($student[1], $assigned[1]) && strtoupper($student[1]) === strtoupper($assigned[1]);
    }

    public static function assignmentsFor(User $user)
    {
        if (!self::isFourthYear($user)) return CaseAssignment::query()->whereRaw('1=0');

        return CaseAssignment::with('clinicalCase')
            ->where('nivel', '4.º medio')
            ->whereIn('estado', ['Activa', 'Programada'])
            ->whereHas('clinicalCase', fn ($query) => $query->where('estado', 'Publicado')->where('nivel', '4.º medio'))
            ->where(function ($query) use ($user) {
                $query->where('curso', 'Todos los cursos');
                $studentCourse = (string) $user->curso;
                if (preg_match('/([A-D])\s*$/iu', trim($studentCourse), $match)) {
                    $query->orWhere('curso', 'like', '%'.strtoupper($match[1]));
                }
            });
    }

    public static function assignment(User $user, string $code): CaseAssignment
    {
        $assignment = self::assignmentsFor($user)
            ->whereHas('clinicalCase', fn ($query) => $query->where('codigo', $code))
            ->orderByDesc('inicio')
            ->firstOrFail();
        if (!self::matchesCourse($user->curso, $assignment->curso)) abort(403);
        return $assignment;
    }

    public static function canStart(CaseAssignment $assignment): bool
    {
        return now()->betweenIncluded($assignment->inicio, $assignment->termino);
    }

    public static function status(?StudentCase $record, CaseAssignment $assignment): string
    {
        if ($record?->submitted_at) return 'Entregada';
        if (now()->greaterThan($assignment->termino)) return 'Tiempo agotado';
        if ($record && now()->greaterThanOrEqualTo($record->started_at->copy()->addMinutes($assignment->effectiveDuration()))) return 'Tiempo agotado';
        return $record ? 'En desarrollo' : 'Disponible';
    }

    public static function editable(StudentCase $record): bool
    {
        $assignment = $record->assignment;
        return $record->submitted_at === null
            && self::canStart($assignment)
            && now()->lessThan($record->started_at->copy()->addMinutes($assignment->effectiveDuration()));
    }

    public static function requireEditable(StudentCase $record): void
    {
        if (!self::editable($record)) throw new HttpException(423, 'La ficha está cerrada o fuera de plazo y no puede modificarse.');
    }

    public static function storageKey(string $section, string $code): string
    {
        return self::SECTIONS[$section][1].$code;
    }
}
