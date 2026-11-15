<?php

namespace App\Support;

use App\Models\StudentCase;

/**
 * Comprobaciones mecánicas de datos presentes. No emite juicios clínicos:
 * la revisión docente confirma o modifica cada resultado propuesto.
 */
final class EvaluationAssistance
{
    public const CESFAM = [
        ['Registro y antecedentes','Motivo de consulta y antecedentes registrados.'],
        ['Signos vitales y antropometría','Presión arterial, FC, FR, temperatura, saturación, peso, talla e IMC.'],
        ['Interpretación de signos','Pertinencia de la interpretación clínica del estudiante.'],
        ['Examen físico general','Calidad de la valoración y la interpretación segmentaria.'],
        ['Prestaciones o acciones TENS','Pertinencia de las intervenciones registradas.'],
        ['Educación al paciente','Claridad y adecuación de la educación entregada.'],
        ['Confirmación de educación','Constancia de confirmación de educación al paciente.'],
        ['Citaciones y controles','Fecha, control, periodicidad, profesional e indicaciones.'],
        ['Cierre y cumplimiento','Cuatro secciones registradas y entrega dentro del plazo.'],
    ];
    public const URGENCIAS = [
        ['Admisión','Datos identificatorios, previsión y contacto de emergencia.'],
        ['Triage','Motivo de consulta, prioridad ESI y EVA cuando corresponda.'],
        ['Evaluación ABCDE','Calidad y pertinencia de evaluación primaria.'],
        ['Anamnesis y examen físico','Evaluación orientada al motivo de consulta.'],
        ['Procedimientos TENS','Procedimientos realizados y registro cronológico.'],
        ['Medicamentos','Corrección de dosis, vía, frecuencia y reacciones.'],
        ['Revisión de exámenes','Solicitudes o exámenes y datos de identificación.'],
        ['Acciones de enfermería','Pertinencia de las acciones y su seguimiento.'],
        ['Destino y cierre','Justificación clínica y continuidad de cuidados.'],
    ];
    private static function isFilled(mixed $v): bool
    {
        if (is_array($v)) return (bool) array_filter($v, fn ($item) => self::isFilled($item));
        if (is_bool($v)) return $v;
        return $v !== null && trim((string)$v) !== '';
    }
    public static function suggestions(StudentCase $record): array
    {
        $snapshots = $record->sections->mapWithKeys(function ($section) {
            $data = json_decode($section->payload, true);
            return [$section->clave => is_array($data) ? $data : []];
        })->all();
        $third = $record->assignment->nivel === '3.º medio';
        $answers = array_fill(0,9,'pendiente');
        $reasons = array_fill(0,9,'Requiere revisión docente de calidad y pertinencia clínica.');
        $sources = array_fill(0,9,'docente');
        $check = function(int $i, bool $passed, string $reason) use (&$answers, &$reasons, &$sources) {
            $answers[$i] = $passed ? 'si' : 'no';
            $reasons[$i] = $reason;
            $sources[$i] = 'automatica';
        };
        if ($third) {
            $ant = $snapshots['antecedentes-3ro'] ?? [];
            $f = $ant['form'] ?? [];
            $tables = $ant['tables'] ?? [];
            $validTables = true;
            foreach (['patologias','alergias','medicamentos'] as $name) {
                if (($f['tiene_'.$name] ?? '') === 'si' && !self::isFilled($tables[$name] ?? [])) $validTables = false;
            }
            $check(0, self::isFilled($f['motivo_consulta'] ?? null) && $validTables,
                'Comprobación de presencia del motivo de consulta y coherencia de las tablas «Sí refiere». No evalúa exactitud clínica.');
            $v = ($snapshots['signos-vitales-3ro']['form'] ?? []);
            $fields=['pas','pad','fc','fr','temperatura','spo2','peso','talla','imc'];
            $missing = array_values(array_filter($fields, fn ($key) => !self::isFilled($v[$key] ?? null)));
            $check(1, !count($missing), $missing ? 'Faltan datos de: '.implode(', ',$missing) : 'Todos los parámetros verificables están registrados.');
            $c = ($snapshots['citaciones-3ro']['form'] ?? []);
            $check(6, filter_var($c['educacion_confirmada'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'Comprueba únicamente que el estudiante marcó la confirmación de educación; no su calidad.');
            $missing = array_values(array_filter(['fecha_hora','tipo_control','periodicidad','profesional_asignado','indicaciones'], fn($key) => !self::isFilled($c[$key] ?? null)));
            $check(7, !count($missing), $missing ? 'Datos de citación faltantes: '.implode(', ',$missing) : 'Campos de citación verificables completos.');
            $check(8, $record->submitted_at !== null && count($snapshots)>=4 && $record->submitted_at->lessThanOrEqualTo($record->assignment->termino),
                'Verifica las cuatro secciones y la entrega dentro del término de la asignación.');
        } else {
            $a = $snapshots['admision'] ?? [];
            $missing = array_values(array_filter(['nombre','rut','prevision','contacto_nombre','contacto_telefono'],fn($key)=>!self::isFilled($a[$key]??null)));
            $check(0,!count($missing),$missing?'Faltan datos de admisión: '.implode(', ',$missing):'Campos identificatorios y contacto de admisión registrados.');
            $t = $snapshots['triage'] ?? [];
            $fields=['motivo','esi'];
            if (filter_var($t['eva_registrada'] ?? false,FILTER_VALIDATE_BOOLEAN)) $fields[]='eva';
            $missing = array_values(array_filter($fields,fn($key)=>!self::isFilled($t[$key]??null)));
            $check(1,!count($missing),$missing?'Faltan campos de Triage: '.implode(', ',$missing):'Campos mecánicos de Triage completos. La prioridad ESI aún requiere revisión de pertinencia.');
            $p = $snapshots['procedimientos'] ?? [];
            $check(4, self::isFilled($p), 'Comprueba existencia de procedimientos, no su pertinencia ni indicación clínica.');
            $e = $snapshots['examenes'] ?? [];
            $check(6, self::isFilled($e), 'Comprueba existencia de solicitudes o registros de exámenes, no su validez clínica.');
        }
        return ['criterios'=>$answers, 'motivos'=>$reasons, 'origen'=>$sources];
    }
}
