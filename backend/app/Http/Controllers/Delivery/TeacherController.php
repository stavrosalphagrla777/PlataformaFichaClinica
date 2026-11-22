<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\CaseAssignment;
use App\Models\ClinicalCase;
use App\Models\EvaluationRubric;
use App\Models\StudentCase;
use App\Models\CaseEvaluation;
use App\Support\ActivityAudit;
use App\Support\FourthYear;
use App\Support\CourseAccess;
use App\Support\CaseNotifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Módulo docente web: catálogo compartido de casos, creación, eliminación por autoría,
 * asignaciones y dashboard. Acceso docente/administrador; controles de permisos en operaciones sensibles.
 */
class TeacherController extends Controller
{
    /** Devuelve casos del autor, salvo el administrador; para indicadores privados. */
    private function ownCases(Request $request)
    {
        return ClinicalCase::query()->when(!$request->user()->hasRole('administrador'), fn ($q) => $q->where('docente_id', $request->user()->id));
    }

    /** Compone métricas, pendientes y evaluaciones visibles para el docente. */
    public function dashboard(Request $request)
    {
        $ids = $this->ownCases($request)->pluck('id');
        $jornada = $request->query('jornada') === 'vespertino' ? 'vespertino' : 'diurno';
        $records = StudentCase::with('assignment.clinicalCase', 'evaluation', 'estudiante')
            ->when(!$request->user()->hasRole('administrador'), fn ($q) => $q->where(fn ($inner) =>
                $inner->whereHas('assignment', fn ($a) => $a->whereIn('clinical_case_id', $ids))
                    ->orWhereHas('assignment', fn ($a) => $a->where('docente_id', $request->user()->id))))
            ->whereHas('estudiante', fn($q)=>$jornada==='vespertino' ? $q->whereIn('curso',['V3ro','V4to']) : $q->whereNotIn('curso',['V3ro','V4to']))
            ->get();
        $finished = $records->filter(fn ($record) => $record->evaluation?->estado === 'finalizada');
        $pending = $records->filter(fn ($record) => in_array(FourthYear::status($record, $record->assignment), ['Entregada', 'Tiempo agotado'], true) && $record->evaluation?->estado !== 'finalizada');
        $evaluaciones = $finished->sortByDesc('evaluation.finalizada_at')->take(10)->map(function ($r) {
            return [
                'estudiante' => $r->estudiante?->name ?? ('Estudiante #'.$r->estudiante_id),
                'curso' => $r->estudiante?->curso,
                'caso' => $r->assignment->clinicalCase->codigo,
                'porcentaje' => $r->evaluation->porcentaje,
                'nota' => $r->evaluation->nota,
            ];
        })->all();
        $cases = $this->ownCases($request)->latest()->get();
        return view('docente.dashboard', [
            'resumen' => ['evaluados' => $finished->count(), 'promedio' => $finished->avg('evaluation.nota'), 'aprobados' => $finished->filter(fn ($r) => $r->evaluation->porcentaje >= 60)->count(), 'pendientes' => $pending->count()],
            'evaluaciones' => $evaluaciones,
            'pendientesDetalle' => $pending->sortByDesc('submitted_at')->take(15)->map(fn ($r) => [
                'id' => $r->id,
                'estudiante' => $r->estudiante?->name ?? ('Estudiante #'.$r->estudiante_id),
                'curso' => $r->estudiante?->curso,
                'codigo' => $r->assignment->clinicalCase->codigo,
                'titulo' => $r->assignment->clinicalCase->titulo,
                'estado' => FourthYear::status($r, $r->assignment),
                'fecha' => ($r->submitted_at ?? $r->updated_at)?->format('d/m/Y H:i'),
            ])->values()->all(),
            'indicadores' => [
                ['nombre' => 'Casos publicados', 'porcentaje' => $cases->count() ? round($cases->where('estado', 'Publicado')->count() / $cases->count() * 100) : 0],
                ['nombre' => 'Fichas evaluadas', 'porcentaje' => $records->count() ? round($finished->count() / $records->count() * 100) : 0],
            ],
            'casos' => $cases, 'jornada' => $jornada,
        ]);
    }

    /** Devuelve listado compartido de casos con banderas de autoría y eliminación. */
    public function index(Request $request)
    {
        // El catálogo de casos es compartido entre docentes y administradores.
        // Las operaciones destructivas siguen controladas por autoría y rol.
        $casos = ClinicalCase::with('docente:id,name')->latest()->get()->map(fn ($case) => [
            'id' => $case->id,
            'codigo' => $case->codigo, 'titulo' => $case->titulo,
            'descripcion' => $case->descripcion, 'antecedentes' => $case->antecedentes, 'segmentario' => $case->segmentario,
            'nivel' => $case->nivel, 'tipo' => $case->tipo, 'estado' => $case->estado,
            'servicio' => $case->servicio, 'duracion' => $case->duracion, 'esi' => $case->esi,
            'creadoPor' => $case->docente?->name ?? 'Usuario no disponible',
            'creadoPorMi' => (int) $case->docente_id === (int) $request->user()->id,
            'puedeEliminar' => $request->user()->hasRole('administrador') || (int) $case->docente_id === (int) $request->user()->id,
        ])->all();
        return view('docente.casos', compact('casos'));
    }


    /**
     * Solo el autor o un administrador puede eliminar un caso.
     * Si existe alguna ficha iniciada, se rechaza para proteger respuestas y evaluaciones.
     * Asignaciones sin fichas se eliminan junto con el caso, de forma atómica.
     */
    /** Elimina caso autorizadamente si no existe trabajo iniciado; deja auditoría. */
    public function destroy(Request $request, ClinicalCase $case)
    {
        abort_unless(
            $request->user()->hasRole('administrador') || (int) $case->docente_id === (int) $request->user()->id,
            403,
            'No tienes permiso para eliminar un caso creado por otro docente.'
        );

        $removedCode = $case->codigo;
        DB::transaction(function () use ($case, $request, $removedCode) {
            $locked = ClinicalCase::whereKey($case->id)->lockForUpdate()->firstOrFail();
            // Evita borrar trabajo realizado por estudiantes, incluso si fue evaluado.
            if ($locked->assignments()->whereHas('studentCases')->exists()) {
                throw ValidationException::withMessages([
                    'caso' => 'No se puede eliminar: hay estudiantes que iniciaron este caso. Conserva el registro para no perder fichas ni evaluaciones.'
                ]);
            }
            $locked->assignments()->delete();
            $locked->delete();
            ActivityAudit::caseDeleted($request->user(), $locked);
        });

        return response()->json(['message' => 'Caso '.$removedCode.' eliminado correctamente.']);
    }

    /** Muestra formulario de creación de caso clínico. */
    public function create() { return view('docente.crear-caso'); }

    /** Valida y crea caso con código único y evento de auditoría. */
    public function store(Request $request)
    {
        // Código generado por la base de datos; no se acepta un identificador del navegador.
        $data = $request->validate([
                        'titulo' => 'required|string|max:180',
            'descripcion' => 'required|string|max:30000',
            'antecedentes' => 'nullable|string|max:30000',
            'segmentario' => 'nullable|array',
            'segmentario.cabeza_cuello' => 'nullable|string|max:2500',
            'segmentario.torax_abdomen' => 'nullable|string|max:2500',
            'segmentario.extremidades_piel' => 'nullable|string|max:2500',
            'segmentario.neurologico' => 'nullable|string|max:2500',
            'nivel' => ['required', Rule::in(['3.º medio', '4.º medio'])],
            'tipo' => ['required', Rule::in(['Examen', 'Tarea', 'Material de apoyo'])],
            'estado' => ['required', Rule::in(['Borrador', 'Publicado', 'Archivado'])],
            'servicio' => ['required', Rule::in(['Servicio de urgencias','Atención primaria (CESFAM)'])],
            'duracion' => 'required|integer|min:1|max:10080',
            'paciente' => 'nullable|string|max:150',
            'edad' => 'nullable|integer|min:0|max:130',
            'sexo' => 'nullable|string|max:35',
            'prevision' => 'nullable|string|max:40',
            'esi' => ['nullable', Rule::in(['ESI 1', 'ESI 2', 'ESI 3', 'ESI 4', 'ESI 5'])],
        ], [
            'titulo.required' => 'Debes ingresar el título del caso.',
            'descripcion.required' => 'Debes completar la descripción e instrucciones del caso.',
            'nivel.in' => 'Selecciona un nivel educativo válido.',
            'servicio.in' => 'Selecciona un contexto clínico válido.',
        ]);
        $data['docente_id'] = $request->user()->id;
        // Primero se reserva un ID autoincremental. Después se construye el código legible.
        // El código temporal es único incluso bajo solicitudes concurrentes.
        $case = DB::transaction(function () use ($data, $request) {
            $case = ClinicalCase::create($data + ['codigo' => 'TMP-' . (string) Str::uuid()]);
            $candidate = 'CAS-' . str_pad((string) $case->id, 6, '0', STR_PAD_LEFT);
            $suffix = 1;
            while (ClinicalCase::where('codigo', $candidate)->whereKeyNot($case->id)->exists()) {
                $candidate = 'CAS-' . $case->id . '-' . $suffix++;
            }
            $case->update(['codigo' => $candidate]);
            ActivityAudit::caseCreated($request->user(), $case);
            return $case;
        });
        return response()->json(['message' => 'Caso creado correctamente.', 'data' => $case], 201);
    }

    /** Muestra asignaciones y catálogo de casos publicados de todos los autores. */
    public function assignments(Request $request)
    {
        // Los casos publicados son un catálogo compartido para docentes y administradores.
        // La autoría solo limita la eliminación de casos, no las asignaciones.
        $casos = ClinicalCase::query()->where('estado', 'Publicado')->orderBy('codigo')->get();
        return view('docente.asignaciones', ['casos' => $casos, 'rubrics' => EvaluationRubric::orderBy('nivel')->orderBy('nombre')->get()]);
    }

    /** Lista asignaciones propias (o todas para administrador). */
    public function assignmentIndex(Request $request)
    {
        return response()->json(['data' => CaseAssignment::with('clinicalCase.rubric','rubric')
            ->when(!$request->user()->hasRole('administrador'), fn($q) => $q->where('docente_id', $request->user()->id))->latest()->get()->map(fn ($a) => $this->assignmentPayload($a))]);
    }

    /** Convierte una asignación en datos del frontend, con duración efectiva. */
    private function assignmentPayload(CaseAssignment $a): array
    {
        return [
            'id' => $a->id, 'codigo' => $a->clinicalCase->codigo,
            'casoTexto' => $a->clinicalCase->codigo.' · '.$a->clinicalCase->titulo,
            'nivel' => $a->nivel, 'curso' => $a->curso,
            'inicio' => $a->inicio->format('Y-m-d\TH:i'), 'termino' => $a->termino->format('Y-m-d\TH:i'),
            'duracion' => $a->effectiveDuration(), 'duracionOriginal' => $a->clinicalCase->duracion, 'estado' => $a->estado,
            'observaciones' => $a->observaciones,
            'rubricId' => $a->rubric_id, 'rubricNombre' => $a->rubric?->nombre ?? $a->clinicalCase->rubric?->nombre ?? 'Pauta predeterminada del nivel',
        ];
    }

    /** Valida curso, nivel, vigencia, colisiones, personalización de tiempo. */
    private function validatedAssignment(Request $request, ?CaseAssignment $current = null): array
    {
        $data = $request->validate([
            'codigo' => 'required|string|max:40',
            'nivel' => ['required', Rule::in(['3.º medio', '4.º medio'])],
            'curso' => ['required', 'string', 'max:50'],
            'inicio' => 'required|date', 'termino' => 'required|date|after:inicio',
            'duracion' => 'required|integer|min:1|max:10080',
            'estado' => ['required', Rule::in(['Activa', 'Programada', 'Inactiva'])],
            'observaciones' => 'nullable|string|max:3000',
            'rubric_id' => 'nullable|integer|exists:evaluation_rubrics,id',
        ]);
        if (!CourseAccess::validCourse($data['curso'], $data['nivel'])) {
            throw ValidationException::withMessages(['curso' => 'El curso no corresponde al nivel seleccionado.']);
        }
        // Se permite asignar cualquier caso publicado del nivel seleccionado.
        $case = ClinicalCase::query()->where('codigo', $data['codigo'])->where('nivel', $data['nivel'])->where('estado', 'Publicado')->firstOrFail();
        if (!empty($data['rubric_id']) && !EvaluationRubric::whereKey($data['rubric_id'])->where('nivel', $data['nivel'])->exists()) {
            throw ValidationException::withMessages(['rubric_id'=>'La pauta elegida no corresponde al nivel del caso.']);
        }
        // Las evaluaciones conservan la pauta elegida al iniciar la ficha.
        if ($current && $current->studentCases()->exists() && (int)($current->rubric_id ?? 0) !== (int)($data['rubric_id'] ?? 0)) {
            throw ValidationException::withMessages(['rubric_id'=>'No puedes cambiar la pauta cuando ya existen fichas iniciadas. Renueva la asignación.']);
        }
        if ($current && $current->studentCases()->exists() && ($current->clinical_case_id !== $case->id || $current->curso !== $data['curso'] || $current->nivel !== $data['nivel'] || $current->inicio->format('Y-m-d\TH:i') !== substr($data['inicio'], 0, 16))) {
            throw ValidationException::withMessages(['codigo' => 'No es posible cambiar caso, nivel, curso ni inicio después de que un estudiante comenzó la actividad.']);
        }
        // No alterar el vencimiento de actividades que un estudiante ya comenzó.
        if ($current && $current->studentCases()->exists() && (int) $data['duracion'] !== $current->effectiveDuration()) {
            throw ValidationException::withMessages(['duracion' => 'No puedes cambiar la duración cuando un estudiante ya inició la actividad.']);
        }
        // Null implica heredar la duración del caso; de otro modo se guarda la personalización.
        $data['duracion_minutos'] = (int) $data['duracion'] === (int) $case->duracion ? null : (int) $data['duracion'];
        unset($data['duracion']);
        $start = \Illuminate\Support\Carbon::parse($data['inicio']);
        $end = \Illuminate\Support\Carbon::parse($data['termino']);
        $overlap = CaseAssignment::where('clinical_case_id', $case->id)->where('nivel', $data['nivel'])->where('estado', '!=', 'Inactiva')
            ->where('inicio', '<', $end)->where('termino', '>', $start)
            ->when($current, fn ($q) => $q->whereKeyNot($current->id))
            ->where(fn ($q) => $q->where('curso', $data['curso'])->orWhere('curso', 'Todos los cursos')->when($data['curso'] === 'Todos los cursos', fn ($q) => $q->orWhere('nivel', $data['nivel'])))
            ->exists();
        if ($overlap && $data['estado'] !== 'Inactiva') throw ValidationException::withMessages(['curso' => 'Ya existe una asignación de este caso para ese curso y periodo.']);
        unset($data['codigo']);
        $data['clinical_case_id'] = $case->id;
        // La asignación pertenece al usuario que la crea (aunque el caso sea ajeno).
        $data['docente_id'] = $request->user()->id;
        return $data;
    }

    /** Crea asignación con registro en auditoría. */
    public function assignmentStore(Request $request)
    {
        $data = $this->validatedAssignment($request);
        $assignment = DB::transaction(function () use ($request, $data) {
            $assignment = CaseAssignment::create($data);
            ActivityAudit::assignment($request->user(), $assignment, 'asignar_caso');
            CaseNotifications::assigned($assignment);
            return $assignment;
        });
        $assignment->load('clinicalCase');
        return response()->json(['data' => $this->assignmentPayload($assignment)], 201);
    }

    /** Modifica asignación autorizada y registra la modificación. */
    public function assignmentUpdate(Request $request, CaseAssignment $assignment)
    {
        abort_unless($request->user()->hasRole('administrador') || $assignment->docente_id === $request->user()->id, 403);
        $data = $this->validatedAssignment($request, $assignment);
        DB::transaction(function () use ($request, $assignment, $data) {
            $assignment->update($data);
            ActivityAudit::assignment($request->user(), $assignment, 'actualizar_asignacion');
        });
        $assignment->load('clinicalCase');
        return response()->json(['data' => $this->assignmentPayload($assignment)]);
    }

    /** Elimina asignación sin fichas iniciadas y registra su eliminación. */
    public function assignmentDestroy(Request $request, CaseAssignment $assignment)
    {
        abort_unless($request->user()->hasRole('administrador') || $assignment->docente_id === $request->user()->id, 403);
        if ($assignment->studentCases()->exists()) return response()->json(['message' => 'No se puede eliminar una asignación que tiene fichas iniciadas. Puedes dejarla Inactiva.'], 409);
        DB::transaction(function () use ($request, $assignment) {
            ActivityAudit::assignment($request->user(), $assignment, 'eliminar_asignacion');
            $assignment->delete();
        });
        return response()->json(['message' => 'Asignación eliminada.']);
    }

    /** Muestra la vista de revisión de fichas. */
    public function review() { return view('docente.revision-fichas'); }
    /** Muestra la vista de pautas de evaluación. */
    public function evaluations() { return view('docente.evaluaciones'); }
}
