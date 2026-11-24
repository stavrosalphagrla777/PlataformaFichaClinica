<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\StudentCase;
use App\Models\CaseEvaluation;
use App\Support\ActivityAudit;
use App\Support\FourthYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Compatibilidad de revisión de 3.º medio mediante formulario tradicional.
 * Conserva la pauta manual y audita el cierre definitivo de la evaluación.
 */
class ThirdReviewController extends Controller
{
    /** Comprueba nivel CESFAM y acceso del revisor. */
    private function eligible(Request $request, StudentCase $record): StudentCase
    {
        $record->load(
            'assignment.clinicalCase',
            'estudiante',
            'sections',
            'evaluation'
        );

        abort_unless($record->assignment->nivel === '3.º medio', 404);

        abort_unless(
            $request->user()->hasRole('administrador')
                || (int) $record->assignment->clinicalCase->docente_id === (int) $request->user()->id
                || (int) $record->assignment->docente_id === (int) $request->user()->id,
            403
        );

        return $record;
    }

    /** Lista fichas de 3.º autorizadas para el docente. */
    public function index(Request $request)
    {
        $records = StudentCase::with(
            'assignment.clinicalCase',
            'estudiante',
            'evaluation'
        )
            ->whereHas(
                'assignment.clinicalCase',
                fn ($q) => $q->where('nivel', '3.º medio')
            )
            ->when(
                !$request->user()->hasRole('administrador'),
                fn ($q) => $q->where(fn ($s) =>
                    $s->whereHas(
                        'assignment',
                        fn ($a) => $a->where('docente_id', $request->user()->id)
                    )->orWhereHas(
                        'assignment.clinicalCase',
                        fn ($c) => $c->where('docente_id', $request->user()->id)
                    )
                )
            )
            ->latest()
            ->paginate(30);

        return view('docente.revision-3ro', [
            'records' => $records
        ]);
    }

    /** Presenta una ficha CESFAM para evaluación tradicional. */
    public function show(Request $request, StudentCase $record)
    {
        return view('docente.revision-3ro-detalle', [
            'record' => $this->eligible($request, $record),
            'estado' => FourthYear::status($record, $record->assignment)
        ]);
    }

    /** Guarda o finaliza retroalimentación manual con auditoría de la nota. */
    public function feedback(Request $request, StudentCase $record)
    {
        $record = $this->eligible($request, $record);

        $data = $request->validate([
            'retroalimentacion' => 'required|string|max:10000',
            'nota' => 'nullable|numeric|min:2|max:7',
            'porcentaje' => 'nullable|numeric|min:20|max:100',
            'finalizar' => 'nullable|boolean',
        ]);

        $final = (bool) ($data['finalizar'] ?? false);

        if ($record->evaluation?->estado === 'finalizada') {
            return back()->withErrors([
                'error' => 'Esta evaluación ya fue finalizada.'
            ]);
        }

        if (
            $final &&
            !in_array(
                FourthYear::status($record, $record->assignment),
                ['Entregada', 'Tiempo agotado'],
                true
            )
        ) {
            return back()->withErrors([
                'error' => 'Para finalizar una evaluación, la ficha debe estar entregada o cerrada por plazo.'
            ]);
        }

        DB::transaction(function () use ($record, $request, $data, $final) {
            StudentCase::whereKey($record->id)
                ->lockForUpdate()
                ->firstOrFail();

            abort_if(
                CaseEvaluation::where('student_case_id', $record->id)
                    ->where('estado', 'finalizada')
                    ->exists(),
                409,
                'La evaluación ya fue finalizada.'
            );

            $evaluation = CaseEvaluation::updateOrCreate(
                ['student_case_id' => $record->id],
                [
                    'docente_id' => $request->user()->id,
                    'criterios' => [
                        'modalidad' => 'evaluacion_cesfam_manual'
                    ],
                    'notas' => [],
                    'rubrica_criterios' => (
                        $record->assignment->rubric
                        ?? $record->assignment->clinicalCase->rubric
                    )?->criterios,
                    'retroalimentacion' => $data['retroalimentacion'],
                    'nota' => $data['nota'] ?? null,
                    'porcentaje' => $data['porcentaje'] ?? null,
                    'estado' => $final ? 'finalizada' : 'borrador',
                    'finalizada_at' => $final ? now() : null,
                ]
            );

            if ($final) {
                ActivityAudit::evaluation(
                    $request->user(),
                    $record,
                    $evaluation
                );
            }
        });

        return back()->with(
            'ok',
            $final
                ? 'Evaluación cerrada.'
                : 'Retroalimentación guardada.'
        );
    }
}
