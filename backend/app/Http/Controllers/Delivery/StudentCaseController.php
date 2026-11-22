<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\StudentCase;
use App\Support\ActivityAudit;
use App\Support\CaseNotifications;
use App\Models\CaseSection;
use App\Support\FourthYear;
use App\Support\ThirdYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * Flujo de estudiantes de 4.º medio: acceso a casos, snapshots, persistencia JSON
 * por sección y entrega definitiva. Autorización por usuario, curso, estado y plazo del caso.
 */
class StudentCaseController extends Controller
{
    /** Consulta casos asignados al estudiante y estado de cada ficha. */
    public function index(Request $request)
    {
        $third = ThirdYear::isThirdYear($request->user());
        $assignments = ($third ? ThirdYear::assignmentsFor($request->user()) : FourthYear::assignmentsFor($request->user()))->orderByDesc('inicio')->get();
        $studentRecords = StudentCase::with('evaluation')->where('estudiante_id', $request->user()->id)->whereIn('case_assignment_id', $assignments->pluck('id'))->get()->keyBy('case_assignment_id');
        $casosPayload = $assignments->map(function ($assignment) use ($studentRecords) {
            $case = $assignment->clinicalCase;
            $record = $studentRecords->get($assignment->id);
            $state = FourthYear::status($record, $assignment);
            return [
                'id' => $case->codigo, 'esi' => $case->esi ? substr($case->esi, -1) : 'Sin clasificar',
                'name' => $case->titulo, 'patient' => $case->paciente ?: 'Paciente simulado',
                'type' => $case->tipo === 'Material de apoyo' ? 'Apoyo' : $case->tipo,
                'assigned' => $assignment->inicio->format('d/m/Y H:i'),
                'modified' => $record?->updated_at?->format('d/m/Y H:i') ?: 'Sin modificaciones',
                'status' => $state === 'Entregada' ? 'Finalizado' : ($state === 'Tiempo agotado' ? 'Tiempo agotado' : (now()->lessThan($assignment->inicio) ? 'Programada' : $state)),
                'grade' => $record?->evaluation?->estado === 'finalizada' ? $record->evaluation->nota : null,
                'achievement' => $record?->evaluation?->estado === 'finalizada' ? (int) $record->evaluation->porcentaje : null,
                'description' => strip_tags($case->descripcion),
                'available' => FourthYear::canStart($assignment), 'nivel' => $case->nivel,
            ];
        })->all();
        return view('estudiante.casos', compact('casosPayload'));
    }

    /** Presenta resumen del caso y evaluación publicada al estudiante autorizado. */
    public function show(Request $request, string $id)
    {
        $third = ThirdYear::isThirdYear($request->user());
        $assignment = $third ? ThirdYear::assignment($request->user(), $id) : FourthYear::assignment($request->user(), $id);
        $record = StudentCase::with('evaluation', 'sections')->where('case_assignment_id', $assignment->id)->where('estudiante_id', $request->user()->id)->first();
        return view($third ? 'estudiante.detalle-caso-tercero' : 'estudiante.detalle-caso-integrado', [
            'case' => $assignment->clinicalCase,
            'assignment' => $assignment,
            'record' => $record,
            'status' => FourthYear::status($record, $assignment),
            'canStart' => FourthYear::canStart($assignment),
            'editable' => $record ? FourthYear::editable($record) : FourthYear::canStart($assignment),
            'sections' => $third ? ThirdYear::SECTIONS : FourthYear::SECTIONS,
        ]);
    }

    /** Obtiene o inicializa la ficha propia dentro del periodo autorizado. */
    private function getStudentRecord(Request $request, string $code, bool $start = false): StudentCase
    {
        $assignment = FourthYear::assignment($request->user(), $code);
        $record = StudentCase::where('case_assignment_id', $assignment->id)->where('estudiante_id', $request->user()->id)->first();
        if (!$record && $start && FourthYear::canStart($assignment)) {
            $record = StudentCase::firstOrCreate(
                ['case_assignment_id' => $assignment->id, 'estudiante_id' => $request->user()->id],
                ['started_at' => now(), 'estado' => 'En desarrollo']
            );
        }
        abort_unless($record, 409, 'Aún no has iniciado este caso o su periodo está fuera de plazo.');
        $record->load('assignment.clinicalCase');
        return $record;
    }

    /** Entrega al frontend las secciones JSON previamente guardadas. */
    public function snapshots(Request $request, string $code)
    {
        $record = $this->getStudentRecord($request, $code);
        return response()->json(['data' => $record->sections()->get(['clave', 'storage_key', 'storage_type', 'payload', 'updated_at'])]);
    }

    /** Valida clave y JSON y guarda sección si la ficha está editable. */
    public function saveSection(Request $request, string $code, string $section)
    {
        abort_unless(isset(FourthYear::SECTIONS[$section]), 404);
        $record = $this->getStudentRecord($request, $code, true);
        FourthYear::requireEditable($record);
        $data = $request->validate([
            'storage_key' => 'required|string|max:160',
            'storage_type' => ['required', Rule::in(['session', 'local'])],
            'payload' => 'required|string|max:524288',
        ]);
        abort_unless($data['storage_key'] === FourthYear::storageKey($section, $code)
            && $data['storage_type'] === FourthYear::SECTIONS[$section][2], 422, 'La clave del registro no corresponde al caso y sección.');
        try { $decoded = json_decode($data['payload'], true, 512, JSON_THROW_ON_ERROR); }
        catch (\JsonException $exception) { abort(422, 'El registro debe estar codificado como JSON válido.'); }
        if ($section === 'signos-vitales' && is_array($decoded)) {
            foreach ($decoded as $measure) {
                if (!is_array($measure)) continue;
                $peso = (float)($measure['peso']??0); $talla=(float)($measure['talla']??0);
                $imc=$measure['imc']??null;
                if ($peso > 0 && $talla > 0 && (!is_numeric($imc) || abs((float)$imc-$peso/(($talla/100)**2))>.11))
                    abort(422,'El IMC registrado no coincide con peso y talla.');
            }
        }

        $saved = CaseSection::updateOrCreate(
            ['student_case_id' => $record->id, 'clave' => $section], $data
        );
        $record->touch();
        return response()->json(['message' => 'Registro guardado correctamente.', 'updated_at' => $saved->updated_at->toIso8601String()]);
    }

    /** Cierra definitivamente una ficha de 4.º y audita una sola entrega. */
    public function submit(Request $request, string $code)
    {
        $record = $this->getStudentRecord($request, $code);
        $submitted = DB::transaction(function () use ($request, $record) {
            // Bloqueo de fila: dos clics simultáneos no generan dos eventos de entrega.
            $locked = StudentCase::with('assignment.clinicalCase')->whereKey($record->id)->lockForUpdate()->firstOrFail();
            FourthYear::requireEditable($locked);
            if (!$locked->sections()->where('clave', 'destino')->exists()) {
                abort(422, 'Guarda primero la sección final Destino y cierre.');
            }
            $locked->update(['estado' => 'Entregada', 'submitted_at' => now()]);
            ActivityAudit::submission($request->user(), $locked);
            CaseNotifications::submitted($locked);
            return $locked;
        });
        return response()->json(['message' => 'Ficha entregada. Ya no puede modificarse.', 'submitted_at' => $submitted->submitted_at->toIso8601String()]);
    }

    /** Archivos de exámenes privados: se asocian a la ficha y al usuario autorizado. */
    public function uploadExamFile(Request $request, string $code, string $section)
    {
        abort_unless($section === 'examenes', 404);
        $record=$this->getStudentRecord($request,$code,true);
        FourthYear::requireEditable($record);
        $request->validate(['archivo'=>'required|file|mimes:jpg,jpeg,png,pdf|max:10240']);
        $file=$request->file('archivo');
        $filename=$file->hashName();
        $path=$file->storeAs("clinical-exams/{$record->id}",$filename,'local');
        return response()->json([
            'message'=>'Archivo subido correctamente.',
            'path'=>$path,
            'url'=>route('delivery.student.file',['code'=>$code,'filename'=>$filename]),
            'name'=>$file->getClientOriginalName(),
            'mime'=>$file->getMimeType(),
            'size'=>$file->getSize(),
        ]);
    }

    /** Entrega archivo únicamente al estudiante dueño de la ficha. */
    public function downloadExamFile(Request $request, string $code, string $filename)
    {
        abort_unless(preg_match('/^[A-Za-z0-9]{40}\.(?:jpg|jpeg|png|pdf)$/i',$filename)===1,404);
        $record=$this->getStudentRecord($request,$code,false);
        $path="clinical-exams/{$record->id}/{$filename}";
        abort_unless(\Storage::disk('local')->exists($path),404);
        return response()->file(\Storage::disk('local')->path($path), [
            'Cache-Control' => 'private, no-store, max-age=0',
            'X-Content-Type-Options' => 'nosniff',
            'Content-Disposition' => 'inline',
        ]);
    }
}
