<?php

namespace App\Http\Middleware;

use App\Models\StudentCase;
use App\Support\FourthYear;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireFourthYearCase
{
    public function handle(Request $request, Closure $next): Response
    {
        $code = (string) $request->query('caso', '');

        if (!$code) {
            return redirect()->route('preview.estudiante.casos');
        }

        $assignment = FourthYear::assignment($request->user(), $code);

        $record = StudentCase::with('sections')
            ->where('case_assignment_id', $assignment->id)
            ->where('estudiante_id', $request->user()->id)
            ->first();

        if (!$record && !FourthYear::canStart($assignment)) {
            return redirect()
                ->route('preview.estudiante.casos')
                ->with('error', 'Este caso aún no está disponible o ya venció.');
        }

        if (!$record) {
            $record = StudentCase::firstOrCreate(
                [
                    'case_assignment_id' => $assignment->id,
                    'estudiante_id' => $request->user()->id,
                ],
                [
                    'started_at' => now(),
                    'estado' => 'En desarrollo',
                ]
            );

            $record->load('sections');
        }

        $sectionSnapshots = [];

        foreach ($record->sections as $section) {
            $sectionSnapshots[$section->clave] = [
                'key' => $section->storage_key,
                'type' => $section->storage_type,
                'value' => $section->payload,
            ];
        }

        $expiresAt = $record->started_at
            ->copy()
            ->addMinutes($assignment->effectiveDuration());

        if ($expiresAt->greaterThan($assignment->termino)) {
            $expiresAt = $assignment->termino;
        }

        view()->share('ficha4toContext', [
            'codigo' => $code,
            'student_id' => $request->user()->id,
            'titulo' => $assignment->clinicalCase->titulo,
            'segmentario' => $assignment->clinicalCase->segmentario ?? [],
            'assignment_id' => $assignment->id,
            'editable' => FourthYear::editable($record),
            'status' => FourthYear::status($record, $assignment),
            'expires_at' => $expiresAt->toIso8601String(),
            'snapshots' => $sectionSnapshots,
            'csrf' => csrf_token(),
        ]);

        return $next($request);
    }
}
