<?php
namespace App\Http\Controllers\Delivery;
use App\Http\Controllers\Controller;
use App\Models\StudentCase;
use App\Models\CaseSection;
use App\Support\ActivityAudit;
use Illuminate\Support\Facades\DB;
use App\Support\ThirdYear;
use App\Support\FourthYear;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Flujo CESFAM de 3.º medio: formularios, validaciones, persistencia de cuatro secciones
 * y entrega definitiva con auditoría; verifica identidad y asignación del estudiante.
 */
class ThirdYearController extends Controller {
    /** Resuelve ficha propia de 3.º e inicializa dentro del plazo. */
    private function record(Request $r,string $code,bool $start=true):StudentCase{
        $assignment=ThirdYear::assignment($r->user(),$code);
        $record=StudentCase::with('sections','assignment.clinicalCase')->where('case_assignment_id',$assignment->id)->where('estudiante_id',$r->user()->id)->first();
        if (!$record && $start && FourthYear::canStart($assignment)) {
           $record=StudentCase::firstOrCreate(['case_assignment_id'=>$assignment->id,'estudiante_id'=>$r->user()->id],['started_at'=>now(),'estado'=>'En desarrollo']);
           $record->load('sections','assignment.clinicalCase');
        }
        abort_unless($record,409,'Esta actividad no está disponible.');
        return $record;
    }
    /** Muestra sección de CESFAM y datos previos del estudiante. */
    public function show(Request $request,string $section) {
        abort_unless(isset(ThirdYear::SECTIONS[$section]),404);
        $code=(string)$request->query('caso','');
        abort_if(!$code,404,'Selecciona un caso asignado.');
        $record=$this->record($request,$code);
        $key='ficha-tp-3ro:'.$code.':'.$section;
        $saved=$record->sections->firstWhere('clave',$section);
        $context=['codigo'=>$code,'section'=>$section,'editable'=>FourthYear::editable($record),
            'snapshot'=>$saved ? json_decode($saved->payload,true):null, 'usuario'=>$request->user()->name];
        return view('estudiante.'.$section,['casoId'=>$code,'thirdYearContext'=>$context, 'segmentario'=>$record->assignment->clinicalCase->segmentario ?? []]);
    }
    /** Valida y persiste secciones CESFAM; impone reglas de antecedentes/citaciones. */
    public function save(Request $request,string $code,string $section){
        abort_unless(isset(ThirdYear::SECTIONS[$section]),404);
        $record=$this->record($request,$code);
        FourthYear::requireEditable($record);
        $data=$request->validate(['form'=>'required|array','tables'=>'nullable|array']);
        if ($section === 'antecedentes-3ro') {
            $motivo = trim((string)($data['form']['motivo_consulta'] ?? ''));
            if ($motivo === '') throw ValidationException::withMessages(['motivo_consulta' => 'Debes ingresar el motivo de consulta antes de guardar.']);
            foreach (['patologias'=>'patologias','alergias'=>'alergias','medicamentos'=>'medicamentos'] as $radio=>$table) {
                if (($data['form']['tiene_'.$radio] ?? '') === 'si' && !collect($data['tables'][$table] ?? [])->contains(fn ($row) => is_array($row) && collect($row)->contains(fn($value) => is_scalar($value) && trim((string)$value) !== ''))) {
                    throw ValidationException::withMessages([$table => 'Seleccionaste «Sí refiere» para '.$table.'. Añade al menos un registro o cambia la opción.']);
                }
            }
        }
        if ($section === 'citaciones-3ro') {
            $data['form']['profesional_asignado'] = $request->user()->name;
        }
        $payload=json_encode(['form'=>$data['form'],'tables'=>$data['tables']??[]],JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR);
        abort_if(strlen($payload)>524288,422,'Registro demasiado extenso.');
        CaseSection::updateOrCreate(['student_case_id'=>$record->id,'clave'=>$section],
          ['storage_key'=>'ficha-tp-3ro:'.$code.':'.$section,'storage_type'=>'local','payload'=>$payload]);
        $record->touch();
        return response()->json(['message'=>'Registro guardado correctamente.']);
    }
    /** Entrega ficha CESFAM tras guardar la última sección y registra evento único. */
    public function submit(Request $request,string $code){
        $record = $this->record($request, $code, false);
        DB::transaction(function () use ($request, $record) {
            // El bloqueo impide auditar entregas duplicadas cuando se envía dos veces.
            $locked = StudentCase::with('assignment.clinicalCase')->whereKey($record->id)->lockForUpdate()->firstOrFail();
            FourthYear::requireEditable($locked);
            abort_unless($locked->sections()->where('clave', 'citaciones-3ro')->exists(), 422,
                'Guarda primero la sección final de Citaciones y próximos controles.');
            $locked->update(['estado' => 'Entregada', 'submitted_at' => now()]);
            ActivityAudit::submission($request->user(), $locked);
        });
        return response()->json(['message'=>'Ficha entregada correctamente.']);
    }
}
