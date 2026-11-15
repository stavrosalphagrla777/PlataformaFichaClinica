<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\CaseEvaluation;
use App\Models\StudentCase;
use App\Support\FourthYear;
use App\Support\ActivityAudit;
use Illuminate\Support\Facades\DB;
use App\Support\EvaluationAssistance;
use App\Support\UrgencyRubric;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Revisión docente de fichas CESFAM y Urgencias. Calcula sugerencias mecánicas,
 * guarda borradores/finales de pautas y registra en auditoría solo la evaluación finalizada.
 */
class ReviewController extends Controller
{
    /** Comprueba permiso docente para revisar o calificar una ficha. */
    private function own(Request $request, StudentCase $record): StudentCase
    {
        $record->load('assignment.clinicalCase', 'estudiante', 'sections', 'evaluation');
        abort_unless($request->user()->hasRole('administrador') || ((int)$record->assignment->clinicalCase->docente_id === (int)$request->user()->id || (int)$record->assignment->docente_id === (int)$request->user()->id), 403);
        return $record;
    }

    /** Proporciona fichas y sugerencias automáticas para revisión. */
    public function index(Request $request)
    {
        $records = StudentCase::with('assignment.clinicalCase', 'estudiante', 'sections', 'evaluation')
            ->when(!$request->user()->hasRole('administrador'), fn($q) => $q->where(fn($inner) => $inner->whereHas('assignment.clinicalCase', fn($case) => $case->where('docente_id', $request->user()->id))->orWhereHas('assignment', fn($assignment) => $assignment->where('docente_id', $request->user()->id))))
            ->latest()->get();
        return response()->json(['data' => $records->map(function ($r) {
            $case = $r->assignment->clinicalCase;
            return [
                'id' => $r->id, 'estudiante' => $r->estudiante->name, 'curso' => $r->estudiante->curso,
                'nivel' => $r->assignment->nivel, 'codigo' => $case->codigo, 'caso' => $case->titulo,
                'servicio' => str_contains(mb_strtolower($case->servicio ?? ''), 'cesfam') ? 'cesfam' : 'urgencias', 'estado' => FourthYear::status($r, $r->assignment),
                'ultimoGuardado' => $r->updated_at->toIso8601String(),
                'duracion' => $r->assignment->effectiveDuration(),
                'secciones' => $r->sections->mapWithKeys(fn ($s) => [$s->clave => $s->payload])->all(),
                'evaluacion' => $r->evaluation?->only(['estado', 'criterios', 'notas', 'retroalimentacion', 'porcentaje', 'nota']),
                'evaluacionSugerida' => EvaluationAssistance::suggestions($r),
            ];
        })]);
    }

    /** Entrega los datos íntegros de una ficha autorizada. */
    public function show(Request $request, StudentCase $record)
    {
        $record = $this->own($request, $record);
        return response()->json(['data' => $record]);
    }

    /** Guarda pauta como borrador o final y audita solo la calificación final. */
    public function evaluate(Request $request, StudentCase $record)
    {
        $record = $this->own($request, $record);
        if (!in_array(FourthYear::status($record, $record->assignment), ['Entregada', 'Tiempo agotado'], true)) {
            return response()->json(['message' => 'Solo pueden evaluarse fichas entregadas o cerradas por plazo.'], 409);
        }
        if ($record->evaluation?->estado === 'finalizada') return response()->json(['message' => 'La evaluación definitiva es de solo lectura.'], 409);
        // Compatibilidad de fichas CESFAM evaluadas con la modalidad manual anterior.
        // Las evaluaciones nuevas de ambos niveles envían los 9 criterios.
        if ($record->assignment->nivel === '3.º medio' && !$request->has('criterios')) {
            $data = $request->validate([
                'retroalimentacion' => 'required|string|max:10000',
                'nota' => 'nullable|numeric|min:2|max:7',
                'porcentaje' => 'nullable|numeric|min:20|max:100',
                'finalizar' => 'sometimes|boolean',
            ]);
            $final = (bool) ($data['finalizar'] ?? false);
            $evaluation = DB::transaction(function () use ($record, $request, $data, $final) {
                StudentCase::whereKey($record->id)->lockForUpdate()->firstOrFail();
                abort_if(CaseEvaluation::where('student_case_id', $record->id)->where('estado', 'finalizada')->exists(),
                    409, 'La evaluación definitiva es de solo lectura.');
                $evaluation = CaseEvaluation::updateOrCreate(
                    ['student_case_id' => $record->id],
                    ['docente_id' => $request->user()->id,
                     'criterios' => ['modalidad' => 'evaluacion_cesfam_manual'],
                     'notas' => [], 'retroalimentacion' => $data['retroalimentacion'],
                     'nota' => $data['nota'] ?? null, 'porcentaje' => $data['porcentaje'] ?? null,
                     'estado' => $final ? 'finalizada' : 'borrador', 'finalizada_at' => $final ? now() : null]
                );
                if ($final) ActivityAudit::evaluation($request->user(), $record, $evaluation);
                return $evaluation;
            });
            return response()->json(['message' => $final ? 'Evaluación de CESFAM finalizada.' : 'Borrador CESFAM guardado.', 'data' => $evaluation]);
        }
        $data = $request->validate([
            'criterios' => 'required|array|size:9',
            'criterios.*' => ['required', Rule::in(['si', 'no', 'pendiente'])],
            'retroalimentacion' => 'nullable|string|max:10000',
            'notas' => 'sometimes|array|size:9',
            'notas.*' => 'nullable|string|max:1000',
            'finalizar' => 'sometimes|boolean',
        ]);
        $answers = array_values($data['criterios']);
        $result = UrgencyRubric::result($answers);
        $final = (bool) ($data['finalizar'] ?? false);
        if ($final && $result['pendientes']) throw ValidationException::withMessages(['criterios' => 'Debes revisar los nueve criterios antes de finalizar.']);
        $evaluation = DB::transaction(function () use ($record, $request, $data, $answers, $result, $final) {
            StudentCase::whereKey($record->id)->lockForUpdate()->firstOrFail();
            abort_if(CaseEvaluation::where('student_case_id', $record->id)->where('estado', 'finalizada')->exists(),
                409, 'La evaluación definitiva es de solo lectura.');
            $evaluation = CaseEvaluation::updateOrCreate(
                ['student_case_id' => $record->id],
                [
                    'docente_id' => $request->user()->id, 'criterios' => $answers,
                    'notas' => array_values($data['notas'] ?? array_fill(0, 9, '')),
                    'retroalimentacion' => $data['retroalimentacion'] ?? '',
                    'porcentaje' => $result['porcentaje'], 'nota' => $result['nota'],
                    'estado' => $final ? 'finalizada' : 'borrador',
                    'finalizada_at' => $final ? now() : null,
                ]
            );
            if ($final) ActivityAudit::evaluation($request->user(), $record, $evaluation);
            return $evaluation;
        });
        return response()->json(['message' => $final ? 'Evaluación finalizada.' : 'Borrador de evaluación guardado.', 'data' => $evaluation, 'resultado' => $result]);
    }

    /** Expone la definición de criterios para el frontend. */
    public function rubric()
    {
        return response()->json(['criterios' => UrgencyRubric::CRITERIA]);
    }
}
